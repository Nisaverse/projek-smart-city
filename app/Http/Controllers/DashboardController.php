<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IoTStateManager;
use App\Models\EnvironmentLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    private $stateManager;

    public function __construct(IoTStateManager $stateManager)
    {
        $this->stateManager = $stateManager;
    }

    // ===== PAGES ROUTES =====

    public function index()
    {
        return view('dashboard.index');
    }

    public function smartLamp()
    {
        return view('dashboard.smart-lamp');
    }

    public function smartTemp()
    {
        return view('dashboard.temp'); 
    }

    public function smartParking()
    {
        return view('dashboard.smart-parking');
    }

    public function control()
    {
        return view('dashboard.control');
    }

    // ===== API ENDPOINTS (GET DATA REAL-TIME) =====

  public function apiLampData()
    {
        // Ambil data langsung dari Cache yang diisi oleh ListenMqtt.php
        return response()->json([
            'L01' => \Illuminate\Support\Facades\Cache::get('smart_lamp_L01', ['sector_code' => 'L01', 'status' => 'inactive']),
            'L02' => \Illuminate\Support\Facades\Cache::get('smart_lamp_L02', ['sector_code' => 'L02', 'status' => 'inactive']),
            'L03' => \Illuminate\Support\Facades\Cache::get('smart_lamp_L03', ['sector_code' => 'L03', 'status' => 'inactive']),
            'L04' => \Illuminate\Support\Facades\Cache::get('smart_lamp_L04', ['sector_code' => 'L04', 'status' => 'inactive']),
        ]);
    }

    public function apiParkingData()
    {
        return response()->json($this->stateManager->getParkingData());
    }

    // Melayani data Suhu & Kelembapan (DHT Sensor & Monitoring)
    public function apiEnvironmentData()
    {
        try {
            $latest = EnvironmentLog::orderBy('id', 'desc')->first();

            if ($latest) {
                return response()->json([
                    'has_data'    => true, // <-- Kunci penting agar diterima oleh frontend temp.blade.php
                    'temperature' => (float)$latest->temperature,
                    'humidity'    => (float)$latest->humidity,
                    'air_quality' => (int)($latest->air_quality ?? 0),
                    'rain_status' => $latest->rain_status ?? 'Cerah',
                    'water_level' => (float)($latest->water_level ?? 0),
                    'timestamp'   => $latest->created_at->format('H:i:s')
                ]);
            }
        } catch (\Exception $e) {}

        // Fallback jika database masih kosong
        return response()->json([
            'has_data'    => false,
            'temperature' => '--',
            'humidity'    => '--',
            'air_quality' => '--',
            'rain_status' => 'Menunggu data',
            'water_level' => '--',
            'timestamp'   => now()->format('H:i:s')
        ]);
    }

    // ===== API KHUSUS HALAMAN PENGUNJUNG (MONITORING PUBLIC) =====
    public function publicEnvironmentData()
    {
        try {
            $latest = EnvironmentLog::orderBy('id', 'desc')->first();

            if ($latest) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'temperature' => (float)$latest->temperature,
                        'humidity'    => (float)$latest->humidity,
                        'air_quality' => (int)($latest->air_quality ?? 0),
                        'rain_status' => $latest->rain_status ?? 'Cerah',
                        'water_level' => (float)($latest->water_level ?? 0),
                        'iot_status'  => 'Online',
                    ]
                ]);
            }
        } catch (\Exception $e) {}

        return response()->json([
            'status' => 'empty',
            'data' => [
                'temperature' => '--',
                'humidity'    => '--',
                'air_quality' => '--',
                'rain_status' => 'Menunggu data',
                'water_level' => '--',
                'iot_status'  => 'Offline',
            ]
        ]);
    }

    public function apiDashboardData()
    {
        $summary = $this->stateManager->getDashboardSummary();

        if (!isset($summary['temperature'])) {
            $envData = method_exists($this->stateManager, 'getEnvironmentData')
                ? $this->stateManager->getEnvironmentData()
                : ['temperature' => 29.5, 'humidity' => 65.0];

            $summary['temperature'] = $envData['temperature'] ?? 29.5;
            $summary['humidity']    = $envData['humidity'] ?? 65.0;
        }

        return response()->json($summary);
    }

    // ===== API ESP INGESTION (PENERIMAAN DATA SENSOR LINGKUNGAN) =====
    public function storeEnvironmentData(Request $request)
    {
        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'humidity'    => 'required|numeric',
            'air_quality' => 'nullable|numeric',
            'rain_status' => 'nullable|string',
            'water_level' => 'nullable|numeric',
        ]);

        $now = Carbon::now();
        $latestLog = EnvironmentLog::latest('created_at')->first();
        $shouldStore = false;

        if (!$latestLog) {
            $shouldStore = true;
        } else {
            $diffInMinutes = $latestLog->created_at->diffInMinutes($now);
            if ($diffInMinutes >= 60) {
                $shouldStore = true;
            }
        }

        if ($shouldStore) {
            EnvironmentLog::create([
                'temperature' => $validated['temperature'],
                'humidity'    => $validated['humidity'],
                'air_quality' => $validated['air_quality'] ?? 0,
                'rain_status' => $validated['rain_status'] ?? 'Normal',
                'water_level' => $validated['water_level'] ?? 0,
                'created_at'  => $now,
            ]);
        }

        if (method_exists($this->stateManager, 'updateEnvironmentData')) {
            $this->stateManager->updateEnvironmentData($validated);
        }

        return response()->json([
            'success'   => true,
            'stored'    => $shouldStore,
            'message'   => $shouldStore 
                ? 'Data berhasil disimpan ke database (interval 1 jam terpenuhi).' 
                : 'Data diterima untuk monitoring real-time, tetapi tidak disimpan (belum 1 jam).',
            'timestamp' => $now->format('Y-m-d H:i:s')
        ]);
    }

    // ===== HAPUS ARSIP LAPORAN MINGGUAN =====
    public function destroyReport($id)
    {
        $report = DB::table('weekly_reports')->where('id', $id)->first();

        if ($report) {
            // Hapus file fisik PDF dari storage public jika ada
            if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
                Storage::disk('public')->delete($report->file_path);
            }

            // Hapus data dari tabel database weekly_reports
            DB::table('weekly_reports')->where('id', $id)->delete();
        }

        return redirect()->back()->with('success', 'Arsip laporan berhasil dihapus.');
    }

    // ===== API ENDPOINTS (CONTROL) =====

    public function controlLamp(Request $request)
    {
        $validated = $request->validate([
            'lamp_id'    => 'required|in:lamp_1,lamp_2,lamp_3,lamp_4',
            'status'     => 'nullable|boolean',
            'brightness' => 'nullable|integer|min:0|max:100',
        ]);

        $this->stateManager->setControlMode('manual');
        $this->stateManager->updateLamp($validated['lamp_id'], $validated);

        return response()->json(['success' => true, 'mode' => 'manual']);
    }

    public function setControlMode(Request $request)
    {
        $validated = $request->validate([
            'mode' => 'required|in:manual,auto_schedule,auto_sensor',
        ]);

        $this->stateManager->setControlMode($validated['mode']);
        return response()->json(['success' => true, 'mode' => $validated['mode']]);
    }

    public function setSensorLightLevel(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|integer|min:0|max:100',
        ]);

        $this->stateManager->setSensorLightLevel($validated['level']);
        return response()->json(['success' => true, 'light_level' => $validated['level']]);
    }

    // ===== AUTO SETTINGS =====

    public function getAutoSettings()
    {
        return response()->json($this->stateManager->getAutoSettings());
    }

    public function updateAutoSettings(Request $request)
    {
        $validated = $request->validate([
            'schedule_on_hour'    => 'required|integer|min:0|max:23',
            'schedule_on_minute'  => 'required|integer|min:0|max:59',
            'schedule_off_hour'   => 'required|integer|min:0|max:23',
            'schedule_off_minute' => 'required|integer|min:0|max:59',
            'sensor_threshold'   => 'required|integer|min:0|max:100',
        ]);

        $this->stateManager->updateAutoSettings($validated);
        $this->stateManager->reEvaluateAutoLogic();
        return response()->json([
            'success'  => true, 
            'settings' => $this->stateManager->getAutoSettings()
        ]);
    }

    public function toggleAutoSchedule(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $enabled = $this->stateManager->toggleAutoSchedule($validated['enabled']);
        
        return response()->json([
            'success'               => true,
            'auto_schedule_enabled' => $enabled,
        ]);
    }

    public function toggleAutoSensor(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $enabled = $this->stateManager->toggleAutoSensor($validated['enabled']);
        
        return response()->json([
            'success'             => true,
            'auto_sensor_enabled' => $enabled,
        ]);
    }
}