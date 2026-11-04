<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Preorder extends Model
{
    use HasFactory;

    protected $fillable = [
        'preorder_code',
        'pickup_secret_pin',
        'user_id',
        'merchandise_id',
        'quantity',
        'total_price',
        'pickup_method',
        'tracking_number',
        'payment_scheme',
        'amount_paid',
        'remaining_balance',
        'platform_fee',
        'net_creator_income',
        'payment_status',
        'status',
        'notes',
    ];

    public function scopeForCreator(Builder $query, User $creator): Builder
    {
        return $query->whereHas('merchandise', fn (Builder $query) => $query->whereBelongsTo($creator, 'creator'));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function merchandise(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class);
    }

    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }
}
