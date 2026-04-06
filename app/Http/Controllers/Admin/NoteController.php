<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function index()
    {
        $notes = DB::table('notes')
            ->orderBy('pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.notes.index', compact('notes'));
    }

    private function handleImage(Request $request, $existing = null)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $uploadPath = public_path('tema/uploads/notes');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            return $filename;
        }
        return null;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:20480',
        ]);

        $image = $this->handleImage($request);

        DB::table('notes')->insert([
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color ?: '#fef3c7',
            'image' => $image,
            'pinned' => $request->has('pinned') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.notes.index')->with('success', 'Note added!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'nullable|string',
            'color' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:20480',
        ]);

        $existing = DB::table('notes')->where('id', $id)->first();
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color ?: '#fef3c7',
            'pinned' => $request->has('pinned') ? 1 : 0,
            'updated_at' => now(),
        ];

        // Remove image
        if ($request->has('remove_image') && !$request->hasFile('image')) {
            if ($existing && $existing->image) {
                $oldPath = public_path('tema/uploads/notes/' . $existing->image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $data['image'] = null;
        }

        // Upload new image
        $newImage = $this->handleImage($request);
        if ($newImage) {
            $data['image'] = $newImage;
        }

        DB::table('notes')->where('id', $id)->update($data);

        return redirect()->route('admin.notes.index')->with('success', 'Note updated!');
    }

    public function destroy($id)
    {
        $note = DB::table('notes')->where('id', $id)->first();
        if ($note && $note->image) {
            $path = public_path('tema/uploads/notes/' . $note->image);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        DB::table('notes')->where('id', $id)->delete();
        return redirect()->route('admin.notes.index')->with('success', 'Note deleted!');
    }

    public function togglePin($id)
    {
        $note = DB::table('notes')->where('id', $id)->first();
        if ($note) {
            DB::table('notes')->where('id', $id)->update([
                'pinned' => $note->pinned ? 0 : 1,
                'updated_at' => now(),
            ]);
        }
        return redirect()->route('admin.notes.index');
    }
}
