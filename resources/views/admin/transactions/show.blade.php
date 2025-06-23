@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center text-primary-green hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Transaksi
            </a>
        </div>

        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Detail Transaksi #{{ $transaction->order_id }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Donatur:</h3>
                @if($transaction->donatur)
                <p class="text-gray-800">{{ $transaction->donatur->name }}</p>
                <p class="text-gray-600 text-sm">{{ $transaction->donatur->email }}</p>
                @else
                <p class="text-gray-500">Data donatur tidak tersedia</p>
                @endif
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Panti Asuhan:</h3>
                @if($transaction->panti)
                <p class="text-gray-800">{{ $transaction->panti->nama_panti }}</p>
                <p class="text-gray-600 text-sm">{{ $transaction->panti->alamat }}</p>
                @else
                <p class="text-gray-500">Data panti tidak tersedia</p>
                @endif
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Jumlah Donasi:</h3>
                <p class="text-gray-800">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Metode Pembayaran:</h3>
                <p class="text-gray-800">{{ $transaction->payment_method ? strtoupper($transaction->payment_method) : 'Belum dipilih' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Status:</h3>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->status_color }}">
                    {{ $transaction->status_label }}
                </span>
                <form method="POST" action="{{ route('admin.transactions.update-status', $transaction->id) }}" class="mt-2">
                    @csrf
                    <select name="status" class="border rounded px-2 py-1 text-sm">
                        <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="waiting confirmation" {{ $transaction->status == 'waiting confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="success" {{ $transaction->status == 'success' ? 'selected' : '' }}>Sukses</option>
                        <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="canceled" {{ $transaction->status == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                        <option value="expired" {{ $transaction->status == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    </select>
                    <button type="submit" class="ml-2 bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600">
                        Update
                    </button>
                </form>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Tanggal Transaksi:</h3>
                <p class="text-gray-800">{{ $transaction->created_at->format('d M Y H:i:s') }}</p>
            </div>
            @if($transaction->snap_token)
            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Token Midtrans:</h3>
                <p class="text-gray-800 break-all">{{ $transaction->snap_token }}</p>
            </div>
            @endif
        </div>

        <div class="flex space-x-4">
            <form method="POST" action="{{ route('admin.transactions.destroy', $transaction->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                    Hapus Transaksi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection