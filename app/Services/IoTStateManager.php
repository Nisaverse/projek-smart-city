<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IoTStateManager
{
    private $stateFile = 'private/iot_state/central_state.json';
    private $state;

    public function __construct()
    {
        $this->loadState();
    }

    private function loadState()
    {
        if (Storage::exists($this->stateFile)) {
            $this->state = json_decode(Storage::get($this->stateFile), true);
            
            if (!isset($this->state['lamps']) || !is_array($this->state['lamps'])) {
                $this->state['lamps'] = $this->getDefaultState()['lamps'];
                $this->saveState();
            }
            if (!isset($this->state['waste']) || !is_array($this->state['waste'])) {
                $this->state['waste'] = $this->getDefaultState()['waste'];
                $this->saveState();
            }
        } else {
            $this->state = $this->getDefaultState();
            $this->saveState();
        }

        if (
            !isset($this->state['parking']) ||
            $this->state['parking']['total_slots'] != 8 ||
            isset($this->state['parking']['zones']['zone_c']) ||
            !isset($this->state['parking']['zones']['zone_b'])
        ) {
            $this->state['parking'] = [
                'total_slots' => 8,
                'occupied' => 4,
                'zones' => [
                    'zone_a' => ['total' => 4, 'occupied' => 2],
                    'zone_b' => ['total' => 4, 'occupied' => 2],
                ]
            ];
            $this->saveState();
        }

        if (!isset($this->state['auto_settings'])) {
            $this->state['auto_settings'] = $this->getDefaultState()['auto_settings'];
            $this->saveState();
        }

        if (!isset($this->state['auto_settings']['auto_schedule_enabled'])) {
            $this->state['auto_settings']['auto_schedule_enabled'] = true;
            $this->saveState();
        }
        if (!isset($this->state['auto_settings']['auto_sensor_enabled'])) {
            $this->state['auto_settings']['auto_sensor_enabled'] = false;
            $this->saveState();
        }
    }

    private function getDefaultState()
    {
        return [
            'updated_at' => now()->toIso8601String(),
            'lamps' => [
                'lamp_1' => ['status' => 1, 'brightness' => 80, 'power' => 4.8, 'name' => 'Jl. Sudirman'],
                'lamp_2' => ['status' => 0, 'brightness' => 51, 'power' => 3.1, 'name' => 'Jl. Thamrin'],
                'lamp_3' => ['status' => 1, 'brightness' => 77, 'power' => 4.6, 'name' => 'Jl. Gatot Subroto'],
                'lamp_4' => ['status' => 0, 'brightness' => 63, 'power' => 3.8, 'name' => 'Jl. Rasuna Said'],
            ],
            'waste' => [
                'bin_1' => ['level' => 45, 'location' => 'Jl. Sudirman'],
                'bin_2' => ['level' => 60, 'location' => 'Jl. Thamrin'],
                'bin_3' => ['level' => 30, 'location' => 'Jl. Gatot Subroto'],
                'bin_4' => ['level' => 70, 'location' => 'Jl. Rasuna Said'],
            ],
            'parking' => [
                'total_slots' => 8,
                'occupied' => 4,
                'zones' => [
                    'zone_a' => ['total' => 4, 'occupied' => 2],
                    'zone_b' => ['total' => 4, 'occupied' => 2],
                ]
            ],
            'control_mode' => 'manual',
            'sensor_light_level' => 65,

            'auto_settings' => [
                'schedule_on_hour' => 17,
                'schedule_on_minute' => 30,
                'schedule_off_hour' => 6,
                'schedule_off_minute' => 0,
                'sensor_threshold' => 35,
                'auto_schedule_enabled' => true,
                'auto_sensor_enabled' => false,
            ],
        ];
    }

    public function updateAutoSettings($settings)
    {
        if (!isset($this->state['auto_settings'])) {
            $this->state['auto_settings'] = $this->getDefaultState()['auto_settings'];
        }
        
        foreach ($settings as $key => $value) {
            if (isset($this->state['auto_settings'][$key])) {
                $this->state['auto_settings'][$key] = (int)$value;
            }
        }
        
        $this->saveState();
    }

    public function getAutoSettings()
    {
        if (!isset($this->state['auto_settings'])) {
            $this->state['auto_settings'] = $this->getDefaultState()['auto_settings'];
        }
        return $this->state['auto_settings'];
    }

    public function toggleAutoSchedule($enabled)
    {
        if (!isset($this->state['auto_settings'])) {
            $this->state['auto_settings'] = $this->getDefaultState()['auto_settings'];
        }
        
        $this->state['auto_settings']['auto_schedule_enabled'] = (bool)$enabled;
        $this->saveState();
        
        return $this->state['auto_settings']['auto_schedule_enabled'];
    }

    public function toggleAutoSensor($enabled)
    {
        if (!isset($this->state['auto_settings'])) {
            $this->state['auto_settings'] = $this->getDefaultState()['auto_settings'];
        }
        
        $this->state['auto_settings']['auto_sensor_enabled'] = (bool)$enabled;
        $this->saveState();
        
        return $this->state['auto_settings']['auto_sensor_enabled'];
    }

    public function saveState()
    {
        $this->state['updated_at'] = now()->toIso8601String();
        Storage::put($this->stateFile, json_encode($this->state, JSON_PRETTY_PRINT));
    }

    public function getState()
    {
        return $this->state;
    }

    public function updateLamp($lampId, $data)
    {
        if (isset($this->state['lamps'][$lampId])) {
            foreach ($data as $key => $value) {
                if (isset($this->state['lamps'][$lampId][$key])) {
                    $this->state['lamps'][$lampId][$key] = $value;
                }
            }

            if (isset($data['mode'])) {
                if ($data['mode'] === 'AUTO') {
                    $settings = $this->getAutoSettings();
                    if (!($settings['auto_schedule_enabled'] ?? false) && !($settings['auto_sensor_enabled'] ?? false)) {
                        $this->state['control_mode'] = 'manual';
                        $this->setControlModeDB('manual');
                    }
                } elseif ($data['mode'] === 'MANUAL') {
                    $this->state['control_mode'] = 'manual';
                    $this->setControlModeDB('manual');
                }
            }
            
            if (!isset($data['power'])) {
                $basePower = ($this->state['lamps'][$lampId]['brightness'] / 100) * 6;
                $variation = rand(-3, 3) / 10;
                $this->state['lamps'][$lampId]['power'] = round(max(0, $basePower + $variation), 1);
            } else {
                $this->state['lamps'][$lampId]['power'] = round(max(0, (float)$data['power']), 1);
            }
            
            $this->saveState();
        }
    }

    public function setControlMode($mode)
    {
        $this->state['control_mode'] = $mode;
        $this->saveState();
        $this->setControlModeDB($mode);
    }

    // Sinkronisasi mode ke Database MySQL (tabel control_settings)
    private function setControlModeDB($mode)
    {
        try {
            DB::table('control_settings')
                ->updateOrInsert(
                    ['setting_key' => 'control_mode'],
                    ['setting_value' => $mode, 'updated_at' => now()]
                );
        } catch (\Exception $e) {
            // Abaikan jika tabel belum siap
        }
    }

    public function setSensorLightLevel($level)
    {
        $this->state['sensor_light_level'] = $level;
        $this->saveState();
    }

    // ✅ TERHUBUNG KE DATABASE MYSQl (tabel environment_histories) untuk Suhu & Kelembapan
    public function getEnvironmentData()
    {
        try {
            $latest = DB::table('environment_histories')->orderBy('created_at', 'desc')->first();

            if ($latest) {
                return [
                    'temperature' => (float)$latest->temperature,
                    'humidity'    => (float)$latest->humidity,
                    'timestamp'   => Carbon::parse($latest->created_at)->format('H:i:s')
                ];
            }
        } catch (\Exception $e) {
            // Fallback jika tabel belum di-query
        }

        return [
            'temperature' => 29.5,
            'humidity'    => 65.0,
            'timestamp'   => now()->format('H:i:s')
        ];
    }

    public function getLampData()
    {
        $lamps = $this->state['lamps'] ?? $this->getDefaultState()['lamps'];
        
        // Ambil mode kontrol terbaru dari database jika ada
        $dbMode = 'manual';
        try {
            $setting = DB::table('control_settings')->where('setting_key', 'control_mode')->first();
            if ($setting) {
                $dbMode = $setting->setting_value;
            }
        } catch (\Exception $e) {
            $dbMode = $this->state['control_mode'] ?? 'manual';
        }
        
        return [
            'lamp_1' => $lamps['lamp_1'] ?? [],
            'lamp_2' => $lamps['lamp_2'] ?? [],
            'lamp_3' => $lamps['lamp_3'] ?? [],
            'lamp_4' => $lamps['lamp_4'] ?? [],
            'sensor_light_level' => round($this->state['sensor_light_level'] ?? 0, 1),
            'control_mode' => $dbMode,
            'auto_settings' => $this->getAutoSettings(),
            'timestamp' => now()->format('H:i:s'),
        ];
    }

    public function getWasteData()
    {
        $waste = $this->state['waste'] ?? $this->getDefaultState()['waste'];
        return [
            'bin_1' => ['level' => $waste['bin_1']['level'], 'status' => $waste['bin_1']['level'] > 80 ? 'Penuh' : 'Normal', 'location' => $waste['bin_1']['location']],
            'bin_2' => ['level' => $waste['bin_2']['level'], 'status' => $waste['bin_2']['level'] > 80 ? 'Penuh' : 'Normal', 'location' => $waste['bin_2']['location']],
            'bin_3' => ['level' => $waste['bin_3']['level'], 'status' => $waste['bin_3']['level'] > 80 ? 'Penuh' : 'Normal', 'location' => $waste['bin_3']['location']],
            'bin_4' => ['level' => $waste['bin_4']['level'], 'status' => $waste['bin_4']['level'] > 80 ? 'Penuh' : 'Normal', 'location' => $waste['bin_4']['location']],
            'timestamp' => now()->format('H:i:s'),
        ];
    }

    public function getParkingData()
    {
        try {
            // Mengambil langsung dari tabel database MySQL smart_parkings
            $parkings = DB::table('smart_parkings')->get();

            if ($parkings->isNotEmpty()) {
                $totalSlots = $parkings->count();
                $occupiedCount = $parkings->where('status', 'occupied')->count();
                $availableCount = $totalSlots - $occupiedCount;

                $slots = [];
                foreach ($parkings as $p) {
                    $slots[$p->slot_code] = [
                        'status' => $p->status, // 'available' atau 'occupied'
                    ];
                }

                return [
                    'total_slots' => $totalSlots,
                    'occupied'    => $occupiedCount,
                    'available'   => $availableCount,
                    'slots'       => $slots,
                    'timestamp'   => now()->format('H:i:s'),
                ];
            }
        } catch (\Exception $e) {
            // Fallback jika terjadi kendala
        }

        // Fallback cadangan jika database kosong
        return [
            'total_slots' => 10,
            'occupied' => 4,
            'available' => 6,
            'timestamp' => now()->format('H:i:s'),
        ];
    }

    public function getDashboardSummary()
    {
        $lamps = $this->state['lamps'] ?? $this->getDefaultState()['lamps'];
        $waste = $this->state['waste'] ?? $this->getDefaultState()['waste'];
        $env = $this->getEnvironmentData();

        // Ambil data parkir langsung dari database MySQL agar sinkron dengan Smart Parking
        $parkingTotal = 10;
        $parkingOccupied = 4;
        try {
            $parkings = DB::table('smart_parkings')->get();
            if ($parkings->isNotEmpty()) {
                $parkingTotal = $parkings->count();
                $parkingOccupied = $parkings->where('status', 'occupied')->count();
            }
        } catch (\Exception $e) {
            // Fallback jika gagal query
        }
        $parkingAvailable = $parkingTotal - $parkingOccupied;

        $activeLamps = count(array_filter($lamps, fn($l) => $l['status'] === 1));
        $totalBins = count($waste);
        $alerts = count(array_filter($waste, fn($b) => $b['level'] > 80));

        return [
            'active_lamps' => $activeLamps,
            'total_bins' => $totalBins,
            'parking_available' => $parkingAvailable,
            'parking_occupied' => $parkingOccupied, // Tambahan agar dashboard tahu jumlah terisi
            'parking_total' => $parkingTotal,       // Tambahan total slot
            'alerts' => $alerts,
            'avg_lamp_brightness' => round(array_sum(array_column($lamps, 'brightness')) / 4),
            'avg_waste_level' => round(array_sum(array_column($waste, 'level')) / 4),
            'temperature' => $env['temperature'],
            'humidity' => $env['humidity'],
        ];
    }

    public function reEvaluateAutoLogic()
    {
        $settings = $this->getAutoSettings();
        $lightLevel = $this->state['sensor_light_level'] ?? 0;
        $controlMode = $this->state['control_mode'] ?? 'manual';
        
        if ($controlMode === 'auto_sensor' && ($settings['auto_sensor_enabled'] ?? false)) {
            $isDark = $lightLevel < $settings['sensor_threshold'];
            
            foreach ($this->state['lamps'] as &$lamp) {
                $lamp['status'] = $isDark ? 1 : 0;
                $lamp['brightness'] = $isDark ? 90 : 0;
                $lamp['power'] = round(($lamp['brightness'] / 100) * 6, 1);
            }
            
            $this->saveState();
            return true;
        }
        
        if ($controlMode === 'auto_schedule' && ($settings['auto_schedule_enabled'] ?? false)) {
            $currentHour = (int)now()->format('H');
            $currentMinute = (int)now()->format('i');
            $currentTime = ($currentHour * 60) + $currentMinute;
            
            $onTimeMinutes = ($settings['schedule_on_hour'] * 60) + $settings['schedule_on_minute'];
            $offTimeMinutes = ($settings['schedule_off_hour'] * 60) + $settings['schedule_off_minute'];
            
            $isNightTime = false;
            if ($onTimeMinutes > $offTimeMinutes) {
                $isNightTime = ($currentTime >= $onTimeMinutes) || ($currentTime < $offTimeMinutes);
            } else {
                $isNightTime = ($currentTime >= $onTimeMinutes) && ($currentTime < $offTimeMinutes);
            }
            
            foreach ($this->state['lamps'] as &$lamp) {
                $lamp['status'] = $isNightTime ? 1 : 0;
                $lamp['brightness'] = $isNightTime ? 85 : 0;
                $lamp['power'] = round(($lamp['brightness'] / 100) * 6, 1);
            }
            
            $this->saveState();
            return true;
        }
        
        return false;
    }
}