<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PantiAsuhan extends Model
{
    use HasUuids;

    protected $table = 'panti_asuhan';
    protected $fillable = [
        'user_id', 'nama_panti', 'pengurus', 'alamat', 'deskripsi', 
        'foto_profil', 'dokumen_verifikasi', 'status_verifikasi',
        'nomor_rekening', 'bank', 'kontak'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    // Status verifikasi
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_TERVERIFIKASI = 'terverifikasi';
    const STATUS_DITOLAK = 'ditolak';

    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_MENUNGGU => 'Menunggu Verifikasi',
            self::STATUS_TERVERIFIKASI => 'Terverifikasi',
            self::STATUS_DITOLAK => 'Ditolak'
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