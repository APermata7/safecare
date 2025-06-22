<x-app-layout>

    <div class="pt-24 sm:pt-0 pb-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                {{-- Header --}}
                <div class="flex justify-center mt-4">
                    <h2 class="font-semibold text-lg text-gray-700 bg-white shadow-sm rounded-full px-8 py-3">
                        {{ __('Profil & Pengaturan Akun') }}
                    </h2>
                </div>

                {{-- Card Update Profile Information --}}
                <div class="p-4 sm:p-8 bg-white shadow border rounded-2xl">
                    {{-- Di sini kita menggunakan file partial yang sudah kita buat sebelumnya --}}
                    @include('profile.partials.update-profile-information-form', ['user' => $user, 'pantiAsuhan' => $pantiAsuhan])
                </div>

                {{-- Card Update Password --}}
                <div class="p-4 sm:p-8 bg-white shadow border rounded-2xl">
                     @include('profile.partials.update-password-form')
                </div>

                {{-- Card Delete User Account --}}
                <div class="p-4 sm:p-8 bg-white shadow border rounded-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
</x-app-layout>
