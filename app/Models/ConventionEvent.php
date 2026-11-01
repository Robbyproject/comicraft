<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConventionEvent extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'venue', 'event_date', 'status'];

    public function booths()
    {
        return $this->hasMany(BoothRental::class);
    }
}
