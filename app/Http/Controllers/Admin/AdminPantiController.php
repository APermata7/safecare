<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PantiAsuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPantiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pantis = PantiAsuhan::when($search, function ($query) use ($search) {
            return $query->where('nama_panti', 'like', '%' . $search . '%')
                ->orWhere('kontak', 'like', '%' . $search . '%')
                ->orWhere('alamat', 'like', '%' . $search . '%');
        })
            ->latest()
            ->paginate(10);

        return view('admin.pantis.index', compact('pantis', 'search'));
    }

    public function create()
    {
        return view('admin.pantis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_panti' => 'required|string|max:255',
            'pengurus' => 'required|string|max:255', // Ini sudah benar
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'kontak' => 'required|string|max:20',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_verifikasi' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'nomor_rekening' => 'required|string',
            'bank' => 'required|string'
        ]);

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('panti/foto', 'public');
        }

        // Upload dokumen verifikasi
        if ($request->hasFile('dokumen_verifikasi')) {
            $validated['dokumen_verifikasi'] = $request->file('dokumen_verifikasi')->store('panti/dokumen', 'public');
        }

        $validated['status_verifikasi'] = 'verified';
        $validated['user_id'] = auth()->id(); // Assign ke user yang login

        PantiAsuhan::create($validated);

        return redirect()->route('admin.panti.index')
            ->with('success', 'Panti Asuhan berhasil ditambahkan');
    }

    public function show(PantiAsuhan $panti)
    {
        return view('admin.pantis.show', compact('panti'));
    }

    public function edit(PantiAsuhan $panti)
    {
        return view('admin.pantis.edit', compact('panti'));
    }

    public function update(Request $request, PantiAsuhan $panti)
    {
        $validated = $request->validate([
            'nama_panti' => 'required|string|max:255',
            'pengurus' => 'required|string|max:255',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'kontak' => 'required|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_verifikasi' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'nomor_rekening' => 'required|string',
            'bank' => 'required|string',
            'status_verifikasi' => 'required|in:menunggu,terverifikasi,ditolak'
        ]);

        // Update foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            if ($panti->foto_profil) {
                Storage::disk('public')->delete($panti->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('panti/foto', 'public');
        }

        // Update dokumen verifikasi jika ada
        if ($request->hasFile('dokumen_verifikasi')) {
            if ($panti->dokumen_verifikasi) {
                Storage::disk('public')->delete($panti->dokumen_verifikasi);
            }
            $validated['dokumen_verifikasi'] = $request->file('dokumen_verifikasi')->store('panti/dokumen', 'public');
        }

        $panti->update($validated);

        return redirect()->route('admin.panti.index')
            ->with('success', 'Panti Asuhan berhasil diperbarui');
    }

    public function destroy(PantiAsuhan $panti)
    {
        // Hapus file terkait
        if ($panti->foto_profil) {
            Storage::disk('public')->delete($panti->foto_profil);
        }
        if ($panti->dokumen_verifikasi) {
            Storage::disk('public')->delete($panti->dokumen_verifikasi);
        }

        $panti->delete();

        return redirect()->route('admin.panti.index')
            ->with('success', 'Panti Asuhan berhasil dihapus');
    }
}
