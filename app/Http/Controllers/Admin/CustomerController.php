<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function create()
    {
        $activities = DB::table('activities')->where('durum', 1)->orderBy('title')->get();
        $transfers  = DB::table('transfers')->where('durum', 1)->orderBy('title')->get();
        return view('admin.customers.create', compact('activities', 'transfers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|max:150',
            'phone'         => 'required|string|max:30',
            'type'          => 'required|in:transfer,activity',
            'package'       => 'nullable|string|max:120',
            'activity_name' => 'nullable|string|max:120',
            'hotel_name'    => 'nullable|string|max:200',
            'adult_count'   => 'nullable|integer|min:0',
            'child_count'   => 'nullable|integer|min:0',
            'arrival_date'  => 'nullable|date',
            'arrival_time'  => 'nullable|string|max:10',
            'departure_date'=> 'nullable|date',
            'departure_time'=> 'nullable|string|max:10',
            'notes'          => 'nullable|string|max:1000',
            'birth_date'     => 'nullable|date',
            'registered_at'  => 'nullable|date',
        ]);
        $data['payment_status'] = 'unpaid';
        $data['seen'] = 1;
        $data['registered_at'] = $data['registered_at'] ?? now();
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $id = DB::table('customers')->insertGetId($data);

        // Silinirse geri kurulabilsin diye tam veri loga yazilir.
        \Log::info('[EKLE] PANELDEN-MUSTERI', [
            'customer_id'  => $id,
            'ekleyen_admin'=> auth()->id(),
            'kayit'        => $data,
        ]);

        return redirect()->route('admin.customers.show', $id)->with('success', 'Müşteri eklendi.');
    }

    public function destroy(int $id)
    {
        $customer = DB::table('customers')->where('id', $id)->first();

        if (!$customer) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Kayıt bulunamadı.');
        }

        // --- 1) Parasi alinmis ya da bankada bekleyen kayit SILINEMEZ ---
        $odeme = DB::table('payments')
            ->where('customer_id', $id)
            ->whereIn('status', ['paid', 'pending'])
            ->orderByDesc('id')
            ->first();

        if ($odeme) {
            \Log::warning('[SIL] ENGELLENDI-ODEMESI-VAR', [
                'customer_id' => $id,
                'order_id'    => $odeme->order_id,
                'tutar'       => $odeme->amount,
                'odeme_durum' => $odeme->status,
                'silen_admin' => auth()->id(),
            ]);

            return redirect()->route('admin.customers.index')->with(
                'error',
                'Bu kayıt silinemez: ödeme kaydı var (sipariş ' . $odeme->order_id
                . ', ' . $odeme->amount . ' GBP, durum: ' . $odeme->status . '). '
                . 'Muhasebe izini korumak için ödemesi olan müşteriler silinmez.'
            );
        }

        // --- 2) Silinmeden ONCE tum veriyi loga yaz (geri kurulabilsin) ---
        \Log::warning('[SIL] MUSTERI-SILINDI', [
            'silen_admin' => auth()->id(),
            'ip'          => request()->ip(),
            'kayit'       => (array) $customer,
        ]);

        // --- 3) Odeme satirlarina DOKUNMA — para izi kalsin (varsa 'failed' olanlar) ---
        DB::table('customers')->where('id', $id)->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Müşteri silindi. (Kayıt, geri kurulabilmesi için log dosyasına yazıldı.)');
    }

    public function updateNote(Request $request, int $id)
    {
        DB::table('customers')->where('id', $id)->update([
            'crm_note' => $request->input('crm_note'),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Not güncellendi.');
    }

    public function show(int $id)
    {
        $customer = Customer::findOrFail($id);

        // All payments for this customer
        $payments = DB::table('payments')
            ->where('customer_id', $id)
            ->orderByDesc('created_at')
            ->get();

        $totalSpent  = (float) $payments->where('status', 'paid')->sum('amount');

        // Eğer ödeme kaydı yoksa rezervasyonların hesaplanan tutarını kullan
        if ($totalSpent <= 0) {
            $allBookingsForCalc = DB::table('customers')->where('email', $customer->email)->get();
            foreach ($allBookingsForCalc as $b) {
                $pax = max(1, (int)($b->adult_count ?? 0) + (int)($b->child_count ?? 0));
                if ($b->type === 'activity') {
                    $aRec = DB::table('activities')->where('slug', $b->package)->orWhere('title', $b->activity_name)->first();
                    $totalSpent += (float)($aRec->price ?? 0) * $pax;
                } else {
                    $tRec = DB::table('transfers')->where('title', $b->package)->first();
                    if ($tRec) {
                        if ($pax <= 4)       $totalSpent += (float)($tRec->price_1_4 ?? 0);
                        elseif ($pax <= 6)   $totalSpent += (float)($tRec->price_5_6 ?? 0);
                        elseif ($pax <= 8)   $totalSpent += (float)($tRec->price_7_8 ?? 0);
                        else                 $totalSpent += (float)($tRec->price_9_14 ?? 0);
                    }
                }
            }
        }
        $paidCount   = $payments->where('status', 'paid')->count();
        $pendingSum  = (float) $payments->where('status', 'pending')->sum('amount');
        $failedCount = $payments->where('status', 'failed')->count();

        // All bookings: same email aggregated (transfers + activities)
        $allBookings = DB::table('customers')
            ->where('email', $customer->email)
            ->orderByDesc('created_at')
            ->get();

        $transferBookings = $allBookings->where('type', 'transfer');
        $activityBookings = $allBookings->where('type', 'activity');

        // Contact messages from same email
        $messages = DB::table('contact_messages')
            ->where('email', $customer->email)
            ->orderByDesc('created_at')
            ->get();

        // Reviews — match by name (reviews table has no email column)
        $fullName = trim($customer->first_name . ' ' . $customer->last_name);
        $reviews = DB::table('reviews')
            ->where('name', $fullName)
            ->orderByDesc('created_at')
            ->get();

        // Activity timeline (merge bookings + payments + messages + reviews)
        $timeline = collect();
        foreach ($allBookings as $b) {
            $timeline->push([
                'type' => 'booking',
                'icon' => $b->type === 'transfer' ? 'fa-shuttle-van' : 'fa-mountain-sun',
                'color' => $b->type === 'transfer' ? '#0ea5e9' : '#f59e0b',
                'title' => ($b->type === 'transfer' ? 'Transfer' : 'Aktivite') . ' rezervasyonu',
                'desc' => $b->activity_name ?: $b->package,
                'date' => $b->created_at,
            ]);
        }
        foreach ($payments as $p) {
            $timeline->push([
                'type' => 'payment',
                'icon' => $p->status === 'paid' ? 'fa-check-circle' : ($p->status === 'failed' ? 'fa-times-circle' : 'fa-hourglass-half'),
                'color' => $p->status === 'paid' ? '#10b981' : ($p->status === 'failed' ? '#ef4444' : '#f59e0b'),
                'title' => 'Ödeme · ' . ucfirst($p->status),
                'desc' => '£' . number_format($p->amount, 2) . ' · ' . ucfirst($p->provider ?? '—'),
                'date' => $p->created_at,
            ]);
        }
        foreach ($messages as $m) {
            $timeline->push([
                'type' => 'message',
                'icon' => 'fa-envelope',
                'color' => '#8b5cf6',
                'title' => 'İletişim mesajı',
                'desc' => \Str::limit($m->message ?? '', 80),
                'date' => $m->created_at,
            ]);
        }
        foreach ($reviews as $r) {
            $timeline->push([
                'type' => 'review',
                'icon' => 'fa-star',
                'color' => '#f59e0b',
                'title' => $r->rating . ' yıldız yorum',
                'desc' => \Str::limit($r->comment ?? '', 80),
                'date' => $r->created_at,
            ]);
        }
        $timeline = $timeline->sortByDesc('date')->values();

        // Customer score (loyalty)
        $score = min(100, ($paidCount * 15) + (count($reviews) * 5) + ($totalSpent > 200 ? 20 : 0));

        // Auto tags
        $tags = [];
        if ($totalSpent >= 500)                                           $tags[] = ['label' => 'VIP',         'color' => '#f59e0b', 'icon' => 'fa-crown'];
        if ($allBookings->count() >= 3)                                   $tags[] = ['label' => 'Sadık Müşteri','color' => '#8b5cf6', 'icon' => 'fa-heart'];
        if (\Carbon\Carbon::parse($customer->created_at)->gt(now()->subDays(7))) $tags[] = ['label' => 'Yeni',        'color' => '#10b981', 'icon' => 'fa-sparkles'];
        if ($payments->where('status','failed')->count() > 0)             $tags[] = ['label' => 'Ödeme Sorunu', 'color' => '#ef4444', 'icon' => 'fa-triangle-exclamation'];
        if (count($reviews) > 0)                                          $tags[] = ['label' => 'Yorum Yaptı', 'color' => '#0ea5e9', 'icon' => 'fa-star'];
        if (empty($tags))                                                 $tags[] = ['label' => 'Standart',    'color' => '#64748b', 'icon' => 'fa-user'];

        $ayar = DB::table('ayarlar')->first();

        return view('admin.customers.show', compact(
            'customer', 'payments', 'totalSpent', 'paidCount', 'pendingSum', 'failedCount',
            'allBookings', 'transferBookings', 'activityBookings', 'messages', 'reviews',
            'timeline', 'score', 'tags', 'ayar'
        ));
    }

    public function markPaid(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);
        $amount = (float) $request->input('amount', 0);

        if ($amount <= 0) {
            // Auto-fetch from activity/transfer table
            if ($customer->type === 'activity') {
                $rec = DB::table('activities')->where('slug', $customer->package)->first();
            } else {
                $rec = DB::table('transfers')->where('title', $customer->package)->first();
            }
            if ($rec && $customer->type === 'transfer') {
                $pax = (int)($customer->adult_count ?? 0) + (int)($customer->child_count ?? 0);
                if ($pax >= 9)      $amount = (float) $rec->price_9_14;
                elseif ($pax >= 7)  $amount = (float) $rec->price_7_8;
                elseif ($pax >= 5)  $amount = (float) $rec->price_5_6;
                elseif ($pax >= 1)  $amount = (float) $rec->price_1_4;
                if ($amount <= 0)   $amount = (float) $rec->price;
            } else {
                $amount = (float) ($rec->price ?? 0);
            }
        }

        if ($amount <= 0) {
            return back()->with('error', 'Geçerli bir tutar girilmedi.');
        }

        DB::table('payments')->insert([
            'customer_id'    => $customer->id,
            'order_id'       => 'TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT),
            'amount'         => $amount,
            'currency'       => 'GBP',
            'provider'       => 'manuel',
            'status'         => 'paid',
            'card_holder'    => $customer->first_name . ' ' . $customer->last_name,
            'transaction_id' => 'MAN' . uniqid(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        DB::table('customers')->where('id', $customer->id)->update(['payment_status' => 'paid', 'updated_at' => now()]);

        return back()->with('success', 'Ödeme kaydedildi: £' . number_format($amount, 2));
    }

    public function index(\Illuminate\Http\Request $request)
    {
        DB::table('customers')->where('seen', 0)->update(['seen' => 1]);

        $type  = $request->query('type');
        $day   = $request->query('day');
        $month = $request->query('month');
        $year  = $request->query('year');

        if ($day && !$month) $month = now()->month;
        if (($day || $month) && !$year) $year = now()->year;


        $query = Customer::query()
            ->addSelect(['total_spent' => DB::table('payments')
                ->selectRaw('COALESCE(SUM(amount), 0)')
                ->whereColumn('payments.customer_id', 'customers.id')
                ->where('payments.status', 'paid')
            ])
            ->orderByDesc('customers.id');

        if (in_array($type, ['transfer', 'activity'], true)) {
            $query->where('customers.type', $type);
        }
        // Varsayilan: GELIS tarihi. ?tarih=kayit ile kayit tarihine gore de suzulebilir.
        $tarihAlani = $request->query('tarih') === 'kayit'
            ? 'customers.created_at'
            : 'customers.arrival_date';

        if ($year)  $query->whereYear($tarihAlani, $year);
        if ($month) $query->whereMonth($tarihAlani, $month);
        if ($day)   $query->whereDay($tarihAlani, $day);

        $customers = $query->paginate(20)->withQueryString();

        // Ödeme kaydı yoksa rezervasyon fiyatını hesapla (transfer tier / aktivite × kişi)
        foreach ($customers as $c) {
            if ((float)$c->total_spent > 0) continue;
            $pax = max(1, (int)($c->adult_count ?? 0) + (int)($c->child_count ?? 0));
            $price = 0;
            if ($c->type === 'activity') {
                $aRec = DB::table('activities')->where('slug', $c->package)->orWhere('title', $c->activity_name)->first();
                $price = (float)($aRec->price ?? 0) * $pax;
            } else {
                $tRec = DB::table('transfers')->where('title', $c->package)->first();
                if ($tRec) {
                    if ($pax <= 4)       $price = (float)($tRec->price_1_4 ?? 0);
                    elseif ($pax <= 6)   $price = (float)($tRec->price_5_6 ?? 0);
                    elseif ($pax <= 8)   $price = (float)($tRec->price_7_8 ?? 0);
                    else                 $price = (float)($tRec->price_9_14 ?? 0);
                }
            }
            $c->total_spent = $price;
        }

        $totalTransfer = DB::table('customers')->where('type', 'transfer')->count();
        $totalActivity = DB::table('customers')->where('type', 'activity')->count();

        $years = DB::table('customers')
            ->selectRaw('YEAR(created_at) as y')
            ->distinct()->orderByDesc('y')->pluck('y');

        return view('admin.customers.index', compact(
            'customers', 'type', 'totalTransfer', 'totalActivity',
            'day', 'month', 'year', 'years'
        ) + ['tarih' => request()->query('tarih', 'gelis')]);
    }

    public function exportCsv(\Illuminate\Http\Request $request)
    {
        $type  = $request->query('type');
        $day   = $request->query('day');
        $month = $request->query('month');
        $year  = $request->query('year');

        if ($day && !$month) $month = now()->month;
        if (($day || $month) && !$year) $year = now()->year;

        $prefix = in_array($type, ['transfer', 'activity'], true) ? $type . '-' : '';
        $filename = $prefix . 'customers-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        return response()->stream(function () use ($type, $day, $month, $year) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['First Name', 'Last Name', 'Email', 'Phone', 'Type', 'Package/Activity', 'Date', 'Total Spent (£)'], ';');

            $q = Customer::query()
                ->addSelect(['total_spent' => DB::table('payments')
                    ->selectRaw('COALESCE(SUM(amount), 0)')
                    ->whereColumn('payments.customer_id', 'customers.id')
                    ->where('payments.status', 'paid')
                ])
                ->orderByDesc('customers.id');

            $q;
            if (in_array($type, ['transfer', 'activity'], true)) {
                $q->where('customers.type', $type);
            }
            // Liste ile ayni mantik: varsayilan gelis tarihi
            $alan = $request->query('tarih') === 'kayit'
                ? 'customers.created_at'
                : 'customers.arrival_date';
            if ($year)  $q->whereYear($alan, $year);
            if ($month) $q->whereMonth($alan, $month);
            if ($day)   $q->whereDay($alan, $day);

            $q->chunk(200, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->first_name,
                            $row->last_name,
                            $row->email,
                            $row->phone,
                            $row->type,
                            $row->activity_name ?: $row->package,
                            optional($row->created_at)->format('Y-m-d H:i:s'),
                            number_format((float) $row->total_spent, 2, '.', ''),
                        ], ';');
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
