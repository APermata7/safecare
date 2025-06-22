@extends('admin.layout')

@section('title', 'Manajemen Panti')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Daftar Panti Asuhan</h2>
        <a href="{{ route('admin.panti.create') }}" class="bg-primary-green text-white px-4 py-2 rounded hover:bg-green-700 transition">
            Tambah Panti
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($pantis->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Panti</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pantis as $panti)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $panti->nama_panti }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $panti->alamat }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.panti.show', $panti->id) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                <a href="{{ route('admin.panti.edit', $panti->id) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                <form method="POST" action="{{ route('admin.panti.destroy', $panti->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus panti ini?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $pantis->links() }}
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                Tidak ada panti yang tersedia
            </div>
        @endif
    </div>
</div>
@endsection