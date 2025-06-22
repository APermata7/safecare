@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- User Count -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                    <p class="text-3xl font-semibold text-gray-800 mt-1">{{ $userCount }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-full text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Panti Count -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Panti</p>
                    <p class="text-3xl font-semibold text-gray-800 mt-1">{{ $pantiCount }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-full text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Messages Count -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pesan Baru</p>
                    <p class="text-3xl font-semibold text-gray-800 mt-1">{{ $messageCount }}</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-full text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Pesan Terbaru</h3>
                <a href="{{ route('admin.messages.index') }}" class="text-sm text-primary-green hover:underline flex items-center">
                    Lihat Semua
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
        
        @if($messages->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($messages as $message)
                <div class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center">
                                <p class="font-medium text-gray-900">{{ $message->user->name }}</p>
                                <span class="ml-2 text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">{{ $message->user->email }}</span>
                            </div>
                            <p class="mt-1 text-gray-600">{{ Str::limit($message->isi, 120) }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                            <a href="{{ route('admin.messages.show', $message->id) }}" class="text-primary-green hover:text-green-700 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pesan</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada pesan yang masuk.</p>
            </div>
        @endif
    </div>
</div>
@endsection