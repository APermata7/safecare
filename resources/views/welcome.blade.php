<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SafeCare - Platform Donasi Panti Asuhan</title>
    <meta name="description" content="Platform donasi terpercaya untuk membantu panti asuhan di seluruh Indonesia">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)),
                url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 80vh;
            width: 100%;
        }

        .btn-donate {
            background-color: #537D5D;
            color: white;
            padding: 1rem 2.5rem;
            min-width: 250px;
            transition: all 0.3s ease;
        }

        .btn-donate:hover {
            background-color: #43644B;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-register {
            background-color: #537D5D;
            color: white;
        }

        .btn-register:hover {
            background-color: #43644B;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col font-sans">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ml-2 text-xl font-bold text-primary-600">SafeCare</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition">Admin Panel</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 transition">Logout</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition">Login</a>
                    <a href="{{ route('register') }}" class="ml-4 text-sm font-medium text-white btn-register px-4 py-2 rounded-md transition shadow-sm">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-grow">
        <div class="hero-section flex items-center justify-center w-full">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
                <div class="space-y-8">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 leading-tight">
                        Selamat Datang di <span class="text-primary-600">SafeCare</span>
                    </h1>
                    <p class="text-2xl md:text-3xl text-gray-700 max-w-3xl mx-auto">
                        Platform donasi terpercaya untuk membantu panti asuhan di seluruh Indonesia.
                    </p>
                    <div class="pt-8">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center btn-donate text-lg font-semibold rounded-lg transition">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ayo Donasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Feature Highlights Section -->
    <section class="bg-white">
        <!-- Top Spacer (between hero image and this section) -->
        <div class="py-8"></div>

        <div class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Mengapa Memilih SafeCare?</h2>
                    <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                        Platform kami memberikan pengalaman berdonasi yang aman, transparan, dan berdampak langsung
                    </p>
                </div>

                <!-- Horizontal Feature Cards -->
                <div class="bg-primary-50 rounded-xl p-8 shadow-inner">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Card 1 -->
                        <div class="bg-white p-6 rounded-lg shadow-md flex items-start">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center mr-4">
                                <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aman &amp; Terpercaya</h3>
                                <p class="text-gray-600">Transaksi donasi dilindungi dengan sistem keamanan terbaik</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-white p-6 rounded-lg shadow-md flex items-start">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center mr-4">
                                <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Transparan 100%</h3>
                                <p class="text-gray-600">Laporan penggunaan dana dapat dipantau secara real-time</p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="bg-white p-6 rounded-lg shadow-md flex items-start">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center mr-4">
                                <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Beragam Metode</h3>
                                <p class="text-gray-600">Donasi mudah melalui berbagai metode pembayaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mb-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-8 py-4">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="mt-2 md:mt-0 md:order-1">
                        <p class="text-center text-base text-gray-500">
                            © 2025 SafeCare. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </footer>