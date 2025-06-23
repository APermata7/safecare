<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\PantiAsuhan;
use App\Models\User;
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

        return response()->json([
            'messages' => $messages,
            'totalPanti' => PantiAsuhan::count(),
            'totalDonatur' => User::where('role', 'donatur')->count(),
        ], 200);
    }

    /**
     * Menyimpan message baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|in:Feedback,Request panti user,Request menjadi panti',
            'message' => 'required|string',
            'file' => 'nullable|file|max:16384', // Maksimal 16MB

            // Validasi tambahan jika request menjadi panti
            'nama_panti' => 'required_if:judul,Request menjadi panti|string|nullable',
            'alamat' => 'required_if:judul,Request menjadi panti|string|nullable',
            'deskripsi' => 'required_if:judul,Request menjadi panti|string|nullable',
            'foto_profil' => 'required_if:judul,Request menjadi panti|file|nullable|max:16384',
            'dokumen_verifikasi' => 'required_if:judul,Request menjadi panti|file|nullable|max:16384',
            'nomor_rekening' => 'required_if:judul,Request menjadi panti|string|nullable',
            'bank' => 'required_if:judul,Request menjadi panti|string|nullable',
            'kontak' => 'required_if:judul,Request menjadi panti|string|nullable',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('message_files', 'public');
        }

        // Simpan pesan seperti biasa
        $message = Message::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'role' => Auth::user()->role,
            'message' => $request->message,
            'file_path' => $filePath,
        ]);

        // Jika request menjadi panti, simpan juga ke tabel panti_asuhan
        if ($request->judul === 'Request panti user') {
            // Simpan file foto_profil dan dokumen_verifikasi jika ada
            $fotoProfilPath = null;
            $dokumenVerifikasiPath = null;

            if ($request->hasFile('foto_profil')) {
                $fotoProfilPath = $request->file('foto_profil')->store('panti_images', 'public');
            }
            if ($request->hasFile('dokumen_verifikasi')) {
                $dokumenVerifikasiPath = $request->file('dokumen_verifikasi')->store('documents', 'public');
            }

            PantiAsuhan::create([
                'user_id' => Auth::id(),
                'nama_panti' => $request->nama_panti,
                'alamat' => $request->alamat,
                'deskripsi' => $request->deskripsi,
                'foto_profil' => $fotoProfilPath,
                'dokumen_verifikasi' => $dokumenVerifikasiPath,
                'status_verifikasi' => 'unverified',
                'nomor_rekening' => $request->nomor_rekening,
                'bank' => $request->bank,
                'kontak' => $request->kontak,
            ]);
        }

        return response()->json(['message' => $message, 'success' => 'Message berhasil dikirim'], 201);
    }

    /**
     * Menampilkan detail message
     */
    public function show($id)
    {
        $message = Message::with('user')->findOrFail($id);

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