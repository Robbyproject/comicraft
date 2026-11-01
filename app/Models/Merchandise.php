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
     * Return the merchandise image URL from its configured storage disk.
     */
    public function imageUrl(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        $diskName = config('filesystems.merchandise_disk', 'public');
        $disk = Storage::disk($diskName);

        if (config("filesystems.disks.{$diskName}.driver") === 's3') {
            return $disk->url($this->image);
        }

        if ($disk->exists($this->image)) {
            return $disk->url($this->image);
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
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
