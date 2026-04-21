<?php

namespace App\Models;

use App\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PressImage extends Model
{
    use HasPublishStatus;

    protected $fillable = [
        'status',
        'press_event_id',
        'image',
        'caption',
        'sort_order',
    ];

    protected $attributes = [
        'status' => self::STATUS_PUBLISHED,
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function pressEvent(): BelongsTo
    {
        return $this->belongsTo(PressEvent::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}
