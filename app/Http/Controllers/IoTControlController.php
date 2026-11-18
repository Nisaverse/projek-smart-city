<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class IoTControlController extends Controller
{
    // Fungsi untuk mengirim perintah kontrol (Lampu / Relay / Mode)
    public function sendCommand(Request $request)
    {
        $device = $request->input('device'); // contoh: 'lamp' atau 'relay'
        $sector = $request->input('sector'); // contoh: 1, 2, 3, 4 atau 'all'
        $action = $request->input('action'); // contoh: true/false atau 'ON'/'OFF'

        $server   = 'broker.hivemq.com';
        $port     = 1883;
        $clientId = 'laravel_control_admin_' . uniqid();

        try {
            $mqtt = new MqttClient($server, $port, $clientId);
            $connectionSettings = new ConnectionSettings();
            
            // Hubungkan ke broker publik
            $mqtt->connect($connectionSettings, true);

            // Tentukan topik tujuan (disesuaikan dengan format ESP32 temanmu)
            // Contoh topic command umum atau spesifik device
            $topic = "aethersense/command"; 

            // Format JSON yang cocok dengan fungsi handleMqttMessage di ESP32 temanmu
            // ESP32 temanmu mendeteksi kunci "sector" dan "state"
            $payload = json_encode([
                'sector' => $sector,
                'state'  => $action,
                'time'   => now()->format('Y-m-d H:i:s')
            ]);

            // Kirim pesan (Publish) ke MQTT broker
            $mqtt->publish($topic, $payload, 0);

            // Putuskan koneksi
            $mqtt->disconnect();

            return response()->json([
                'status'  => 'success',
                'message' => "Perintah untuk Sektor {$sector} berhasil dikirim!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal terhubung ke MQTT Broker: ' . $e->getMessage()
            ], 500);
        }
    }
}