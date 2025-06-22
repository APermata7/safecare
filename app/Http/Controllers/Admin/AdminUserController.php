<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->banned = true;
        $user->save();

        return back()->with('success', 'User berhasil di-ban.');
    }

    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->banned = false;
        $user->save();

        return back()->with('success', 'User berhasil di-unban.');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function getDonatorOnly()
    {
        $donaturs = User::where('role', 'donatur')->get();
        return view('admin.users.donaturs', compact('donaturs'));
    }

    public function updateRole($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'panti';
        $user->save();

        return back()->with('success', 'Role user berhasil diubah ke Panti.');
    }
}