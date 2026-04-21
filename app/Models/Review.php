<?php

namespace App\Models;

use App\Concerns\HasPublishStatus;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasPublishStatus;

    protected $fillable = [
        'status',
        'album_slug',
        'excerpt',
        'body',
        'author',
        'publication',
        'source_url',
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
}
