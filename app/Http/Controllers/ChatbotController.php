<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IoTStateManager;

class DashboardController extends Controller
{
    private $stateManager;

    public function __construct(IoTStateManager $stateManager)
    {
        $this->stateManager = $stateManager;
    }

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

    public function apiLampData()
    {
        return response()->json($this->stateManager->getLampData());
    }

    // Menggantikan apiWasteData() untuk melayani data Suhu & Kelembapan (DHT Sensor)
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

    public function controlLamp(Request $request)
    {
        $validated = $request->validate([
            'lamp_id' => 'required|in:lamp_1,lamp_2,lamp_3,lamp_4',
            'status' => 'nullable|boolean',
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

    public function getAutoSettings()
    {
        return response()->json($this->stateManager->getAutoSettings());
    }

    public function updateAutoSettings(Request $request)
    {
        $validated = $request->validate([
            'schedule_on_hour' => 'required|integer|min:0|max:23',
            'schedule_on_minute' => 'required|integer|min:0|max:59',
            'schedule_off_hour' => 'required|integer|min:0|max:23',
            'schedule_off_minute' => 'required|integer|min:0|max:59',
            'sensor_threshold' => 'required|integer|min:0|max:100',
        ]);

        $this->stateManager->updateAutoSettings($validated);
        $this->stateManager->reEvaluateAutoLogic();
        return response()->json([
            'success' => true, 
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
            'success' => true,
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
            'success' => true,
            'auto_sensor_enabled' => $enabled,
        ]);
    }
}