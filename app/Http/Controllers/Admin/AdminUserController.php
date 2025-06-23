<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'admin')
            ->withCount(['transactions as donations_count' => function($q) {
                $q->where('status', 'success');
            }])
            ->latest();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            if ($request->status === 'banned') {
                $query->whereNotNull('banned_at');
            } else {
                $query->whereNull('banned_at');
            }
        }

        $users = $query->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
            'roles' => ['donatur' => 'Donatur', 'panti' => 'Panti'],
            'statuses' => ['active' => 'Aktif', 'banned' => 'Banned']
        ]);
    }

    public function getDonatorOnly(Request $request)
    {
        $query = User::where('role', 'donatur')
            ->withCount(['transactions as donations_count' => function($q) {
                $q->where('status', 'success');
            }])
            ->latest();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->has('status')) {
            if ($request->status === 'banned') {
                $query->whereNotNull('banned_at');
            } else {
                $query->whereNull('banned_at');
            }
        }

        $donaturs = $query->paginate(10);

        return view('admin.users.donaturs', [
            'donaturs' => $donaturs,
            'statuses' => ['active' => 'Aktif', 'banned' => 'Banned']
        ]);
    }

    public function ban(User $user)
    {
        $user->banned_at = now();
        $user->save();

        return back()->with('success', 'User berhasil dibanned.');
    }

    public function unban(User $user)
    {
        $user->banned_at = null;
        $user->save();

        return back()->with('success', 'User berhasil diunban.');
    }

    public function updateRole(User $user)
    {
        $user->role = 'panti';
        $user->save();

        return back()->with('success', 'Role user berhasil diubah ke Panti.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}