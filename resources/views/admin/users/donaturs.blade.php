@extends('layouts.admin')

@section('title', 'Manajemen Donatur')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center">
            <i class="fas fa-hand-holding-heart mr-2"></i> Manajemen Donatur
        </h1>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn-primary">
                <i class="fas fa-users mr-1"></i> Lihat Semua Pengguna
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <h2 class="text-lg font-semibold">Daftar Donatur</h2>
            
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <form action="{{ route('admin.donaturs.index') }}" method="GET" class="flex space-x-2">
                    <div class="relative w-full md:w-64">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Cari donatur..." 
                            class="input-search"
                            value="{{ request('search') }}"
                        >
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donatur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Donasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($donaturs as $donatur)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-secondary-100 flex items-center justify-center">
                                    <i class="fas fa-hand-holding-heart text-secondary-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $donatur->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $donatur->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $donatur->banned_at ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $donatur->banned_at ? 'Banned' : 'Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $donatur->donations_count }} kali
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            @if($donatur->banned_at)
                                <form action="{{ route('admin.users.unban', $donatur->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-success btn-sm">
                                        <i class="fas fa-check mr-1"></i> Unban
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.ban', $donatur->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-warning btn-sm">
                                        <i class="fas fa-ban mr-1"></i> Ban
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.users.destroy', $donatur->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin menghapus donatur ini?')">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                            
                            @if($donatur->role === 'donatur')
                            <form action="{{ route('admin.donaturs.update-role', $donatur->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn-accent btn-sm" onclick="return confirm('Ubah role ini ke Panti Asuhan?')">
                                    <i class="fas fa-exchange-alt mr-1"></i> Jadikan Panti
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-hand-holding-usd text-4xl text-gray-400 mb-2"></i>
                                <p>Tidak ada donatur yang ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($donaturs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $donaturs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection