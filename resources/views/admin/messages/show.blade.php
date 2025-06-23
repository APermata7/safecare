@extends('layouts.admin')

@section('title', 'Detail Pesan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center text-primary-green hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Pesan
            </a>
        </div>

        <div class="border-b border-gray-200 pb-4 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $message->subject }}</h2>
                    <div class="mt-1 flex items-center text-sm text-gray-500">
                        <span>{{ $message->user->name }}</span>
                        <span class="mx-1">•</span>
                        <span>{{ $message->user->email }}</span>
                        <span class="mx-1">•</span>
                        <span>{{ $message->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $message->status_color }}">
                    {{ $message->status_label }}
                </span>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Isi Pesan:</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-800 whitespace-pre-line">{{ $message->message }}</p>
                
                @if($message->file_path)
                <div class="mt-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Lampiran:</h4>
                    <a href="{{ Storage::url($message->file_path) }}" 
                       target="_blank"
                       class="inline-flex items-center text-primary-green hover:underline">
                        <i class="fas fa-paperclip mr-1"></i>
                        Download File
                    </a>
                </div>
                @endif
            </div>
        </div>

        @if($message->reply)
        <div class="mb-8">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Balasan Anda:</h3>
            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                <p class="text-gray-800 whitespace-pre-line">{{ $message->reply }}</p>
                <div class="mt-2 text-xs text-gray-500">
                    Dibalas pada: {{ $message->replied_at->format('d M Y H:i') }}
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.messages.reply', $message->id) }}">
            @csrf
            <div class="mb-4">
                <label for="reply" class="block text-sm font-medium text-gray-700 mb-2">Balas Pesan</label>
                <textarea id="reply" name="reply" rows="5" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-green focus:border-primary-green"
                    placeholder="Tulis balasan Anda di sini..."
                    required>{{ old('reply', $message->reply) }}</textarea>
                @error('reply')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn-primary">
                <i class="fas fa-reply mr-1"></i> Kirim Balasan
            </button>
        </form>
    </div>
</div>
@endsection