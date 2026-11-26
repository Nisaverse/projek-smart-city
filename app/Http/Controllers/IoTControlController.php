<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class IoTControlController extends Controller
{
    private const DEFAULT_DEVICE_ID = 'esp32s3-9C3B21AD4F7C';
    private const MQTT_HOST = 'broker.hivemq.com';
    private const MQTT_PORT = 1883;

    public function sendCommand(Request $request)
    {
        $device   = $request->input('device'); // 'lighting_mode', 'lamp', atau 'relay'
        $sector   = $request->input('sector'); // 1, 2, 3, 4 atau 'all'
        $action   = $request->input('action'); // true/false atau 'manual'/'auto'
        $deviceId = $request->input('device_id', self::DEFAULT_DEVICE_ID);

        try {
            // 1. Jika perintah mengubah mode kontrol (Manual / Otomatis)
            if ($device === 'lighting_mode' || in_array(strtolower((string) $action), ['manual', 'auto', 'auto_sensor', 'auto_schedule'], true)) {
                $rawMode = strtolower((string) $action);
                $espMode = ($rawMode === 'manual') ? 'manual' : 'auto';
                $laravelMode = ($espMode === 'manual') ? 'manual' : 'auto_sensor';

                Cache::put('control_mode', $laravelMode, now()->addDay());

                $payload = json_encode([
                    'type' => 'lighting_mode',
                    'mode' => $espMode,
                    'time' => now()->format('Y-m-d H:i:s'),
                ]);

                $this->publishToBroker($payload, $deviceId);

                return response()->json([
                    'status'        => 'success',
                    'mode'          => $laravelMode,
                    'lighting_mode' => $espMode,
                    'message'       => "Mode berhasil diubah ke {$espMode}.",
                ]);
            }

            // 2. Jika perintah kontrol relay/lampu sektor 1-4
            $sectorNum = (int) $sector;
            $isOn = filter_var($action, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($isOn === null) {
                $isOn = in_array(strtolower((string) $action), ['1', 'on', 'true'], true);
            }

            if ($sectorNum >= 1 && $sectorNum <= 4) {
                $sectorCode = 'L0' . $sectorNum;
                Cache::put('control_mode', 'manual', now()->addDay());
                Cache::put("smart_lamp_{$sectorCode}", [
                    'sector_code' => $sectorCode,
                    'status'      => $isOn ? 'on' : 'off',
                    'updated_at'  => now()->format('Y-m-d H:i:s'),
                ], now()->addDay());
            }

            $payload = json_encode([
                'relay'  => $sectorNum > 0 ? $sectorNum : $sector,
                'sector' => $sectorNum > 0 ? $sectorNum : $sector,
                'state'  => $isOn,
                'time'   => now()->format('Y-m-d H:i:s'),
            ]);

            $this->publishToBroker($payload, $deviceId);

            return response()->json([
                'status'  => 'success',
                'message' => "Perintah untuk Sektor {$sector} berhasil dikirim!",
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal terhubung ke MQTT Broker: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function publishToBroker(string $payload, string $deviceId): void
    {
        $clientId = 'laravel_control_admin_' . uniqid();
        $mqtt = new MqttClient(self::MQTT_HOST, self::MQTT_PORT, $clientId);
        $connectionSettings = (new ConnectionSettings())
            ->setConnectTimeout(3)
            ->setSocketTimeout(3);

        $mqtt->connect($connectionSettings, true);
        $mqtt->publish("aethersense/{$deviceId}/command", $payload, 0, false);
        $mqtt->publish('aethersense/command', $payload, 0, false);
        $mqtt->disconnect();
    }
}