<x-app-layout>
    <div class="pt-24 sm:pt-4 pb-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="flex justify-center">
                <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                    Riwayat Donasi Diterima
                </h2>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 md:p-8">
                @if(session('info'))
                    <div class="mb-4 p-4 bg-blue-100 text-blue-700 rounded-lg shadow">
                        {{ session('info') }}
                    </div>
                @endif

                <div class="space-y-4">
                    <h2 class="font-bold text-[20px] text-primary-green">Total Terkumpul: Rp{{ number_format($totalDonasi, 0, ',', '.') }}</h2>
                    <hr>
                    @forelse($transaksis as $transaksi)
                        <div class="p-4 border rounded-xl flex justify-between items-center hover:bg-gray-50 transition">
                            <div>
                                {{-- Pastikan relasi user sudah di-load --}}
                                <p class="font-bold text-gray-800">Donasi dari: {{ $transaksi->user->name ?? 'Donatur Dihapus/Anonim' }}</p>
                                <p class="text-sm text-gray-800">{{ $transaksi->user->email ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $transaksi->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg text-primary-green">Rp{{ number_format($transaksi->amount, 0, ',', '.') }}</p>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                             @if($transaksi->status === 'success') bg-green-100 text-green-800
                                             @elseif($transaksi->status === 'waiting confirmation') bg-yellow-100 text-yellow-800
                                             @else bg-red-100 text-red-800 @endif">{{ strtoupper($transaksi->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                         <div class="text-center py-12 border-2 border-dashed rounded-xl">
                            <i class="fa-solid fa-hand-holding-dollar text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada donasi yang diterima.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>