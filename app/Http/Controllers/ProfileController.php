<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PantiAsuhan; // Import model PantiAsuhan
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage; // Untuk menghapus avatar/foto profil jika ada

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $pantiAsuhanData = null;

        // Jika role user adalah 'panti', ambil data dari tabel panti_asuhan
        if ($user->role === 'panti') {
            // Mengambil data panti asuhan yang terkait dengan user_id
            $pantiAsuhanData = PantiAsuhan::where('user_id', $user->id)->first();
        }

        return view('profile.edit', [
            'user' => $user,
            'pantiAsuhan' => $pantiAsuhanData, // Mengirim data panti asuhan ke view
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Mengisi data user dari request yang sudah divalidasi
        $user->fill($request->validated());

        // Jika email berubah, set email_verified_at menjadi null
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Simpan perubahan pada data user
        $user->save();

        // Jika role user adalah 'panti', perbarui juga data panti asuhan
        if ($user->role === 'panti') {
            $pantiAsuhan = PantiAsuhan::where('user_id', $user->id)->first();

            // Jika panti asuhan belum ada, buat yang baru
            if (!$pantiAsuhan) {
                $pantiAsuhan = new PantiAsuhan();
                $pantiAsuhan->user_id = $user->id;
            }

            // Mengisi data panti asuhan dari request
            // Pastikan field-field ini ada di $request->validated() atau di request langsung
            $pantiAsuhan->nama_panti = $request->input('nama_panti');
            $pantiAsuhan->alamat = $request->input('alamat');
            $pantiAsuhan->deskripsi = $request->input('deskripsi');
            $pantiAsuhan->nomor_rekening = $request->input('nomor_rekening');
            $pantiAsuhan->bank = $request->input('bank');
            $pantiAsuhan->kontak = $request->input('kontak');

            // Handle upload foto_profil (jika ada)
            if ($request->hasFile('foto_profil')) {
                // Hapus foto profil lama jika ada
                if ($pantiAsuhan->foto_profil && Storage::disk('public')->exists($pantiAsuhan->foto_profil)) {
                    Storage::disk('public')->delete($pantiAsuhan->foto_profil);
                }
                $path = $request->file('foto_profil')->store('panti_asuhan_photos', 'public');
                $pantiAsuhan->foto_profil = $path;
            }

            // Handle upload dokumen_verifikasi (jika ada dan hanya saat membuat, atau bisa di update sesuai kebutuhan)
            // Untuk update, mungkin ada validasi khusus atau hanya bisa diupload sekali
            // Contoh sederhana: Jika file baru diupload, update.
            if ($request->hasFile('dokumen_verifikasi')) {
                if ($pantiAsuhan->dokumen_verifikasi && Storage::disk('public')->exists($pantiAsuhan->dokumen_verifikasi)) {
                    Storage::disk('public')->delete($pantiAsuhan->dokumen_verifikasi);
                }
                $path = $request->file('dokumen_verifikasi')->store('panti_asuhan_documents', 'public');
                $pantiAsuhan->dokumen_verifikasi = $path;
            }


            $pantiAsuhan->save();
        }

        // Redirect kembali ke halaman edit profile dengan pesan sukses
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Jika user adalah 'panti', hapus juga data panti asuhan yang terkait
        if ($user->role === 'panti') {
            $pantiAsuhan = PantiAsuhan::where('user_id', $user->id)->first();
            if ($pantiAsuhan) {
                // Hapus foto profil dan dokumen verifikasi dari storage
                if ($pantiAsuhan->foto_profil && Storage::disk('public')->exists($pantiAsuhan->foto_profil)) {
                    Storage::disk('public')->delete($pantiAsuhan->foto_profil);
                }
                if ($pantiAsuhan->dokumen_verifikasi && Storage::disk('public')->exists($pantiAsuhan->dokumen_verifikasi)) {
                    Storage::disk('public')->delete($pantiAsuhan->dokumen_verifikasi);
                }
                $pantiAsuhan->delete();
            }
        }
        // Hapus avatar user jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
