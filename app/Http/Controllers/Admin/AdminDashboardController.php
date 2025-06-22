<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\PantiAsuhan;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'userCount' => User::where('role', '!=', 'admin')->count(),
            'pantiCount' => PantiAsuhan::count(),
            'messageCount' => Message::count(),
            'messages' => Message::with('user')->latest()->take(5)->get()
        ];

        return view('admin.dashboard', $stats);
    }

    public function show($id)
    {
        $message = Message::with('user')->findOrFail($id);
        return view('admin.message-show', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string'
        ]);

        $message = Message::findOrFail($id);
        $message->balasan = $request->balasan;
        $message->save();

        return redirect()->route('admin.dashboard')->with('success', 'Pesan berhasil dibalas.');
    }
}