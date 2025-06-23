<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        // Hapus avatar jika ada
        if ($user->avatar && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}