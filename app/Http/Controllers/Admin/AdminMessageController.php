<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(10);
        return view('admin.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $validated = $request->validate([
            'reply_content' => 'required|string'
        ]);

        $message->update([
            'reply' => $validated['reply_content'],
            'replied_at' => now()
        ]);

        return redirect()->route('admin.messages.index')
            ->with('success', 'Reply sent successfully');
    }
}