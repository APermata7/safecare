<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-center mb-8">

                {{-- Tombol Kembali --}}
                <a href="{{ route('dashboard') }}"
                   class="absolute left-0 flex items-center justify-center w-16 h-12 bg-white rounded-full shadow-md text-gray-600 hover:bg-gray-100 hover:text-gray-800 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                {{-- Judul Halaman --}}
                <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                    Detail Panti Asuhan
                </h2>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                {{-- Header Visual Panti --}}
                <div class="relative w-full h-96 bg-gray-200">
                    @if($panti['foto_profil_url'])
                        <img src="{{ $panti['foto_profil_url'] }}" alt="Foto Profil {{ $panti['nama_panti'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="text-gray-500">Gambar Panti Tidak Tersedia</span>
                        </div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 sm:left-6 sm:right-auto">
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-4 border border-white/20 shadow-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0 bg-gray-300 ring-2 ring-white/50">
                                    @if($panti['user']['avatar_url'])
                                        <img src="{{ $panti['user']['avatar_url'] }}" alt="Avatar Pengelola" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                             <svg class="w-12 h-12 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="pb-1">
                                    <h1 class="text-xl lg:text-2xl font-bold text-white drop-shadow-lg leading-tight">{{ $panti['nama_panti'] }}</h1>
                                    <p class="text-sm text-gray-100 drop-shadow-lg">Dikelola oleh: {{ $panti['user']['name'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Konten --}}
                <div class="p-6 md:p-8">
                   <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                        <div class="space-y-8">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-3">Deskripsi Panti</h3>
                                <p class="text-gray-700 whitespace-pre-line">{{ $panti['deskripsi'] ?? 'Tidak ada deskripsi.' }}</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-600 flex items-center"><i class="fa-solid fa-map-location-dot mr-2 text-primary-green"></i>Alamat</h4>
                                <p class="mt-1 text-gray-800">{{ $panti['alamat'] }}</p>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-600 flex items-center"><i class="fa-solid fa-phone mr-2 text-primary-green"></i>Kontak</h4>
                                <p class="mt-1 text-gray-800">{{ $panti['kontak'] }}</p>
                            </div>
                            @if(isset($panti['dokumen_verifikasi_url']) && $panti['dokumen_verifikasi_url'])
                                <div>
                                    <h4 class="font-semibold text-gray-600 flex items-center"><i class="fa-solid fa-shield-halved text-primary-green"></i>Verifikasi</h4>
                                    <p class="text-sm text-gray-500 mb-2">Panti asuhan ini telah mengunggah dokumen untuk verifikasi.</p>
                                    <button
                                        data-modal-toggle="documentModal"
                                        data-document-url="{{ $panti['dokumen_verifikasi_url'] }}"
                                        data-panti-name="{{ $panti['nama_panti'] }}"
                                        class="inline-flex items-center px-4 py-2 bg-secondary-green border border-transparent
                                        font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-green
                                        focus:outline-none focus:ring-2 focus:ring-light-green focus:ring-offset-2 rounded-xl
                                        transition ease-in-out duration-150">
                                        <i class="fa-solid fa-file-lines mr-2"></i>
                                        Lihat Dokumen Verifikasi
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-8">
                            @auth
                                @if(auth()->user()->id !== $panti['user_id'])
                                    <div class="border rounded-2xl p-6 shadow-sm bg-gray-50">
                                        <h3 class="text-lg font-bold text-gray-900 mb-4">Bantu Panti Asuhan Ini</h3>
                                        <form id="donationForm" action="{{ route('donasi.create') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="panti_id" value="{{ $panti['id'] }}">
                                            <div>
                                                <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah Donasi (Rp)</label>
                                                <input type="number" id="amount" name="amount" min="10000" step="1000"
                                                       class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm
                                                              focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50
                                                              sm:text-sm" required>
                                                @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            <button type="submit"
                                                    class="mt-4 w-full inline-flex justify-center items-center px-4 py-2
                                                           bg-secondary-green border border-transparent rounded-xl font-semibold
                                                           text-xs text-white uppercase tracking-widest
                                                           active:bg-primary-green focus:outline-none focus:ring-2
                                                           focus:ring-offset-2 focus:ring-light-green disabled:opacity-25
                                                           transition ease-in-out duration-150">
                                                Donasi Sekarang
                                            </button>
                                        </form>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Riwayat Donasi Anda</h3>
                                        <div class="mt-4 space-y-3">
                                            @forelse($riwayatTransaksi as $transaksi)
                                                <div class="p-4 bg-white rounded-2xl border shadow-sm">
                                                    <div class="flex justify-between items-center">
                                                        <div class="text-sm">
                                                            <p class="font-medium text-gray-600">{{ $transaksi->created_at->format('d M Y, H:i') }}</p>
                                                            <p class="text-xs text-gray-500">ID: {{ $transaksi->order_id }}</p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="font-bold text-primary-green">Rp {{ number_format($transaksi->amount, 0, ',', '.') }}</p>
                                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                                                @if($transaksi->status === 'done') bg-green-100 text-green-800
                                                                @elseif($transaksi->status === 'pending') bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800 @endif">
                                                                {{ strtoupper($transaksi->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-6 border-2 border-dashed rounded-lg">
                                                    <p class="text-sm text-gray-500">Belum ada riwayat donasi ke panti ini.</p>
                                                </div>
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


    {{-- Modal dan Javascript --}}
    <div id="documentModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-800">Dokumen Verifikasi</h3>
                <button data-modal-close="documentModal" class="text-gray-500 hover:text-gray-800">
                    <i class="fa-solid fa-xmark fa-xl"></i>
                </button>
            </div>
            <div class="p-2 md:p-4 flex-grow overflow-auto">
                <img id="documentImage" src="" alt="Dokumen Verifikasi" class="max-w-full mx-auto hidden">
                <iframe id="documentFrame" src="" class="w-full h-[75vh] hidden" frameborder="0"></iframe>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalToggles = document.querySelectorAll('[data-modal-toggle]');
            const modalCloses = document.querySelectorAll('[data-modal-close]');
            modalToggles.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.dataset.modalToggle;
                    const modal = document.getElementById(modalId);
                    const docUrl = this.dataset.documentUrl;
                    const pantiName = this.dataset.pantiName;
                    const imageEl = modal.querySelector('#documentImage');
                    const frameEl = modal.querySelector('#documentFrame');
                    const titleEl = modal.querySelector('#modalTitle');
                    imageEl.classList.add('hidden');
                    frameEl.classList.add('hidden');
                    titleEl.textContent = `Dokumen Verifikasi: ${pantiName}`;
                    if (docUrl.match(/\.(jpeg|jpg|gif|png)$/i)) {
                        imageEl.src = docUrl;
                        imageEl.classList.remove('hidden');
                    } else if (docUrl.match(/\.pdf$/i)) {
                        frameEl.src = docUrl;
                        frameEl.classList.remove('hidden');
                    } else {
                        window.open(docUrl, '_blank');
                        return;
                    }
                    modal.classList.remove('hidden');
                });
            });
            const closeModal = (modal) => {
                const imageEl = modal.querySelector('#documentImage');
                const frameEl = modal.querySelector('#documentFrame');
                modal.classList.add('hidden');
                if(imageEl) imageEl.src = "";
                if(frameEl) frameEl.src = "";
            };
            modalCloses.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.dataset.modalClose;
                    const modal = document.getElementById(modalId);
                    closeModal(modal);
                });
            });
            const modal = document.getElementById('documentModal');
            if (modal) {
                modal.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeModal(this);
                    }
                });
            }
        });
    </script>
    @auth
        @if(auth()->user()->id !== $panti['user_id'])
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
            <script>
                const donationForm = document.getElementById('donationForm');
                if (donationForm) {
                    donationForm.addEventListener('submit', function(e) {
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
                                return response.json().then(err => { throw new Error(err.message || 'Gagal memproses donasi.'); });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.snap_token) {
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) { window.location.reload(); },
                                    onPending: function(result) { window.location.reload(); },
                                    onError: function(result) { window.location.reload(); },
                                    onClose: function() { window.location.reload(); }
                                });
                            } else {
                                throw new Error('Token pembayaran tidak diterima.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error: ' + error.message);
                            button.disabled = false;
                            button.innerHTML = 'Donasi Sekarang';
                        });
                    });
                }
            </script>
        @endif
    @endauth
</x-app-layout>
