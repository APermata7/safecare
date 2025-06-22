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
                'status' => 'waiting confirmation',
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
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memproses pembayaran'
            ], 500);
        }
    }

    public function userDonationHistory()
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat riwayat donasi.');
        }

        $transaksis = Transaksi::where('user_id', auth()->id())
            ->with('panti') // Ambil data panti asuhan terkait
            ->orderBy('created_at', 'desc')
            ->get();

        return view('donasi.riwayat', compact('transaksis'));
    }

    /**
     * Menampilkan riwayat transaksi donasi yang diterima oleh panti asuhan yang dikelola user.
     */
    public function pantiDonationHistory()
    {
        // Pastikan user sudah login dan role-nya adalah 'panti'
        if (!auth()->check() || auth()->user()->role !== 'panti') {
            abort(403, 'Akses Dilarang. Hanya pengurus panti yang dapat melihat halaman ini.');
        }

        // Ambil ID panti asuhan yang dikelola oleh user yang sedang login
        $pantiAsuhanId = auth()->user()->pantiAsuhan->id ?? null;

        if (!$pantiAsuhanId) {
            // Jika user dengan role 'panti' tapi belum punya data panti asuhan
            return view('panti.donasi-diterima', ['transaksis' => collect([])])
                ->with('info', 'Anda belum memiliki data panti asuhan terdaftar. Silakan lengkapi profil panti Anda.');
        }

        $transaksis = Transaksi::where('panti_id', $pantiAsuhanId)
            ->where('status', 'success') // Hanya tampilkan donasi yang berhasil
            ->with('user') // Ambil data donatur terkait
            ->orderBy('created_at', 'desc')
            ->get();

        return view('panti.donasi-diterima', compact('transaksis'));
    }

    /**
     * Menampilkan semua riwayat transaksi untuk admin.
     */
    public function adminTransactionHistory()
    {
        $transaksis = Transaksi::with(['user', 'panti'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.transaksi.index', compact('transaksis'));
    }

    /**
     * Memperbarui status transaksi.
     * Biasanya dipanggil oleh admin untuk menandai bahwa transfer ke panti sudah dilakukan.
     */
    public function updateTransactionStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:waiting confirmation,success,canceled',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // Hanya admin yang boleh mengubah status ke 'success' atau 'canceled' secara manual
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Hanya admin yang dapat mengubah status transaksi.'
            ], 403);
        }

        $transaksi->status = $request->status;
        $transaksi->save();

        return response()->json([
            'message' => 'Status transaksi berhasil diperbarui.',
            'transaksi' => $transaksi
        ]);
    }
    // riwayat transaksi yang dikirim user sedang login

    // riwayat transaksi yang diterima panti asuhan

    // transaksi si admin
    // get all transactions

    // update status transaksi
    // klo udh selesai transfer ke panti, update status jadi success

}