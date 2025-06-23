<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PantiAsuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user dengan informasi dasar
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'avatar', 'role', 'status')
            ->orderBy('created_at', 'asc')->where('role', '!=', 'admin') // Hanya menampilkan user non-admin
            ->get();

        // Mengubah path avatar menjadi URL yang dapat diakses
        $users->transform(function ($user) {
            if ($user->avatar) {
                $user->avatar = Storage::url($user->avatar);
            }
            return $user;
        });

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Melakukan ban pada user
     */
    public function ban($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat memban admin'
            ], 403);
        }

        $user->status = 'banned';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diban'
        ]);
    }

    /**
     * Melakukan unban pada user
     */
    public function unban($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $user->status = 'active';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diunban'
        ]);
    }

    /**
     * Menghapus user
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus admin'
            ], 403);
        }

        // Hapus data panti asuhan dan filenya jika user adalah pengurus panti
        if ($user->role === 'panti') {
            $pantiAsuhan = PantiAsuhan::where('user_id', $user->id)->first();
            if ($pantiAsuhan) {
                // Hapus foto profil panti jika ada
                if ($pantiAsuhan->foto_profil && Storage::disk('public')->exists($pantiAsuhan->foto_profil)) {
                    Storage::disk('public')->delete($pantiAsuhan->foto_profil);
                }
                // Hapus dokumen verifikasi panti jika ada
                if ($pantiAsuhan->dokumen_verifikasi && Storage::disk('public')->exists($pantiAsuhan->dokumen_verifikasi)) {
                    Storage::disk('public')->delete($pantiAsuhan->dokumen_verifikasi);
                }
                // Hapus record panti dari database
                $pantiAsuhan->delete();
            }
        }

        // Hapus avatar user jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Hapus user dari database
        $user->delete();

        // Kembalikan response sukses dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'User beserta data terkait berhasil dihapus'
        ]);
    }
}