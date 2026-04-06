<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolKontrol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roller  İzin verilen roller (patron, calisan, bayi, musteri)
     */
    public function handle(Request $request, Closure $next, ...$roller): Response
    {
        if (!session()->has('admin_logged_in') || !session('admin_logged_in')) {
            return redirect()->route('admin.giris')->with('error', 'Lütfen giriş yapın!');
        }
        
        $adminRol = session('admin_rol', 2); // Varsayılan: Çalışan
        
        // Rol kontrolü
        $izinVar = false;
        foreach ($roller as $rol) {
            if ($rol === 'patron' && $adminRol == 1) {
                $izinVar = true;
                break;
            }
            if ($rol === 'calisan' && $adminRol == 2) {
                $izinVar = true;
                break;
            }
            if ($rol === 'bayi' && $adminRol == 3) {
                $izinVar = true;
                break;
            }
            if ($rol === 'musteri' && $adminRol == 4) {
                $izinVar = true;
                break;
            }
        }
        
        if (!$izinVar) {
            // Müşteri ise müşteri paneline yönlendir
            if ($adminRol == 4) {
                return redirect()->route('hesabim')->with('error', 'Bu sayfaya erişim yetkiniz yok!');
            }
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Bu sayfaya erişim yetkiniz yok!');
        }
        
        // Sayfa bazlı yetkilendirme kontrolü (sadece çalışan ve bayi için)
        if (in_array($adminRol, [2, 3])) {
            $routeName = $request->route()->getName();
            if ($routeName) {
                // Bu yönetici için bu sayfa için yetki kaydı var mı?
                $yetkiKaydi = \Illuminate\Support\Facades\DB::table('yonetici_yetkileri')
                    ->where('yonetici_id', session('admin_id'))
                    ->where('sayfa_route', $routeName)
                    ->first();
                
                // Eğer yetki kaydı varsa ve gorebilir=0 ise, izin verme
                if ($yetkiKaydi) {
                    if ($yetkiKaydi->gorebilir != 1) {
                        \Log::warning('Yetkisiz erişim denemesi', [
                            'yonetici_id' => session('admin_id'),
                            'route' => $routeName,
                            'gorebilir' => $yetkiKaydi->gorebilir
                        ]);
                        return redirect()->route('admin.dashboard')
                            ->with('error', 'Bu sayfaya erişim yetkiniz yok!');
                    }
                } else {
                    // Yetki kaydı yoksa, varsayılan olarak İZİN VERME (güvenlik için)
                    // Sadece dashboard'a izin ver
                    if ($routeName !== 'admin.dashboard') {
                        \Log::warning('Yetki kaydı olmayan sayfaya erişim denemesi', [
                            'yonetici_id' => session('admin_id'),
                            'route' => $routeName
                        ]);
                        return redirect()->route('admin.dashboard')
                            ->with('error', 'Bu sayfaya erişim yetkiniz yok! Lütfen yöneticinizden yetki talep edin.');
                    }
                }
            }
        }
        
        return $next($request);
    }
}


