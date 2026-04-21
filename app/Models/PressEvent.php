<?php

namespace App\Models;

use App\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PressEvent extends Model
{
    use HasPublishStatus;

    protected $fillable = [
        'status',
        'title',
        'slug',
        'publication',
        'description',
        'date',
        'pdf',
        'sort_order',
    ];

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PressEvent $event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(PressImage::class)->orderBy('sort_order');
    }

    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf) {
            return null;
        }

        if (str_starts_with($this->pdf, 'http')) {
            return $this->pdf;
        }

        return asset('storage/' . $this->pdf);
    }
}
