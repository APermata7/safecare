<x-app-layout>
    {{-- Slot header bawaan tidak kita gunakan lagi --}}

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-center mb-8">
                            {{-- Judul Halaman di Tengah (dibuat lebih kecil & rounded) --}}
                            <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                                Daftar Panti Asuhan
                            </h2>
                        </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg shadow">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ====================================================================== --}}
            {{-- CARD UTAMA SEBAGAI PEMBUNGKUS GRID                                     --}}
            {{-- ====================================================================== --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($pantis as $panti)
                        {{-- PERUBAHAN: Penyesuaian style pada card panti individual --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden hover:shadow-[0_0_25px_rgba(0,0,0,0.15)] hover:border-transparent hover:-translate-y-1 transition-all duration-300">
                            <a href="{{ route('panti.show', $panti['id']) }}" class="block">
                                @if($panti['foto_profil_url'])
                                    <img src="{{ $panti['foto_profil_url'] }}" alt="{{ $panti['nama_panti'] }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        {{-- Ganti dengan icon jika mau --}}
                                        <i class="fa-solid fa-image text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-800 truncate">{{ $panti['nama_panti'] }}</h3>
                                    <p class="text-gray-600 mt-2 flex items-center">
                                        {{-- PERUBAHAN: Memberi warna pada ikon --}}
                                        <i class="fas fa-phone mr-2 text-secondary-green"></i>
                                        {{ $panti['kontak'] }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @empty
                        {{-- Tampilan jika tidak ada panti sama sekali --}}
                        <div class="md:col-span-2 lg:col-span-3 text-center py-16">
                            <i class="fa-solid fa-house-chimney-window text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700">Belum Ada Panti Asuhan</h3>
                            <p class="text-gray-500 mt-2">Saat ini belum ada panti asuhan yang terdaftar.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
