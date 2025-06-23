@extends('layouts.admin')

@section('title', 'Manajemen Pesan')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold flex items-center">
            <i class="fas fa-envelope text-yellow-600 mr-2"></i> Manajemen Pesan
        </h1>
    </div>

    {{-- Filter & Search --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">

        {{-- Filter --}}
        <div class="relative">
            <button onclick="toggleFilter()" type="button"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-filter text-gray-500 mr-2"></i> Filter Status
            </button>

            <div id="filterDropdown" class="hidden absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg z-20 ring-1 ring-black ring-opacity-5">
                <div class="py-1">
                    <a href="{{ route('admin.messages.index') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-table text-gray-500 w-5 mr-3"></i> Semua Pesan
                    </a>
                    <a href="{{ route('admin.messages.index', ['status' => 'pending']) }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-hourglass-half text-yellow-500 w-5 mr-3"></i> Belum Dibalas
                    </a>
                    <a href="{{ route('admin.messages.index', ['status' => 'replied']) }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-check-circle text-green-500 w-5 mr-3"></i> Sudah Dibalas
                    </a>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <form action="{{ route('admin.messages.index') }}" method="GET" class="w-full md:w-64">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesan..."
                class="block w-full border border-gray-300 rounded-md px-3 py-2 leading-5 bg-white placeholder-gray-500 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </form>

    </div>

    {{-- Tabel --}}
    <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
        @if($messages->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subjek</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengirim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($messages as $message)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $message->subject }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $message->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $message->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $message->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $message->status_color }}">
                                {{ $message->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="{{ route('admin.messages.show', $message->id) }}"
                                class="text-blue-600 hover:text-blue-800 inline-flex items-center"
                                title="Lihat">
                                <i class="fas fa-eye mr-1"></i> Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $messages->appends(request()->query())->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-500">
            <div class="mx-auto w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <i class="fas fa-envelope-open-text text-3xl text-gray-400"></i>
            </div>
            <p class="text-lg font-medium">Tidak ada pesan ditemukan</p>
        </div>
        @endif
    </div>
</div>

{{-- Dropdown JS --}}
<script>
    function toggleFilter() {
        const dropdown = document.getElementById("filterDropdown");
        dropdown.classList.toggle("hidden");

        // Close when click outside
        document.addEventListener("click", function (e) {
            if (!e.target.closest("#filterDropdown") && !e.target.closest("button[onclick='toggleFilter()']")) {
                dropdown.classList.add("hidden");
            }
        }, { once: true });
    }
</script>
@endsection
