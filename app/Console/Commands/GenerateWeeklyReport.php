<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EnvironmentLog;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateWeeklyReport extends Command
{
    protected $signature = 'report:generate-weekly';
    protected $description = 'Generate weekly environment PDF report, save to archive, and reset database records';

    public function handle()
    {
        // Naikkan batas memori PHP sementara agar tidak error saat merender data banyak
        ini_set('memory_limit', '1024M');

        $this->info('📊 Mengambil data dari database...');

        $logs = EnvironmentLog::all();

        if ($logs->isEmpty()) {
            $this->warn('⚠️ Tidak ada data untuk direkap saat ini.');
            return;
        }

        // 1. Buat PDF dari template
        $pdf = Pdf::loadView('reports.environment_weekly', compact('logs'));

        // 2. Simpan file PDF ke folder storage/app/public/reports/
        $fileName = 'Laporan_Mingguan_' . date('Y-m-d') . '.pdf';
        $filePath = 'reports/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        // 3. Catat arsip ke tabel weekly_reports
        DB::table('weekly_reports')->insert([
            'title'      => 'Rekap Minggu ' . date('d/m/Y'),
            'file_path'  => $filePath,
            'total_logs' => $logs->count(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("✅ Arsip PDF berhasil direkam ke database: {$fileName}");

        // 4. Reset tabel environment_histories menjadi kosong untuk minggu baru
        EnvironmentLog::truncate();
        $this->info('🧹 Database environment_histories berhasil di-reset menjadi kosong!');
    }
}