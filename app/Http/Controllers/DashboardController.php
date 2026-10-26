<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IoTStateManager;
use App\Models\EnvironmentLog; // Pastikan Model ini sudah dibuat untuk tabel riwayat data lingkungan
use Carbon\Carbon;

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

    // ===== API ENDPOINTS (GET DATA) =====

    public function apiLampData()
    {
        return response()->json($this->stateManager->getLampData());
    }

    // Melayani data Suhu & Kelembapan (DHT Sensor)
    public function apiEnvironmentData()
    {
        if (method_exists($this->stateManager, 'getEnvironmentData')) {
            return response()->json($this->stateManager->getEnvironmentData());
        }

        // Fallback jika method belum dibuat di IoTStateManager
        return response()->json([
            'temperature' => 29.5,
            'humidity'    => 65.0,
            'timestamp'   => now()->format('H:i:s')
        ]);
    }

    public function apiParkingData()
    {
        return response()->json($this->stateManager->getParkingData());
    }

    public function apiDashboardData()
    {
        $summary = $this->stateManager->getDashboardSummary();

        // Menggabungkan data suhu & kelembapan ke dalam summary dashboard
        if (!isset($summary['temperature'])) {
            $envData = method_exists($this->stateManager, 'getEnvironmentData')
                ? $this->stateManager->getEnvironmentData()
                : ['temperature' => 29.5, 'humidity' => 65.0];

            $summary['temperature'] = $envData['temperature'] ?? 29.5;
            $summary['humidity']    = $envData['humidity'] ?? 65.0;
        }

        return response()->json($summary);
    }

    // ===== API ESP INGESTION (PENERIMAAN DATA SENSOR) =====

    /**
     * Menerima kiriman data dari ESP (misal setiap 5 menit sekali).
     * Logika Filter: Hanya menyimpan ke database jika interval data terakhir sudah >= 1 jam (60 menit).
     */
    public function storeEnvironmentData(Request $request)
    {
        // Validasi payload dari ESP
        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'humidity'    => 'required|numeric',
            'air_quality' => 'nullable|numeric',
            'rain_status' => 'nullable|string',
        ]);

        $now = Carbon::now();

        // Cek log data terakhir di database
        $latestLog = EnvironmentLog::latest('created_at')->first();
        $shouldStore = false;

        if (!$latestLog) {
            // Jika database masih kosong, izinkan penyimpanan pertama
            $shouldStore = true;
        } else {
            // Hitung selisih waktu dalam menit
            $diffInMinutes = $latestLog->created_at->diffInMinutes($now);
            
            // Jika selisih waktu sudah mencapai 60 menit (1 jam) atau lebih
            if ($diffInMinutes >= 60) {
                $shouldStore = true;
            }
        }

        // Simpan ke database jika memenuhi interval 1 jam
        if ($shouldStore) {
            EnvironmentLog::create([
                'temperature' => $validated['temperature'],
                'humidity'    => $validated['humidity'],
                'air_quality' => $validated['air_quality'] ?? 0,
                'rain_status' => $validated['rain_status'] ?? 'Normal',
                'created_at'  => $now,
            ]);
        }

        // Update state secara real-time di memori/cache agar grafik & UI langsung diperbarui
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

    // Toggle Auto Schedule ON/OFF
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

    // Toggle Auto Sensor ON/OFF
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