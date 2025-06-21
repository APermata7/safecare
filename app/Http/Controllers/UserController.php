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

    public function getDonatorOnly()
    {
        $users = User::select('id', 'name', 'email', 'avatar', 'role', 'status')
            ->orderBy('created_at', 'asc')->where('role', '==', 'donatur')
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

    public function getPantiAsuhanList()
    {
        $users = User::select('id', 'name', 'email', 'avatar', 'role', 'status')
            ->orderBy('created_at', 'asc')->where('role', '==', 'panti')
            ->get();

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

    /**
     * Mengubah role dari donatur ke panti
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:donatur'
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        if ($user->role === 'panti') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah role panti'
            ], 404);
        }

        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah role admin'
            ], 404);
        }

        $user->role = $request->role;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Role user berhasil diubah',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    }
}