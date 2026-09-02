<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:5000',
        ]);

        $message = DB::table('contact_messages')->where('id', $id)->first();
        if (!$message) {
            return redirect()->route('admin.contacts.index')->with('error', 'Mesaj bulunamadı.');
        }

        $body = $request->reply;
        $subject = 'Re: ' . $message->subject;
        $to = $message->email;
        $name = $message->name;
        $original = $message->message;

        try {
            Mail::send([], [], function ($mail) use ($to, $name, $subject, $body, $original) {
                $html = '<div style="font-family:Segoe UI,Arial,sans-serif;font-size:14px;line-height:1.6;color:#0b1d33;">'
                    . '<p>Merhaba ' . e($name) . ',</p>'
                    . '<div style="white-space:pre-wrap;">' . e($body) . '</div>'
                    . '<hr style="border:none;border-top:1px solid #e2e8f0;margin:20px 0;">'
                    . '<div style="font-size:12px;color:#64748b;"><strong>Sizin mesajınız:</strong><br>'
                    . nl2br(e($original)) . '</div>'
                    . '</div>';
                $mail->to($to, $name)->subject($subject)->html($html);
            });
        } catch (\Throwable $e) {
            \Log::error('Contact reply mail failed', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Mail gönderilemedi: ' . $e->getMessage());
        }

        DB::table('contact_messages')->where('id', $id)->update([
            'admin_reply' => $body,
            'replied_at'  => now(),
            'updated_at'  => now(),
        ]);

        return back()->with('success', 'Yanıtınız gönderildi.');
    }

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
