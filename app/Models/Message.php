<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasUuids;

    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'file_path',
        'reply',
        'replied_at',
        'status'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute(): string
    {
        return $this->replied_at ? 'replied' : 'pending';
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'pending' => 'Belum Dibalas',
            'replied' => 'Sudah Dibalas'
        ][$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'replied' => 'bg-green-100 text-green-800'
        ][$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}