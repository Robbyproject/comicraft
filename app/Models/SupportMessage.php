<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preorder_id',
        'subject',
        'message',
        'admin_reply',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preorder()
    {
        return $this->belongsTo(Preorder::class);
    }
}
