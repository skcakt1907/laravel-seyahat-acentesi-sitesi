<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = DB::table('transfers')
            ->orderBy('sira', 'asc')
            ->paginate(20);

        return view('admin.transfers.index', compact('transfers'));
    }

    public function create()
    {
        return view('admin.transfers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_location' => 'required|string|max:100',
            'to_location' => 'required|string|max:100',
        ]);

        $title = $request->title ?: ($request->from_location . ' → ' . $request->to_location . ' Transfer');

        DB::table('transfers')->insert([
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'title' => $title,
            'icon' => $request->icon ?: 'fa-shuttle-van',
            'price' => $request->price ?? 0,
            'description' => $request->description,
            'sira' => $request->sira ?? 0,
            'durum' => $request->has('durum') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.transfers.index')->with('success', 'Transfer route added successfully!');
    }

    public function edit($id)
    {
        $transfer = DB::table('transfers')->where('id', $id)->first();

        if (!$transfer) {
            return redirect()->route('admin.transfers.index')->with('error', 'Transfer route not found!');
        }

        return view('admin.transfers.edit', compact('transfer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'from_location' => 'required|string|max:100',
            'to_location' => 'required|string|max:100',
        ]);

        $title = $request->title ?: ($request->from_location . ' → ' . $request->to_location . ' Transfer');

        DB::table('transfers')->where('id', $id)->update([
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'title' => $title,
            'icon' => $request->icon ?: 'fa-shuttle-van',
            'price' => $request->price ?? 0,
            'description' => $request->description,
            'sira' => $request->sira ?? 0,
            'durum' => $request->has('durum') ? 1 : 0,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.transfers.index')->with('success', 'Transfer route updated successfully!');
    }

    public function destroy($id)
    {
        DB::table('transfers')->where('id', $id)->delete();
        return redirect()->route('admin.transfers.index')->with('success', 'Transfer route deleted successfully!');
    }
}
