<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
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
        } else {
            $this->state = $this->getDefaultState();
            $this->saveState();
        }

        if (!isset($this->state['parking'])) {
            $this->state['parking'] = [
                'total_slots' => 10,
            ];
            $this->saveState();
        }

        if (!isset($this->state['auto_settings'])) {
            $this->state['auto_settings'] = $this->getDefaultState()['auto_settings'];
            $this->saveState();
        }
    }

    private function getDefaultState()
    {
        return [
            'updated_at' => now()->toIso8601String(),
            'lamps' => [
                'L01' => ['name' => 'Sektor Utara', 'status' => 'active', 'updated_at' => now()->format('H:i:s')],
                'L02' => ['name' => 'Sektor Selatan', 'status' => 'inactive', 'updated_at' => now()->format('H:i:s')],
                'L03' => ['name' => 'Sektor Timur', 'status' => 'active', 'updated_at' => now()->format('H:i:s')],
                'L04' => ['name' => 'Sektor Barat', 'status' => 'inactive', 'updated_at' => now()->format('H:i:s')],
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

    public function saveState()
    {
        $this->state['updated_at'] = now()->toIso8601String();
        Storage::put($this->stateFile, json_encode($this->state, JSON_PRETTY_PRINT));
    }

    public function getState()
    {
        return $this->state;
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

    public function updateLamp($lampId, $data)
    {
        if (isset($this->state['lamps'][$lampId])) {
            foreach ($data as $key => $value) {
                if (isset($this->state['lamps'][$lampId][$key])) {
                    $this->state['lamps'][$lampId][$key] = $value;
                }
            }
            $this->saveState();

            Cache::put("smart_lamp_{$lampId}", [
                'sector_code' => $lampId,
                'status' => $this->state['lamps'][$lampId]['status'],
                'updated_at' => now()->format('Y-m-d H:i:s')
            ], now()->addMinutes(10));
        }
    }

    public function setControlMode($mode)
    {
        $this->state['control_mode'] = $mode;
        $this->saveState();
    }

    public function setSensorLightLevel($level)
    {
        $this->state['sensor_light_level'] = $level;
        $this->saveState();
    }

    // TAMBAHKAN INI SUPAYA ERROR MERAH DI CONTROLLER HILANG
    public function updateEnvironmentData(array $data)
    {
        if (!isset($this->state['environment'])) {
            $this->state['environment'] = [];
        }
        $this->state['environment'] = array_merge($this->state['environment'], $data);
        $this->saveState();
    }

    public function getEnvironmentData()
    {
        try {
            $latest = \App\Models\EnvironmentLog::latest('created_at')->first();

            if ($latest) {
                return [
                    'has_data'    => true,
                    'temperature' => (float)$latest->temperature,
                    'humidity'    => (float)$latest->humidity,
                    'air_quality' => (int)($latest->air_quality ?? 0),
                    'rain_status' => $latest->rain_status ?? 'Cerah',
                    'water_level' => (float)($latest->water_level ?? 0),
                    'timestamp'   => $latest->created_at->format('H:i:s')
                ];
            }
        } catch (\Exception $e) {}

        return [
            'has_data'    => false,
            'temperature' => '--',
            'humidity'    => '--',
            'air_quality' => '--',
            'rain_status' => 'Menunggu data',
            'water_level' => '--',
            'timestamp'   => now()->format('H:i:s')
        ];
    }

    public function getLampData()
    {
        $sectorKeys = ['L01', 'L02', 'L03', 'L04'];
        $result = [];

        foreach ($sectorKeys as $sectorId) {
            $cachedData = Cache::get("smart_lamp_{$sectorId}");
            
            if ($cachedData) {
                $result[$sectorId] = [
                    'name'       => $this->state['lamps'][$sectorId]['name'] ?? "Sektor {$sectorId}",
                    'status'     => $cachedData['status'],
                    'updated_at' => $cachedData['updated_at']
                ];
            } else {
                $result[$sectorId] = [
                    'name'       => $this->state['lamps'][$sectorId]['name'] ?? "Sektor {$sectorId}",
                    'status'     => 'inactive',
                    'updated_at' => null
                ];
            }
        }

        $result['control_mode'] = $this->state['control_mode'] ?? 'manual';
        $result['timestamp']    = now()->format('H:i:s');

        return $result;
    }

    public function getParkingData()
    {
        $slotKeys = ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P08', 'P09', 'P10'];
        $slots = [];
        $occupiedCount = 0;
        $availableCount = 0;

        foreach ($slotKeys as $slotId) {
            $cachedData = Cache::get("parking_slot_{$slotId}");

            if ($cachedData) {
                $status = $cachedData['status'];
                $slots[$slotId] = [
                    'status'     => $status,
                    'updated_at' => $cachedData['updated_at']
                ];

                $rawStatus = strtolower((string)$status);
                if ($rawStatus === 'occupied' || $rawStatus === '1' || $rawStatus === 'true' || $status === true) {
                    $occupiedCount++;
                } else {
                    $availableCount++;
                }
            } else {
                $slots[$slotId] = [
                    'status'     => 'available',
                    'updated_at' => null
                ];
                $availableCount++;
            }
        }

        return [
            'total_slots' => count($slotKeys), // Diperbaiki dari count(names: ...)
            'occupied'    => $occupiedCount,
            'available'   => $availableCount,
            'slots'       => $slots,
            'timestamp'   => now()->format('H:i:s'),
        ];
    }

    public function getDashboardSummary()
    {
        $env = $this->getEnvironmentData();
        $parking = $this->getParkingData();
        $lampData = $this->getLampData();

        $activeLamps = 0;
        foreach ($lampData as $key => $val) {
            if ($key !== 'control_mode' && $key !== 'timestamp' && is_array($val)) {
                $st = strtolower((string)($val['status'] ?? ''));
                if ($st === 'active' || $st === 'on' || $st === '1' || $val['status'] === true) {
                    $activeLamps++;
                }
            }
        }

        return [
            'active_lamps'      => $activeLamps,
            'parking_available' => $parking['available'],
            'parking_occupied'  => $parking['occupied'],
            'parking_total'     => $parking['total_slots'],
            'temperature'       => $env['temperature'],
            'humidity'          => $env['humidity'],
        ];
    }

    public function reEvaluateAutoLogic()
    {
        $settings = $this->getAutoSettings();
        $lightLevel = $this->state['sensor_light_level'] ?? 0;
        $controlMode = $this->state['control_mode'] ?? 'manual';
        
        if ($controlMode === 'auto_sensor' && ($settings['auto_sensor_enabled'] ?? false)) {
            $isDark = $lightLevel < $settings['sensor_threshold'];
            
            foreach ($this->state['lamps'] as $lampId => &$lamp) {
                $lamp['status'] = $isDark ? 'active' : 'inactive';
                Cache::put("smart_lamp_{$lampId}", [
                    'sector_code' => $lampId,
                    'status' => $lamp['status'],
                    'updated_at' => now()->format('Y-m-d H:i:s')
                ], now()->addMinutes(10));
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
            
            foreach ($this->state['lamps'] as $lampId => &$lamp) {
                $lamp['status'] = $isNightTime ? 'active' : 'inactive';
                Cache::put("smart_lamp_{$lampId}", [
                    'sector_code' => $lampId,
                    'status' => $lamp['status'],
                    'updated_at' => now()->format('Y-m-d H:i:s')
                ], now()->addMinutes(10));
            }
            
            $this->saveState();
            return true;
        }
        
        return false;
    }
}