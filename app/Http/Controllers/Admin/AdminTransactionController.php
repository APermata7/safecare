<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaksi::with(['user', 'panti'])
            ->latest()
            ->paginate(10);
            
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaksi $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    public function destroy(Transaksi $transaction)
    {
        $transaction->delete();
        
        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}