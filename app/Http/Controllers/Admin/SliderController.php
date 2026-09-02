<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    public function index()
    {
        $sliderlar = DB::table('slider')
            ->orderBy('sira', 'asc')
            ->paginate(20);

        return view('admin.slider.index', compact('sliderlar'));
    }

    public function ekle()
    {
        return view('admin.slider.ekle');
    }

    public function eklePost(Request $request)
    {
        $request->validate([
            'adi' => 'required|string|max:255',
            'media_type' => 'required|in:image,video',
            'resim' => 'required_if:media_type,image|nullable|image',
            'video' => 'required_if:media_type,video|nullable|mimetypes:video/mp4,video/webm,video/ogg|max:102400',
        ]);

        $resim = null;
        $video = null;

        if ($request->hasFile('resim')) {
            $resim = upload_as_webp($request->file('resim'), public_path('tema/uploads/slider'), 'slide_');
        }

        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoName = time() . '_video_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $videoFile->getClientOriginalName());
            $videoPath = public_path('tema/uploads/slider/videos');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }
            $videoFile->move($videoPath, $videoName);
            $video = $videoName;
        }

        DB::table('slider')->insert([
            'ceviri' => ceviri_derle($request->input('ceviri')),
            'adi' => $request->adi,
            'link' => $request->link,
            'resim' => $resim,
            'media_type' => $request->media_type ?? 'image',
            'video' => $video,
            'aciklama' => $request->aciklama,
            'sira' => $request->sira ?? 0,
            'durum' => $request->has('durum') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.slider.index')->with('success', 'Slider added successfully!');
    }

    public function duzenle($id)
    {
        $slider = DB::table('slider')->where('id', $id)->first();

        if (!$slider) {
            return redirect()->route('admin.slider.index')->with('error', 'Slider not found!');
        }

        return view('admin.slider.duzenle', compact('slider'));
    }

    public function duzenlePost(Request $request, $id)
    {
        $request->validate([
            'adi' => 'required|string|max:255',
            'media_type' => 'required|in:image,video',
            'resim' => 'nullable|image',
            'video' => 'nullable|mimetypes:video/mp4,video/webm,video/ogg|max:102400',
        ]);

        $existing = DB::table('slider')->where('id', $id)->first();

        $data = [
            'adi' => $request->adi,
            'link' => $request->link,
            'aciklama' => $request->aciklama,
            'sira' => $request->sira ?? 0,
            'durum' => $request->has('durum') ? 1 : 0,
            'media_type' => $request->media_type,
            'updated_at' => now(),
        ];

        if ($request->hasFile('resim')) {
            $data['resim'] = upload_as_webp($request->file('resim'), public_path('tema/uploads/slider'), 'slide_');
            if ($request->media_type === 'image') {
                $data['video'] = null;
            }
        }

        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoName = time() . '_video_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $videoFile->getClientOriginalName());
            $videoPath = public_path('tema/uploads/slider/videos');
            if (!file_exists($videoPath)) {
                mkdir($videoPath, 0777, true);
            }
            $videoFile->move($videoPath, $videoName);
            $data['video'] = $videoName;
            if ($request->media_type === 'video') {
                $data['resim'] = null;
            }
        }

        $data['ceviri'] = ceviri_derle($request->input('ceviri'));

        DB::table('slider')->where('id', $id)->update($data);

        return redirect()->route('admin.slider.index')->with('success', 'Slider updated successfully!');
    }

    public function sil($id)
    {
        DB::table('slider')->where('id', $id)->delete();
        return redirect()->route('admin.slider.index')->with('success', 'Slider deleted successfully!');
    }
}
