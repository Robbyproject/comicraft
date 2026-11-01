<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoothRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'convention_event_id',
        'creator_id',
        'circle_name',
        'pic_name',
        'whatsapp_number',
        'merchandise_plan',
        'booth_number',
        'booth_type',
        'base_price',
        'platform_fee',
        'total_rental_cost',
        'payment_method',
        'rental_status',
        'paid_at',
        'notes',
    ];

    public function event()
    {
        return $this->belongsTo(ConventionEvent::class, 'convention_event_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
