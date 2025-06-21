<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PantiAsuhan;
use App\Models\User; // Pastikan model User di-import
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil pengguna.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $pantiAsuhan = null;

        if ($user->role === 'panti') {
            // Menggunakan firstOrNew lebih aman, jika data panti belum ada,
            // ia akan membuat instance baru untuk diisi di form.
            $pantiAsuhan = PantiAsuhan::firstOrNew(['user_id' => $user->id]);
        }

        return view('profile.edit', [
            'user' => $user,
            'pantiAsuhan' => $pantiAsuhan,
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Ambil semua data yang sudah lolos validasi dari ProfileUpdateRequest
        $validatedData = $request->validated();
        $user = $request->user();

        // 1. UPDATE DATA USER
        // Gunakan fill() untuk mengisi data user dari array yang sudah divalidasi
        $user->fill($validatedData);

        // Jika email diubah, reset status verifikasi
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle upload avatar PENGGUNA (yang sebelumnya tidak ada)
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Simpan yang baru dan dapatkan path-nya
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save(); // Simpan semua perubahan pada model User

        // 2. UPDATE DATA PANTI ASUHAN (JIKA ROLE-NYA 'panti')
        if ($user->role === 'panti') {
            // Ambil atau buat instance baru PantiAsuhan
            $pantiAsuhan = PantiAsuhan::firstOrNew(['user_id' => $user->id]);

            // Gunakan fill() untuk mengisi semua data panti dari array validasi
            // Ini jauh lebih aman dan ringkas daripada menggunakan $request->input()
            $pantiAsuhan->fill($validatedData);

            // Handle upload foto profil PANTI
            if ($request->hasFile('foto_profil')) {
                if ($pantiAsuhan->foto_profil && Storage::disk('public')->exists($pantiAsuhan->foto_profil)) {
                    Storage::disk('public')->delete($pantiAsuhan->foto_profil);
                }
                $pantiAsuhan->foto_profil = $request->file('foto_profil')->store('panti_asuhan_photos', 'public');
            }

            // Handle upload dokumen verifikasi PANTI
            if ($request->hasFile('dokumen_verifikasi')) {
                if ($pantiAsuhan->dokumen_verifikasi && Storage::disk('public')->exists($pantiAsuhan->dokumen_verifikasi)) {
                    Storage::disk('public')->delete($pantiAsuhan->dokumen_verifikasi);
                }
                $pantiAsuhan->dokumen_verifikasi = $request->file('dokumen_verifikasi')->store('panti_asuhan_documents', 'public');
            }

            $pantiAsuhan->save(); // Simpan semua perubahan pada model PantiAsuhan
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Logout dulu sebelum menghapus data
        Auth::logout();

        // Hapus data panti asuhan dan filenya jika ada
        if ($user->role === 'panti') {
            $pantiAsuhan = PantiAsuhan::where('user_id', $user->id)->first();
            if ($pantiAsuhan) {
                if ($pantiAsuhan->foto_profil) Storage::disk('public')->delete($pantiAsuhan->foto_profil);
                if ($pantiAsuhan->dokumen_verifikasi) Storage::disk('public')->delete($pantiAsuhan->dokumen_verifikasi);
                $pantiAsuhan->delete();
            }
        }

        // Hapus avatar user jika ada
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Hapus user
        $user->delete();

        // Invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
