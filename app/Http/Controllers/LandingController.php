<?php

namespace App\Http\Controllers;

use App\Mail\BookingAdminMail;
use App\Mail\BookingCustomerMail;
use App\Mail\ContactAdminMail;
use App\Mail\ReviewAdminMail;
use App\Models\Customer;
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
            $existing->update([...$validated, 'type' => 'transfer']);
            $customer = $existing;
        } else {
            $customer = Customer::create([...$validated, 'type' => 'transfer']);
            Mail::to($customer->email)->send(new BookingCustomerMail($customer));
            Mail::to(config('mail.admin_email'))->send(new BookingAdminMail($customer));
        }

        return redirect()->route('confirmation', $customer->id);
    }

    public function confirmation(int $id)
    {
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
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'activity_name' => 'required|string|max:120',
        ]);

        $existing = Customer::where('first_name', $validated['first_name'])
            ->where('last_name', $validated['last_name'])
            ->where('email', $validated['email'])
            ->first();

        if ($existing) {
            $existing->update(['phone' => $validated['phone'], 'activity_name' => $validated['activity_name'], 'package' => $slug, 'type' => 'activity']);
            $customer = $existing;
        } else {
            $customer = Customer::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'type' => 'activity',
                'activity_name' => $validated['activity_name'],
                'package' => $slug,
            ]);
            Mail::to($customer->email)->send(new BookingCustomerMail($customer));
            Mail::to(config('mail.admin_email'))->send(new BookingAdminMail($customer));
        }

        return redirect()->route('payment.garanti', $customer->id);
    }

    public function garantiPayment(int $id)
    {
        $customer = Customer::findOrFail($id);
        $ayar = DB::table('ayarlar')->first();
        $odemeAyarlari = $ayar && $ayar->odeme_ayarlari ? json_decode($ayar->odeme_ayarlari, true) : null;

        $price = 0;
        if ($customer->type === 'activity') {
            $record = DB::table('activities')->where('slug', $customer->package)->first();
            $price = $record->price ?? 0;
        } elseif ($customer->type === 'transfer') {
            $record = DB::table('transfers')->where('title', $customer->package)->first();
            $price = $record->price ?? 0;
        }

        return view('front.payment-garanti', compact('customer', 'ayar', 'odemeAyarlari', 'price'));
    }

    public function processPayment(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);
        $ayar = DB::table('ayarlar')->first();
        $odemeAyarlari = $ayar && $ayar->odeme_ayarlari ? json_decode($ayar->odeme_ayarlari, true) : null;

        if (!$odemeAyarlari || empty($odemeAyarlari['aktif']) || empty($odemeAyarlari['provider'])) {
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

        // Calculate price
        $price = 0;
        if ($customer->type === 'activity') {
            $record = DB::table('activities')->where('slug', $customer->package)->first();
            $price = $record->price ?? 0;
        } elseif ($customer->type === 'transfer') {
            $record = DB::table('transfers')->where('title', $customer->package)->first();
            $price = $record->price ?? 0;
        }

        if ($price <= 0) {
            return redirect()->route('payment.garanti', $customer->id)
                ->with('error', 'Invalid payment amount.');
        }

        // Garanti POS 3D Secure
        if ($provider === 'garanti') {
            return $this->processGarantiPayment($customer, $odemeAyarlari, $cardNumber, $expMonth, $expYear, $cvv, $cardHolder, $price);
        }

        return redirect()->route('payment.garanti', $customer->id)
            ->with('info', 'This payment provider is not yet available.');
    }

    private function processGarantiPayment($customer, $odemeAyarlari, $cardNumber, $expMonth, $expYear, $cvv, $cardHolder, $price)
    {
        $terminalId = $odemeAyarlari['garanti_terminal_id'] ?? '';
        $merchantId = $odemeAyarlari['garanti_merchant_id'] ?? '';
        $storeKey = $odemeAyarlari['garanti_store_key'] ?? '';
        $provisionPassword = $odemeAyarlari['garanti_provision_password'] ?? '';
        $testMode = !empty($odemeAyarlari['garanti_test_mode']);

        // Amount in kuruş (pennies) - price is in GBP, Garanti expects smallest unit * 100
        $amount = (int)($price * 100);
        $orderId = 'TCM' . str_pad($customer->id, 5, '0', STR_PAD_LEFT) . 'T' . time();
        $successUrl = route('payment.callback3d');
        $failUrl = route('payment.callback3d');

        // Generate security hash
        // Garanti 3D Secure hash: SHA512(terminalId + orderId + amount + successUrl + failUrl + type + installment + storeKey + securityHash)
        $securityData = strtoupper(sha1($provisionPassword . str_pad($terminalId, 9, '0', STR_PAD_LEFT)));
        $hashData = $terminalId . $orderId . $amount . $successUrl . $failUrl . 'sales' . '' . $storeKey . $securityData;
        $hash = strtoupper(hash('sha512', $hashData));

        // Store order info in session for callback verification
        session([
            'garanti_order_id' => $orderId,
            'garanti_customer_id' => $customer->id,
            'garanti_amount' => $amount,
            'garanti_price' => $price,
        ]);

        // Save payment record as pending
        DB::table('payments')->insert([
            'customer_id' => $customer->id,
            'order_id' => $orderId,
            'amount' => $price,
            'currency' => 'GBP',
            'provider' => 'garanti',
            'status' => 'pending',
            'card_last4' => substr($cardNumber, -4),
            'card_holder' => $cardHolder,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $gatewayUrl = $testMode
            ? 'https://sanalposprovtest.garanti.com.tr/servlet/gt3dengine'
            : 'https://sanalposprov.garanti.com.tr/servlet/gt3dengine';

        // Return auto-submit form to redirect to Garanti 3D Secure page
        return response()->view('front.payment-3d-redirect', [
            'gatewayUrl' => $gatewayUrl,
            'params' => [
                'mode' => $testMode ? 'TEST' : 'PROD',
                'apiversion' => 'v0.01',
                'terminalprovuserid' => 'PROVAUT',
                'terminaluserid' => $merchantId,
                'terminalmerchantid' => $merchantId,
                'terminalid' => str_pad($terminalId, 9, '0', STR_PAD_LEFT),
                'txntype' => 'sales',
                'txnamount' => $amount,
                'txncurrencycode' => '826', // GBP currency code
                'txninstallmentcount' => '',
                'orderid' => $orderId,
                'successurl' => $successUrl,
                'errorurl' => $failUrl,
                'customeremailaddress' => $customer->email,
                'customeripaddress' => request()->ip(),
                'secure3dsecuritylevel' => '3D',
                'cardnumber' => $cardNumber,
                'cardexpiredatemonth' => $expMonth,
                'cardexpiredateyear' => $expYear,
                'cardcvv2' => $cvv,
                'secure3dhash' => $hash,
            ],
        ]);
    }

    public function paymentCallback3D(Request $request)
    {
        $mdStatus = $request->input('mdstatus');
        $orderId = $request->input('orderid');
        $customerId = session('garanti_customer_id');

        if (!$customerId || !$orderId) {
            return redirect()->route('anasayfa')->with('error', 'Invalid payment session.');
        }

        // Check 3D Secure authentication result
        // mdstatus: 1 = success, 2,3,4 = card not enrolled but attempted, others = fail
        if (!in_array($mdStatus, ['1', '2', '3', '4'])) {
            DB::table('payments')->where('order_id', $orderId)->update([
                'status' => 'failed',
                'error_message' => $request->input('mderrormessage', '3D Secure authentication failed'),
                'updated_at' => now(),
            ]);

            return redirect()->route('payment.fail', $customerId);
        }

        // 3D auth passed - now complete the payment via GVP XML API
        $ayar = DB::table('ayarlar')->first();
        $odemeAyarlari = json_decode($ayar->odeme_ayarlari, true);
        $testMode = !empty($odemeAyarlari['garanti_test_mode']);
        $terminalId = str_pad($odemeAyarlari['garanti_terminal_id'] ?? '', 9, '0', STR_PAD_LEFT);
        $merchantId = $odemeAyarlari['garanti_merchant_id'] ?? '';
        $provisionPassword = $odemeAyarlari['garanti_provision_password'] ?? '';
        $amount = session('garanti_amount');
        $price = session('garanti_price');

        $securityData = strtoupper(sha1($provisionPassword . $terminalId));
        $hashData = $orderId . $terminalId . $amount . $securityData;
        $hash = strtoupper(hash('sha512', $hashData));

        $cavv = $request->input('cavv', '');
        $eci = $request->input('eci', '');
        $md = $request->input('md', '');

        $customer = Customer::findOrFail($customerId);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<GVPSRequest>
    <Mode>' . ($testMode ? 'TEST' : 'PROD') . '</Mode>
    <Version>v0.01</Version>
    <Terminal>
        <ProvUserID>PROVAUT</ProvUserID>
        <HashData>' . $hash . '</HashData>
        <UserID>' . $merchantId . '</UserID>
        <ID>' . $terminalId . '</ID>
        <MerchantID>' . $merchantId . '</MerchantID>
    </Terminal>
    <Customer>
        <IPAddress>' . request()->ip() . '</IPAddress>
        <EmailAddress>' . htmlspecialchars($customer->email) . '</EmailAddress>
    </Customer>
    <Order>
        <OrderID>' . $orderId . '</OrderID>
    </Order>
    <Transaction>
        <Type>sales</Type>
        <InstallmentCnt/>
        <Amount>' . $amount . '</Amount>
        <CurrencyCode>826</CurrencyCode>
        <CardholderPresentCode>13</CardholderPresentCode>
        <MotoInd>N</MotoInd>
        <Secure3D>
            <AuthenticationCode>' . $cavv . '</AuthenticationCode>
            <SecurityLevel>' . $eci . '</SecurityLevel>
            <TxnID>' . $md . '</TxnID>
            <Md>' . $md . '</Md>
        </Secure3D>
    </Transaction>
</GVPSRequest>';

        $provisionUrl = $testMode
            ? 'https://sanalposprovtest.garanti.com.tr/VPServlet'
            : 'https://sanalposprov.garanti.com.tr/VPServlet';

        // Send XML request to Garanti
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $provisionUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . urlencode($xml));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($ch);
        curl_close($ch);

        // Parse response
        $responseCode = '99';
        $reasonCode = '';
        $errorMsg = 'Unknown error';

        if ($response) {
            $xmlResponse = @simplexml_load_string($response);
            if ($xmlResponse) {
                $responseCode = (string)($xmlResponse->Transaction->Response->Code ?? '99');
                $reasonCode = (string)($xmlResponse->Transaction->Response->ReasonCode ?? '');
                $errorMsg = (string)($xmlResponse->Transaction->Response->ErrorMsg ?? 'Unknown error');
            }
        }

        if ($responseCode === '00') {
            // Payment successful
            DB::table('payments')->where('order_id', $orderId)->update([
                'status' => 'paid',
                'transaction_id' => $reasonCode,
                'updated_at' => now(),
            ]);

            // Update customer payment status
            Customer::where('id', $customerId)->update(['payment_status' => 'paid']);

            // Clear session
            session()->forget(['garanti_order_id', 'garanti_customer_id', 'garanti_amount', 'garanti_price']);

            return redirect()->route('payment.success', $customerId);
        } else {
            // Payment failed
            DB::table('payments')->where('order_id', $orderId)->update([
                'status' => 'failed',
                'error_message' => $errorMsg,
                'transaction_id' => $reasonCode,
                'updated_at' => now(),
            ]);

            session()->forget(['garanti_order_id', 'garanti_customer_id', 'garanti_amount', 'garanti_price']);

            return redirect()->route('payment.fail', $customerId);
        }
    }

    public function paymentSuccess(int $id)
    {
        $customer = Customer::findOrFail($id);
        $ayar = DB::table('ayarlar')->first();
        $payment = DB::table('payments')->where('customer_id', $id)->where('status', 'paid')->latest('id')->first();
        return view('front.payment-success', compact('customer', 'ayar', 'payment'));
    }

    public function paymentFail(int $id)
    {
        $customer = Customer::findOrFail($id);
        $ayar = DB::table('ayarlar')->first();
        $payment = DB::table('payments')->where('customer_id', $id)->where('status', 'failed')->latest('id')->first();
        return view('front.payment-fail', compact('customer', 'ayar', 'payment'));
    }

    public function reviewPage()
    {
        $ayar = DB::table('ayarlar')->first();
        $reviews = DB::table('reviews')
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
            'location' => 'nullable|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        DB::table('reviews')->insert([
            'name' => $request->name,
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
        if ($request->filled('website')) {
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

        Mail::to(config('mail.admin_email'))->send(new ContactAdminMail(
            contactName: $request->name,
            contactEmail: $request->email,
            contactSubject: $request->subject,
            contactMessage: $request->message,
        ));

        return redirect()->route('anasayfa', ['#contact'])->with('contact_success', 'Message sent successfully! We\'ll get back to you soon.');
    }
}

