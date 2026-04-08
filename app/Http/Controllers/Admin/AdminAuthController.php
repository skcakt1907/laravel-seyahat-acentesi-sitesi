<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 15;

    public function giris()
    {
      //  if (session()->has('admin_logged_in') && session('admin_logged_in')) {
          //  return redirect()->route('admin.dashboard');
        //}
        
        return view('admin.giris');
    }
    
    public function girisPost(Request $request)
    {
        // Honeypot — bots fill the hidden 'website' field
        if ($request->filled('website')) {
            Log::warning('Admin login: honeypot triggered', ['ip' => $request->ip()]);
            return redirect()->route('admin.giris')->with('error', 'Geçersiz istek.');
        }

        $request->validate([
            'kullanici_adi' => 'required|string|max:120',
            'sifre' => 'required|string|max:200',
        ]);

        // Brute-force lockout check (per IP + per username)
        $ipKey   = 'admin_login_ip_'   . md5($request->ip());
        $userKey = 'admin_login_user_' . md5(strtolower($request->kullanici_adi));
        $ipAttempts   = (int) Cache::get($ipKey,   0);
        $userAttempts = (int) Cache::get($userKey, 0);

        if ($ipAttempts >= self::MAX_ATTEMPTS || $userAttempts >= self::MAX_ATTEMPTS) {
            Log::warning('Admin login locked out', [
                'ip' => $request->ip(),
                'user' => $request->kullanici_adi,
                'ip_attempts' => $ipAttempts,
                'user_attempts' => $userAttempts,
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Çok fazla başarısız deneme. Lütfen ' . self::LOCKOUT_MINUTES . ' dakika sonra tekrar deneyin.');
        }

        $admin = DB::table('yoneticiler')
            ->where('kullaniciadi', $request->kullanici_adi)
            ->where('durum', 1)
            ->first();

        $sifreDogrumu = false;
        if ($admin) {
            if ($admin->sifre === md5($request->sifre)) {
                $sifreDogrumu = true;
            } elseif (Hash::check($request->sifre, $admin->sifre)) {
                $sifreDogrumu = true;
            }
        }

        if (!$admin || !$sifreDogrumu) {
            Cache::put($ipKey,   $ipAttempts   + 1, now()->addMinutes(self::LOCKOUT_MINUTES));
            Cache::put($userKey, $userAttempts + 1, now()->addMinutes(self::LOCKOUT_MINUTES));
            $remaining = self::MAX_ATTEMPTS - max($ipAttempts + 1, $userAttempts + 1);
            Log::warning('Admin login failed', [
                'ip' => $request->ip(),
                'user' => $request->kullanici_adi,
                'remaining' => $remaining,
            ]);
            $msg = 'Kullanıcı adı veya şifre hatalı!';
            if ($remaining > 0 && $remaining <= 3) {
                $msg .= ' (Kalan deneme: ' . $remaining . ')';
            }
            return redirect()->back()->withInput()->with('error', $msg);
        }

        // Success — clear lockout counters & rotate session id
        Cache::forget($ipKey);
        Cache::forget($userKey);
        $request->session()->regenerate();
        
        // Session'ı kaydet - tüm değerleri tek seferde
        $sessionData = [
            'admin_logged_in' => true,
            'admin_id' => $admin->id,
            'admin_kullanici_adi' => $admin->kullaniciadi,
            'admin_adi' => $admin->adi ?? $admin->kullaniciadi,
            'admin_yetki' => $admin->yetki ?? 1,
            'admin_rol' => $admin->rol ?? 2,
            'admin_ip' => $request->ip(),
            'admin_last_activity' => time(),
        ];
        
        foreach ($sessionData as $key => $value) {
            session()->put($key, $value);
        }
        
        // Veritabanını güncelle
        DB::table('yoneticiler')
            ->where('id', $admin->id)
            ->update([
                'son_giris' => now('Europe/Istanbul'),
                'son_ip' => $request->ip(),
            ]);
        
        // Session'ı kesinlikle kaydet
        session()->save();
        
        // Session'ın kaydedildiğini doğrula
        if (!session()->has('admin_id')) {
            \Log::error('Session kaydedilemedi!', ['admin_id' => $admin->id]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Oturum oluşturulamadı. Lütfen tekrar deneyin.');
        }
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Hoş geldiniz!');
    }
    
    public function cikis(Request $request)
    {
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.giris')->with('success', 'Başarıyla çıkış yaptınız.');
    }
}
