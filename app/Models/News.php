<?php

namespace App\Models;

use App\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasPublishStatus;

    protected $fillable = [
        'status',
        'title',
        'slug',
        'body',
        'date',
        'image',
        'link',
        'link_new_window',
    ];

    protected $attributes = [
        'status' => self::STATUS_DRAFT,
        'link_new_window' => true,
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'link_new_window' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (News $news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
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
