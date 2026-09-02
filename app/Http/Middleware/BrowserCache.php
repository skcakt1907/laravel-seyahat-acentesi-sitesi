<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BrowserCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        $path = $request->path();
        
        // Statik dosyalar için uzun süreli cache
        if (preg_match('/\.(jpg|jpeg|png|gif|ico|svg|webp|woff|woff2|ttf|eot|css|js)$/i', $path)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        }
        // HTML sayfalar için kısa süreli cache
        elseif ($response->headers->get('Content-Type') && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            // Admin ve panel sayfaları için cache yok
            if (str_starts_with($path, 'admin/') || str_starts_with($path, 'hesabim') || str_starts_with($path, 'bilgilerim')) {
                $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
            } else {
                $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
            }
        }
        
        // ETag header ekle (değişiklik kontrolü için)
        if (!$response->headers->has('ETag')) {
            $etag = md5($response->getContent());
            $response->headers->set('ETag', $etag);
            
            // Eğer client'ta aynı ETag varsa 304 Not Modified döndür
            if ($request->headers->get('If-None-Match') === $etag) {
                return response('', 304)->withHeaders($response->headers->all());
            }
        }
        
        return $response;
    }
}
