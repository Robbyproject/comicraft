<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FandomCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'banner'];

    /**
     * URL banner fandom, atau null bila belum diunggah.
     */
    public function bannerUrl(): ?string
    {
        return $this->banner ? Storage::url($this->banner) : null;
    }

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }
}
