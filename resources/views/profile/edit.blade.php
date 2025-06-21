{{--
    File: resources/views/profile/edit.blade.php
    Deskripsi: Kontainer utama halaman profil yang telah dimodifikasi untuk
               memungkinkan konten di dalamnya (form) tampil lebih lebar.
--}}
<x-app-layout>

    <div class="pt-24 sm:pt-28 pb-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                {{-- Header "pil" baru ditambahkan di sini --}}
                <div class="flex justify-center mt-4">
                    <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                        {{ __('Profil & Pengaturan Akun') }}
                    </h2>
                </div>

                {{-- Card untuk Update Profile Information --}}
                <div class="p-4 sm:p-8 bg-white shadow border rounded-2xl">
                    {{-- Di sini kita menggunakan file partial yang sudah kita buat sebelumnya --}}
                    @include('profile.partials.update-profile-information-form', ['user' => $user, 'pantiAsuhan' => $pantiAsuhan])
                </div>

                {{-- Card untuk Update Password --}}
                <div class="p-4 sm:p-8 bg-white shadow border rounded-2xl">
                     @include('profile.partials.update-password-form')
                </div>

                {{-- Card untuk Delete User Account --}}
                <div class="p-4 sm:p-8 bg-white shadow border rounded-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
</x-app-layout>
