<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'user_id',
        'preorder_id',
        'subject',
        'message',
        'admin_reply',
        'status',
    ];

    public function scopeForCreator(Builder $query, User $creator): Builder
    {
        return $query->whereBelongsTo($creator, 'creator');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preorder(): BelongsTo
    {
        return $this->belongsTo(Preorder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
