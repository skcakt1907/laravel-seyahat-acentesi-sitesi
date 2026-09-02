<?php

namespace App\Http\Controllers;

use App\Mail\BookingAdminMail;
use App\Mail\BookingCustomerMail;
use App\Mail\ContactAdminMail;
use App\Mail\ReviewAdminMail;
use App\Models\Customer;
use App\Services\GarantiHashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LandingController extends Controller
{
    public function index()
    {
        $slides = DB::table('slider')
            ->where('durum', 1)
            ->orderBy('sira', 'asc')
            ->get();

        $transferRoutes = DB::table('transfers')
            ->where('durum', 1)
            ->orderBy('sira', 'asc')
            ->get();

        $activities = DB::table('activities')
            ->where('durum', 1)
            ->orderBy('sira', 'asc')
            ->get();

        $ayar = DB::table('ayarlar')->first();

        $reviews = DB::table('reviews')
            ->where('approved', 1)
            ->orderBy('id', 'desc')
            ->get();

        $sectionOrder = $ayar && $ayar->section_order
            ? json_decode($ayar->section_order, true)
            : ['transfers', 'activities', 'about', 'why-us', 'testimonials', 'contact'];

        if (!in_array('about', $sectionOrder)) {
            $sectionOrder[] = 'about';
        }

        return view('front.home', compact('slides', 'transferRoutes', 'activities', 'ayar', 'reviews', 'sectionOrder'));
    }

    public function submitTransfer(Request $request)
    {
        // Honeypot — bots fill hidden field
        if ($request->filled('website')) {
            return redirect()->route('anasayfa')->with('success', 'Booking received.');
        }

        // Per-IP daily cap (max 8 transfer bookings per IP per 24h)
        $ipKey = 'transfer_book_ip_' . md5($request->ip());
        $ipCount = (int) \Cache::get($ipKey, 0);
        if ($ipCount >= 8) {
            return back()->withInput()->withErrors(['email' => 'Çok fazla rezervasyon denemesi. Lütfen yarın tekrar deneyin veya bizimle iletişime geçin.']);
        }

        // Per-email cooldown — same email can book max 3 transfers per 24h
        $emailKey = 'transfer_book_email_' . md5(strtolower($request->input('email', '')));
        $emailCount = (int) \Cache::get($emailKey, 0);
        if ($emailCount >= 3) {
            return back()->withInput()->withErrors(['email' => 'Bu e-posta adresi için günlük rezervasyon limitine ulaşıldı. Lütfen bizimle iletişime geçin.']);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'package' => 'required|string|max:120',
            'hotel_name' => 'required|string|max:200',
            'adult_count' => 'required|integer|min:1',
            'adult_names' => 'nullable|string|max:500',
            'child_count' => 'nullable|integer|min:0',
            'child_names' => 'nullable|string|max:500',
            'arrival_date' => 'required|date|after_or_equal:today',
            'arrival_time' => 'required|string|max:10',
            'arrival_flight' => 'required|string|max:30',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            'departure_time' => 'required|string|max:10',
            'departure_flight' => 'required|string|max:30',
            'notes' => 'nullable|string|max:1000',
        ]);

        $existing = Customer::where('first_name', $validated['first_name'])
            ->where('last_name', $validated['last_name'])
            ->where('email', $validated['email'])
            ->first();

        if ($existing) {
            $updateData = [...$validated, 'type' => 'transfer'];
            if ($existing->payment_status !== 'paid') {
                $updateData['payment_status'] = 'unpaid';
            }
            $existing->update($updateData);
            $customer = $existing->fresh();
        } else {
            $customer = Customer::create([...$validated, 'type' => 'transfer', 'payment_status' => 'unpaid', 'dil' => app()->getLocale()]);
        }

        \Cache::put($ipKey, $ipCount + 1, now()->addDay());
        \Cache::put($emailKey, $emailCount + 1, now()->addDay());

        // Silinirse geri kurulabilsin diye tam veri loga yazilir.
        \Log::info('[TRF] REZERVASYON', [
            'customer_id' => $customer->id,
            'kayit'       => $customer->only([
                'first_name','last_name','email','phone','package','type','hotel_name',
                'adult_count','child_count','arrival_date','arrival_time','arrival_flight',
                'departure_date','departure_time','departure_flight','notes','dil',
            ]),
        ]);

        // Transfer için ödeme yok — direkt mail gönder ve onay sayfasına yönlendir
        try {
            Mail::to($customer->email)->send(new BookingCustomerMail($customer));
            Mail::to(config('mail.admin_email'))->send(new BookingAdminMail($customer));
        } catch (\Throwable $e) {
            \Log::error('Transfer booking mail failed', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);
        }

        session(['confirmed_customer_id' => $customer->id]);
        return redirect()->route('confirmation', $customer->id);
    }

    public function confirmation(int $id)
    {
        if (session('confirmed_customer_id') !== $id) {
            return redirect()->route('anasayfa');
        }
        $customer = Customer::findOrFail($id);
        return view('front.confirmation', compact('customer'));
    }

    public function activityDetail(string $slug)
    {
        $activity = DB::table('activities')->where('slug', $slug)->where('durum', 1)->first();

        if (!$activity) {
            return redirect()->route('anasayfa')->with('error', 'Activity not found.');
        }

        $ayar = DB::table('ayarlar')->first();
        $gallery = $activity->gallery ? json_decode($activity->gallery, true) : [];

        return view('front.activity-detail', compact('activity', 'gallery', 'ayar'));
    }

    public function buyActivity(Request $request, string $slug)
    {
        // Honeypot — botlar bu görünmez alanı doldurur
        if ($request->filled('website')) {
            return $request->expectsJson()
                ? response()->json(['success' => true, 'customerId' => 0, 'posActive' => false])
                : redirect()->route('anasayfa')->with('success', 'Booking received.');
        }

        // IP başına günde 8 aktivite alımı
        $ipKey = 'activity_buy_ip_' . md5($request->ip());
        $ipCount = (int) \Cache::get($ipKey, 0);
        if ($ipCount >= 8) {
            $msg = 'Çok fazla rezervasyon denemesi. Lütfen yarın tekrar deneyin veya bizimle iletişime geçin.';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 429)
                : back()->withInput()->withErrors(['email' => $msg]);
        }

        // E-posta başına günde 3 aktivite alımı
        $emailKey = 'activity_buy_email_' . md5(strtolower($request->input('email', '')));
        $emailCount = (int) \Cache::get($emailKey, 0);
        if ($emailCount >= 3) {
            $msg = 'Bu e-posta adresi için günlük rezervasyon limitine ulaşıldı. Lütfen bizimle iletişime geçin.';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 429)
                : back()->withInput()->withErrors(['email' => $msg]);
        }

        \Log::info('[ACT] 0-BUY-INPUT', [
            'slug' => $slug,
            'ip' => $request->ip(),
            'ua' => substr((string) $request->userAgent(), 0, 120),
            'payload_keys' => array_keys($request->all()),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'activity_name' => $request->input('activity_name'),
            'hotel_name' => $request->input('hotel_name'),
            'adult_count' => $request->input('adult_count'),
            'child_count' => $request->input('child_count'),
            'arrival_date' => $request->input('arrival_date'),
            'notes_len' => strlen((string) $request->input('notes')),
        ]);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'activity_name' => 'required|string|max:120',
            'hotel_name' => 'nullable|string|max:200',
            'adult_count' => 'nullable|integer|min:1',
            'child_count' => 'nullable|integer|min:0',
            'arrival_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);
        \Log::info('[ACT] 1-BUY-VALIDATED', ['email' => $validated['email']]);

        $bookingData = [
            'phone' => $validated['phone'],
            'activity_name' => $validated['activity_name'],
            'package' => $slug,
            'type' => 'activity',
            'hotel_name' => $validated['hotel_name'] ?? null,
            'adult_count' => $validated['adult_count'] ?? 1,
            'child_count' => $validated['child_count'] ?? 0,
            'arrival_date' => $validated['arrival_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        \Cache::put($ipKey, $ipCount + 1, now()->addDay());
        \Cache::put($emailKey, $emailCount + 1, now()->addDay());

        $ayar = DB::table('ayarlar')->first();
        $odemeAyarlari = $ayar && $ayar->odeme_ayarlari ? json_decode($ayar->odeme_ayarlari, true) : null;
        $posAcik = $odemeAyarlari && !empty($odemeAyarlari['aktif']);

        \Log::info('[ACT] 3-BUY-POS-CHECK', [
            'email'     => $validated['email'],
            'pos_aktif' => $odemeAyarlari['aktif'] ?? null,
            'provider'  => $odemeAyarlari['provider'] ?? null,
        ]);

        // ---- Odeme sarti varsa musteri kaydi ACILMAZ ----
        // Form verisi `pending_bookings` icinde bekler. Musteri ancak banka odemeyi
        // onayladiginda olusur; odenmeyen deneme panele hic dusmez, silinecek sey olmaz.
        if ($posAcik) {
            // 24 saati gecmis tamamlanmamis form verilerini at (musteri kaydi degil)
            DB::table('pending_bookings')->where('created_at', '<', now()->subDay())->delete();

            $pendingId = DB::table('pending_bookings')->insertGetId([
                'data' => json_encode(array_merge([
                    'dil'        => app()->getLocale(),
                    'first_name' => $validated['first_name'],
                    'last_name'  => $validated['last_name'],
                    'email'      => $validated['email'],
                ], $bookingData), JSON_UNESCAPED_UNICODE),
                'email'      => $validated['email'],
                'type'       => 'activity',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Log::info('[ACT] 4-BUY-REDIRECT-PAYMENT', ['pending_id' => $pendingId]);
            session(['pending_payment_customer_id' => $pendingId]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'    => true,
                    'customerId' => $pendingId,
                    'posActive'  => true,
                    'processUrl' => route('payment.process', $pendingId),
                ]);
            }

            return redirect()->route('payment.garanti', $pendingId);
        }

        // ---- POS kapali: eskisi gibi kayit olusur ve mail gider ----
        $existing = Customer::where('first_name', $validated['first_name'])
            ->where('last_name', $validated['last_name'])
            ->where('email', $validated['email'])
            ->first();

        if ($existing) {
            $updateData = $bookingData;
            if ($existing->payment_status !== 'paid') {
                $updateData['payment_status'] = 'unpaid';
            }
            $existing->update($updateData);
            $customer = $existing->fresh();
            \Log::info('[ACT] 2-BUY-CUSTOMER-UPDATED', ['customer_id' => $customer->id]);
        } else {
            $customer = Customer::create(array_merge([
                'dil'            => app()->getLocale(),
                'first_name'     => $validated['first_name'],
                'last_name'      => $validated['last_name'],
                'email'          => $validated['email'],
                'payment_status' => 'unpaid',
            ], $bookingData));
            \Log::info('[ACT] 2-BUY-CUSTOMER-CREATED', ['customer_id' => $customer->id]);
        }

        // POS kapalı — mail gönder ve onay sayfasına yönlendir
        try {
            Mail::to($customer->email)->send(new BookingCustomerMail($customer));
            Mail::to(config('mail.admin_email'))->send(new BookingAdminMail($customer));
        } catch (\Throwable $e) {
            \Log::error('Activity booking mail failed', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);
        }

        if ($request->expectsJson()) {
            $whatsappUrl = '';
            if (!empty($ayar->whatsapp)) {
                $whatsappUrl = 'https://wa.me/' . $ayar->whatsapp . '?text=' . urlencode(
                    'Hi, I have a booking #TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT)
                    . ' - ' . ($customer->activity_name ?: $customer->package)
                );
            }
            return response()->json([
                'success'      => true,
                'customerId'   => $customer->id,
                'posActive'    => false,
                'bookingId'    => '#TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT),
                'whatsappUrl'  => $whatsappUrl,
                'confirmUrl'   => route('confirmation', $customer->id),
            ]);
        }

        session(['confirmed_customer_id' => $customer->id]);
        return redirect()->route('confirmation', $customer->id);
    }

    /**
     * Bekleyen rezervasyonu KAYDEDILMEMIS bir Customer nesnesi olarak dondurur.
     * Odeme sayfalari ve sablonlar degismeden calisir; `customers` tablosunda satir yoktur.
     */
    private function bekleyenMusteri(int $pendingId): ?Customer
    {
        $satir = DB::table('pending_bookings')->where('id', $pendingId)->first();
        if (!$satir) {
            return null;
        }

        $customer = new Customer(json_decode($satir->data, true) ?: []);
        $customer->id = $pendingId;      // rota ve siparis numarasi icin
        $customer->payment_status = 'unpaid';
        $customer->exists = false;       // yanlislikla kaydedilmesin

        return $customer;
    }

    /**
     * Odeme onaylandi: bekleyen kayittan GERCEK musteriyi olusturur.
     * Ayni kisi zaten kayitliysa uzerine yazar, mukerrer satir acmaz.
     */
    private function bekleyeniMusteriyeCevir(int $pendingId): ?Customer
    {
        $satir = DB::table('pending_bookings')->where('id', $pendingId)->first();
        if (!$satir) {
            return null;
        }

        $veri = json_decode($satir->data, true) ?: [];

        $mevcut = Customer::where('first_name', $veri['first_name'] ?? '')
            ->where('last_name', $veri['last_name'] ?? '')
            ->where('email', $veri['email'] ?? '')
            ->first();

        if ($mevcut) {
            $mevcut->update($veri);
            $customer = $mevcut->fresh();
        } else {
            $customer = Customer::create($veri);
        }

        $customer->forceFill(['payment_status' => 'paid'])->save();
        DB::table('pending_bookings')->where('id', $pendingId)->delete();

        \Log::info('[POS] BEKLEYEN-MUSTERIYE-CEVRILDI', [
            'pending_id' => $pendingId, 'customer_id' => $customer->id,
        ]);

        return $customer;
    }

    public function garantiPayment(int $id)
    {
        \Log::info('[POS] 0-PAGE-LOAD', ['customer_id' => $id, 'ip' => request()->ip()]);
        if (session('pending_payment_customer_id') !== $id) {
            \Log::warning('[POS] 0-PAGE-UNAUTHORIZED', ['customer_id' => $id, 'session_id' => session('pending_payment_customer_id')]);
            return redirect()->route('anasayfa');
        }
        $customer = $this->bekleyenMusteri($id);
        if (!$customer) {
            \Log::warning('[POS] 0-PAGE-PENDING-YOK', ['pending_id' => $id]);
            return redirect()->route('anasayfa');
        }
        $ayar = DB::table('ayarlar')->first();
        $odemeAyarlari = $ayar && $ayar->odeme_ayarlari ? json_decode($ayar->odeme_ayarlari, true) : null;

        if (!$odemeAyarlari || empty($odemeAyarlari['aktif'])) {
            \Log::info('[POS] 0-PAGE-POS-OFF', ['customer_id' => $id]);
            return view('front.confirmation', compact('customer', 'ayar'));
        }

        $price = 0;
        $priceSource = null;
        if ($customer->type === 'activity') {
            $record = DB::table('activities')->where('slug', $customer->package)->first();
            $price = $record->price ?? 0;
            $priceSource = ['table' => 'activities', 'slug' => $customer->package, 'found' => (bool) $record, 'raw_price' => $record->price ?? null];
        } elseif ($customer->type === 'transfer') {
            $record = DB::table('transfers')->where('title', $customer->package)->first();
            $price = $record->price ?? 0;
            $priceSource = ['table' => 'transfers', 'title' => $customer->package, 'found' => (bool) $record, 'raw_price' => $record->price ?? null];
        }

        \Log::info('[POS] 0-PAGE-PRICE', [
            'customer_id' => $id,
            'type' => $customer->type,
            'price_gbp' => $price,
            'source' => $priceSource,
        ]);

        return view('front.payment-garanti', compact('customer', 'ayar', 'odemeAyarlari', 'price'));
    }

    public function processPayment(Request $request, int $id)
    {
        \Log::info('[POS] 0-PROCESS-INPUT', [
            'customer_id' => $id,
            'ip' => $request->ip(),
            'ua' => substr((string) $request->userAgent(), 0, 120),
            'has_card_number' => $request->filled('card_number'),
            'card_number_len' => strlen(preg_replace('/\D/', '', (string) $request->card_number)),
            'has_cvv' => $request->filled('cvv'),
            'cvv_len' => strlen((string) $request->cvv),
            'exp_month' => $request->expiry_month,
            'exp_year' => $request->expiry_year,
            'has_holder' => $request->filled('card_holder'),
        ]);

        if (session('pending_payment_customer_id') !== $id) {
            \Log::warning('[POS] 0-PROCESS-UNAUTHORIZED', ['customer_id' => $id, 'session_id' => session('pending_payment_customer_id')]);
            return redirect()->route('anasayfa');
        }

        $customer = $this->bekleyenMusteri($id);
        if (!$customer) {
            \Log::warning('[POS] 0-PROCESS-PENDING-YOK', ['pending_id' => $id]);
            return redirect()->route('anasayfa');
        }
        $ayar = DB::table('ayarlar')->first();
        $odemeAyarlari = $ayar && $ayar->odeme_ayarlari ? json_decode($ayar->odeme_ayarlari, true) : null;

        if (!$odemeAyarlari || empty($odemeAyarlari['aktif']) || empty($odemeAyarlari['provider'])) {
            \Log::warning('[POS] 0-PROCESS-POS-OFF', ['customer_id' => $id]);
            return redirect()->route('anasayfa')->with('error', 'Payment gateway is not configured.');
        }

        $request->validate([
            'card_holder' => 'required|string|max:100',
            'card_number' => 'required|string|max:19',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|size:2',
            'cvv' => 'required|string|min:3|max:4',
        ]);

        $provider = $odemeAyarlari['provider'];
        $cardNumber = preg_replace('/\D/', '', $request->card_number);
        $expMonth = $request->expiry_month;
        $expYear = $request->expiry_year;
        $cvv = $request->cvv;
        $cardHolder = $request->card_holder;

        $price = 0;
        $priceSource = null;
        if ($customer->type === 'activity') {
            $record = DB::table('activities')->where('slug', $customer->package)->first();
            $price = $record->price ?? 0;
            $priceSource = ['table' => 'activities', 'slug' => $customer->package, 'found' => (bool) $record];
        } elseif ($customer->type === 'transfer') {
            $record = DB::table('transfers')->where('title', $customer->package)->first();
            $price = $record->price ?? 0;
            $priceSource = ['table' => 'transfers', 'title' => $customer->package, 'found' => (bool) $record];
        }

        \Log::info('[POS] 0-PROCESS-PRICE', [
            'customer_id' => $customer->id,
            'type' => $customer->type,
            'price_gbp' => $price,
            'source' => $priceSource,
            'provider' => $provider,
            'card_last4' => substr($cardNumber, -4),
            'card_bin' => substr($cardNumber, 0, 6),
            'holder_len' => strlen($cardHolder),
        ]);

        if ($price <= 0) {
            \Log::warning('[POS] 0-PROCESS-INVALID-PRICE', ['customer_id' => $customer->id]);
            return redirect()->route('payment.garanti', $customer->id)
                ->with('error', 'Invalid payment amount.');
        }

        if ($provider === 'garanti') {
            return $this->processGarantiPayment($customer, $odemeAyarlari, $cardNumber, $expMonth, $expYear, $cvv, $cardHolder, $price);
        }

        \Log::warning('[POS] 0-PROCESS-UNKNOWN-PROVIDER', ['provider' => $provider]);
        return redirect()->route('payment.garanti', $customer->id)
            ->with('info', 'This payment provider is not yet available.');
    }

    // ===== Garanti BBVA Sanal POS — 3D_PAY (apiversion=512, SHA512) =====
    private function processGarantiPayment($customer, $odemeAyarlari, $cardNumber, $expMonth, $expYear, $cvv, $cardHolder, $price)
    {
        $terminalId        = (string) ($odemeAyarlari['garanti_terminal_id'] ?? '');
        $merchantId        = (string) ($odemeAyarlari['garanti_merchant_id'] ?? '');
        $storeKey          = (string) ($odemeAyarlari['garanti_store_key'] ?? '');
        $provisionPassword = (string) ($odemeAyarlari['garanti_provision_password'] ?? '');
        $testMode          = !empty($odemeAyarlari['garanti_test_mode']);

        $amount = (int) round((float) $price * 100); // pence (GBP)

        $orderId    = 'TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT) . 'T' . time();
        $successUrl = route('payment.callback3d');
        $failUrl    = route('payment.callback3d');

        // Hash'e giren 7 alan — referans ASP.NET .cs formülü ile birebir
        // (apiversion HASH'E GİRMEZ, sadece form alanı olarak gönderilir)
        $hashInput = [
            'orderid'             => $orderId,
            'txnamount'           => (string) $amount,
            'txncurrencycode'     => '826',
            'successurl'          => $successUrl,
            'errorurl'            => $failUrl,
            'txntype'             => 'sales',
            'txninstallmentcount' => '',
        ];

        $hashService  = new GarantiHashService($terminalId, $provisionPassword, $storeKey);
        $secure3dHash = $hashService->secure3DHash($hashInput);

        \Log::info('[POS] 1-HASH', [
            'order'              => $orderId,
            'amount_pence'       => $amount,
            'gbp_price'          => $price,
            'test_mode'          => $testMode,
            'terminal'           => $terminalId,
            'merchant_id'        => $merchantId,
            'has_store_key'      => (bool) $storeKey,
            'has_prov_password'  => (bool) $provisionPassword,
            'success_url'        => $successUrl,
            'security_data_head' => substr($hashService->securityData(), 0, 8),
            'hash_head'          => substr($secure3dHash, 0, 8),
        ]);

        session([
            'garanti_order_id'    => $orderId,
            'garanti_customer_id' => $customer->id,
            'garanti_amount'      => $amount,
            'garanti_price'       => $price,
        ]);

        DB::table('payments')->insert([
            'customer_id'     => $customer->exists ? $customer->id : null,
            'pending_id'      => $customer->exists ? null : $customer->id,
            'order_id'        => $orderId,
            'amount'          => $price,
            'currency'        => 'GBP',
            'charge_amount'   => $price,
            'charge_currency' => 'GBP',
            'fx_rate'         => 1.0,
            'provider'        => 'garanti',
            'status'          => 'pending',
            'card_last4'      => substr($cardNumber, -4),
            'card_holder'     => $cardHolder,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        \Log::info('[POS] 2-PENDING', [
            'order'        => $orderId,
            'customer'     => $customer->id,
            'amount_gbp'   => $price,
            'amount_pence' => $amount,
            'card_last4'   => substr($cardNumber, -4),
            'card_bin'     => substr($cardNumber, 0, 6),
        ]);

        $gatewayUrl = $testMode
            ? 'https://sanalposprovtest.garantibbva.com.tr/servlet/gt3dengine'
            : 'https://sanalposprov.garanti.com.tr/servlet/gt3dengine';

        \Log::info('[POS] 3-REDIRECT', [
            'order'           => $orderId,
            'gateway'         => $gatewayUrl,
            'txnamount'       => $amount,
            'txncurrencycode' => '826',
            'card_bin'        => substr($cardNumber, 0, 6),
            'card_last4'      => substr($cardNumber, -4),
            'exp_month'       => $expMonth,
            'exp_year'        => $expYear,
        ]);

        // Garanti 3D Secure'e otomatik submit edilecek form (referans .cs sırasıyla)
        return response()
            ->view('front.payment-3d-redirect', [
                'gatewayUrl' => $gatewayUrl,
                'params'     => array_merge($hashInput, [
                    'mode'                    => $testMode ? 'TEST' : 'PROD',
                    'apiversion'              => '512',
                    'secure3dsecuritylevel'   => '3D',
                    'terminalprovuserid'      => 'PROVAUT',
                    'terminaluserid'          => 'PROVAUT',
                    'terminalmerchantid'      => $merchantId,
                    'terminalid'              => $terminalId, // ham değer, padding YOK
                    'cardholderpresentcode'   => '13',
                    'motoind'                 => 'N',
                    'lang'                    => 'en',
                    'customeremailaddress'    => $customer->email,
                    'customeripaddress'       => request()->ip(),
                    'cardnumber'              => $cardNumber,
                    'cardexpiredatemonth'     => $expMonth,
                    'cardexpiredateyear'      => $expYear,
                    'cardcvv2'                => $cvv,
                    'secure3dhash'            => $secure3dHash,
                ]),
            ])
            ->header('Content-Type', 'text/html; charset=ISO-8859-9');
    }

    // ===== Garanti 3D Secure callback + VPServlet provision (referans .cs ile birebir) =====
    public function paymentCallback3D(Request $request)
    {
        $orderId  = (string) $request->input('orderid');
        $mdStatus = (string) $request->input('mdstatus');

        $allCallback = $request->all();
        unset(
            $allCallback['cardnumber'],
            $allCallback['cardcvv2'],
            $allCallback['cardexpiredatemonth'],
            $allCallback['cardexpiredateyear']
        );

        \Log::info('[POS] 4-CALLBACK', [
            'order_id'         => $orderId,
            'mdstatus'         => $mdStatus,
            'mderrormessage'   => $request->input('mderrormessage'),
            'errmsg'           => $request->input('errmsg'),
            'cavv_present'     => $request->filled('cavv'),
            'eci'              => $request->input('eci'),
            'xid_present'      => $request->filled('xid'),
            'md_present'       => $request->filled('md'),
            'has_secure3dhash' => $request->filled('secure3dhash'),
            'ip'               => $request->ip(),
            'method'           => $request->method(),
            'all_keys'         => array_keys($allCallback),
        ]);
        \Log::info('[POS] 4-CALLBACK-FULL', $allCallback);

        if ($orderId === '') {
            \Log::warning('[POS] 4-CALLBACK-NO-ORDER');
            return redirect()->route('anasayfa')->with('error', 'Invalid payment callback.');
        }

        $pendingPayment = DB::table('payments')->where('order_id', $orderId)->first();
        if (!$pendingPayment) {
            \Log::error('[POS] PAYMENT NOT FOUND', ['order' => $orderId]);
            return redirect()->route('anasayfa')->with('error', 'Payment record not found.');
        }

        $customerId = $pendingPayment->customer_id;          // odeme onaylandiysa dolu
        $pendingId  = $pendingPayment->pending_id ?? null;   // musteri henuz yoksa dolu
        $yonId      = $customerId ?: $pendingId;             // ekran yonlendirmesi icin

        \Log::info('[POS] 4-PAYMENT-FOUND', [
            'order'       => $orderId,
            'customer_id' => $customerId,
            'status'      => $pendingPayment->status,
            'amount_gbp'  => $pendingPayment->amount,
        ]);

        // Idempotency — replay protection
        if ($pendingPayment->status !== 'pending') {
            \Log::info('[POS] 4-ALREADY-PROCESSED', ['order' => $orderId, 'status' => $pendingPayment->status]);
            session(['garanti_authorized_customer_id' => $yonId]);
            return $pendingPayment->status === 'paid'
                ? redirect()->route('payment.success', $customerId)
                : redirect()->route('payment.fail', $yonId);
        }

        // POS ayarları
        $ayar          = DB::table('ayarlar')->first();
        $odemeAyarlari = $ayar && $ayar->odeme_ayarlari ? json_decode($ayar->odeme_ayarlari, true) : [];

        $terminalId        = (string) ($odemeAyarlari['garanti_terminal_id'] ?? '');
        $merchantId        = (string) ($odemeAyarlari['garanti_merchant_id'] ?? '');
        $storeKey          = (string) ($odemeAyarlari['garanti_store_key'] ?? '');
        $provisionPassword = (string) ($odemeAyarlari['garanti_provision_password'] ?? '');
        $testMode          = !empty($odemeAyarlari['garanti_test_mode']);

        $hashService = new GarantiHashService($terminalId, $provisionPassword, $storeKey);

        // 1) Hash doğrulaması — bankanın gönderdiği secure3dhash bizim store_key'imizle yeniden hesaplanmalı
        if (!$hashService->verifyCallbackHash($request->all())) {
            \Log::warning('[POS] 4-HASH-MISMATCH', ['order' => $orderId]);
            DB::table('payments')->where('order_id', $orderId)->update([
                'status'        => 'failed',
                'error_message' => 'Hash verification failed',
                'updated_at'    => now(),
            ]);
            session(['garanti_authorized_customer_id' => $yonId]);
            return redirect()->route('payment.fail', $yonId);
        }

        \Log::info('[POS] 4-HASH-OK', ['order' => $orderId]);

        // 2) 3D doğrulaması (mdstatus 1-4 = başarılı, diğerleri başarısız)
        if (!in_array($mdStatus, ['1', '2', '3', '4'], true)) {
            $errorMsg = $request->input('mderrormessage')
                ?: $request->input('errmsg')
                ?: ('3D authentication failed (mdstatus=' . $mdStatus . ')');

            DB::table('payments')->where('order_id', $orderId)->update([
                'status'        => 'failed',
                'error_message' => $errorMsg,
                'updated_at'    => now(),
            ]);

            \Log::info('[POS] 5-3D-FAILED', ['order' => $orderId, 'mdstatus' => $mdStatus, 'error' => $errorMsg]);

            session(['garanti_authorized_customer_id' => $yonId]);
            return redirect()->route('payment.fail', $yonId);
        }

        \Log::info('[POS] 5-3D-PASSED', ['order' => $orderId, 'mdstatus' => $mdStatus]);

        // 3) VPServlet provizyon — 3D modelinde gerekli
        $price  = (float) $pendingPayment->amount;
        $amount = (int) round($price * 100); // pence (GBP)

        $provisionHash = $hashService->provisionHash($orderId, $amount, '826');

        $cavv     = (string) $request->input('cavv', '');
        $eci      = (string) $request->input('eci', '');
        $xid      = (string) $request->input('xid', '');
        $md       = (string) $request->input('md', '');
        $apiVer   = (string) $request->input('apiversion', '512');
        $mode     = $testMode ? 'TEST' : 'PROD';

        $customer = $customerId
            ? Customer::findOrFail($customerId)
            : $this->bekleyenMusteri((int) $pendingId);
        if (!$customer) {
            \Log::error('[POS] KAYIT-YOK', ['order' => $orderId, 'pending' => $pendingId]);
            return redirect()->route('anasayfa');
        }

        // XML şablonu — referans .cs satır 3103 ile birebir alan sırası
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<GVPSRequest>'
            . '<Mode>' . $mode . '</Mode>'
            . '<Version>' . $apiVer . '</Version>'
            . '<ChannelCode></ChannelCode>'
            . '<Terminal>'
            .     '<ProvUserID>PROVAUT</ProvUserID>'
            .     '<HashData>' . $provisionHash . '</HashData>'
            .     '<UserID>PROVAUT</UserID>'
            .     '<ID>' . $terminalId . '</ID>'
            .     '<MerchantID>' . $merchantId . '</MerchantID>'
            . '</Terminal>'
            . '<Customer>'
            .     '<IPAddress>' . $request->ip() . '</IPAddress>'
            .     '<EmailAddress>' . htmlspecialchars($customer->email, ENT_XML1) . '</EmailAddress>'
            . '</Customer>'
            . '<Card>'
            .     '<Number></Number>'
            .     '<ExpireDate></ExpireDate>'
            .     '<CVV2></CVV2>'
            . '</Card>'
            . '<Order>'
            .     '<OrderID>' . $orderId . '</OrderID>'
            .     '<GroupID></GroupID>'
            . '</Order>'
            . '<Transaction>'
            .     '<Type>sales</Type>'
            .     '<InstallmentCnt></InstallmentCnt>'
            .     '<Amount>' . $amount . '</Amount>'
            .     '<CurrencyCode>826</CurrencyCode>'
            .     '<CardholderPresentCode>13</CardholderPresentCode>'
            .     '<MotoInd>N</MotoInd>'
            .     '<Secure3D>'
            .         '<AuthenticationCode>' . htmlspecialchars($cavv, ENT_XML1) . '</AuthenticationCode>'
            .         '<SecurityLevel>' . htmlspecialchars($eci, ENT_XML1) . '</SecurityLevel>'
            .         '<TxnID>' . htmlspecialchars($xid, ENT_XML1) . '</TxnID>'
            .         '<Md>' . htmlspecialchars($md, ENT_XML1) . '</Md>'
            .     '</Secure3D>'
            . '</Transaction>'
            . '</GVPSRequest>';

        $provisionUrl = $testMode
            ? 'https://sanalposprovtest.garantibbva.com.tr/VPServlet'
            : 'https://sanalposprov.garanti.com.tr/VPServlet';

        \Log::info('[POS] 6-PROVISION', [
            'order'             => $orderId,
            'amount_pence'      => $amount,
            'amount_gbp'        => $price,
            'test_mode'         => $testMode,
            'terminal'          => $terminalId,
            'merchant'          => $merchantId,
            'security_data_head'=> substr($hashService->securityData(), 0, 8),
            'hash_head'         => substr($provisionHash, 0, 8),
            'cavv_len'          => strlen($cavv),
            'eci'               => $eci,
            'md_len'            => strlen($md),
            'provision_url'     => $provisionUrl,
        ]);

        $t0 = microtime(true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $provisionUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . urlencode($xml));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        $curlErr  = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $elapsed = round((microtime(true) - $t0) * 1000);

        \Log::info('[POS] 7-VPSERVLET', [
            'order'        => $orderId,
            'http_code'    => $httpCode,
            'curl_error'   => $curlErr,
            'elapsed_ms'   => $elapsed,
            'response_len' => strlen((string) $response),
            'response'     => $response,
        ]);

        // 4) XML response parse — referans .cs ReasonCode=00 kontrolü
        $reasonCode = '';
        $code       = '99';
        $errorMsg   = 'Unknown error';
        $authCode   = '';
        $hostRefNum = '';
        $sysErr     = '';

        if ($response) {
            $xmlResponse = @simplexml_load_string($response);
            if ($xmlResponse) {
                $code       = (string) ($xmlResponse->Transaction->Response->Code ?? '99');
                $reasonCode = (string) ($xmlResponse->Transaction->Response->ReasonCode ?? '');
                $errorMsg   = (string) ($xmlResponse->Transaction->Response->ErrorMsg ?? 'Unknown error');
                $sysErr     = (string) ($xmlResponse->Transaction->Response->SysErrMsg ?? '');
                $authCode   = (string) ($xmlResponse->Transaction->AuthCode ?? '');
                $hostRefNum = (string) ($xmlResponse->Transaction->HostRefNum ?? '');
            } else {
                \Log::warning('[POS] 7-VPSERVLET-PARSE-FAIL', ['order' => $orderId]);
            }
        }

        \Log::info('[POS] 8-PARSED', [
            'order'        => $orderId,
            'code'         => $code,
            'reason_code'  => $reasonCode,
            'error'        => $errorMsg,
            'sys_err'      => $sysErr,
            'auth_code'    => $authCode,
            'host_ref_num' => $hostRefNum,
        ]);

        // Referans .cs satır 3127: ReasonCode=00 → başarılı
        if ($reasonCode === '00') {
            // NOT: `customers` tablosu MyISAM — transaction geri alma YAPMAZ.
            // Bu yuzden islem sirasi, her arizada parasi alinmis satisin
            // kayitli kalmasini saglayacak sekilde secildi.
            try {
                if ($customerId) {
                    // Eski akis: kayit zaten vardi
                    Customer::where('id', $customerId)->update(['payment_status' => 'paid']);
                } else {
                    // 1. ADIM — once musteri olusur. Bundan sonrasi bozulsa bile
                    //           satis panelde gorunur, kaybolmaz.
                    $gercek = $this->bekleyeniMusteriyeCevir((int) $pendingId);
                    if (!$gercek) {
                        throw new \RuntimeException('Bekleyen rezervasyon bulunamadi: ' . $pendingId);
                    }
                    $customer   = $gercek;
                    $customerId = $gercek->id;
                }

                // 2. ADIM — odeme kaydi tek guncellemede kapatilir (InnoDB)
                $yazildi = DB::table('payments')
                    ->where('order_id', $orderId)
                    ->where('status', 'pending')
                    ->update([
                        'status'         => 'paid',
                        'customer_id'    => $customerId,
                        'transaction_id' => $hostRefNum ?: $authCode ?: $reasonCode,
                        'updated_at'     => now(),
                    ]);

                if (!$yazildi) {
                    // Musteri kaydi olustu ama odeme satiri kapanmadi — satis kayipsiz,
                    // sadece muhasebe eslesmesi eksik. Gurultulu logla, akisi bozma.
                    \Log::error('[POS] ODEME-SATIRI-KAPANMADI', [
                        'order' => $orderId, 'customer' => $customerId,
                    ]);
                }
            } catch (\Throwable $e) {
                // Para alindi ama kayit acilamadi — en kotu durum. Sessiz kalma.
                \Log::critical('[POS] PARA-ALINDI-KAYIT-YOK', [
                    'order'      => $orderId,
                    'pending_id' => $pendingId,
                    'tutar'      => $price ?? null,
                    'err'        => $e->getMessage(),
                ]);

                try {
                    Mail::raw(
                        "ACIL: Odeme alindi ama rezervasyon kaydi olusturulamadi.\n\n"
                        . "Siparis no : {$orderId}\n"
                        . "Bekleyen no: {$pendingId}\n"
                        . "Hata       : {$e->getMessage()}\n\n"
                        . "payments tablosundan siparisi bulup musteriyi elle olusturun.",
                        fn ($m) => $m->to(config('mail.admin_email'))
                                     ->subject('ACIL — Odeme alindi, kayit yok: ' . $orderId)
                    );
                } catch (\Throwable $e2) {
                    \Log::critical('[POS] UYARI-MAILI-DE-GITMEDI', ['err' => $e2->getMessage()]);
                }

                return redirect()->route('payment.fail', $yonId)
                    ->with('error', 'Payment finalization failed. Please contact support.');
            }

            try {
                Mail::to($customer->email)->send(new BookingCustomerMail($customer));
                Mail::to(config('mail.admin_email'))->send(new BookingAdminMail($customer));
            } catch (\Throwable $e) {
                \Log::error('[POS] MAIL ERROR', ['customer' => $customerId, 'err' => $e->getMessage()]);
            }

            \Log::info('[POS] 9-SUCCESS', [
                'order'        => $orderId,
                'customer'     => $customerId,
                'auth_code'    => $authCode,
                'host_ref_num' => $hostRefNum,
                'reason_code'  => $reasonCode,
                'amount_gbp'   => $price,
            ]);

            session(['garanti_authorized_customer_id' => $customerId]);
            return redirect()->route('payment.success', $customerId);
        }

        // Provizyon başarısız
        DB::table('payments')->where('order_id', $orderId)->update([
            'status'         => 'failed',
            'error_message'  => $errorMsg ?: $sysErr ?: ('VPServlet declined (code=' . $code . ', reason=' . $reasonCode . ')'),
            'transaction_id' => $hostRefNum ?: null,
            'updated_at'     => now(),
        ]);

        \Log::info('[POS] 9-FAILED', [
            'order'        => $orderId,
            'customer'     => $customerId,
            'code'         => $code,
            'reason_code'  => $reasonCode,
            'error'        => $errorMsg,
            'sys_err'      => $sysErr,
            'amount_try'   => $tryAmount,
            'amount_gbp'   => $price,
        ]);

        session(['garanti_authorized_customer_id' => $yonId]);
        return redirect()->route('payment.fail', $yonId);
    }

    public function paymentSuccess(int $id)
    {
        if (session('garanti_authorized_customer_id') !== $id) {
            return redirect()->route('anasayfa');
        }
        $customer = Customer::findOrFail($id);
        $ayar = DB::table('ayarlar')->first();
        $payment = DB::table('payments')->where('customer_id', $id)->where('status', 'paid')->latest('id')->first();
        return view('front.payment-success', compact('customer', 'ayar', 'payment'));
    }

    public function paymentFail(int $id)
    {
        if (session('garanti_authorized_customer_id') !== $id) {
            return redirect()->route('anasayfa');
        }
        $customer = Customer::find($id) ?: $this->bekleyenMusteri($id);
        if (!$customer) {
            return redirect()->route('anasayfa');
        }
        $ayar = DB::table('ayarlar')->first();
        $payment = DB::table('payments')
            ->where('status', 'failed')
            ->where(fn ($q) => $q->where('customer_id', $id)->orWhere('pending_id', $id))
            ->latest('id')->first();
        return view('front.payment-fail', compact('customer', 'ayar', 'payment'));
    }

    public function reviewPage()
    {
        $ayar = DB::table('ayarlar')->first();
        $reviews = DB::table('reviews')
            ->where('approved', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view('front.reviews', compact('ayar', 'reviews'));
    }

    public function submitReview(Request $request)
    {
        if ($request->filled('website')) {
            return redirect()->route('reviews')->with('success', 'Thank you for your review!');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'location' => 'nullable|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $email = strtolower(trim($request->email));

        // Duplicate guard: same email submitted in the last 24 hours
        $recent = DB::table('reviews')
            ->where('email', $email)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($recent) {
            return redirect()->route('reviews')
                ->with('success', 'Thank you for your review! It will appear after approval.');
        }

        DB::table('reviews')->insert([
            'name' => $request->name,
            'email' => $email,
            'location' => $request->location,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'approved' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::to(config('mail.admin_email'))->send(new ReviewAdminMail(
            reviewName: $request->name,
            reviewLocation: $request->location ?? '',
            reviewRating: (int) $request->rating,
            reviewComment: $request->comment,
        ));

        return redirect()->route('reviews')->with('success', 'Thank you for your review! It will appear after approval.');
    }

    public function aboutPage()
    {
        $ayar = DB::table('ayarlar')->first();
        $transferRoutes = DB::table('transfers')->where('durum', 1)->orderBy('sira', 'asc')->get();
        return view('front.about', compact('ayar', 'transferRoutes'));
    }

    public function privacyPage()
    {
        $ayar = DB::table('ayarlar')->first();
        return view('front.privacy', compact('ayar'));
    }

    public function termsPage()
    {
        $ayar = DB::table('ayarlar')->first();
        return view('front.terms', compact('ayar'));
    }

    public function submitContact(Request $request)
    {
        if ($request->filled('hp_field_xz9')) {
            \Log::warning('Contact honeypot triggered', ['ip' => $request->ip(), 'value' => $request->input('hp_field_xz9')]);
            return redirect()->route('anasayfa')->with('success', 'Message sent!');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        DB::table('contact_messages')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::to(config('mail.admin_email'))->send(new ContactAdminMail(
                contactName: $request->name,
                contactEmail: $request->email,
                contactSubject: $request->subject,
                contactMessage: $request->message,
            ));
        } catch (\Throwable $e) {
            \Log::error('Contact admin mail failed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('anasayfa', ['#contact'])->with('contact_success', 'Message sent successfully! We\'ll get back to you soon.');
    }
}
