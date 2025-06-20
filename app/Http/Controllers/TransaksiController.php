<?php

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Midtrans\Config;
use Midtrans\Snap;

class TransaksiController extends Controller
{
    /**
     * Create a new donation transaction.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function createDonation(Request $request)
    {
        $request->validate([
            'panti_id' => 'required|exists:panti_asuhan,id',
            'amount' => 'required|numeric|min:10000'
        ]);

        // Buat transaksi
        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'panti_id' => $request->panti_id,
            'order_id' => Transaksi::generateOrderId(),
            'amount' => $request->amount,
            'status' => 'pending'
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;

        // Buat parameter pembayaran
        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->order_id,
                'gross_amount' => $transaksi->amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'item_details' => [
                [
                    'id' => 'DONASI-' . $transaksi->panti_id,
                    'price' => $transaksi->amount,
                    'quantity' => 1,
                    'name' => 'Donasi untuk ' . $transaksi->panti->nama_panti
                ]
            ]
        ];

        // Generate Snap Token
        $snapToken = Snap::getSnapToken($params);

        // Simpan snap token ke transaksi
        $transaksi->update(['snap_token' => $snapToken]);

        return response()->json([
            'snap_token' => $snapToken,
            'transaksi' => $transaksi
        ]);
    }
}