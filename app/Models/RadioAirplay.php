<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadioAirplay extends Model
{
    protected $fillable = [
        'rank',
        'chart',
        'detail',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'rank' => 'integer',
            'sort_order' => 'integer',
        ];
    }
}
