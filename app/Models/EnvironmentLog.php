<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvironmentLog extends Model
{
    // Menghubungkan Model ini secara eksplisit ke tabel di database
    protected $table = 'environment_histories';

    // Izinkan semua kolom diisi secaramassal (mass assignment)
    protected $guarded = [];
}