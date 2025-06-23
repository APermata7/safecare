@extends('layouts.admin')

@section('title', 'Manajemen Donatur')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center">
            <i class="fas fa-hand-holding-heart text-pink-600 mr-2"></i> Manajemen Donatur
        </h1>
        <a href="{{ route('admin.users.index') }}" class="btn-primary">
            <i class="fas fa-users text-blue-600 mr-1"></i> Lihat Semua Pengguna
        </a>
    </div>

    <div class="bg-white shadow-card rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap gap-4 justify-between items-center">
            <h2 class="text-lg font-semibold">Daftar Donatur</h2>
            <form action="{{ route('admin.donaturs.index') }}" method="GET" class="w-full md:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari donatur..."
                    class="w-full pl-4 pr-4 py-2 rounded-lg border-gray-300 focus:ring-primary-300 focus:border-primary-400">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Donatur</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Total Donasi</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($donaturs as $donatur)
                    <tr>
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 bg-pink-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-hand-holding-heart text-pink-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $donatur->name }}</div>
                                    <div class="text-gray-500">{{ $donatur->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full font-medium 
                                {{ $donatur->banned_at ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $donatur->banned_at ? 'Banned' : 'Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $donatur->donations_count }} kali
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($donatur->banned_at)
                                <form action="{{ route('admin.users.unban', $donatur->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Unban">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.users.ban', $donatur->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="Ban">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.users.destroy', $donatur->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus donatur ini?')" class="text-red-600 hover:text-red-800" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                @if($donatur->role === 'donatur')
                                <form action="{{ route('admin.donaturs.update-role', $donatur->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Jadikan Panti Asuhan?')" class="text-blue-600 hover:text-blue-800" title="Jadikan Panti">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-hand-holding-usd text-4xl text-gray-400 mb-2"></i>
                                <p>Tidak ada donatur ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $donaturs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
