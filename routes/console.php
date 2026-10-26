<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\EnvironmentLog;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ===== JADWAL AUTO-DESTROY DATA SENSOR =====
// Dijalankan setiap hari Senin pukul 01:00 pagi
Schedule::call(function () {
    // Menghapus data riwayat yang umurnya sudah lebih dari 7 hari,
    // TAPI mengecualikan data yang masuk pada hari Minggu (DAYOFWEEK != 1 di MySQL).
    // Jadi hari Senin-Sabtu minggu lalu terhapus, hari Minggu tetap aman!
    EnvironmentLog::where('created_at', '<', Carbon::now()->subDays(7))
                  ->whereRaw('DAYOFWEEK(created_at) != 1')
                  ->delete();
})->weeklyOn(1, '01:00');