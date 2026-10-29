<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvironmentLog extends Model
{
    protected $table = 'environment_histories';

    // 👉 TAMBAHKAN BARIS INI (memberitahu Laravel bahwa tabel tidak pakai updated_at)
    const UPDATED_AT = null;

    protected $fillable = [
        'temperature',
        'humidity',
        'air_quality',
        'rain_status',
        'water_level',
    ];
}