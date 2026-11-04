<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function merchandises(): HasMany
    {
        return $this->hasMany(Merchandise::class, 'creator_id');
    }

    public function preorders(): HasMany
    {
        return $this->hasMany(Preorder::class);
    }

    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function isCreator(): bool
    {
        return $this->role === 'creator';
    }
}
