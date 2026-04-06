<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = DB::table('activities')
            ->orderBy('sira', 'asc')
            ->paginate(20);

        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'image' => 'nullable|image|max:20480',
        ]);

        $slug = Str::slug($request->title);
        $image = null;

        if ($request->hasFile('image')) {
            $image = upload_as_webp($request->file('image'), public_path('tema/uploads/activities'), 'act_');
        }

        // Gallery images
        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $gFile) {
                $gName = upload_as_webp($gFile, public_path('tema/uploads/activities'), 'gal_');
                if ($gName) $gallery[] = $gName;
            }
        }

        DB::table('activities')->insert([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $image,
            'gallery' => !empty($gallery) ? json_encode($gallery) : null,
            'price' => $request->price ?? 0,
            'badge' => $request->badge ?: null,
            'sira' => $request->sira ?? 0,
            'durum' => $request->has('durum') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.activities.index')->with('success', 'Activity added successfully!');
    }

    public function edit($id)
    {
        $activity = DB::table('activities')->where('id', $id)->first();

        if (!$activity) {
            return redirect()->route('admin.activities.index')->with('error', 'Activity not found!');
        }

        return view('admin.activities.edit', compact('activity'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'image' => 'nullable|image|max:20480',
        ]);

        $existing = DB::table('activities')->where('id', $id)->first();
        $slug = Str::slug($request->title);

        $data = [
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price ?? 0,
            'badge' => $request->badge ?: null,
            'sira' => $request->sira ?? 0,
            'durum' => $request->has('durum') ? 1 : 0,
            'updated_at' => now(),
        ];

        // Remove cover image
        if ($request->input('remove_image') == '1' && !$request->hasFile('image')) {
            if ($existing->image) {
                $oldPath = public_path('tema/uploads/activities/' . $existing->image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = upload_as_webp($request->file('image'), public_path('tema/uploads/activities'), 'act_');
        }

        // Remove selected gallery images
        $existingGallery = $existing->gallery ? json_decode($existing->gallery, true) : [];
        if ($request->has('remove_gallery')) {
            $removeIdxs = array_filter($request->remove_gallery, fn($v) => $v !== '' && $v !== null);
            foreach ($removeIdxs as $idx) {
                if (isset($existingGallery[$idx])) {
                    $gPath = public_path('tema/uploads/activities/' . $existingGallery[$idx]);
                    if (file_exists($gPath)) {
                        @unlink($gPath);
                    }
                    unset($existingGallery[$idx]);
                }
            }
            $existingGallery = array_values($existingGallery);
            $data['gallery'] = !empty($existingGallery) ? json_encode($existingGallery) : null;
        }

        // Gallery images (append to existing)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $gFile) {
                $gName = upload_as_webp($gFile, public_path('tema/uploads/activities'), 'gal_');
                if ($gName) $existingGallery[] = $gName;
            }
            $data['gallery'] = json_encode($existingGallery);
        }

        DB::table('activities')->where('id', $id)->update($data);

        return redirect()->route('admin.activities.index')->with('success', 'Activity updated successfully!');
    }

    public function destroy($id)
    {
        DB::table('activities')->where('id', $id)->delete();
        return redirect()->route('admin.activities.index')->with('success', 'Activity deleted successfully!');
    }
}
