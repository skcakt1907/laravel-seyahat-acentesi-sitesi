<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function giris()
    {
      //  if (session()->has('admin_logged_in') && session('admin_logged_in')) {
          //  return redirect()->route('admin.dashboard');
        //}
        
        return view('admin.giris');
    }
    
    public function girisPost(Request $request)
    {
        $request->validate([
            'kullanici_adi' => 'required|string',
            'sifre' => 'required|string',
        ]);
        
        $admin = DB::table('yoneticiler')
            ->where('kullaniciadi', $request->kullanici_adi)
            ->where('durum', 1)
            ->first();
        
        if (!$admin) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kullanıcı adı veya şifre hatalı!');
        }
        
        $sifreDogrumu = false;
        
        if ($admin->sifre === md5($request->sifre)) {
            $sifreDogrumu = true;
        } elseif (Hash::check($request->sifre, $admin->sifre)) {
            $sifreDogrumu = true;
        }
        
        if (!$sifreDogrumu) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kullanıcı adı veya şifre hatalı!');
        }
        
        // Session'ı kaydet - tüm değerleri tek seferde
        $sessionData = [
            'admin_logged_in' => true,
            'admin_id' => $admin->id,
            'admin_kullanici_adi' => $admin->kullaniciadi,
            'admin_adi' => $admin->adi ?? $admin->kullaniciadi,
            'admin_yetki' => $admin->yetki ?? 1,
            'admin_rol' => $admin->rol ?? 2, // 1=Patron, 2=Çalışan, 3=Bayi
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
    
    public function cikis()
    {
        session()->flush();
        return redirect()->route('admin.giris')->with('success', 'Başarıyla çıkış yaptınız.');
    }
}
