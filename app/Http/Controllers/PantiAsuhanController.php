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
        $panti = PantiAsuhan::with(['user:id,name,avatar'])
                ->findOrFail($id);

        $riwayatTransaksi = [];
        
            if (auth()->check()) {
                $riwayatTransaksi = Transaksi::where('user_id', auth()->id())
                    ->where('panti_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        
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
                'name' => $panti->user->name,
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
            'created_at' => $panti->created_at,
            'updated_at' => $panti->updated_at,
            'user' => [
                'id' => $panti->user_id,
                'name' => $panti->user->name ?? 'User tidak ditemukan',
                'avatar_url' => $panti->user->avatar ? asset('storage/' . $panti->user->avatar) : null,
            ]
        ];

        return view('admin.panti.show', compact('pantiData'));
    }
}
