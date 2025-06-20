<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\PantiAsuhan;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;

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

        try {
            $panti = PantiAsuhan::findOrFail($request->panti_id);

            if ($panti->user_id === auth()->id()) {
                return response()->json([
                    'error' => 'Kamu tidak bisa berdonasi ke panti milik sendiri.'
                ], 403);
            }

            $transaksi = Transaksi::create([
                'user_id' => auth()->id(),
                'panti_id' => $request->panti_id,
                'order_id' => Transaksi::generateOrderId(),
                'amount' => $request->amount,
                'status' => 'waiting_confirmation',
            ]);

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaksi->order_id,
                    'gross_amount' => $transaksi->amount,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'phone' => auth()->user()->phone,
                ],
                'item_details' => [
                    [
                        'id' => 'DONASI-' . $transaksi->id,
                        'price' => $transaksi->amount,
                        'quantity' => 1,
                        'name' => 'Donasi untuk ' . $panti->nama_panti,
                        'category' => 'Donation'
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            $transaksi->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'transaksi' => $transaksi
            ]);

        } catch (\Exception $e) {
            \Log::error('Midtrans Error: '.$e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memproses pembayaran'
            ], 500);
        }
    }

    // riwayat transaksi yang dikirim user sedang login

    // riwayat transaksi yang diterima panti asuhan
}