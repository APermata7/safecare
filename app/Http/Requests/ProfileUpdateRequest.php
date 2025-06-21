<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $user = $this->user(); // Dapatkan user yang sedang login

        // Aturan validasi dasar untuk tabel users
        $rules = [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'], // Max 2MB untuk avatar user
        ];

        // Tambahkan aturan validasi khusus jika role user adalah 'panti'
        if ($user->role === 'panti') {
            $rules = array_merge($rules, [
                'nama_panti' => ['required', 'string', 'max:255'],
                'alamat' => ['required', 'string'],
                'deskripsi' => ['nullable', 'string'],
                'foto_profil' => ['nullable', 'image', 'max:2048'], // Max 2MB untuk foto profil panti
                // dokumen_verifikasi mungkin tidak selalu diupdate, atau bisa diupdate dengan aturan berbeda
                // Untuk contoh ini, saya buat nullable dan image, tapi bisa disesuaikan
                'dokumen_verifikasi' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // Max 5MB, bisa PDF/Image
                'nomor_rekening' => ['nullable', 'string', 'max:50'],
                'bank' => ['nullable', 'string', 'max:50'],
                'kontak' => ['required', 'string', 'max:255'],
            ]);
        }

        return $rules;
    }
}
