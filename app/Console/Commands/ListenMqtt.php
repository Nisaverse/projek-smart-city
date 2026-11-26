<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Services\IoTStateManager;
use App\Models\EnvironmentLog;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ListenMqtt extends Command
{
    protected $signature = 'mqtt:listen {--topic=aethersense/#}';
    protected $description = 'Listen to MQTT messages from AetherSense ESP32-S3 devices';

    private $stateManager;

    public function handle(IoTStateManager $stateManager)
    {
        $this->stateManager = $stateManager;

        $this->info('Starting AetherSense MQTT Listener...');
        $this->info('Broker: broker.hivemq.com (Public Port 1883)');
        $this->info('Topic: ' . $this->option('topic'));
        $this->info('Press Ctrl+C to stop');
        $this->newLine();

        $server = 'broker.hivemq.com';
        $port = 1883;
        $clientId = 'laravel_aethersense_' . uniqid();

        try {
            $mqtt = new MqttClient($server, $port, $clientId);
            $connectionSettings = new ConnectionSettings();

            $this->info('Connecting to HiveMQ Public Broker...');
            $mqtt->connect($connectionSettings, true);
            $this->info('Connected to broker successfully!');

            $topic = $this->option('topic');
            $this->info("Subscribing to: {$topic}");

            $mqtt->subscribe($topic, function (string $topic, string $message) {
                $this->processMessage($topic, $message);
            }, 0);

            $this->info('Successfully subscribed and listening for sensor telemetry...');
            $this->newLine();

            $mqtt->loop(true);
        } catch (\Throwable $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    public function processMessage($topic, $message)
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $this->info("[{$timestamp}] Topic: {$topic} | Message: {$message}");

        $data = json_decode($message, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            $this->warn("   Pesan bukan format JSON yang valid");
            return;
        }

        if (isset($data['relays']) || isset($data['relay1'])) {
            $this->handleLampMessage($topic, $data);
        }

        if (str_contains($topic, 'telemetry') || isset($data['temperature_c'])) {
            $this->handleEnvironmentMessage($topic, $data);
        }

        if (
            str_contains($topic, 'parking') ||
            array_key_exists('slot_code', $data) ||
            array_key_exists('parking_total_slots', $data) ||
            array_key_exists('parking_occupied_slots', $data) ||
            array_key_exists('parking_available_slots', $data)
        ) {
            $this->handleParkingMessage($topic, $data);
        }

        $this->newLine();
    }

    private function handleEnvironmentMessage($topic, $data)
    {
        $this->info("   Memproses data lingkungan ESP32-S3...");

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

            $this->info("   JALUR 1 (WEB): Cache lingkungan diperbarui.");

            $lastLoggedRaw = Cache::get('environment_last_logged_time');
            $lastLoggedTime = null;

            if ($lastLoggedRaw instanceof \DateTimeInterface) {
                $lastLoggedTime = Carbon::instance($lastLoggedRaw);
            } elseif (is_string($lastLoggedRaw) && $lastLoggedRaw !== '') {
                try {
                    $lastLoggedTime = Carbon::parse($lastLoggedRaw);
                } catch (\Throwable $ignored) {
                    $lastLoggedTime = null;
                }
            }

            if (!$lastLoggedTime || $lastLoggedTime->diffInMinutes(now()) >= 60) {
                EnvironmentLog::create([
                    'temperature' => $temp,
                    'humidity'    => $humidity,
                    'air_quality' => $airQuality,
                    'rain_status' => $rainStatus,
                    'water_level' => $waterLevel,
                ]);

                Cache::put('environment_last_logged_time', now()->format('Y-m-d H:i:s'), now()->addHours(2));
                $this->info("   JALUR 2 (DATABASE): Rekap data disimpan ke MySQL.");
            }
        } catch (\Throwable $e) {
            $this->error("   Gagal memproses data lingkungan: " . $e->getMessage());
        }
    }

    private function handleParkingMessage($topic, $data)
    {
        $this->info("   [PARKIR] Memproses data Smart Parking...");

        try {
            $slotCode = $data['slot_code'] ?? null;
            $status   = $data['status'] ?? null;

            if ($slotCode && $status) {
                Cache::put("parking_slot_{$slotCode}", [
                    'slot_code'  => $slotCode,
                    'status'     => $status,
                    'updated_at' => now()->format('Y-m-d H:i:s')
                ], now()->addDay());

                $this->info("   [PARKIR SLOT] {$slotCode} -> {$status}");
            }

            if (
                array_key_exists('parking_total_slots', $data) ||
                array_key_exists('parking_occupied_slots', $data) ||
                array_key_exists('parking_available_slots', $data)
            ) {
                $total     = (int) ($data['parking_total_slots'] ?? 10);
                $occupied  = (int) ($data['parking_occupied_slots'] ?? 0);
                $available = (int) ($data['parking_available_slots'] ?? max(0, $total - $occupied));
                $isFull    = (bool) ($data['is_parking_full'] ?? ($available <= 0));
                $nowStr    = now()->format('Y-m-d H:i:s');

                Cache::put('smart_parking_summary', [
                    'total_slots'     => $total,
                    'occupied_slots'  => $occupied,
                    'available_slots' => $available,
                    'is_parking_full' => $isFull,
                    'entry_gate_open' => (bool) ($data['entry_gate_open'] ?? false),
                    'exit_gate_open'  => (bool) ($data['exit_gate_open'] ?? false),
                    'ir_entry'        => (bool) ($data['ir_entry_detected'] ?? false),
                    'ir_exit'         => (bool) ($data['ir_exit_detected'] ?? false),
                    'updated_at'      => $nowStr
                ], now()->addDay());

                $this->info("   [PARKIR SUMMARY] Tersedia {$available}/{$total} (Terisi: {$occupied}) @ {$nowStr}");
            }
        } catch (\Throwable $e) {
            $this->error("   [PARKIR ERROR] " . $e->getMessage());
        }
    }

    private function handleLampMessage($topic, $data)
    {
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
                if ($val !== null) {
                    $isOn = ($val === true || $val === 'true' || $val === 1 || $val === '1' || strtolower((string)$val) === 'on');
                    $statusStr = $isOn ? 'on' : 'off';

                    Cache::put("smart_lamp_{$sectorCode}", [
                        'sector_code' => $sectorCode,
                        'status'      => $statusStr,
                        'updated_at'  => now()->format('Y-m-d H:i:s')
                    ], now()->addDay());
                }
            }
        } catch (\Throwable $e) {
            $this->error("   [LAMPU ERROR] " . $e->getMessage());
        }
    }
}