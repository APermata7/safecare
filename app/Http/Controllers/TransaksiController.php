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

        return response()->json([
            'success' => true,
            'transaksis' => $transaksis
        ]);
    }

    public function showDetail($id)
{
    $transaksi = Transaksi::with(['user', 'panti'])->findOrFail($id);

    return response()->json([
        'success' => true,
        'transaksi' => [
            'id' => $transaksi->id,
            'order_id' => $transaksi->order_id,
            'amount' => $transaksi->amount,
            'status' => $transaksi->status,
            'created_at' => $transaksi->created_at,
            'updated_at' => $transaksi->updated_at,
            'payment_method' => $transaksi->payment_method,
            'hide_name' => $transaksi->hide_name,
            // Data user
            'user' => $transaksi->user ? [
                'id' => $transaksi->user->id,
                'name' => $transaksi->user->name,
                'email' => $transaksi->user->email,
            ] : null,
            // Data panti
            'panti' => $transaksi->panti ? [
                'id' => $transaksi->panti->id,
                'nama_panti' => $transaksi->panti->nama_panti,
                'alamat' => $transaksi->panti->alamat,
            ] : null,
        ]
    ]);
}

    /**
     * Memperbarui status transaksi.
     * Biasanya dipanggil oleh admin untuk menandai bahwa transfer ke panti sudah dilakukan.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'sometimes|required|in:waiting confirmation,success,canceled',
        'payment_method' => 'sometimes|required|in:bank transfer,QRIS',
    ]);

    $transaksi = Transaksi::findOrFail($id);

    // Hanya admin yang boleh mengubah status
    if ($request->has('status') && auth()->user()->role !== 'admin') {
        return response()->json([
            'message' => 'Unauthorized. Hanya admin yang dapat mengubah status transaksi.'
        ], 403);
    }

    if ($request->has('status')) {
        $transaksi->status = $request->status;
    }
    if ($request->has('payment_method')) {
        $transaksi->payment_method = $request->payment_method;
    }
    $transaksi->save();

    return response()->json([
        'message' => 'Transaksi berhasil diperbarui.',
        'transaksi' => $transaksi
    ]);
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
                'created_at' => $transaksi->created_at->format('d F Y, H:i'),
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
}