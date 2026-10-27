<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name_tk', 'site_name_ru', 'site_name_en',
        'logo', 'favicon',
        'phone_1', 'phone_2', 'email',
        'instagram', 'tiktok', 'imo'
    ];
}