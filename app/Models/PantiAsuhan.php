<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PantiAsuhan extends Model
{
    use HasUuids;

    protected $table = 'panti_asuhan';
    protected $fillable = [
        'user_id', 'nama_panti', 'alamat', 'deskripsi', 
        'foto_profil', 'dokumen_verifikasi', 'status_verifikasi',
        'nomor_rekening', 'bank', 'kontak'
    ];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'panti_id');
    }
}
