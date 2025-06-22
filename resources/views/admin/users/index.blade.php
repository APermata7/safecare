@extends('admin.layout')

@section('title', 'Manajemen User')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Manajemen User</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.donaturs.index') }}" 
               class="bg-olive text-white px-4 py-2 rounded hover:bg-olive-dark transition">
                Lihat Donatur
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">{{ $user->role }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->banned)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Banned</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            @if($user->banned)
                                <form method="POST" action="{{ route('admin.users.unban', $user->id) }}">
                                    @csrf @method('PUT')
                                    <button type="submit" class="text-green-600 hover:text-green-900">Unban</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.ban', $user->id) }}">
                                    @csrf @method('PUT')
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900">Ban</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection