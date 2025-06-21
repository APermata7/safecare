<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Update Profile Information Section (including Panti Asuhan info) --}}
            {{-- Hapus atau ganti kelas dark:bg-gray-800 jika Anda ingin latar belakang tetap terang --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{--
                        Di sini kita meneruskan variabel $user dan $pantiAsuhan
                        ke partial update-profile-information-form.blade.php
                    --}}
                    @include('profile.partials.update-profile-information-form', ['user' => $user, 'pantiAsuhan' => $pantiAsuhan])
                </div>
            </div>

            {{-- Update Password Section --}}
            {{-- Hapus atau ganti kelas dark:bg-gray-800 jika Anda ingin latar belakang tetap terang --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete User Account Section --}}
            {{-- Hapus atau ganti kelas dark:bg-gray-800 jika Anda ingin latar belakang tetap terang --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
