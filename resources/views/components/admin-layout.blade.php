<x-app-layout>
    <div class="pt-6 pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Ubah flex menjadi flex-col md:flex-row agar sidebar di atas saat mobile -->
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Sidebar -->
                <aside class="w-full md:w-64">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <nav class="flex flex-col">
                            <a href="{{ url('/admin') }}"
                               class="flex-1 md:flex-none px-6 py-4 font-semibold text-gray-700 flex items-center gap-2
                               {{ request()->routeIs('admin') ? 'bg-primary-green text-white shadow-sm' : 'text-gray-600 hover:text-primary-green' }}">
                                <i class="fa-solid fa-envelope mr-2"></i>
                                Manajemen Pesan
                            </a>
                            <a href="{{ url('/admin/users') }}"
                               class="flex-1 md:flex-none px-6 py-4 font-semibold text-gray-700 flex items-center gap-2
                               {{ request()->routeIs('admin.users') ? 'bg-primary-green text-white shadow-sm' : 'text-gray-600 hover:text-primary-green' }}">
                                <i class="fa-solid fa-users mr-2"></i>
                                Manajemen Users
                            </a>
                            <a href="{{ url('/admin/panti') }}"
                               class="flex-1 md:flex-none px-6 py-4 font-semibold text-gray-700 flex items-center gap-2
                               {{ request()->routeIs('admin.panti') ? 'bg-primary-green text-white shadow-sm' : 'text-gray-600 hover:text-primary-green' }}">
                                <i class="fa-solid fa-house mr-2"></i>
                                Manajemen Panti
                            </a>
                            <a href="{{ url('/admin/transaksi') }}"
                               class="flex-1 md:flex-none px-6 py-4 font-semibold text-gray-700 flex items-center gap-2
                               {{ request()->routeIs('admin.transaksi') ? 'bg-primary-green text-white shadow-sm' : 'text-gray-600 hover:text-primary-green' }}">
                                <i class="fa-solid fa-exchange-alt mr-2"></i>
                                Manajemen Transaksi
                            </a>
                        </nav>
                    </div>
                </aside>
                <!-- End Sidebar -->

                <!-- Main Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</x-app-layout>