<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $ayar = DB::table('ayarlar')->first();
        return view('admin.settings.index', compact('ayar'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_baslik' => 'required|string|max:255',
            'firma_telefon' => 'nullable|string|max:50',
            'firma_email' => 'nullable|email|max:150',
            'renk1' => 'nullable|string|max:20',
            'renk2' => 'nullable|string|max:20',
            'renk3' => 'nullable|string|max:20',
        ]);

        $data = [
            'site_baslik' => $request->site_baslik,
            'firma_adi' => $request->firma_adi,
            'firma_telefon' => $request->firma_telefon,
            'firma_email' => $request->firma_email,
            'firma_adres' => $request->firma_adres,
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'site_desc' => $request->site_desc,
            'copyright' => $request->copyright,
            'renk1' => $request->renk1,
            'renk2' => $request->renk2,
            'renk3' => $request->renk3,
            'google_analytics' => $request->google_analytics,
            'google_reviews_ayar' => json_encode([
                'api_key' => $request->google_api_key,
                'place_id' => $request->google_place_id,
                'auto_sync' => $request->has('google_auto_sync') ? 1 : 0,
                'last_sync' => $existing && $existing->google_reviews_ayar
                    ? (json_decode($existing->google_reviews_ayar, true)['last_sync'] ?? null)
                    : null,
            ]),
            'odeme_ayarlari' => json_encode([
                'aktif' => $request->has('odeme_aktif') ? 1 : 0,
                'provider' => $request->odeme_provider,
                'garanti_terminal_id' => $request->garanti_terminal_id,
                'garanti_merchant_id' => $request->garanti_merchant_id,
                'garanti_store_key' => $request->garanti_store_key,
                'garanti_provision_password' => $request->garanti_provision_password,
                'garanti_test_mode' => $request->has('garanti_test_mode') ? 1 : 0,
                'paytr_merchant_id' => $request->paytr_merchant_id,
                'paytr_merchant_key' => $request->paytr_merchant_key,
                'paytr_merchant_salt' => $request->paytr_merchant_salt,
                'paytr_test_mode' => $request->has('paytr_test_mode') ? 1 : 0,
                'iyzico_api_key' => $request->iyzico_api_key,
                'iyzico_secret_key' => $request->iyzico_secret_key,
                'iyzico_base_url' => $request->iyzico_base_url ?: 'https://api.iyzipay.com',
                'iyzico_test_mode' => $request->has('iyzico_test_mode') ? 1 : 0,
            ]),
            'updated_at' => now(),
        ];

        // Logo upload
        if ($request->hasFile('firma_logo')) {
            $data['firma_logo'] = upload_as_webp($request->file('firma_logo'), public_path('tema/uploads'), 'logo_');
        }

        // Favicon upload
        if ($request->hasFile('favicon')) {
            $data['favicon'] = upload_as_webp($request->file('favicon'), public_path('tema/uploads'), 'favicon_');
        }

        // Notification sound
        $existing = DB::table('ayarlar')->first();

        if ($request->has('remove_notification_sound') && !$request->hasFile('notification_sound')) {
            if ($existing && $existing->notification_sound && $existing->notification_sound !== 'default-notification.wav') {
                $oldPath = public_path('tema/uploads/sounds/' . $existing->notification_sound);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $data['notification_sound'] = null;
        }

        if ($request->hasFile('notification_sound')) {
            $file = $request->file('notification_sound');
            $filename = 'notif_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('tema/uploads/sounds');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            $data['notification_sound'] = $filename;
        }

        if ($existing) {
            DB::table('ayarlar')->where('id', $existing->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('ayarlar')->insert($data);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully!');
    }

    public function syncGoogleReviews()
    {
        $ayar = DB::table('ayarlar')->first();
        if (!$ayar || !$ayar->google_reviews_ayar) {
            return response()->json(['success' => false, 'message' => 'Google ayarları yapılandırılmamış.']);
        }

        $config = json_decode($ayar->google_reviews_ayar, true);
        $apiKey = $config['api_key'] ?? '';
        $placeId = $config['place_id'] ?? '';

        if (!$apiKey || !$placeId) {
            return response()->json(['success' => false, 'message' => 'API Key ve Place ID gerekli.']);
        }

        // Google Places API (New) - Place Details
        $url = 'https://places.googleapis.com/v1/places/' . $placeId . '?fields=reviews,rating,userRatingCount&key=' . $apiKey . '&languageCode=en';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Goog-Api-Key: ' . $apiKey,
            'X-Goog-FieldMask: reviews,rating,userRatingCount',
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return response()->json(['success' => false, 'message' => 'Bağlantı hatası: ' . $curlError]);
        }

        if ($httpCode !== 200) {
            $errorData = json_decode($response, true);
            $errorMsg = $errorData['error']['message'] ?? ('HTTP ' . $httpCode);
            return response()->json(['success' => false, 'message' => 'Google API hatası: ' . $errorMsg]);
        }

        $data = json_decode($response, true);
        if (!$data || empty($data['reviews'])) {
            return response()->json(['success' => false, 'message' => 'Yorum bulunamadı.']);
        }

        $added = 0;
        $skipped = 0;

        foreach ($data['reviews'] as $review) {
            $name = $review['authorAttribution']['displayName'] ?? 'Google User';
            $rating = $review['rating'] ?? 5;
            $comment = $review['text']['text'] ?? '';
            $publishTime = $review['publishTime'] ?? now();

            if (empty($comment)) {
                $skipped++;
                continue;
            }

            // Check duplicate by name + comment similarity
            $exists = DB::table('reviews')
                ->where('name', $name)
                ->where(function ($q) use ($comment) {
                    $q->where('comment', $comment)
                      ->orWhere('comment', 'LIKE', substr($comment, 0, 50) . '%');
                })
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            DB::table('reviews')->insert([
                'name' => $name,
                'location' => 'Google Review',
                'rating' => $rating,
                'comment' => $comment,
                'approved' => 1,
                'seen' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime($publishTime)),
                'updated_at' => now(),
            ]);
            $added++;
        }

        // Update last sync time
        $config['last_sync'] = now()->format('d.m.Y H:i');
        DB::table('ayarlar')->where('id', $ayar->id)->update([
            'google_reviews_ayar' => json_encode($config),
        ]);

        $totalRating = $data['rating'] ?? null;
        $totalCount = $data['userRatingCount'] ?? null;
        $ratingInfo = $totalRating ? " (Google: {$totalRating}/5, toplam {$totalCount} yorum)" : '';

        return response()->json([
            'success' => true,
            'message' => "{$added} yeni yorum eklendi, {$skipped} atlandı.{$ratingInfo}",
        ]);
    }
}
