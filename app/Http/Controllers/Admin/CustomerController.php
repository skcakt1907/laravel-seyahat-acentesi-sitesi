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
            'notes'         => 'nullable|string|max:1000',
        ]);
        $data['payment_status'] = 'unpaid';
        $data['seen'] = 1;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $id = DB::table('customers')->insertGetId($data);

        return redirect()->route('admin.customers.show', $id)->with('success', 'Müşteri eklendi.');
    }

    public function destroy(int $id)
    {
        DB::table('payments')->where('customer_id', $id)->delete();
        DB::table('customers')->where('id', $id)->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Müşteri silindi.');
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
            $amount = (float) ($rec->price ?? 0);
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

    public function index()
    {
        // Mark all unseen customers as seen
        DB::table('customers')->where('seen', 0)->update(['seen' => 1]);

        $customers = Customer::query()
            ->leftJoin('payments', function ($j) {
                $j->on('payments.customer_id', '=', 'customers.id')
                  ->where('payments.status', '=', 'paid');
            })
            ->select('customers.*', DB::raw('COALESCE(SUM(payments.amount), 0) as total_spent'))
            ->groupBy('customers.id')
            ->orderByDesc('customers.id')
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function exportCsv()
    {
        $filename = 'customers-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['First Name', 'Last Name', 'Email', 'Phone', 'Type', 'Package/Activity', 'Date', 'Total Spent (£)'], ';');

            Customer::query()
                ->leftJoin('payments', function ($j) {
                    $j->on('payments.customer_id', '=', 'customers.id')
                      ->where('payments.status', '=', 'paid');
                })
                ->select('customers.*', DB::raw('COALESCE(SUM(payments.amount), 0) as total_spent'))
                ->groupBy('customers.id')
                ->orderByDesc('customers.id')
                ->chunk(200, function ($rows) use ($handle) {
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

