@extends('layouts.admin')

@section('title', 'Management Transaksi')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center">
            <i class="fas fa-exchange-alt mr-2"></i> Management Transaksi
        </h1>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold">Daftar Transaksi</h2>
            <div class="relative">
                <form action="{{ route('admin.transactions.index') }}" method="GET">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari transaksi..." 
                        class="border rounded px-3 py-1 pl-8"
                        value="{{ request('search') }}"
                    >
                    <i class="fas fa-search absolute left-2 top-2 text-gray-400"></i>
                </form>
            </div>
        </div>

        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Donatur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Panti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->order_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->donatur)
                                <div class="text-sm text-gray-900">{{ $transaction->donatur->name }}</div>
                                <div class="text-sm text-gray-500">{{ $transaction->donatur->email }}</div>
                                @else
                                <div class="text-sm text-gray-500">Donatur tidak ditemukan</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->panti->nama_panti ?? 'Panti tidak ditemukan' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->status_color }}">
                                    {{ $transaction->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-exchange-alt text-4xl mb-2"></i>
                <p>Tidak ada transaksi yang ditemukan</p>
            </div>
        @endif
    </div>
</div>
@endsection