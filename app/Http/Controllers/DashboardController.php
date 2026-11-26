<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IoTStateManager;
use App\Models\EnvironmentLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class DashboardController extends Controller
{
    private $stateManager;

    private const DEFAULT_DEVICE_ID = 'esp32s3-9C3B21AD4F7C';
    private const MQTT_HOST = 'broker.hivemq.com';
    private const MQTT_PORT = 1883;
    private const IOT_TIMEOUT_SECONDS = 20;

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

    // ===== HELPER CEK STATUS ONLINE / OFFLINE IoT =====

    private function parseTimestamp(?string $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function isTimestampFresh(?Carbon $timestamp, int $maxAgeSeconds = self::IOT_TIMEOUT_SECONDS): bool
    {
        if (!$timestamp) {
            return false;
        }

        return $timestamp->diffInSeconds(now()) <= $maxAgeSeconds;
    }

    private function getLampOnlineState(): bool
    {
        $latestTime = null;
        for ($i = 1; $i <= 4; $i++) {
            $lamp = Cache::get('smart_lamp_L0' . $i);
            if (is_array($lamp)) {
                $dt = $this->parseTimestamp($lamp['updated_at'] ?? null);
                if ($dt && (!$latestTime || $dt->greaterThan($latestTime))) {
                    $latestTime = $dt;
                }
            }
        }

        return $this->isTimestampFresh($latestTime);
    }

    private function getParkingOnlineState(): bool
    {
        $summary = Cache::get('smart_parking_summary');
        $summaryTime = is_array($summary) ? $this->parseTimestamp($summary['updated_at'] ?? null) : null;

        $latestSlotTime = null;
        for ($i = 1; $i <= 10; $i++) {
            $code = 'P' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $slot = Cache::get("parking_slot_{$code}");
            if (is_array($slot)) {
                $dt = $this->parseTimestamp($slot['updated_at'] ?? null);
                if ($dt && (!$latestSlotTime || $dt->greaterThan($latestSlotTime))) {
                    $latestSlotTime = $dt;
                }
            }
        }

        return $this->isTimestampFresh($summaryTime ?? $latestSlotTime);
    }

    private function getEnvironmentOnlineState(): bool
    {
        $cached = Cache::get('latest_environment_data');
        if (!is_array($cached) || empty($cached)) {
            return false;
        }

        $cachedDt = $this->parseTimestamp($cached['updated_at'] ?? null);
        return $this->isTimestampFresh($cachedDt);
    }

    // ===== API ENDPOINTS (GET DATA REAL-TIME) =====

    public function apiLampData()
    {
        $lamps = [
            'L01' => Cache::get('smart_lamp_L01', ['sector_code' => 'L01', 'status' => 'inactive']),
            'L02' => Cache::get('smart_lamp_L02', ['sector_code' => 'L02', 'status' => 'inactive']),
            'L03' => Cache::get('smart_lamp_L03', ['sector_code' => 'L03', 'status' => 'inactive']),
            'L04' => Cache::get('smart_lamp_L04', ['sector_code' => 'L04', 'status' => 'inactive']),
        ];

        $isOnline = $this->getLampOnlineState();

        return response()->json([
            'L01'        => $lamps['L01'],
            'L02'        => $lamps['L02'],
            'L03'        => $lamps['L03'],
            'L04'        => $lamps['L04'],
            'iot_status' => $isOnline ? 'Online' : 'Offline',
            'is_online'  => $isOnline,
        ]);
    }

    public function apiControlState()
    {
        $envCached = Cache::get('latest_environment_data', []);
        $espLightingMode = is_array($envCached) ? ($envCached['lighting_mode'] ?? null) : null;

        $mode = Cache::get('control_mode');
        if (!$mode && $espLightingMode) {
            $mode = strtolower((string) $espLightingMode) === 'auto' ? 'auto_sensor' : 'manual';
        }
        $mode = $mode ?: 'manual';

        $isOnline = $this->getEnvironmentOnlineState();

        return response()->json([
            'mode'          => $mode,
            'lighting_mode' => $espLightingMode ?? (str_starts_with($mode, 'auto') ? 'auto' : 'manual'),
            'iot_status'    => $isOnline ? 'Online' : 'Offline',
            'is_online'     => $isOnline,
            'lamps'         => [
                'L01' => Cache::get('smart_lamp_L01', ['sector_code' => 'L01', 'status' => 'off']),
                'L02' => Cache::get('smart_lamp_L02', ['sector_code' => 'L02', 'status' => 'off']),
                'L03' => Cache::get('smart_lamp_L03', ['sector_code' => 'L03', 'status' => 'off']),
                'L04' => Cache::get('smart_lamp_L04', ['sector_code' => 'L04', 'status' => 'off']),
            ],
            'updated_at'    => now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function apiParkingData()
    {
        $summary = Cache::get('smart_parking_summary');
        $fallback = method_exists($this->stateManager, 'getParkingData')
            ? $this->stateManager->getParkingData()
            : [];

        $slots = [];
        $hasSlotCache = false;

        for ($i = 1; $i <= 10; $i++) {
            $code = 'P' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $cachedSlot = Cache::get("parking_slot_{$code}");

            if ($cachedSlot) {
                $slots[$code] = $cachedSlot;
                $hasSlotCache = true;
            }
        }

        if (!$hasSlotCache) {
            if ($summary && isset($summary['occupied_slots'])) {
                $occupiedCount = (int) $summary['occupied_slots'];
                $updatedAt     = $summary['updated_at'] ?? now()->format('Y-m-d H:i:s');

                for ($i = 1; $i <= 10; $i++) {
                    $code = 'P' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $slots[$code] = [
                        'slot_code'  => $code,
                        'status'     => ($i <= $occupiedCount) ? 'occupied' : 'available',
                        'updated_at' => $updatedAt,
                    ];
                }
            } elseif (is_array($fallback) && !empty($fallback)) {
                $rawFallbackSlots = $fallback['slots'] ?? $fallback;
                for ($i = 1; $i <= 10; $i++) {
                    $code = 'P' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    if (isset($rawFallbackSlots[$code])) {
                        $slots[$code] = $rawFallbackSlots[$code];
                    }
                }
            }
        }

        $isOnline = $this->getParkingOnlineState();

        return response()->json([
            'summary'    => $summary ?? ($fallback['summary'] ?? null),
            'slots'      => $slots,
            'iot_status' => $isOnline ? 'Online' : 'Offline',
            'is_online'  => $isOnline,
        ]);
    }

    // Melayani data Suhu & Kelembapan (DHT Sensor & Monitoring Admin)
    public function apiEnvironmentData()
    {
        $cached = Cache::get('latest_environment_data');

        if (is_array($cached) && !empty($cached)) {
            $cachedDt = $this->parseTimestamp($cached['updated_at'] ?? null);
            $isOnline = $this->isTimestampFresh($cachedDt);
            $timeStr = $cachedDt ? $cachedDt->format('H:i:s') : now()->format('H:i:s');

            return response()->json([
                'has_data'    => true,
                'temperature' => (float) ($cached['temperature'] ?? 0),
                'humidity'    => (float) ($cached['humidity'] ?? 0),
                'air_quality' => (int) ($cached['air_quality'] ?? 0),
                'rain_status' => $cached['rain_status'] ?? 'Kering',
                'water_level' => (float) ($cached['water_level'] ?? 0),
                'iot_status'  => $isOnline ? 'Online' : 'Offline',
                'is_online'   => $isOnline,
                'timestamp'   => $timeStr,
            ]);
        }

        try {
            $latest = EnvironmentLog::orderBy('id', 'desc')->first();

            if ($latest) {
                return response()->json([
                    'has_data'    => true,
                    'temperature' => (float) $latest->temperature,
                    'humidity'    => (float) $latest->humidity,
                    'air_quality' => (int) ($latest->air_quality ?? 0),
                    'rain_status' => $latest->rain_status ?? 'Cerah',
                    'water_level' => (float) ($latest->water_level ?? 0),
                    'iot_status'  => 'Offline',
                    'is_online'   => false,
                    'timestamp'   => $latest->created_at->format('H:i:s'),
                ]);
            }
        } catch (\Throwable $e) {
        }

        return response()->json([
            'has_data'    => false,
            'temperature' => '--',
            'humidity'    => '--',
            'air_quality' => '--',
            'rain_status' => 'Menunggu data',
            'water_level' => '--',
            'iot_status'  => 'Offline',
            'is_online'   => false,
            'timestamp'   => now()->format('H:i:s'),
        ]);
    }

    // ===== API KHUSUS HALAMAN PENGUNJUNG (MONITORING PUBLIC) =====
    public function publicEnvironmentData()
    {
        $cached = Cache::get('latest_environment_data');

        $activeLamps = 0;
        for ($i = 1; $i <= 4; $i++) {
            $lamp = Cache::get('smart_lamp_L0' . $i);
            if (is_array($lamp) && strtolower((string) ($lamp['status'] ?? '')) === 'on') {
                $activeLamps++;
            }
        }

        $parkingSummary = Cache::get('smart_parking_summary');
        $totalSlots     = is_array($parkingSummary) ? (int) ($parkingSummary['total_slots'] ?? 10) : 10;
        $availableSlots = is_array($parkingSummary) ? (int) ($parkingSummary['available_slots'] ?? 10) : 10;
        $occupiedSlots  = is_array($parkingSummary)
            ? (int) ($parkingSummary['occupied_slots'] ?? max(0, $totalSlots - $availableSlots))
            : 0;

        if (is_array($cached) && !empty($cached)) {
            $isOnline = $this->getEnvironmentOnlineState();

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'temperature'   => (float) ($cached['temperature'] ?? 0),
                    'humidity'      => (float) ($cached['humidity'] ?? 0),
                    'air_quality'   => (int) ($cached['air_quality'] ?? 0),
                    'rain_status'   => $cached['rain_status'] ?? 'Kering',
                    'water_level'   => (float) ($cached['water_level'] ?? 0),
                    'iot_status'    => $isOnline ? 'Online' : 'Offline',
                    'is_online'     => $isOnline,
                    'lamps_active'  => $activeLamps,
                    'lamps_total'   => 4,
                    'parking_total' => $totalSlots,
                    'parking_avail' => $availableSlots,
                    'parking_used'  => $occupiedSlots,
                ],
            ]);
        }

        try {
            $latest = EnvironmentLog::orderBy('id', 'desc')->first();

            if ($latest) {
                return response()->json([
                    'status' => 'success',
                    'data'   => [
                        'temperature'   => (float) $latest->temperature,
                        'humidity'      => (float) $latest->humidity,
                        'air_quality'   => (int) ($latest->air_quality ?? 0),
                        'rain_status'   => $latest->rain_status ?? 'Cerah',
                        'water_level'   => (float) ($latest->water_level ?? 0),
                        'iot_status'    => 'Offline',
                        'is_online'     => false,
                        'lamps_active'  => $activeLamps,
                        'lamps_total'   => 4,
                        'parking_total' => $totalSlots,
                        'parking_avail' => $availableSlots,
                        'parking_used'  => $occupiedSlots,
                    ],
                ]);
            }
        } catch (\Throwable $e) {
        }

        return response()->json([
            'status' => 'empty',
            'data'   => [
                'temperature'   => '--',
                'humidity'      => '--',
                'air_quality'   => '--',
                'rain_status'   => 'Menunggu data',
                'water_level'   => '--',
                'iot_status'    => 'Offline',
                'is_online'     => false,
                'lamps_active'  => $activeLamps,
                'lamps_total'   => 4,
                'parking_total' => $totalSlots,
                'parking_avail' => $availableSlots,
                'parking_used'  => $occupiedSlots,
            ],
        ]);
    }

    public function apiDashboardData()
    {
        $summary = $this->stateManager->getDashboardSummary();
        if (!is_array($summary)) {
            $summary = [];
        }

        if (!isset($summary['temperature'])) {
            $envData = method_exists($this->stateManager, 'getEnvironmentData')
                ? $this->stateManager->getEnvironmentData()
                : ['temperature' => 29.5, 'humidity' => 65.0];

            $summary['temperature'] = $envData['temperature'] ?? 29.5;
            $summary['humidity']    = $envData['humidity'] ?? 65.0;
        }

        $lampOnline = $this->getLampOnlineState();
        $parkingOnline = $this->getParkingOnlineState();
        $environmentOnline = $this->getEnvironmentOnlineState();

        $summary['lamp_online']        = $lampOnline;
        $summary['lamp_status']        = $lampOnline ? 'Online' : 'Offline';
        $summary['parking_online']     = $parkingOnline;
        $summary['parking_status']     = $parkingOnline ? 'Online' : 'Offline';
        $summary['environment_online'] = $environmentOnline;
        $summary['environment_status'] = $environmentOnline ? 'Online' : 'Offline';
        $summary['iot_online']         = $lampOnline || $parkingOnline || $environmentOnline;
        $summary['iot_status']         = $summary['iot_online'] ? 'Online' : 'Offline';

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
            if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
                Storage::disk('public')->delete($report->file_path);
            }

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
            'device_id'  => 'nullable|string',
        ]);

        $this->stateManager->setControlMode('manual');
        $this->stateManager->updateLamp($validated['lamp_id'], $validated);
        Cache::put('control_mode', 'manual', now()->addDay());

        $relayIndexMap = [
            'lamp_1' => [1, 'L01'],
            'lamp_2' => [2, 'L02'],
            'lamp_3' => [3, 'L03'],
            'lamp_4' => [4, 'L04'],
        ];

        [$relayNum, $sectorCode] = $relayIndexMap[$validated['lamp_id']];
        $isOn = isset($validated['status'])
            ? (bool) $validated['status']
            : ((int) ($validated['brightness'] ?? 0) > 0);

        Cache::put("smart_lamp_{$sectorCode}", [
            'sector_code' => $sectorCode,
            'status'      => $isOn ? 'on' : 'off',
            'updated_at'  => now()->format('Y-m-d H:i:s'),
        ], now()->addDay());

        $mqttPublished = $this->publishMqttCommand([
            'relay' => $relayNum,
            'state' => $isOn,
        ], $validated['device_id'] ?? self::DEFAULT_DEVICE_ID);

        return response()->json([
            'success'        => true,
            'mode'           => 'manual',
            'mqtt_published' => $mqttPublished,
        ]);
    }

    public function setControlMode(Request $request)
    {
        $validated = $request->validate([
            'mode'      => 'required|in:manual,auto_schedule,auto_sensor',
            'device_id' => 'nullable|string',
        ]);

        $this->stateManager->setControlMode($validated['mode']);
        Cache::put('control_mode', $validated['mode'], now()->addDay());

        $espMode = ($validated['mode'] === 'manual') ? 'manual' : 'auto';

        $mqttPublished = $this->publishMqttCommand([
            'type' => 'lighting_mode',
            'mode' => $espMode,
        ], $validated['device_id'] ?? self::DEFAULT_DEVICE_ID);

        return response()->json([
            'success'        => true,
            'mode'           => $validated['mode'],
            'lighting_mode'  => $espMode,
            'mqtt_published' => $mqttPublished,
        ]);
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

    // ===== HELPER PUBLISH MQTT KE BROKER =====

    private function publishMqttCommand(array $payload, string $deviceId = self::DEFAULT_DEVICE_ID): bool
    {
        $clientId = 'laravel_web_' . uniqid();
        $jsonPayload = json_encode($payload);

        try {
            $mqtt = new MqttClient(self::MQTT_HOST, self::MQTT_PORT, $clientId);
            $connectionSettings = (new ConnectionSettings())
                ->setConnectTimeout(3)
                ->setSocketTimeout(3);

            $mqtt->connect($connectionSettings, true);
            $mqtt->publish("aethersense/{$deviceId}/command", $jsonPayload, 0, false);
            $mqtt->publish('aethersense/command', $jsonPayload, 0, false);
            $mqtt->disconnect();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}