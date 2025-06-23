<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // Relationship with user (donatur)
    public function donatur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with panti asuhan
    public function panti(): BelongsTo
    {
        return $this->belongsTo(PantiAsuhan::class, 'panti_id');
    }

    // Generate unique order ID
    public static function generateOrderId(): string
    {
        return 'DONASI-' . now()->format('YmdHis') . '-' . substr(md5(uniqid()), 0, 8);
    }

    // Status label accessor
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'pending' => 'Menunggu Pembayaran',
            'waiting confirmation' => 'Menunggu Konfirmasi',
            'success' => 'Sukses',
            'failed' => 'Gagal',
            'canceled' => 'Dibatalkan',
            'expired' => 'Kadaluarsa'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Status color accessor
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'waiting confirmation' => 'bg-yellow-100 text-yellow-800',
            'success' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'canceled' => 'bg-red-100 text-red-800',
            'expired' => 'bg-gray-100 text-gray-800'
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}