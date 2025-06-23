@extends('layouts.admin')

@section('title', 'Management Panti Asuhan')

@section('content')
<div class="space-y-6">
    <!-- Header dengan tombol tambah -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center text-gray-800">
            <i class="fas fa-home mr-2 text-primary-500"></i> Management Panti Asuhan
        </h1>
        <a href="{{ route('admin.panti.create') }}"
            class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md shadow flex items-center transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah Panti
        </a>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6 rounded">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Card Utama -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
        <!-- Header dengan search -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Panti Asuhan</h2>

            <!-- Form Search -->
            <form method="GET" action="{{ route('admin.panti.index') }}" class="w-full md:w-auto">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari panti..."
                        class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-150">
                    @if(request('search'))
                    <a href="{{ route('admin.panti.index') }}"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        @if($pantis->count() > 0)
        <!-- Tabel Panti -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Panti</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pantis as $panti)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $loop->iteration + ($pantis->currentPage() - 1) * $pantis->perPage() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $panti->nama_panti }}</div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $panti->alamat }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $panti->kontak }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusColors = [
                            'menunggu' => 'bg-yellow-100 text-yellow-800',
                            'terverifikasi' => 'bg-green-100 text-green-800',
                            'ditolak' => 'bg-red-100 text-red-800'
                            ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$panti->status_verifikasi] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $panti->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($panti->foto_profil)
                            <img src="{{ asset('storage/' . $panti->foto_profil) }}"
                                alt="Foto Panti"
                                class="h-10 w-10 rounded-full object-cover border border-gray-200">
                            @else
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 border border-gray-300">
                                <i class="fas fa-home text-gray-500"></i>
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <!-- Tombol Edit -->
                            <a href="{{ route('admin.panti.edit', $panti->id) }}"
                                class="inline-flex items-center px-3 py-1.5 border border-blue-400 rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors duration-200"
                                title="Edit">
                                <i class="fas fa-edit mr-1.5 text-xs"></i>
                                <span class="text-xs">Edit</span>
                            </a>

                            <!-- Tombol Detail -->
                            <a href="{{ route('admin.panti.show', $panti->id) }}"
                                class="inline-flex items-center px-3 py-1.5 border border-green-400 rounded-md text-green-600 bg-green-50 hover:bg-green-100 transition-colors duration-200"
                                title="Detail">
                                <i class="fas fa-eye mr-1.5 text-xs"></i>
                                <span class="text-xs">Detail</span>
                            </a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.panti.destroy', $panti->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 border border-red-400 rounded-md text-red-600 bg-red-50 hover:bg-red-100 transition-colors duration-200"
                                    title="Hapus"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus panti ini?')">
                                    <i class="fas fa-trash mr-1.5 text-xs"></i>
                                    <span class="text-xs">Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pantis->appends(['search' => request('search')])->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada panti asuhan</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan panti asuhan baru.</p>
            <div class="mt-6">
                <a href="{{ route('admin.panti.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i> Tambah Panti
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection