<div>
    <x-input-label for="nama_panti" :value="__('Orphanage Name')" />
    <x-text-input
        id="nama_panti"
        name="nama_panti"
        type="text"
        class="mt-1 block w-full"
        :value="old('nama_panti', $pantiAsuhan->nama_panti ?? '')"
        required />
    <x-input-error class="mt-2" :messages="$errors->get('nama_panti')" />
</div>
<div>
    <x-input-label for="kontak_panti" :value="__('Contact Person/Number (Orphanage)')" />
    <x-text-input
        id="kontak_panti"
        name="kontak"
        type="text"
        class="mt-1 block w-full"
        :value="old('kontak', $pantiAsuhan->kontak ?? '')"
        required />
    <x-input-error class="mt-2" :messages="$errors->get('kontak')" />
</div>
<div>
    <x-input-label for="alamat" :value="__('Address')" />
    <textarea
        id="alamat"
        name="alamat"
        rows="3"
        class="mt-1 block w-full border-gray-300 focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50
               rounded-xl shadow-sm"
        required
    >
        {{ old('alamat', $pantiAsuhan->alamat ?? '') }}
    </textarea>
    <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
</div>
<div>
    <x-input-label for="deskripsi" :value="__('Description')" />
    <textarea
        id="alamat"
        name="alamat"
        rows="3"
        class="mt-1 block w-full border-gray-300 focus:border-primary-green focus:ring-primary-green focus:ring-opacity-50
               rounded-xl shadow-sm"
        required
    >
        {{ old('deskripsi', $pantiAsuhan->deskripsi ?? '') }}
    </textarea>
    <x-input-error class="mt-2" :messages="$errors->get('deskripsi')" />
</div>
<div>
    <x-input-label for="foto_profil_panti" :value="__('Orphanage Profile Photo (Banner)')" />
    <input
        id="foto_profil_panti"
        name="foto_profil" type="file"
        class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
               file:text-sm file:font-semibold file:bg-gray-100 file:text-primary-green hover:file:bg-gray-200"
        accept="image/*" />
    <x-input-error class="mt-2" :messages="$errors->get('foto_profil')" />
</div>
<div>
    <x-input-label for="nomor_rekening" :value="__('Account Number')" />
    <x-text-input
        id="nomor_rekening"
        name="nomor_rekening"
        type="text"
        class="mt-1 block w-full"
        :value="old('nomor_rekening', $pantiAsuhan->nomor_rekening ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('nomor_rekening')" />
</div>
<div>
    <x-input-label for="bank" :value="__('Bank Name')" />
    <x-text-input
        id="bank"
        name="bank"
        type="text"
        class="mt-1 block w-full"
        :value="old('bank', $pantiAsuhan->bank ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('bank')" />
</div>
<div>
    <x-input-label for="dokumen_verifikasi" :value="__('Verification Document')" />
    <input
        id="dokumen_verifikasi"
        name="dokumen_verifikasi"
        type="file"
        class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
               file:text-sm file:font-semibold file:bg-gray-100 file:text-primary-green hover:file:bg-gray-200"
        accept=".pdf,.jpg,.jpeg,.png" />
    <x-input-error class="mt-2" :messages="$errors->get('dokumen_verifikasi')" />
    @if ($pantiAsuhan && $pantiAsuhan->dokumen_verifikasi)
        <div class="mt-2">
            <a href="{{ Storage::url($pantiAsuhan->dokumen_verifikasi) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                {{ __('View Current Document') }}
            </a>
        </div>
    @endif
</div>
