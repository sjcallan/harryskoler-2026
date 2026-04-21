<?php

namespace App\Models;

use App\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasPublishStatus;

    protected $fillable = [
        'status',
        'image',
        'thumbnail',
        'caption',
        'alt_text',
        'credit',
        'sort_order',
    ];

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/'.$this->image);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }

        return asset('storage/'.$this->thumbnail);
    }
}
