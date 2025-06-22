@extends('admin.layout')

@section('title', 'Detail Pesan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center text-primary-green hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="border-b border-gray-200 pb-4 mb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-medium text-gray-800">{{ $message->user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $message->user->email }}</p>
                </div>
                <span class="text-sm text-gray-500">{{ $message->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>

        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Isi Pesan:</h4>
            <p class="text-gray-800 whitespace-pre-line">{{ $message->isi }}</p>
        </div>

        @if($message->balasan)
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h4 class="text-sm font-medium text-gray-500 mb-2">Balasan Anda:</h4>
                <p class="text-gray-800 whitespace-pre-line">{{ $message->balasan }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.messages.reply', $message->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="balasan" class="block text-sm font-medium text-gray-700 mb-2">Balas Pesan</label>
                <textarea id="balasan" name="balasan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-green focus:border-primary-green">{{ old('balasan', $message->balasan) }}</textarea>
                @error('balasan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-primary-green text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-green">
                Kirim Balasan
            </button>
        </form>
    </div>
</div