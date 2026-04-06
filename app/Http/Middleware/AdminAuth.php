<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Session kontrolü
        if (!session()->has('admin_logged_in') || !session('admin_logged_in')) {
            return redirect()->route('admin.giris')->with('error', 'Lütfen giriş yapın!');
        }
        
        if (!session()->has('admin_id')) {
            session()->flush();
            return redirect()->route('admin.giris')->with('error', 'Oturum sonlanmış, lütfen tekrar giriş yapın!');
        }
        
        // Session'ı her istekte kaydet (timeout'u önlemek için)
        session()->save();
        
        // Sayfa bazlı yetkilendirme kontrolü (sadece çalışan ve bayi için)
        $adminRol = session('admin_rol', 2);
        if (in_array($adminRol, [2, 3])) {
            $routeName = $request->route()?->getName();
            
            // Debug: Route name'i logla
            if ($routeName) {
                \Log::info('AdminAuth Middleware - Route kontrolü', [
                    'yonetici_id' => session('admin_id'),
                    'admin_rol' => $adminRol,
                    'route_name' => $routeName,
                    'url' => $request->fullUrl()
                ]);
            }
            
            if ($routeName && Schema::hasTable('yonetici_yetkileri')) {
                // Bu yönetici için bu sayfa için yetki kaydı var mı?
                $yetkiKaydi = DB::table('yonetici_yetkileri')
                    ->where('yonetici_id', session('admin_id'))
                    ->where('sayfa_route', $routeName)
                    ->first();
                
                // Eğer yetki kaydı varsa ve gorebilir=0 ise, izin verme
                if ($yetkiKaydi) {
                    if ($yetkiKaydi->gorebilir != 1) {
                        \Log::warning('Yetkisiz erişim denemesi - REDDEDİLDİ', [
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
                        \Log::warning('Yetki kaydı olmayan sayfaya erişim denemesi - REDDEDİLDİ', [
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
