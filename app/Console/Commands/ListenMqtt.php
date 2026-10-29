<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Services\IoTStateManager;
use App\Models\EnvironmentLog;

class ListenMqtt extends Command
{
    // Menggunakan wildcard aethersense/# agar menangkap semua chip ID ESP32-S3 secara otomatis
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

        // Menggunakan broker publik yang sama persis dengan kodingan ESP32 Arduino kamu
        $server = 'broker.hivemq.com';
        $port = 1883;
        $clientId = 'laravel_aethersense_' . uniqid();
        $username = null;
        $password = null;

        try {
            // 1. Initialize MQTT Client
            $mqtt = new MqttClient($server, $port, $clientId);

            // 2. Setup Connection Settings (Tanpa TLS karena port 1883 standar)
            $connectionSettings = new ConnectionSettings();

            $this->info('🔐 Connecting to HiveMQ Public Broker...');
            $mqtt->connect($connectionSettings, true);
            $this->info('✅ Connected to broker successfully!');

            // 3. Subscribe
            $topic = $this->option('topic');
            $this->info("📋 Subscribing to: {$topic}");

            $mqtt->subscribe($topic, function (string $topic, string $message) {
                // Panggil processMessage untuk mengurai data
                $this->processMessage($topic, $message);
            }, 0);

            $this->info('✅ Successfully subscribed and listening for sensor telemetry...');
            $this->newLine();

            // 4. Loop
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
        $this->info("📨 [$timestamp] Topic: $topic");

        $data = json_decode($message, true);
        $isJson = (json_last_error() === JSON_ERROR_NONE);

        if (!$isJson) {
            $this->warn("   ⚠️ Pesan bukan format JSON yang valid");
            return;
        }

        // Cek apakah ini pesan telemetri dari sensor lingkungan AetherSense
        if (str_contains($topic, 'telemetry') || isset($data['temperature_c']) || isset($data['water_level_cm'])) {
            $this->handleEnvironmentMessage($topic, $data);
        } else {
            $this->info("   📊 Received other data: " . json_encode($data));
        }

        $this->newLine();
    }

    // 👉 MENANGKAP DATA TELEMETRI ESP32-S3 DAN MENYIMPANNYA KE DATABASE MYSQL
    private function handleEnvironmentMessage($topic, $data)
    {
        $this->info("   🌡️ Memproses data lingkungan & banjir ESP32-S3...");

        try {
            // Menyesuaikan kunci JSON persis dengan yang dikirim oleh skrip Arduino ESP32 kamu
            $temp       = $data['temperature_c'] ?? 28.5;
            $humidity   = $data['humidity_percent'] ?? 65.0;
            $airQuality = $data['mq135_raw'] ?? 40;
            $rainStatus = $data['rain_status'] ?? 'Kering';
            $waterLevel = $data['water_level_cm'] ?? 0.0;

            EnvironmentLog::create([
                'temperature' => $temp,
                'humidity'    => $humidity,
                'air_quality' => $airQuality,
                'rain_status' => $rainStatus,
                'water_level' => $waterLevel,
            ]);

            $this->info("   ✅ SUCCESS: Data tersimpan ke MySQL! [Suhu: {$temp}°C, Lembap: {$humidity}%, Air: {$waterLevel}cm, Hujan: {$rainStatus}]");
        } catch (\Exception $e) {
            $this->error("   ❌ Gagal menyimpan ke database: " . $e->getMessage());
        }
    }
}