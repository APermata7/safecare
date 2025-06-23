<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PantiAsuhan extends Model
{
    use HasUuids;

    protected $table = 'panti_asuhan';
    protected $fillable = [
        'user_id', 
        'nama_panti', 
        'pengurus', // Pastikan ini ada
        'alamat', 
        'deskripsi', 
        'foto_profil', 
        'dokumen_verifikasi', 
        'status_verifikasi',
        'nomor_rekening', 
        'bank', 
        'kontak'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    // Sesuaikan dengan enum di migration
    const STATUS_TERVERIFIKASI = 'verified';
    const STATUS_MENUNGGU = 'unverified'; // atau bisa juga dibuat 'pending' jika lebih sesuai
    const STATUS_DITOLAK = 'rejected'; // Tambahkan jika diperlukan

    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_TERVERIFIKASI => 'Terverifikasi',
            self::STATUS_MENUNGGU => 'Menunggu Verifikasi',
            self::STATUS_DITOLAK => 'Ditolak' // Jika diperlukan
        ];

        return $statuses[$this->status_verifikasi] ?? $this->status_verifikasi;
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}