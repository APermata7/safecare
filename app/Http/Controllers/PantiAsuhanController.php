<?php

namespace App\Http\Controllers;

use App\Models\PantiAsuhan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PantiAsuhanController extends Controller
{
    /**
     * Menampilkan daftar panti asuhan (hanya foto, nama, dan kontak)
     */
    public function index()
    {
        $pantis = PantiAsuhan::where('status_verifikasi', 'verified')
            ->select('id', 'nama_panti', 'foto_profil', 'kontak')
            ->get()
            ->map(function ($panti) {
                return [
                    'id' => $panti->id,
                    'nama_panti' => $panti->nama_panti,
                    'foto_profil_url' => $panti->foto_profil ? asset('storage/' . $panti->foto_profil) : null,
                    'kontak' => $panti->kontak
                ];
            });

        return view('dashboard', compact('pantis'));
    }

    /**
     * Menampilkan detail lengkap panti asuhan
     */
    public function show($id)
    {
        $panti = PantiAsuhan::with(['user:id,avatar'])
            ->findOrFail($id);

        $riwayatTransaksi = [];
        
        $riwayatTransaksi = Transaksi::where('user_id', auth()->id())
            ->where('panti_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pantiData = [
            'id' => $panti->id,
            'nama_panti' => $panti->nama_panti,
            'alamat' => $panti->alamat,
            'deskripsi' => $panti->deskripsi,
            'foto_profil_url' => $panti->foto_profil ? asset('storage/' . $panti->foto_profil) : null,
            'dokumen_verifikasi_url' => $panti->dokumen_verifikasi ? asset('storage/' . $panti->dokumen_verifikasi) : null,
            'kontak' => $panti->kontak,
            'status_verifikasi' => $panti->status_verifikasi,
            'user_id' => $panti->user_id,
            'user' => [
                'avatar_url' => $panti->user->avatar ? asset('storage/' . $panti->user->avatar) : null,
            ]
        ];

        return view('panti-show', [
            'panti' => $pantiData,
            'riwayatTransaksi' => $riwayatTransaksi
        ]);
    }

    /**
     * menampilkan daftar panti asuhan untuk admin,
     * lengkap dengan avatar dan nama user pengurus panti
    **/
    public function indexAdmin()
    {
        $pantis = PantiAsuhan::with(['user:id,name,avatar']) // Eager load user data yang dibutuhkan
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($panti) {
                return [
                    'id' => $panti->id,
                    'nama_panti' => $panti->nama_panti,
                    'alamat' => $panti->alamat,
                    'foto_profil_url' => $panti->foto_profil ? asset('storage/' . $panti->foto_profil) : null,
                    'status_verifikasi' => $panti->status_verifikasi,
                    'kontak' => $panti->kontak,
                    'created_at' => $panti->created_at->format('d-m-Y H:i:s'),
                    'updated_at' => $panti->updated_at->format('d-m-Y H:i:s'),
                    'user' => [
                        'id' => $panti->user_id,
                        'name' => $panti->user->name ?? 'User tidak ditemukan',
                        'avatar_url' => $panti->user->avatar ? asset('storage/' . $panti->user->avatar) : null,
                    ]
                ];
            });

        return view('admin.panti.index', compact('pantis'));
    }

    /**
     * Menampilkan detail panti asuhan untuk admin
     */
    public function showAdmin($id)
    {
        $panti = PantiAsuhan::with(['user:id,name,avatar'])
            ->findOrFail($id);

        $pantiData = [
            'id' => $panti->id,
            'nama_panti' => $panti->nama_panti,
            'alamat' => $panti->alamat,
            'deskripsi' => $panti->deskripsi,
            'foto_profil_url' => $panti->foto_profil ? asset('storage/' . $panti->foto_profil) : null,
            'dokumen_verifikasi' => $panti->dokumen_verifikasi ? asset('storage/' . $panti->dokumen_verifikasi) : null,
            'status_verifikasi' => $panti->status_verifikasi,
            'nomor_rekening' => $panti->nomor_rekening,
            'bank' => $panti->bank,
            'kontak' => $panti->kontak,
            'created_at' => $panti->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $panti->updated_at->format('d-m-Y H:i:s'),
            'user' => [
                'id' => $panti->user_id,
                'name' => $panti->user->name ?? 'User tidak ditemukan',
                'avatar_url' => $panti->user->avatar ? asset('storage/' . $panti->user->avatar) : null,
            ]
        ];

        return view('admin.panti.show', compact('pantiData'));
    }

    /**
     * Menyimpan data panti asuhan baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'nama_panti' => 'required|string|max:255',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:16384',
            'dokumen_verifikasi' => 'required|file|mimes:pdf,jpeg,png,jpg|max:16384', // Maksimal 16MB
            'nomor_rekening' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'kontak' => 'required|string|max:255',
        ]);

        // Cek apakah user memiliki role panti
        $user = User::findOrFail($validated['user_id']);
        if ($user->role !== 'panti') {
            return response()->json([
                'message' => 'Hanya user dengan role panti yang dapat membuat data panti asuhan'
            ], 403);
        }

        // Handle file upload
        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('panti/profil', 'public');
        }

        if ($request->hasFile('dokumen_verifikasi')) {
            $validated['dokumen_verifikasi'] = $request->file('dokumen_verifikasi')->store('panti/dokumen', 'public');
        }

        // Set status verifikasi default
        $validated['status_verifikasi'] = 'verified'; // Atau 'verified' sesuai kebutuhan

        // Buat panti asuhan
        $panti = PantiAsuhan::create($validated);

        return response()->json([
            'message' => 'Panti asuhan berhasil dibuat',
            'data' => $panti
        ], 201);
    }

    /**
     * Update data panti asuhan dengan kontrol manual status verifikasi
     */
    public function update(Request $request, $id)
    {
        $panti = PantiAsuhan::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'nama_panti' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string',
            'deskripsi' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dokumen_verifikasi' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'status_verifikasi' => 'sometimes|in:verified,unverified', // Admin bisa memilih manual
            'nomor_rekening' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'kontak' => 'sometimes|string|max:255',
        ]);

        // Handle file upload untuk foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($panti->foto_profil) {
                Storage::disk('public')->delete($panti->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('panti/profil', 'public');
        }

        // Handle file upload untuk dokumen verifikasi
        if ($request->hasFile('dokumen_verifikasi')) {
            // Hapus dokumen lama jika ada
            if ($panti->dokumen_verifikasi) {
                Storage::disk('public')->delete($panti->dokumen_verifikasi);
            }
            $validated['dokumen_verifikasi'] = $request->file('dokumen_verifikasi')->store('panti/dokumen', 'public');
        }

        // Update data panti
        $panti->update($validated);

        return response()->json([
            'message' => 'Data panti asuhan berhasil diperbarui',
            'data' => $panti
        ]);
    }
}