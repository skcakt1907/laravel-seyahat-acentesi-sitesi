<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// ========================
// FRONTEND ROUTES
// ========================
Route::middleware('web')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('anasayfa');

    // Dil secimi — tercih cookie + session'da saklanir, ziyaretci geldigi sayfaya doner
    Route::get('/dil/{locale}', function (string $locale) {
        $izinli = ['tr', 'en', 'de', 'nl', 'ru', 'ar'];

        if (in_array($locale, $izinli, true)) {
            $harita = ['tr' => 1, 'en' => 2, 'ar' => 3, 'de' => 4, 'nl' => 5, 'ru' => 6];
            session(['locale' => $locale, 'k_dil' => $harita[$locale]]);
            app()->setLocale($locale);

            return redirect()->back()->withCookie(
                cookie('locale_preference', $locale, 60 * 24 * 365)
            );
        }

        return redirect()->back();
    })->name('lang.switch');

    // Eski adres: diskteki lang/ klasoruyle cakistigi icin /dil/ adresine tasindi
    Route::get('/lang/{locale}', fn (string $locale) => redirect('/dil/'.$locale));
    Route::redirect('/giris', '/admin/giris')->name('giris');
    Route::post('/transfer/submit', [LandingController::class, 'submitTransfer'])->middleware('throttle:5,1')->name('transfer.submit');
    Route::get('/confirmation/{id}', [LandingController::class, 'confirmation'])->name('confirmation');
    Route::get('/activity/{slug}', [LandingController::class, 'activityDetail'])->name('activity.detail');
    Route::post('/activity/{slug}/buy', [LandingController::class, 'buyActivity'])->middleware('throttle:5,1')->name('activity.buy');
    Route::get('/payment/garanti/{id}', [LandingController::class, 'garantiPayment'])->name('payment.garanti');
    Route::post('/payment/process/{id}', [LandingController::class, 'processPayment'])->name('payment.process');
    Route::match(['get', 'post'], '/payment/callback-3d', [LandingController::class, 'paymentCallback3D'])->name('payment.callback3d');
    Route::get('/payment/success/{id}', [LandingController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/fail/{id}', [LandingController::class, 'paymentFail'])->name('payment.fail');
    Route::get('/reviews', [LandingController::class, 'reviewPage'])->name('reviews');
    Route::post('/reviews', [LandingController::class, 'submitReview'])->middleware('throttle:3,1')->name('reviews.submit');
    Route::post('/contact', [LandingController::class, 'submitContact'])->middleware('throttle:3,1')->name('contact.submit');
    Route::get('/about', [LandingController::class, 'aboutPage'])->name('about');
    Route::get('/privacy', [LandingController::class, 'privacyPage'])->name('privacy');
    Route::get('/terms', [LandingController::class, 'termsPage'])->name('terms');
});

// ========================
// ADMIN PANEL ROUTES
// ========================
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth (no middleware)
    Route::get('/giris', [\App\Http\Controllers\Admin\AdminAuthController::class, 'giris'])->name('giris');
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'giris'])->name('login');
    Route::post('/giris', [\App\Http\Controllers\Admin\AdminAuthController::class, 'girisPost'])->name('giris.post');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'girisPost'])->name('login.post');
    Route::get('/cikis', [\App\Http\Controllers\Admin\AdminAuthController::class, 'cikis'])->name('cikis');

    Route::get('/', function () {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.giris');
    })->name('index');

    // Protected routes
    Route::middleware(['admin.auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Earnings
        Route::get('/earnings', [\App\Http\Controllers\Admin\EarningsController::class, 'index'])->name('earnings.index');

        // Customers
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/export-csv', [\App\Http\Controllers\Admin\CustomerController::class, 'exportCsv'])->name('customers.export');
        Route::get('/customers/create', [\App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('customers.store');
        Route::post('/customers/{id}/mark-paid', [\App\Http\Controllers\Admin\CustomerController::class, 'markPaid'])->name('customers.markPaid');
        Route::get('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
        Route::delete('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::post('/customers/{id}/note', [\App\Http\Controllers\Admin\CustomerController::class, 'updateNote'])->name('customers.updateNote');

        // Profil (şifre değiştirme)
        Route::get('/profil', [\App\Http\Controllers\Admin\AdminAuthController::class, 'profil'])->name('profil');
        Route::post('/profil/sifre', [\App\Http\Controllers\Admin\AdminAuthController::class, 'sifreGuncelle'])->name('profil.sifre');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

        // Transfers
        Route::get('/transfers', [\App\Http\Controllers\Admin\TransferController::class, 'index'])->name('transfers.index');
        Route::get('/transfers/create', [\App\Http\Controllers\Admin\TransferController::class, 'create'])->name('transfers.create');
        Route::post('/transfers', [\App\Http\Controllers\Admin\TransferController::class, 'store'])->name('transfers.store');
        Route::get('/transfers/{id}/edit', [\App\Http\Controllers\Admin\TransferController::class, 'edit'])->name('transfers.edit');
        Route::put('/transfers/{id}', [\App\Http\Controllers\Admin\TransferController::class, 'update'])->name('transfers.update');
        Route::delete('/transfers/{id}', [\App\Http\Controllers\Admin\TransferController::class, 'destroy'])->name('transfers.destroy');

        // Activities
        Route::get('/activities', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/create', [\App\Http\Controllers\Admin\ActivityController::class, 'create'])->name('activities.create');
        Route::post('/activities', [\App\Http\Controllers\Admin\ActivityController::class, 'store'])->name('activities.store');
        Route::get('/activities/{id}/edit', [\App\Http\Controllers\Admin\ActivityController::class, 'edit'])->name('activities.edit');
        Route::put('/activities/{id}', [\App\Http\Controllers\Admin\ActivityController::class, 'update'])->name('activities.update');
        Route::delete('/activities/{id}', [\App\Http\Controllers\Admin\ActivityController::class, 'destroy'])->name('activities.destroy');

        // About / Hakkımızda
        Route::get('/about', [\App\Http\Controllers\Admin\AboutController::class, 'index'])->name('about.index');
        Route::post('/about', [\App\Http\Controllers\Admin\AboutController::class, 'update'])->name('about.update');

        // Slider
        Route::get('/slider', [\App\Http\Controllers\Admin\SliderController::class, 'index'])->name('slider.index');
        Route::get('/slider/ekle', [\App\Http\Controllers\Admin\SliderController::class, 'ekle'])->name('slider.ekle');
        Route::post('/slider/ekle', [\App\Http\Controllers\Admin\SliderController::class, 'eklePost'])->name('slider.eklePost');
        Route::get('/slider/{id}/duzenle', [\App\Http\Controllers\Admin\SliderController::class, 'duzenle'])->name('slider.duzenle');
        Route::post('/slider/{id}/duzenle', [\App\Http\Controllers\Admin\SliderController::class, 'duzenlePost'])->name('slider.duzenlePost');
        Route::delete('/slider/{id}', [\App\Http\Controllers\Admin\SliderController::class, 'sil'])->name('slider.sil');

        // Notifications API (AJAX polling)
        Route::get('/notifications/count', function () {
            $ayar = \DB::table('ayarlar')->first();
            $soundFile = $ayar->notification_sound ?? null;
            $soundUrl = $soundFile ? asset('tema/uploads/sounds/' . $soundFile) : null;

            return response()->json([
                'pending_reviews' => \DB::table('reviews')->where('seen', 0)->count(),
                'unread_messages' => \DB::table('contact_messages')->where('read', 0)->count(),
                'new_customers' => \DB::table('customers')->where('seen', 0)->count(),
                'total_customers' => \DB::table('customers')->count(),
                'sound_url' => $soundUrl,
            ]);
        })->name('notifications.count');

        // Notes
        Route::get('/notes', [\App\Http\Controllers\Admin\NoteController::class, 'index'])->name('notes.index');
        Route::post('/notes', [\App\Http\Controllers\Admin\NoteController::class, 'store'])->name('notes.store');
        Route::put('/notes/{id}', [\App\Http\Controllers\Admin\NoteController::class, 'update'])->name('notes.update');
        Route::delete('/notes/{id}', [\App\Http\Controllers\Admin\NoteController::class, 'destroy'])->name('notes.destroy');
        Route::post('/notes/{id}/pin', [\App\Http\Controllers\Admin\NoteController::class, 'togglePin'])->name('notes.togglePin');

        // Page Order
        Route::get('/page-order', [\App\Http\Controllers\Admin\PageOrderController::class, 'index'])->name('page-order.index');
        Route::post('/page-order', [\App\Http\Controllers\Admin\PageOrderController::class, 'update'])->name('page-order.update');

        // Contacts
        Route::get('/contacts', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::post('/contacts/{id}/reply', [\App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('contacts.reply');
        Route::delete('/contacts/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

        // Reviews
        Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{id}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    });
});
