<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title_tk',
        'title_ru',
        'title_en',
        'image',
        'link',
        'order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'status' => 'integer',
        ];
    }
}