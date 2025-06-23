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
            'amount' => 'required|numeric|min:10000',
            'hide_name' => 'sometimes|boolean'
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
                'status' => 'waiting confirmation',
                'hide_name' => $request->boolean('hide_name')
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

    public function showHistoryPanti($pantiId)
    {
        // Validasi apakah panti exists
        $panti = PantiAsuhan::findOrFail($pantiId);

        // Ambil transaksi terkait panti ini dengan eager loading user
        $transaksis = Transaksi::with('user')
            ->where('panti_id', $pantiId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung total amount (hanya status sukses)
        $totalAmount = Transaksi::where('panti_id', $pantiId)
            ->where('status', 'success')
            ->sum('amount');

        // Format data transaksi
        $formattedTransaksis = $transaksis->map(function ($transaksi) {
            $donaturName = 'Anonim';
            $donaturEmail = 'anonim@email.com';
            
            // Jika ada relasi user dan bukan hide_name
            if ($transaksi->user && !$transaksi->hide_name) {
                $donaturName = $transaksi->user->name;
                $donaturEmail = $transaksi->user->email;
            } 
            // Jika hide_name true, sensor data
            elseif ($transaksi->user && $transaksi->hide_name) {
                // Sensor nama - hanya tampilkan 3 karakter pertama
                $donaturName = substr($transaksi->user->name, 0, 3) . str_repeat('*', max(0, strlen($transaksi->user->name) - 3));
                
                // Sensor email - hanya tampilkan 3 karakter sebelum @
                $emailParts = explode('@', $transaksi->user->email);
                if (count($emailParts) > 1) {
                    $emailLocal = substr($emailParts[0], 0, 3) . str_repeat('*', max(0, strlen($emailParts[0]) - 3));
                    $donaturEmail = $emailLocal . '@' . $emailParts[1];
                }
            }

            return [
                'donatur_name' => $donaturName,
                'donatur_email' => $donaturEmail,
                'order_id' => $transaksi->order_id,
                'amount' => number_format($transaksi->amount, 0, ',', '.'),
                'status' => $transaksi->status,
                'created_at' => $transaksi->created_at->translatedFormat('j F Y \p\u\k\u\l H.i'),
                'payment_method' => $transaksi->payment_method,
                'hide_name' => $transaksi->hide_name
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedTransaksis,
            'total_amount' => number_format($totalAmount, 0, ',', '.'),
            'panti' => $panti->only(['id', 'nama_panti'])
        ]);
    }

    // riwayat transaksi yang dikirim user sedang login

    // riwayat transaksi yang diterima panti asuhan

    // transaksi si admin
    // get all transactions

    // update status transaksi
    // klo udh selesai transfer ke panti, update status jadi success

}