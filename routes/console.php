<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\EnvironmentLog;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ===== JADWAL REKAP PDF & AUTO-RESET MINGGUAN =====
// Dijalankan otomatis setiap hari Minggu pukul 23:59
// Akan merangkum data jadi PDF, menyimpan arsip ke database, lalu mereset tabel.
Schedule::command('report:generate-weekly')->weeklyOn(0, '23:59');