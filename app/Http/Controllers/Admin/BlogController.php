<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $bloglar = DB::table('blog')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.blog.index', compact('bloglar'));
    }

    public function ekle()
    {
        return view('admin.blog.ekle');
    }

    public function eklePost(Request $request)
    {
        $request->validate([
            'adi' => 'required|string|max:255',
            'aciklama' => 'required|string',
        ]);

        $seo = Str::slug($request->adi);

        $resim = null;
        if ($request->hasFile('resim')) {
            $file = $request->file('resim');
            $filename = time() . '_' . Str::slug($request->adi) . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('tema/uploads/bloglar');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            $resim = $filename;
        }

        DB::table('blog')->insert([
            'adi' => $request->adi,
            'seo' => $seo,
            'aciklama' => $request->aciklama,
            'icerik' => $request->icerik,
            'resim' => $resim,
            'keywords' => $request->keywords,
            'description' => $request->description,
            'durum' => $request->has('durum') ? 1 : 0,
            'sira' => $request->sira ?? 0,
            'tarih' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post added successfully!');
    }

    public function duzenle($id)
    {
        $blog = DB::table('blog')->where('id', $id)->first();

        if (!$blog) {
            return redirect()->route('admin.blog.index')->with('error', 'Blog post not found!');
        }

        return view('admin.blog.duzenle', compact('blog'));
    }

    public function duzenlePost(Request $request, $id)
    {
        $request->validate([
            'adi' => 'required|string|max:255',
            'aciklama' => 'required|string',
        ]);

        $blog = DB::table('blog')->where('id', $id)->first();

        if (!$blog) {
            return redirect()->route('admin.blog.index')->with('error', 'Blog post not found!');
        }

        $seo = Str::slug($request->adi);

        $data = [
            'adi' => $request->adi,
            'seo' => $seo,
            'aciklama' => $request->aciklama,
            'icerik' => $request->icerik,
            'keywords' => $request->keywords,
            'description' => $request->description,
            'durum' => $request->has('durum') ? 1 : 0,
            'sira' => $request->sira ?? 0,
            'updated_at' => now(),
        ];

        if ($request->hasFile('resim')) {
            if ($blog->resim) {
                $oldPath = public_path('tema/uploads/bloglar/' . $blog->resim);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('resim');
            $filename = time() . '_' . Str::slug($request->adi) . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('tema/uploads/bloglar');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            $data['resim'] = $filename;
        }

        DB::table('blog')->where('id', $id)->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully!');
    }

    public function sil($id)
    {
        $blog = DB::table('blog')->where('id', $id)->first();

        if ($blog && $blog->resim) {
            $path = public_path('tema/uploads/bloglar/' . $blog->resim);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        DB::table('blog')->where('id', $id)->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully!');
    }
}
