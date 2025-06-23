@extends('layouts.admin')

@section('title', 'Tambah Panti Asuhan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-6">
            <a href="{{ route('admin.panti.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Panti
            </a>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Panti Asuhan Baru</h2>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Ada kesalahan dalam pengisian form:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.panti.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Informasi Dasar -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Informasi Dasar</h3>
                </div>

                <div>
                    <label for="nama_panti" class="block text-sm font-medium text-gray-700 mb-1">Nama Panti Asuhan*</label>
                    <input type="text" id="nama_panti" name="nama_panti" value="{{ old('nama_panti') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" 
                           placeholder="Contoh: Panti Asuhan Bahagia" required>
                </div>

                <div>
                    <label for="pengurus" class="block text-sm font-medium text-gray-700 mb-1">Nama Pengurus*</label>
                    <input type="text" id="pengurus" name="pengurus" value="{{ old('pengurus') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" 
                           placeholder="Nama pengurus" required>
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap*</label>
                    <textarea id="alamat" name="alamat" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" 
                              placeholder="Jl. Contoh No. 123, Kota, Provinsi" required>{{ old('alamat') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Panti*</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" 
                              placeholder="Deskripsikan kondisi panti, jumlah anak asuh, fasilitas, dll." required>{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Kontak dan Rekening -->
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Kontak dan Rekening</h3>
                </div>

                <div>
                    <label for="kontak" class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontak*</label>
                    <input type="text" id="kontak" name="kontak" value="{{ old('kontak') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" 
                           placeholder="Contoh: 081234567890" required>
                </div>

                <div>
                    <label for="bank" class="block text-sm font-medium text-gray-700 mb-1">Bank*</label>
                    <select id="bank" name="bank" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" required>
                        <option value="">Pilih Bank</option>
                        <option value="BCA" {{ old('bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                        <option value="BRI" {{ old('bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                        <option value="Mandiri" {{ old('bank') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                        <option value="BNI" {{ old('bank') == 'BNI' ? 'selected' : '' }}>BNI</option>
                        <option value="BSI" {{ old('bank') == 'BSI' ? 'selected' : '' }}>BSI</option>
                    </select>
                </div>

                <div>
                    <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening*</label>
                    <input type="text" id="nomor_rekening" name="nomor_rekening" value="{{ old('nomor_rekening') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" 
                           placeholder="Contoh: 1234567890" required>
                </div>

                <!-- Dokumen dan Foto -->
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Dokumen dan Foto</h3>
                </div>

                <div>
                    <label for="foto_profil" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil Panti*</label>
                    <div class="mt-1 flex items-center">
                        <input type="file" id="foto_profil" name="foto_profil" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" 
                               accept="image/jpeg,image/png" required>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Format: JPEG/PNG (max: 2MB)</p>
                </div>

                <div>
                    <label for="dokumen_verifikasi" class="block text-sm font-medium text-gray-700 mb-1">Dokumen Verifikasi*</label>
                    <div class="mt-1 flex items-center">
                        <input type="file" id="dokumen_verifikasi" name="dokumen_verifikasi" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500" 
                               accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Format: PDF/JPEG/PNG (max: 5MB)</p>
                </div>
            </div>

            <div class="flex justify-end space-x-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.panti.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Batal
                </a>
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Simpan Panti Asuhan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection