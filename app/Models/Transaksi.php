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
        'payload'
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

    // Cast payload (JSON) ke array
    protected $casts = [
        'payload' => 'array'
    ];

    // Relasi ke user (donatur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke panti asuhan
    public function panti()
    {
        return $this->belongsTo(PantiAsuhan::class);
    }

    // Helper: Generate order ID
    public static function generateOrderId()
    {
        return 'DONASI-' . now()->format('Ymd') . '-' . strtoupper(uniqid());
    }
}
