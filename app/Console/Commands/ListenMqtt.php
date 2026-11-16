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

        if (str_contains($topic, 'telemetry') || isset($data['temperature_c']) || isset($data['water_level_cm']) || isset($data['jarak'])) {
            $this->handleEnvironmentMessage($topic, $data);
        }
        elseif (str_contains($topic, 'parking')) {
            $this->handleParkingMessage($topic, $data);
        }
        elseif (str_contains($topic, 'lamp')) {
            $this->handleLampMessage($topic, $data);
        } 
        else {
            $this->info("   📊 Received other data: " . json_encode($data));
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

            // =========================================================================
            // JALUR 1: KE WEBSITE (REAL-TIME TIAP 5 DETIK)
            // Setiap ada MQTT masuk, Cache langsung di-update tanpa syarat waktu.
            // Hasilnya: Website/Smart Monitoring gerak terus *live* tiap 5 detik.
            // =========================================================================
            Cache::put('latest_environment_data', [
                'temperature' => $temp,
                'humidity'    => $humidity,
                'air_quality' => $airQuality,
                'rain_status' => $rainStatus,
                'water_level' => $waterLevel,
                'updated_at'  => now()->format('Y-m-d H:i:s')
            ], now()->addMinutes(10));

            $this->info("   ⚡ JALUR 1 (WEB): Tampilan web diperbarui secara real-time ke Cache.");

            // =========================================================================
            // JALUR 2: KE DATABASE MYSQL (HANYA 1 JAM SEKALI)
            // Kita cek waktu terakhir data masuk database. Kalau belum 1 jam, diabaikan!
            // =========================================================================
            $lastLoggedTime = Cache::get('environment_last_logged_time');

            if (!$lastLoggedTime || now()->diffInMinutes($lastLoggedTime) >= 60) {
                EnvironmentLog::create([
                    'temperature' => $temp,
                    'humidity'    => $humidity,
                    'air_quality' => $airQuality,
                    'rain_status' => $rainStatus,
                    'water_level' => $waterLevel,
                ]);

                // Update penanda waktu agar database istirahat dulu selama 1 jam ke depan
                Cache::put('environment_last_logged_time', now(), now()->addHours(2));

                $this->info("   💾 JALUR 2 (DATABASE): Rekap data berhasil disimpan ke MySQL (Masuk 1 jam sekali)!");
            } else {
                $this->info("   ⏳ JALUR 2 (DATABASE): Melewati penyimpanan database (Belum 1 jam sejak log terakhir).");
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
        $this->info("   💡 Memproses data Smart Lamp...");
        try {
            $sectorCode = $data['sector_code'] ?? null;
            $status     = $data['status'] ?? null;

            if ($sectorCode && $status) {
                Cache::put("smart_lamp_{$sectorCode}", [
                    'sector_code' => $sectorCode,
                    'status'      => $status,
                    'updated_at'  => now()->format('Y-m-d H:i:s')
                ], now()->addMinutes(10));
                $this->info("   ⚡ LAMP: Sektor {$sectorCode} diperbarui jadi {$status}");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Gagal memproses lampu: " . $e->getMessage());
        }
    }
}