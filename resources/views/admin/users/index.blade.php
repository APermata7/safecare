@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center">
            <i class="fas fa-users text-black-600 mr-2"></i> Manajemen Pengguna
        </h1>
        <a href="{{ route('admin.donaturs.index') }}" class="btn-primary">
            <i class="fas fa-hand-holding-heart text-blue-600 mr-1"></i> Lihat Donatur
        </a>
    </div>

    <div class="bg-white shadow-card rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap gap-4 justify-between items-center">
            <h2 class="text-lg font-semibold">Daftar Pengguna</h2>
            <form action="{{ route('admin.users.index') }}" method="GET" class="w-full md:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna..."
                    class="w-full pl-4 pr-4 py-2 rounded-lg border-gray-300 focus:ring-primary-300 focus:border-primary-400">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Pengguna</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Donasi</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="h-10 w-10 rounded-full object-cover">
                                @else
                                <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full font-medium 
                                {{ $user->role === 'panti' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full font-medium 
                                {{ $user->banned_at ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $user->banned_at ? 'Banned' : 'Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->donations_count }}x</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($user->banned_at)
                                <form action="{{ route('admin.users.unban', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Unban">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="Ban">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus pengguna ini?')" class="text-red-600 hover:text-red-800" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada pengguna ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
