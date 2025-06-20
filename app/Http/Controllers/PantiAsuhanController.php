<?php

namespace App\Http\Controllers;

use App\Models\PantiAsuhan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

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
        
        if (auth()->check() && auth()->user()->role === 'donatur') {
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
}