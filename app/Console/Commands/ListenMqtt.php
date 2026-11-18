<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Services\IoTStateManager;
use App\Models\EnvironmentLog;
use Illuminate\Support\Facades\Cache;

class ListenMqtt extends Command
{
    protected $signature = 'mqtt:listen {--topic=aethersense/#}';
    protected $description = 'Listen to MQTT messages from AetherSense ESP32-S3 devices';

    private $stateManager;

    public function handle(IoTStateManager $stateManager)
    {
        $this->stateManager = $stateManager;

        $this->info('🚀 Starting AetherSense MQTT Listener...');
        $this->info('📡 Broker: broker.hivemq.com (Public Port 1883)');
        $this->info('📥 Topic: ' . $this->option('topic'));
        $this->info('Press Ctrl+C to stop');
        $this->newLine();

        $server = 'broker.hivemq.com';
        $port = 1883;
        $clientId = 'laravel_aethersense_' . uniqid();

        try {
            $mqtt = new MqttClient($server, $port, $clientId);
            $connectionSettings = new ConnectionSettings();

            $this->info('🔐 Connecting to HiveMQ Public Broker...');
            $mqtt->connect($connectionSettings, true);
            $this->info('✅ Connected to broker successfully!');

            $topic = $this->option('topic');
            $this->info("📋 Subscribing to: {$topic}");

            $mqtt->subscribe($topic, function (string $topic, string $message) {
                $this->processMessage($topic, $message);
            }, 0);

            $this->info('✅ Successfully subscribed and listening for sensor telemetry...');
            $this->newLine();

            $mqtt->loop(true);

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    public function processMessage($topic, $message)
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $this->info("📨 [$timestamp] Topic: $topic | Message: $message");

        $data = json_decode($message, true);
        $isJson = (json_last_error() === JSON_ERROR_NONE);

        if (!$isJson) {
            $this->warn("   ⚠️ Pesan bukan format JSON yang valid");
            return;
        }

        // 1. 💡 KHUSUS LAMPU: Jalankan jika payload JSON memiliki data relays atau relay1
        if (isset($data['relays']) || isset($data['relay1'])) {
            $this->handleLampMessage($topic, $data);
        }

        // 2. 🌡️ KHUSUS LINGKUNGAN: Jalankan jika ada data temperature
        if (str_contains($topic, 'telemetry') || isset($data['temperature_c'])) {
            $this->handleEnvironmentMessage($topic, $data);
        }

        // 3. 🅿️ KHUSUS PARKIR: Jalankan jika topik mengandung kata parking
        if (str_contains($topic, 'parking')) {
            $this->handleParkingMessage($topic, $data);
        }

        $this->newLine();
    }

    private function handleEnvironmentMessage($topic, $data)
    {
        $this->info("   🌡️ Memproses data lingkungan ESP32-S3...");

        try {
            $temp       = $data['temperature_c'] ?? 28.5;
            $humidity   = $data['humidity_percent'] ?? 65.0;
            $airQuality = $data['mq135_raw'] ?? 40;
            $rainStatus = $data['rain_status'] ?? 'Kering';
            $waterLevel = $data['water_level_cm'] ?? $data['water_level'] ?? $data['jarak'] ?? 0.0;

            Cache::put('latest_environment_data', [
                'temperature' => $temp,
                'humidity'    => $humidity,
                'air_quality' => $airQuality,
                'rain_status' => $rainStatus,
                'water_level' => $waterLevel,
                'updated_at'  => now()->format('Y-m-d H:i:s')
            ], now()->addMinutes(10));

            $this->info("   ⚡ JALUR 1 (WEB): Tampilan web diperbarui secara real-time ke Cache.");

            $lastLoggedTime = Cache::get('environment_last_logged_time');

            if (!$lastLoggedTime || now()->diffInMinutes($lastLoggedTime) >= 60) {
                EnvironmentLog::create([
                    'temperature' => $temp,
                    'humidity'    => $humidity,
                    'air_quality' => $airQuality,
                    'rain_status' => $rainStatus,
                    'water_level' => $waterLevel,
                ]);

                Cache::put('environment_last_logged_time', now(), now()->addHours(2));

                $this->info("   💾 JALUR 2 (DATABASE): Rekap data berhasil disimpan ke MySQL!");
            }

        } catch (\Exception $e) {
            $this->error("   ❌ Gagal memproses data lingkungan: " . $e->getMessage());
        }
    }

    private function handleParkingMessage($topic, $data)
    {
        $this->info("   🅿️ Memproses data Smart Parking...");
        try {
            $slotCode = $data['slot_code'] ?? null;
            $status   = $data['status'] ?? null;

            if ($slotCode && $status) {
                Cache::put("parking_slot_{$slotCode}", [
                    'slot_code' => $slotCode,
                    'status'    => $status,
                    'updated_at'=> now()->format('Y-m-d H:i:s')
                ], now()->addMinutes(10));
                $this->info("   ⚡ PARKING: Slot {$slotCode} diperbarui jadi {$status}");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Gagal memproses parkir: " . $e->getMessage());
        }
    }

  private function handleLampMessage($topic, $data)
    {
        $this->info("   💡 Menerima data JSON mentah untuk lampu:");
        $this->line(json_encode($data)); 

        try {
            $relays = $data['relays'] ?? $data;

            $mapping = [
                'relay1' => 'L01',
                'relay2' => 'L02',
                'relay3' => 'L03',
                'relay4' => 'L04'
            ];

            foreach ($mapping as $relayKey => $sectorCode) {
                $val = $relays[$relayKey] ?? ($data[$relayKey] ?? null);

                // CETAK KE TERMINAL: Supaya kita tahu persis nilai dari ESP32 itu apa
                $this->info("      -> Cek {$relayKey} ({$sectorCode}): " . var_export($val, true));

                if ($val !== null) {
                    // Paksa ubah ke boolean true jika nilainya true, 'true', 1, atau '1'
                    $isOn = ($val === true || $val === 'true' || $val === 1 || $val === '1' || strtolower((string)$val) === 'on');
                    $statusStr = $isOn ? 'on' : 'off';

                    \Illuminate\Support\Facades\Cache::forget("smart_lamp_{$sectorCode}");
                    \Illuminate\Support\Facades\Cache::put("smart_lamp_{$sectorCode}", [
                        'sector_code' => $sectorCode,
                        'status'      => $statusStr,
                        'updated_at'  => now()->format('Y-m-d H:i:s')
                    ], now()->addDay());
                }
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Error: " . $e->getMessage());
        }
    }
}