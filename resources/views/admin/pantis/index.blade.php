@extends('layouts.admin')

@section('title', 'Manajemen Panti Asuhan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center text-gray-800">
            <i class="fas fa-home mr-2"></i> Manajemen Panti Asuhan
        </h1>
        <a href="{{ route('admin.panti.create') }}">
            <button type="button"
                class="bg-primary-green text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-green">
                Tambah Panti Asuhan
            </button>
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white shadow-card rounded-xl overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap gap-4 justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Panti Asuhan</h2>

            <form method="GET" action="{{ route('admin.panti.index') }}" class="w-full md:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari panti..."
                    class="w-full pl-4 pr-4 py-2 rounded-lg border-gray-300 focus:ring-primary-300 focus:border-primary-400">
            </form>
        </div>

        @if($pantis->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Nama Panti</th>
                        <th class="px-6 py-3">Kontak</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Foto</th>
                        <th class="px-6 py-3 w-56">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($pantis as $panti)
                    <tr>
                        <td class="px-6 py-4">{{ $loop->iteration + ($pantis->currentPage() - 1) * $pantis->perPage() }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $panti->nama_panti }}</div>
                            <div class="text-gray-500 truncate max-w-xs">{{ $panti->alamat }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $panti->kontak }}</td>
                        <td class="px-6 py-4">
                            @php
                            $statusColors = [
                            'menunggu' => 'bg-yellow-100 text-yellow-800',
                            'terverifikasi' => 'bg-green-100 text-green-800',
                            'ditolak' => 'bg-red-100 text-red-800'
                            ];
                            @endphp
                            <span class="px-4 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$panti->status_verifikasi] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $panti->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($panti->foto_profil)
                            <img src="{{ asset('storage/' . $panti->foto_profil) }}" alt="Foto Panti"
                                class="h-10 w-10 rounded-full object-cover border border-gray-200">
                            @else
                            <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-home text-gray-500"></i>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.panti.edit', $panti->id) }}"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ route('admin.panti.show', $panti->id) }}"
                                    class="text-green-600 hover:text-green-800"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <form action="{{ route('admin.panti.destroy', $panti->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus panti ini?')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800"
                                        title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $pantis->withQueryString()->links() }}
        </div>

        @else
        <div class="px-6 py-12 text-center text-gray-500">
            <i class="fas fa-home text-4xl text-gray-300 mb-4"></i>
            <p class="text-lg font-medium">Belum ada panti asuhan terdaftar.</p>
            <a href="{{ route('admin.panti.create') }}"
                class="mt-6 inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md shadow transition">
                <i class="fas fa-plus mr-2"></i> Tambah Panti
            </a>
        </div>
        @endif
    </div>
</div>
@endsection