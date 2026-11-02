<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ===== JADWAL REKAP PDF & AUTO-RESET MINGGUAN =====
// Otomatis berjalan setiap hari Minggu pukul 23:59
// Membuat PDF, mencatat arsip ke database, lalu mereset tabel database.
Schedule::command('report:generate-weekly')->weeklyOn(0, '23:59');