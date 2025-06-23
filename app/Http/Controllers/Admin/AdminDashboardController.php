<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\PantiAsuhan;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [
            'totalPanti' => PantiAsuhan::count(),
            'totalDonatur' => User::where('role', 'donatur')->count(),
            'totalMessages' => Message::count(),
            'unreadMessages' => Message::whereNull('reply')->count(),
            'latestMessages' => Message::latest()->take(5)->get()
        ]);
    }
}