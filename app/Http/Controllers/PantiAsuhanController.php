<?php

namespace App\Http\Controllers;

use App\Models\PantiAsuhan;
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

        return response()->json([
            'data' => $pantis
        ]);
    }

    /**
     * Menampilkan detail lengkap panti asuhan
     */
    public function show($id)
    {
        $panti = PantiAsuhan::with(['user:id,phone,avatar'])
            ->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $panti->id,
                'nama_panti' => $panti->nama_panti,
                'alamat' => $panti->alamat,
                'deskripsi' => $panti->deskripsi,
                'foto_profil_url' => $panti->foto_profil ? asset('storage/' . $panti->foto_profil) : null,
                'kontak' => $panti->kontak,
                'nomor_rekening' => $panti->nomor_rekening,
                'bank' => $panti->bank,
                'status_verifikasi' => $panti->status_verifikasi,
                'user' => [
                    'phone' => $panti->user->phone,
                    'avatar_url' => $panti->user->avatar ? asset('storage/' . $panti->user->avatar) : null,
                ]
            ]
        ]);
    }
}