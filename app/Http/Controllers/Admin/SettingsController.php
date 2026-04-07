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

        $existing = DB::table('ayarlar')->first();

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

}
