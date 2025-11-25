<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Kita tidak butuh factory karena hanya ada 1 row
    protected $fillable = [
        'app_title',
        'logo_url',
        'video_url',
        'video_type',
        'running_text',
        'marquee_speed',
    ];
}
