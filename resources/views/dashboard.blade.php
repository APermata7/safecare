<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Panti Asuhan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Panti Asuhan Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pantis as $panti)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <a href="{{ route('panti.show', $panti['id']) }}" class="block">
                            @if($panti['foto_profil_url'])
                                <img src="{{ $panti['foto_profil_url'] }}" alt="{{ $panti['nama_panti'] }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $panti['nama_panti'] }}</h3>
                                <p class="text-gray-600 mt-2">
                                    <i class="fas fa-phone mr-2"></i> {{ $panti['kontak'] }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
