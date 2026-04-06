<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        // Mark all unread messages as read
        DB::table('contact_messages')->where('read', 0)->update(['read' => 1]);

        $messages = DB::table('contact_messages')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.contacts.index', compact('messages'));
    }

    public function show($id)
    {
        $message = DB::table('contact_messages')->where('id', $id)->first();
        if (!$message) {
            return redirect()->route('admin.contacts.index')->with('error', 'Message not found.');
        }
        DB::table('contact_messages')->where('id', $id)->update(['read' => 1]);
        return view('admin.contacts.show', compact('message'));
    }

    public function destroy($id)
    {
        DB::table('contact_messages')->where('id', $id)->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted.');
    }
}
