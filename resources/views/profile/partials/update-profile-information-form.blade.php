<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            @if ($user->role === 'panti')
                {{ __('Profil Panti & Akun') }}
            @else
                {{ __('Informasi Profil') }}
            @endif
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ $user->role === 'panti' ? __("Perbarui informasi panti asuhan dan detail akun pengelola Anda.") : __("Perbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        @if ($user->role === 'panti')
            {{-- Header Panti --}}
            <div class="relative w-full h-96 rounded-lg shadow-lg overflow-hidden mb-6">
                @if ($pantiAsuhan && $pantiAsuhan->foto_profil)
                    <img src="{{ Storage::url($pantiAsuhan->foto_profil) }}" alt="Foto Profil {{ $pantiAsuhan->nama_panti }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <p class="text-gray-500 text-center">Unggah Foto Profil Panti Anda<br>(akan tampil sebagai banner di sini)</p>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-4 w-full">
                    <div class="flex items-end space-x-4">
                        <div class="w-24 h-24 rounded-full shadow-md overflow-hidden border-4 border-white flex-shrink-0 bg-gray-300">
                            @if ($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar {{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                     <svg class="w-12 h-12 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div class="pb-1">
                            <h2 class="text-xl font-bold text-white drop-shadow-md leading-tight">{{ $pantiAsuhan->nama_panti ?? 'Nama Panti Asuhan' }}</h2>
                            <p class="text-sm text-gray-200 drop-shadow-md">Dikelola oleh: {{ $user->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Panti --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                {{-- Kolom 1: Info Akun Pengelola --}}
                <div class="space-y-6">
                    <h3 class="text-md font-medium text-gray-800 border-b pb-2">Info Akun Pengelola</h3>
                     <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Phone Number')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>
                    <div>
                        <x-input-label for="role" :value="__('Role')" />
                        <x-text-input
                            id="role"
                            name="role"
                            type="text"
                            class="mt-1 block w-full bg-gray-100 cursor-not-allowed rounded-xl"
                            :value="ucfirst($user->role)"
                            disabled
                        />
                    </div>
                    <div>
                        <x-input-label for="avatar" :value="__('User Avatar')" />
                        <input id="avatar" name="avatar" type="file" class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-primary-green hover:file:bg-gray-200" accept="image/*"/>
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>
                </div>

                {{-- Kolom 2: Info Panti Asuhan --}}
                <div class="space-y-6">
                    <h3 class="text-md font-medium text-gray-800 border-b pb-2">Info Panti Asuhan</h3>
                    {{-- ... semua field panti asuhan ada di sini ... --}}
                    @include('profile.partials.panti-fields')
                </div>
            </div>

        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-10 pt-4">

                {{-- Kolom Kiri --}}
                <div class="lg:col-span-1">
                    {{-- <h3 class="text-lg font-medium text-gray-900">Foto Profil</h3> --}}
                    {{-- <p class="mt-1 text-sm text-gray-600"> --}}
                    {{--     Tampilkan foto terbaik Anda. --}}
                    {{-- </p> --}}

                    {{-- Tampilan Foto --}}
                    <div class="mt-6 flex justify-center">
                        @if ($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="User Avatar" class="w-48 h-48 object-cover rounded-full shadow-lg">
                        @else
                            {{-- Placeholder jika user tidak punya avatar --}}
                            <div class="w-48 h-48 rounded-full shadow-lg bg-gray-200 flex items-center justify-center">
                                 <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Input untuk Upload Foto Baru --}}
                    <div class="mt-6">
                        <x-input-label for="avatar" :value="__('Ganti Foto Profil')" />
                        <input id="avatar" name="avatar" type="file" class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-primary-green hover:file:bg-gray-200" accept="image/*"/>
                        <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div>
                                    <p class="text-sm mt-2 text-gray-800">
                                        {{ __('Alamat email Anda belum terverifikasi.') }}
                                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600">
                                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Nomor Telepon')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                    <div>
                        <x-input-label for="role" :value="__('Role')" />
                        <x-text-input
                            id="role"
                            name="role"
                            type="text"
                            class="mt-1 block w-full bg-gray-100 cursor-not-allowed"
                            :value="ucfirst($user->role)"
                            disabled
                        />
                    </div>
                    </div>
                </div>
            </div>

        @endif
        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4 pt-8 mt-8 border-t border-gray-200">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
