<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeCare Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md">
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold text-gray-800">SafeCare Admin</h1>
            </div>
            <nav class="p-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="block px-4 py-2 rounded mb-1 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.messages.index') }}" 
                   class="block px-4 py-2 rounded mb-1 {{ request()->routeIs('admin.messages*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-envelope mr-2"></i> Pesan Masuk
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="block px-4 py-2 rounded mb-1 {{ request()->routeIs('admin.users*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-users mr-2"></i> Management User
                </a>
                <a href="{{ route('admin.donaturs.index') }}" 
                   class="block px-4 py-2 rounded mb-1 {{ request()->routeIs('admin.donaturs*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-hand-holding-heart mr-2"></i> Management Donatur
                </a>
                <a href="{{ route('admin.panti.index') }}" 
                   class="block px-4 py-2 rounded mb-1 {{ request()->routeIs('admin.panti*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-home mr-2"></i> Management Panti
                </a>
                <a href="{{ route('admin.transactions.index') }}" 
                   class="block px-4 py-2 rounded mb-1 {{ request()->routeIs('admin.transactions*') ? 'bg-blue-100 text-blue-600' : 'text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-exchange-alt mr-2"></i> Transaksi
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Navbar -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center">
                <h2 class="text-lg font-medium">@yield('title')</h2>
                <div class="relative">
                    <button onclick="toggleDropdown()" class="flex items-center space-x-2 focus:outline-none">
                        <span class="bg-blue-500 text-white rounded-full h-8 w-8 flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-2"></i> Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-4">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('profileDropdown').classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const button = document.querySelector('[onclick="toggleDropdown()"]');
            
            if (!button.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>