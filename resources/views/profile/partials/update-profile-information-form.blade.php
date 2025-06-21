<section>
    <header>
        {{-- Hapus dark:text-gray-100 --}}
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        {{-- Hapus dark:text-gray-400 --}}
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Penting: tambahkan enctype="multipart/form-data" untuk upload file --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- FIELDS UNTUK SEMUA ROLE (User Data) --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            {{-- Hapus dark:bg-gray-900 dan dark:text-gray-300 --}}
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            {{-- Hapus dark:bg-gray-900 dan dark:text-gray-300 --}}
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    {{-- Hapus dark:text-gray-200 --}}
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        {{-- Hapus dark:text-gray-400 dan dark:hover:text-gray-100 dan dark:focus:ring-offset-gray-800 --}}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        {{-- Hapus dark:text-green-400 --}}
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            {{-- Hapus dark:bg-gray-900 dan dark:text-gray-300 --}}
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="avatar" :value="__('Avatar')" />
            {{-- Hapus dark:text-gray-100 --}}
            <input id="avatar" name="avatar" type="file" class="mt-1 block w-full text-sm text-gray-900
                   file:mr-4 file:py-2 file:px-4
                   file:rounded-md file:border-0
                   file:text-sm file:font-semibold
                   file:bg-indigo-50 file:text-indigo-700
                   hover:file:bg-indigo-100"/>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            @if ($user->avatar)
                <div class="mt-2">
                    <img src="{{ Storage::url($user->avatar) }}" alt="User Avatar" class="w-20 h-20 object-cover rounded-full shadow-md">
                </div>
            @endif
        </div>

        {{-- CONDITIONAL FIELDS UNTUK ROLE 'panti' --}}
        @if ($user->role === 'panti')
            {{-- Hapus dark:text-gray-100 dan dark:border-gray-700 --}}
            <h3 class="text-lg font-medium text-gray-900 pt-8 border-t border-gray-200 mt-6">
                {{ __('Panti Asuhan Information') }}
            </h3>
            {{-- Hapus dark:text-gray-400 --}}
            <p class="mt-1 text-sm text-gray-600">
                {{ __("Update your orphanage's details.") }}
            </p>

            <div>
                <x-input-label for="nama_panti" :value="__('Orphanage Name')" />
                {{-- Hapus dark:bg-gray-900 dan dark:text-gray-300 --}}
                <x-text-input id="nama_panti" name="nama_panti" type="text" class="mt-1 block w-full" :value="old('nama_panti', $pantiAsuhan->nama_panti ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('nama_panti')" />
            </div>

            <div>
                <x-input-label for="alamat" :value="__('Address')" />
                {{-- Hapus dark:border-gray-700, dark:bg-gray-900, dan dark:text-gray-300 --}}
                <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('alamat', $pantiAsuhan->alamat ?? '') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
            </div>

            <div>
                <x-input-label for="deskripsi" :value="__('Description')" />
                {{-- Hapus dark:border-gray-700, dark:bg-gray-900, dan dark:text-gray-300 --}}
                <textarea id="deskripsi" name="deskripsi" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi', $pantiAsuhan->deskripsi ?? '') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('deskripsi')" />
            </div>

            <div>
                <x-input-label for="foto_profil_panti" :value="__('Orphanage Profile Photo')" />
                {{-- Hapus dark:text-gray-100 --}}
                <input id="foto_profil_panti" name="foto_profil" type="file" class="mt-1 block w-full text-sm text-gray-900
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0
                       file:text-sm file:font-semibold
                       file:bg-indigo-50 file:text-indigo-700
                       hover:file:bg-indigo-100" accept="image/*" />
                <x-input-error class="mt-2" :messages="$errors->get('foto_profil')" />
                @if ($pantiAsuhan && $pantiAsuhan->foto_profil)
                    <div class="mt-2">
                        <img src="{{ Storage::url($pantiAsuhan->foto_profil) }}" alt="Panti Asuhan Profile Photo" class="w-20 h-20 object-cover rounded-full shadow-md">
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="dokumen_verifikasi" :value="__('Verification Document')" />
                {{-- Hapus dark:text-gray-100 --}}
                <input id="dokumen_verifikasi" name="dokumen_verifikasi" type="file" class="mt-1 block w-full text-sm text-gray-900
                       file:mr-4 file:py-2 file:px-4
                       file:rounded-md file:border-0
                       file:text-sm file:font-semibold
                       file:bg-indigo-50 file:text-indigo-700
                       hover:file:bg-indigo-100" accept=".pdf,.jpg,.jpeg,.png" />
                <x-input-error class="mt-2" :messages="$errors->get('dokumen_verifikasi')" />
                @if ($pantiAsuhan && $pantiAsuhan->dokumen_verifikasi)
                    <div class="mt-2">
                        {{-- Hapus dark:text-blue-400 --}}
                        <a href="{{ Storage::url($pantiAsuhan->dokumen_verifikasi) }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ __('View Current Document') }}
                        </a>
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="nomor_rekening" :value="__('Account Number')" />
                {{-- Hapus dark:bg-gray-900 dan dark:text-gray-300 --}}
                <x-text-input id="nomor_rekening" name="nomor_rekening" type="text" class="mt-1 block w-full" :value="old('nomor_rekening', $pantiAsuhan->nomor_rekening ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('nomor_rekening')" />
            </div>

            <div>
                <x-input-label for="bank" :value="__('Bank Name')" />
                {{-- Hapus dark:bg-gray-900 dan dark:text-gray-300 --}}
                <x-text-input id="bank" name="bank" type="text" class="mt-1 block w-full" :value="old('bank', $pantiAsuhan->bank ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('bank')" />
            </div>

            <div>
                <x-input-label for="kontak_panti" :value="__('Contact Person/Number (Orphanage)')" />
                {{-- Hapus dark:bg-gray-900 dan dark:text-gray-300 --}}
                <x-text-input id="kontak_panti" name="kontak" type="text" class="mt-1 block w-full" :value="old('kontak', $pantiAsuhan->kontak ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('kontak')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                {{-- Hapus dark:text-gray-400 --}}
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
