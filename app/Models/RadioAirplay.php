<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RadioAirplay extends Model
{
    protected $fillable = [
        'rank',
        'chart',
        'detail',
        'link',
        'image',
        'thumbnail',
        'album_slug',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'rank' => 'integer',
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
