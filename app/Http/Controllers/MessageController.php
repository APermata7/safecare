<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Menampilkan daftar message untuk pengguna yang login
     */
    public function index()
    {
        // Untuk pengguna biasa, hanya tampilkan message mereka sendiri
        $messages = Message::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['messages' => $messages], 200);
    }

    /**
     * Menampilkan semua message (untuk admin)
     */
    public function adminIndex()
    {
        // Hanya admin yang bisa mengakses
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = Message::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['messages' => $messages], 200);
    }

    /**
     * Menyimpan message baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|in:Feedback,Request panti user',
            'message' => 'required|string',
            'file' => 'nullable|file|max:16384', // Maksimal 16MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('message_files', 'public');
        }

        $message = Message::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'role' => Auth::user()->role,
            'message' => $request->message,
            'file_path' => $filePath,
        ]);

        return response()->json(['message' => $message, 'success' => 'Message berhasil dikirim'], 201);
    }

    /**
     * Menampilkan detail message
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);

        // Pastikan pengguna hanya bisa melihat message mereka sendiri, kecuali admin
        if (Auth::user()->role !== 'admin' && $message->user_id !== Auth::id()) {
            abort(404);
        }

        return response()->json(['message' => $message], 200);
    }

    /**
     * Memberikan reply ke message (untuk admin)
     */
    public function reply(Request $request, $id)
    {
        // Hanya admin yang bisa mereply
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'reply' => 'required|string',
        ]);

        $message = Message::findOrFail($id);
        $message->update([
            'reply' => $request->reply,
        ]);

        return response()->json(['message' => $message, 'success' => 'Reply berhasil dikirim'], 200);
    }
}