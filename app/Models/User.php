<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'role',
        'status',
        'banned_at' // Added banned_at for ban functionality
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'banned_at' => 'datetime' // Cast banned_at as datetime
        ];
    }

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

    /**
     * Get the PantiAsuhan associated with the User.
     */
    public function pantiAsuhan(): HasOne
    {
        return $this->hasOne(PantiAsuhan::class, 'user_id');
    }

    /**
     * Get all transactions initiated by this user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }

    /**
     * Check if user is banned.
     */
    public function isBanned(): bool
    {
        return !is_null($this->banned_at);
    }

    /**
     * Scope a query to only include banned users.
     */
    public function scopeBanned($query)
    {
        return $query->whereNotNull('banned_at');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('banned_at');
    }
}