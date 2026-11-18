<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EnvironmentLog;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProcessHourlyAndWeeklyReport extends Command
{
    protected $signature = 'report:process-hourly-weekly';
    protected $description = 'Menyimpan data per jam dan otomatis mereset serta membuat PDF saat akhir minggu';

    public function handle()
    {
        // 1. SIMPAN DATA PER JAM (Bisa dijalankan setiap 1 jam sekali)
        // Mengambil rata-rata data sensor dalam 1 jam terakhir sebelum ditimpa/dibersihkan
        $latestTemp = EnvironmentLog::avg('temperature');
        $latestHum  = EnvironmentLog::avg('humidity');
        $latestAir  = EnvironmentLog::avg('air_quality');
        $latestRain = EnvironmentLog::latest()->value('rain_status');
        $latestWater = EnvironmentLog::avg('water_level');

        if ($latestTemp !== null) {
            // Simpan ke tabel ringkasan per jam (misal: tabel hourly_summaries)
            DB::table('hourly_summaries')->insert([
                'temperature' => $latestTemp,
                'humidity'    => $latestHum,
                'air_quality' => $latestAir,
                'rain_status' => $latestRain ?? 'Cerah',
                'water_level' => $latestWater,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $this->info('✅ Berhasil merekam data rata-rata jam ini.');
        }

        // 2. CEK APAKAH SUDAH HARI MINGGU MALAM (Waktunya Reset & Buat PDF Mingguan)
        // Misal diset setiap hari Minggu pukul 23:59 atau tengah malam
        if (Carbon::now()->isSunday() && Carbon::now()->hour == 23) {
            $this->info('📊 Waktunya rekap mingguan! Sedang membuat PDF...');

            // Ambil semua data ringkasan per jam selama seminggu ini dari tabel hourly_summaries
            $logs = DB::table('hourly_summaries')->orderBy('created_at', 'asc')->get();

            if ($logs->isNotEmpty()) {
                // Buat PDF dari template
                $pdf = Pdf::loadView('reports.environment_weekly', compact('logs'));

                $fileName = 'Laporan_Mingguan_' . date('Y-m-d') . '.pdf';
                $filePath = 'reports/' . $fileName;
                Storage::disk('public')->put($filePath, $pdf->output());

                // Catat ke arsip dashboard
                DB::table('weekly_reports')->insert([
                    'title'      => 'Rekap Minggu ' . date('d/m/Y'),
                    'file_path'  => $filePath,
                    'total_logs' => $logs->count(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Kosongkan tabel ringkasan per jam untuk minggu depan
                DB::table('hourly_summaries')->truncate();
            }

            // Reset juga tabel utama environment_histories
            EnvironmentLog::truncate();
            $this->info('🧹 Database berhasil di-reset bersih untuk minggu baru!');
        }
    }
}