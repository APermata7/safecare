@extends('admin.layout')

@section('title', 'Tambah Panti')

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

        <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Panti Asuhan</h2>

        <form method="POST" action="{{ route('admin.panti.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <label for="nama_panti" class="block text-sm font-medium text-gray-700 mb-2">Nama Panti</label>
                    <input type="text" id="nama_panti" name="nama_panti" value="{{ old('nama_panti') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-green focus:border-primary-green" required>
                    @error('nama_panti')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-green focus:border-primary-green" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary-green text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-green">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection