<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasUuids;

    protected $table = 'transaksis';
    protected $fillable = [
        'user_id',
        'panti_id',
        'order_id',
        'amount',
        'payment_method',
        'status',
        'snap_token',
        'hide_name'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    // Relasi ke user (donatur)
    public function donatur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke panti asuhan
    public function panti()
    {
        return $this->belongsTo(PantiAsuhan::class, 'panti_id');
    }

    // Helper: Generate order ID
    public static function generateOrderId()
    {
        return 'DONASI-' . now()->format('YmdHis') . '-' . substr(md5(uniqid()), 0, 8);
    }

    // Status transaksi
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Pembayaran',
            'success' => 'Sukses',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa'
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}