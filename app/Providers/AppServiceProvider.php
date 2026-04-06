<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use App\Helpers\TranslationHelper;
use App\View\Composers\TranslationComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        // Bootstrap 5 pagination kullan
        Paginator::useBootstrapFive();

        app()->useLangPath(base_path('lang'));
        
        // Rate Limiting - Güvenlik
        $this->configureRateLimiting();
        
        // Otomatik çeviri Blade directive'i
        Blade::directive('translate', function ($expression) {
            return "<?php echo \App\Helpers\TranslationHelper::translate($expression); ?>";
        });
        
        // Kısa versiyon
        Blade::directive('t', function ($expression) {
            return "<?php echo \App\Helpers\TranslationHelper::translate($expression); ?>";
        });
        
        // Yetki kontrolü Blade directive'i
        Blade::if('canAccess', function ($routeName) {
            return can_access_page($routeName);
        });
        
        // Tüm view'lara çeviri helper'ını ekle
        View::composer('*', TranslationComposer::class);
    }
    
    /**
     * Rate limiting konfigürasyonu
     */
    protected function configureRateLimiting(): void
    {
        // Genel API limiti
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        
        // Login denemesi limiti (brute force koruması)
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json([
                    'message' => 'Çok fazla giriş denemesi. Lütfen 1 dakika bekleyin.'
                ], 429);
            });
        });
        
        // Form gönderimi limiti
        RateLimiter::for('form', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });
        
        // İletişim formu limiti
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(5)->by($request->ip())->response(function () {
                return back()->with('error', 'Çok fazla mesaj gönderdiniz. Lütfen daha sonra tekrar deneyin.');
            });
        });
    }
}
