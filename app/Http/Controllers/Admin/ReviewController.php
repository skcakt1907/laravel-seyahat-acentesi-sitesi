<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        // Mark all unseen reviews as seen
        DB::table('reviews')->where('seen', 0)->update(['seen' => 1]);

        $reviews = DB::table('reviews')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve($id)
    {
        DB::table('reviews')->where('id', $id)->update(['approved' => 1, 'updated_at' => now()]);
        return redirect()->route('admin.reviews.index')->with('success', 'Review approved!');
    }

    public function reject($id)
    {
        DB::table('reviews')->where('id', $id)->update(['approved' => 0, 'updated_at' => now()]);
        return redirect()->route('admin.reviews.index')->with('success', 'Review rejected.');
    }

    public function destroy($id)
    {
        DB::table('reviews')->where('id', $id)->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }
}
