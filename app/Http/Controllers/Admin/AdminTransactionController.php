<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['donatur', 'panti'])
            ->latest();
            
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_id', 'like', '%'.$request->search.'%')
                  ->orWhereHas('donatur', function($q) use ($request) {
                      $q->where('name', 'like', '%'.$request->search.'%');
                  })
                  ->orWhereHas('panti', function($q) use ($request) {
                      $q->where('nama_panti', 'like', '%'.$request->search.'%');
                  });
            });
        }
        
        $transactions = $query->paginate(10);
        
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaksi $transaction)
    {
        $transaction->load(['donatur', 'panti']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaksi $transaction)
    {
        $validStatuses = ['pending', 'waiting confirmation', 'success', 'failed', 'canceled', 'expired'];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $validStatuses),
        ]);

        $transaction->update(['status' => $request->status]);

        return redirect()->route('admin.transactions.show', $transaction->id)
            ->with('success', 'Status transaksi berhasil diperbarui');
    }

    public function destroy(Transaksi $transaction)
    {
        $transaction->delete();
        
        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}