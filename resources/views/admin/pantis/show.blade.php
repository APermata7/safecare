@extends('admin.layout')

@section('title', 'Detail Panti')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <a href="{{ route('admin.panti.index') }}" class="inline-flex items-center text-primary-green hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="border-b border-gray-200 pb-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">{{ $panti->nama_panti }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Nama Panti:</h3>
                <p class="text-gray-800">{{ $panti->nama_panti }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Pengurus:</h3>
                <p class="text-gray-800">{{ $panti->pengurus }}</p>
            </div>
            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Alamat:</h3>
                <p class="text-gray-800">{{ $panti->alamat }}</p>
            </div>
            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Deskripsi:</h3>
                <p class="text-gray-800 whitespace-pre-line">{{ $panti->deskripsi }}</p>
            </div>
            @if($panti->foto)
            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Foto:</h3>
                <img src="{{ asset('storage/' . $panti->foto) }}" alt="Foto Panti" class="h-64 rounded-lg">
            </div>
            @endif
        </div>

        <div class="flex space-x-4">
            <a href="{{ route('admin.panti.edit', $panti->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form method="POST" action="{{ route('admin.panti.destroy', $panti->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus panti ini?')">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection