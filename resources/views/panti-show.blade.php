<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Panti Asuhan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Panti Info -->
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Panti Image -->
                        <div class="w-full md:w-1/3">
                            @if($panti['foto_profil_url'])
                                <img src="{{ $panti['foto_profil_url'] }}" alt="{{ $panti['nama_panti'] }}"
                                     class="w-full h-64 object-cover rounded-lg">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- Panti Avatar -->
                        <div class="w-24 h-24">
                            @if($panti['user']['avatar_url'])
                                <img src="{{ $panti['user']['avatar_url'] }}" alt="{{ $panti['nama_panti'] }}"
                                    class="w-24 h-24 object-cover rounded-full shadow">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-full">
                                    <span class="text-gray-500 text-sm">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- Panti Details -->
                        <div class="w-full md:w-2/3">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $panti['nama_panti'] }}</h1>

                            <div class="mt-4 space-y-4">

                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Alamat</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $panti['alamat'] }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Deskripsi</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $panti['deskripsi'] }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Kontak</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $panti['kontak'] }}</p>
                                </div>

                            </div>

                            <!-- Donation Form -->
                            @auth
                                @if(auth()->user()->id !== $panti['user_id'])
                                    <div class="mt-8 border-t pt-6">
                                        <h3 class="text-lg font-medium text-gray-900">Buat Donasi</h3>
                                        <form id="donationForm" action="{{ route('donasi.create') }}" method="POST" class="mt-4">
                                            @csrf
                                            <input type="hidden" name="panti_id" value="{{ $panti['id'] }}">

                                            <div class="mb-4">
                                                <label for="amount" class="block text-sm font-medium text-gray-700">
                                                    Jumlah Donasi (Rp)
                                                </label>
                                                <input type="number" id="amount" name="amount"
                                                       min="10000" step="1000"
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                                              focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                       required>
                                                @error('amount')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <button type="submit"
                                                    class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2
                                                           bg-indigo-600 border rounded-md font-semibold
                                                           text-xs text-black uppercase tracking-widest hover:bg-indigo-700
                                                           active:bg-indigo-900 focus:outline-none focus:border-indigo-900
                                                           focus:ring ring-indigo-300 disabled:opacity-25 transition
                                                           ease-in-out duration-150">
                                                Donasi Sekarang
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Riwayat Transaksi -->
                                    <div class="mt-8 border-t pt-6">
                                        <h3 class="text-lg font-medium text-gray-900">Riwayat Donasi Anda ke {{ $panti['nama_panti'] }}</h3>
                                        <div class="mt-4 space-y-4">
                                            @forelse($riwayatTransaksi as $transaksi)
                                                <div class="p-4 bg-gray-50 rounded-lg">
                                                    <div class="flex justify-between items-center">
                                                        <div>
                                                            <p class="font-medium">ID Transaksi: {{ $transaksi->order_id }}</p>
                                                            <p class="text-sm text-gray-600">
                                                                Tanggal: {{ $transaksi->created_at->format('d M Y H:i') }}
                                                            </p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="font-bold text-indigo-600">
                                                                Rp {{ number_format($transaksi->amount, 0, ',', '.') }}
                                                            </p>
                                                            <span class="px-2 py-1 text-xs rounded-full
                                                                @if($transaksi->status === 'done') bg-green-100 text-green-800
                                                                @elseif($transaksi->status === 'pending') bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800 @endif">
                                                                {{ strtoupper($transaksi->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if($transaksi->payment_method)
                                                        <p class="text-sm mt-2">
                                                            Metode: {{ strtoupper($transaksi->payment_method) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @empty
                                                <p class="text-gray-500">Belum ada riwayat donasi.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    @auth
        @if(auth()->user()->id !== $panti['user_id'])
            <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                    data-client-key="{{ config('midtrans.client_key') }}"></script>
            <script>
                document.getElementById('donationForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const button = this.querySelector('button[type="submit"]');
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            panti_id: this.panti_id.value,
                            amount: this.amount.value
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw new Error(err.message || 'Network response was not ok'); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.snap_token) {
                            snap.pay(data.snap_token, {
                                // Tetap di halaman yang sama setelah pembayaran
                                onSuccess: function(result) {
                                    window.location.reload();
                                },
                                onPending: function(result) {
                                    window.location.reload();
                                },
                                onError: function(result) {
                                    window.location.reload();
                                },
                                onClose: function() {
                                    window.location.reload();
                                }
                            });
                        } else {
                            throw new Error('Token pembayaran tidak diterima');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                        button.disabled = false;
                        button.innerHTML = 'Donasi Sekarang';
                    });
                });
            </script>
        @endif
    @endauth
</x-app-layout>
