<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function index()
    {
        $ayar = DB::table('ayarlar')->first();
        return view('admin.about.index', compact('ayar'));
    }

    public function update(Request $request)
    {
        $existing = DB::table('ayarlar')->first();

        $data = [
            'about_title'   => $request->about_title,
            'about_text'    => $request->about_text,
            'about_mission' => $request->about_mission,
            'about_stats'   => json_encode([
                ['num' => $request->stat1_num, 'label' => $request->stat1_label, 'icon' => $request->stat1_icon],
                ['num' => $request->stat2_num, 'label' => $request->stat2_label, 'icon' => $request->stat2_icon],
                ['num' => $request->stat3_num, 'label' => $request->stat3_label, 'icon' => $request->stat3_icon],
                ['num' => $request->stat4_num, 'label' => $request->stat4_label, 'icon' => $request->stat4_icon],
            ]),
            'about_features' => json_encode(array_filter(array_map('trim', explode("\n", $request->about_features ?? '')))),
            'updated_at'     => now(),
        ];

        if ($request->hasFile('about_image')) {
            $data['about_image'] = upload_as_webp($request->file('about_image'), public_path('tema/uploads'), 'about_');
        }

        $data['ceviri'] = ceviri_derle($request->input('ceviri'));

        if ($existing) {
            DB::table('ayarlar')->where('id', $existing->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('ayarlar')->insert($data);
        }

        return redirect()->route('admin.about.index')->with('success', 'Hakkımızda bilgileri güncellendi.');
    }
}
