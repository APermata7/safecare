@extends('admin.layout')

@section('title', 'Manajemen Donatur')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Manajemen Donatur</h2>
        <a href="{{ route('admin.users.index') }}" 
           class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300 transition">
            Kembali ke User
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($donaturs as $donatur)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $donatur->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $donatur->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($donatur->banned)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Banned</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form method="POST" action="{{ route('admin.donaturs.update-role', $donatur->id) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="text-primary-green hover:text-green-700" onclick="return confirm('Ubah role user ini ke Panti?')">
                                Ubah ke Panti
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $donaturs->links() }}
        </div>
    </div>
</div>
@endsection