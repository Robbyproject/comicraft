<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Merchandise extends Model
{
    use HasFactory;

    /** Status pre-order yang masih memakai slot kuota produksi (semua kecuali dibatalkan). */
    public const STATUSES_CONSUMING_QUOTA = ['pending', 'producing', 'ready_at_booth', 'completed'];

    protected $fillable = [
        'fandom_category_id',
        'name',
        'item_type',
        'price',
        'stock_quota',
        'image',
    ];

    /**
     * Slot produksi yang masih bebas setelah dikurangi kuantitas pre-order aktif.
     * (Sudah diperbaiki dari typo sintaks sebelumnya)
     */
    public function getAvailableQuotaAttribute(): int
    {
        return max(0, (int) $this->stock_quota - (int) ($this->reserved_quota ?? 0));
    }

    /**
     * URL gambar merchandise:
     * 1. Mengambil file upload admin dari storage
     * 2. Atau file publik lokal
     * 3. Atau fallback gambar anime otomatis agar katalog tidak pernah abu-abu kosong
     */
    /**
     * URL foto asli merchandise dari storage atau file lokal.
     */
    public function imageUrl(): ?string
    {
        // 1. Jika ada foto hasil upload admin di storage
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        // 2. Jika path foto ada di folder public/images/...
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        // 3. Jika image berisi URL eksternal langsung (http/https)
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return null;
    }

    /**
     * Nama ikon monokrom sesuai tipe item.
     */
    public function iconName(): string
    {
        return match ($this->item_type) {
            'acrylic_stand' => 'user',
            'keychain' => 'key',
            'photocard' => 'credit-card',
            'art_print' => 'image',
            default => 'gift',
        };
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FandomCategory::class, 'fandom_category_id');
    }

    public function preorders(): HasMany
    {
        return $this->hasMany(Preorder::class);
    }
}
