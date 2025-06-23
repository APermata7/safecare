@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Dashboard Admin</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Panti -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg mr-3 text-blue-600">
                    <i class="fas fa-home text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Panti</p>
                    <p class="text-xl font-bold">{{ $totalPanti }}</p>
                </div>
            </div>
        </div>

        <!-- Total Donatur -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg mr-3 text-green-600">
                    <i class="fas fa-hand-holding-heart text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Donatur</p>
                    <p class="text-xl font-bold">{{ $totalDonatur }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pesan -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg mr-3 text-purple-600">
                    <i class="fas fa-envelope text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Pesan</p>
                    <p class="text-xl font-bold">{{ $totalMessages }}</p>
                </div>
            </div>
        </div>

        <!-- Pesan Belum Dibalas -->
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3 text-yellow-600">
                    <i class="fas fa-bell text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pesan Belum Dibalas</p>
                    <p class="text-xl font-bold">{{ $unreadMessages }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="bg-white p-4 rounded-lg shadow mt-6">
        <h2 class="text-lg font-semibold mb-4 flex items-center">
            <i class="fas fa-envelope mr-2"></i> Pesan Terbaru
        </h2>
        
        @if($latestMessages->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengirim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subjek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($latestMessages as $message)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $message->user->name ?? 'Unknown' }}</div>
                                <div class="text-sm text-gray-500">{{ $message->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.messages.show', $message->id) }}" class="text-sm text-blue-600 hover:underline">
                                    {{ $message->judul }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $message->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $message->reply ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $message->reply ? 'Sudah dibalas' : 'Belum dibalas' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-envelope-open-text text-4xl mb-2"></i>
                <p>Belum ada pesan yang masuk</p>
            </div>
        @endif
    </div>
</div>
@endsection