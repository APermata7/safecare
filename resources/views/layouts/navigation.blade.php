<nav x-data="{ open: false }" class="py-4 sm:py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center bg-white rounded-full shadow-lg px-4 sm:px-6">

            <div class="flex-1 flex items-center justify-start">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-primary-green" />
                </a>
            </div>

            <div class="flex-1 hidden sm:flex items-center justify-center">
                <div class="flex space-x-2 p-1 bg-gray-100 rounded-full">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition
                              {{ request()->routeIs('dashboard') ? 'bg-primary-green text-white shadow-sm' : 'text-gray-600 hover:text-primary-green' }}">
                        {{ __('Dashboard') }}
                    </a>

                    {{-- Riwayat Donasi Saya --}}
                    <a href="{{ route('donasi.riwayat') }}"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition
                              {{ request()->routeIs('donasi.riwayat') ? 'bg-primary-green text-white shadow-sm' : 'text-gray-600 hover:text-primary-green' }}">
                        {{ __('Riwayat Donasi') }}
                    </a>

                    {{-- Riwayat Donasi Diterima (Panti) --}}
                    @can('view-panti-history')
                         <a href="{{ route('panti.donasi.diterima') }}"
                              class="px-4 py-2 text-sm font-semibold rounded-full transition
                                     {{ request()->routeIs('panti.donasi.diterima') ? 'bg-primary-green text-white shadow-sm' : 'text-gray-600 hover:text-primary-green' }}">
                              {{ __('Donasi Diterima') }}
                         </a>
                    @endcan
                </div>
            </div>

            <div class="flex-1 hidden sm:flex items-center justify-end">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-primary-green focus:outline-none transition">
                            @if(Auth::user()->avatar)
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                            @else
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-secondary-green text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            @endif
                            <div class="hidden md:block">{{ Auth::user()->name }}</div>
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex-1 flex h-full items-center justify-end sm:hidden">
                 <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-primary-green focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg p-4 space-y-1 mb-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-4">
            <div class="flex items-center space-x-3">
                @if(Auth::user()->avatar)
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                @else
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-secondary-green text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                @endif
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
