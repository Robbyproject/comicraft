<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function preorders()
    {
        return $this->hasMany(Preorder::class);
    }

    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function isCreator(): bool
    {
        return $this->role === 'creator';
    }
}
