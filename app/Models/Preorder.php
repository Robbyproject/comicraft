<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class);
    }

    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class);
    }
}
