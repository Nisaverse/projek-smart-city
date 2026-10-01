@extends('layouts.app')
@section('title', 'Control Center')
@section('page-title', 'IoT Device Control Center')

@section('content')
<!-- MODE SELECTOR -->
<div class="data-card mb-4" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
    <h6 class="text-white mb-2" style="font-size: 0.95rem; font-weight: 600;"><i class="fas fa-cogs text-primary me-2"></i> Control Mode</h6>
    <p style="color:#94a3b8; font-size:0.85rem; margin-bottom:15px;">
        Pilih mode operasi lampu. Mode otomatis akan mengontrol lampu berdasarkan jadwal atau sensor cahaya.
    </p>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="mode-card" id="mode-manual" onclick="handleSetMode('manual')" style="cursor:pointer; padding:15px; border-radius:12px; border:2px solid #3b82f6; background:rgba(59,130,246,0.15); color:#ffffff;">
                <div style="font-size:1.5rem; margin-bottom:8px;">🔧</div>
                <div style="font-weight:700; margin-bottom:5px; color:#ffffff;">Manual Control</div>
                <div style="font-size:0.8rem; color:#94a3b8;">Kontrol penuh via panel ini</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mode-card" id="mode-auto-schedule" onclick="handleSetMode('auto_schedule')" style="cursor:pointer; padding:15px; border-radius:12px; border:2px solid rgba(255,255,255,0.1); background:rgba(15, 23, 42, 0.6); color:#ffffff;">
                <div style="font-size:1.5rem; margin-bottom:8px;">🕐</div>
                <div style="font-weight:700; margin-bottom:5px; color:#ffffff;">Auto Schedule</div>
                <div style="font-size:0.8rem; color:#94a3b8;">Nyala sesuai jadwal (bisa diatur)</div>

                <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 12px; background:rgba(0,0,0,0.3); border-radius:8px; margin-top:10px;">
                    <span style="font-size:0.75rem; color:#cbd5e1;">Enabled</span>
                    <label class="toggle-switch flex-shrink-0" style="margin:0;">
                        <input type="checkbox" id="toggleAutoSchedule" onchange="handleToggleAutoSchedule(this.checked); event.stopPropagation();">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mode-card" id="mode-auto-sensor" onclick="handleSetMode('auto_sensor')" style="cursor:pointer; padding:15px; border-radius:12px; border:2px solid rgba(255,255,255,0.1); background:rgba(15, 23, 42, 0.6); color:#ffffff;">
                <div style="font-size:1.5rem; margin-bottom:8px;">🌩️</div>
                <div style="font-weight:700; margin-bottom:5px; color:#ffffff;">Auto Sensor</div>
                <div style="font-size:0.8rem; color:#94a3b8;">Nyala saat gelap (threshold bisa diatur)</div>

                <div style="display:flex; justify-content:space-between; align-items:center; padding:8px 12px; background:rgba(0,0,0,0.3); border-radius:8px; margin-top:10px;">
                    <span style="font-size:0.75rem; color:#cbd5e1;">Enabled</span>
                    <label class="toggle-switch flex-shrink-0" style="margin:0;">
                        <input type="checkbox" id="toggleAutoSensor" onchange="handleToggleAutoSensor(this.checked); event.stopPropagation();">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AUTO SETTINGS PANEL -->
<div class="data-card mb-4" id="autoSettingsPanel" style="display:none; background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
        <h6 class="text-white m-0" style="font-size: 0.95rem; font-weight: 600;"><i class="fas fa-sliders-h text-warning me-2"></i> Auto Mode Settings</h6>
        <button onclick="handleSaveSettings()" class="btn btn-sm btn-success fw-semibold" style="border-radius: 8px;">
            <i class="fas fa-save me-1"></i> Save Settings
        </button>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6" id="scheduleSettings">
            <h6 style="color:#fbbf24; margin-bottom:12px; font-weight:600;"><i class="fas fa-clock me-1"></i> Schedule Settings</h6>
            <div class="row g-3">
                <div class="col-6">
                    <label style="font-size:0.85rem; color:#94a3b8;">Turn ON Time</label>
                    <div style="display:flex; gap:8px; margin-top:5px;">
                        <input type="number" id="onHour" min="0" max="23" class="form-control text-white" placeholder="HH" style="flex:1; background:rgba(15, 23, 42, 0.6); border:1px solid rgba(255,255,255,0.15);">
                        <span style="align-self:center; font-weight:700; color:#ffffff;">:</span>
                        <input type="number" id="onMinute" min="0" max="59" class="form-control text-white" placeholder="MM" style="flex:1; background:rgba(15, 23, 42, 0.6); border:1px solid rgba(255,255,255,0.15);">
                    </div>
                </div>
                <div class="col-6">
                    <label style="font-size:0.85rem; color:#94a3b8;">Turn OFF Time</label>
                    <div style="display:flex; gap:8px; margin-top:5px;">
                        <input type="number" id="offHour" min="0" max="23" class="form-control text-white" placeholder="HH" style="flex:1; background:rgba(15, 23, 42, 0.6); border:1px solid rgba(255,255,255,0.15);">
                        <span style="align-self:center; font-weight:700; color:#ffffff;">:</span>
                        <input type="number" id="offMinute" min="0" max="59" class="form-control text-white" placeholder="MM" style="flex:1; background:rgba(15, 23, 42, 0.6); border:1px solid rgba(255,255,255,0.15);">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6" id="sensorSettings" style="display:none;">
            <h6 style="color:#a78bfa; margin-bottom:12px; font-weight:600;"><i class="fas fa-sun me-1"></i> Sensor Settings</h6>
            <div>
                <label style="font-size:0.85rem; color:#94a3b8;">Light Threshold (0-100)</label>
                <input type="range" id="sensorThreshold" min="0" max="100" class="form-range" style="margin-top:10px;" oninput="document.getElementById('thresholdValue').textContent = this.value">
                <div style="text-align:center; margin-top:8px;">
                    <span style="font-size:1.2rem; font-weight:700; color:#a78bfa;" id="thresholdValue">35</span>
                    <span style="color:#94a3b8; font-size:0.85rem;"> / 100</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.75rem; color:#94a3b8; margin-top:5px;">
                    <span>Terang</span>
                    <span>Gelap</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SMART LAMP CONTROL -->
<div class="data-card mb-4" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
    <h6 class="text-white mb-3" style="font-size: 0.95rem; font-weight: 600;"><i class="fas fa-lightbulb text-primary me-2"></i> Smart Lamp Control</h6>
    <div class="alert" id="modeAlert" style="background:rgba(59,130,246,0.15); border:1px solid rgba(59,130,246,0.4); color:#60a5fa; font-size:0.85rem; margin-bottom:15px; border-radius:10px;">
        <i class="fas fa-info-circle me-1"></i> Mode aktif: <strong id="currentModeLabel" class="text-white">Manual Control</strong>
    </div>
    <div class="row g-3" id="lampControlContainer">
        <div class="col-12 text-center" style="color: #94a3b8;">Loading...</div>
    </div>
</div>

<!-- QUICK ACTIONS -->
<div class="data-card" id="quickActions" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
    <h6 class="text-white mb-3" style="font-size: 0.95rem; font-weight: 600;"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions</h6>
    <div class="row g-3">
        <div class="col-md-4">
            <button class="btn btn-success w-100 fw-semibold" style="border-radius:10px; padding:10px;" onclick="handleTurnOnAll()">
                <i class="fas fa-power-off me-1"></i> Turn ON All Lamps
            </button>
        </div>
        <div class="col-md-4">
            <button class="btn btn-danger w-100 fw-semibold" style="border-radius:10px; padding:10px;" onclick="handleTurnOffAll()">
                <i class="fas fa-power-off me-1"></i> Turn OFF All Lamps
            </button>
        </div>
        <div class="col-md-4">
            <button class="btn btn-warning w-100 fw-semibold text-dark" style="border-radius:10px; padding:10px;" onclick="handleSetAllBrightness(75)">
                <i class="fas fa-sliders-h me-1"></i> Set All to 75%
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .mode-card { transition: all 0.3s ease; }
    .mode-card:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(59,130,246,0.3); }
    .mode-card.active { border-color: #3b82f6 !important; background: rgba(59,130,246,0.2) !important; box-shadow: 0 0 20px rgba(59,130,246,0.4); }
    .lamp-card-disabled { opacity: 0.5; pointer-events: none; filter: grayscale(0.8); }

    /* PENYESUAIAN TOGGLE SWITCH PAS DAN PRESISI */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 22px;
        flex-shrink: 0;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #475569;
        transition: 0.3s;
        border-radius: 22px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px; width: 16px;
        left: 3px; bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }
    input:checked + .toggle-slider { background-color: #3b82f6; }
    input:checked + .toggle-slider:before { transform: translateX(22px); }
</style>
@endpush

@push('scripts')
<script>
    let currentMode = 'manual';
    let autoSettings = {};
    const lampNames = {
        lamp_1: 'Street Lamp A - Jl. Sudirman',
        lamp_2: 'Street Lamp B - Jl. Thamrin',
        lamp_3: 'Street Lamp C - Jl. Gatot Subroto',
        lamp_4: 'Street Lamp D - Jl. Rasuna Said'
    };

    // ===== 1. LOAD SETTINGS =====
    function loadAutoSettings() {
        fetch('/api/auto-settings')
            .then(r => r.json())
            .then(data => {
                autoSettings = data;
                document.getElementById('onHour').value = data.schedule_on_hour || 17;
                document.getElementById('onMinute').value = data.schedule_on_minute || 30;
                document.getElementById('offHour').value = data.schedule_off_hour || 6;
                document.getElementById('offMinute').value = data.schedule_off_minute || 0;
                document.getElementById('sensorThreshold').value = data.sensor_threshold || 35;
                document.getElementById('thresholdValue').textContent = data.sensor_threshold || 35;
                
                const scheduleToggle = document.getElementById('toggleAutoSchedule');
                if (scheduleToggle) scheduleToggle.checked = data.auto_schedule_enabled ?? true;
                
                const sensorToggle = document.getElementById('toggleAutoSensor');
                if (sensorToggle) sensorToggle.checked = data.auto_sensor_enabled ?? false;
            })
            .catch(err => console.error('Error loading settings:', err));
    }

    // ===== 2. TOGGLE HANDLERS =====
    function handleToggleAutoSchedule(enabled) {
        fetch('/api/auto-schedule/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ enabled: enabled })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                console.log('Auto Schedule is now:', enabled ? 'ON' : 'OFF');
                loadAutoSettings();
            }
        })
        .catch(err => {
            console.error('Error:', err);
            document.getElementById('toggleAutoSchedule').checked = !enabled;
        });
    }

    function handleToggleAutoSensor(enabled) {
        fetch('/api/auto-sensor/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ enabled: enabled })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                console.log('Auto Sensor is now:', enabled ? 'ON' : 'OFF');
                loadAutoSettings();
            }
        })
        .catch(err => {
            console.error('Error:', err);
            document.getElementById('toggleAutoSensor').checked = !enabled;
        });
    }

    // ===== 3. MODE SELECTION =====
    function handleSetMode(mode) {
        currentMode = mode;
        
        document.querySelectorAll('.mode-card').forEach(card => {
            card.classList.remove('active');
            card.style.borderColor = 'rgba(255,255,255,0.1)';
            card.style.background = 'rgba(15, 23, 42, 0.6)';
        });
        
        const activeCard = document.getElementById('mode-' + mode.replace('_', '-'));
        if (activeCard) {
            activeCard.classList.add('active');
            activeCard.style.borderColor = '#3b82f6';
            activeCard.style.background = 'rgba(59,130,246,0.2)';
        }

        document.getElementById('autoSettingsPanel').style.display = (mode !== 'manual') ? 'block' : 'none';
        document.getElementById('scheduleSettings').style.display = (mode === 'auto_schedule') ? 'block' : 'none';
        document.getElementById('sensorSettings').style.display = (mode === 'auto_sensor') ? 'block' : 'none';

        const modeLabels = {
            'manual': 'Manual Control - Anda mengontrol lampu secara langsung',
            'auto_schedule': `Auto Schedule - Lampu nyala ${autoSettings.schedule_on_hour || 17}:${String(autoSettings.schedule_on_minute || 30).padStart(2,'0')} - ${autoSettings.schedule_off_hour || 6}:${String(autoSettings.schedule_off_minute || 0).padStart(2,'0')}`,
            'auto_sensor': `Auto Sensor - Lampu nyala saat cahaya < ${autoSettings.sensor_threshold || 35}`
        };
        document.getElementById('currentModeLabel').textContent = modeLabels[mode];

        const lampContainer = document.getElementById('lampControlContainer');
        if (lampContainer) lampContainer.classList.toggle('lamp-card-disabled', mode !== 'manual');
        document.getElementById('quickActions').style.display = (mode === 'manual') ? 'block' : 'none';

        fetch('/api/control/mode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ mode: mode })
        })
        .then(r => r.json())
        .then(data => {
            loadAutoSettings();
            fetchLampData();
        })
        .catch(err => console.error('Error setting mode:', err));
    }

    // ===== 4. SAVE SETTINGS =====
    function handleSaveSettings() {
        const settings = {
            schedule_on_hour: parseInt(document.getElementById('onHour').value) || 17,
            schedule_on_minute: parseInt(document.getElementById('onMinute').value) || 30,
            schedule_off_hour: parseInt(document.getElementById('offHour').value) || 6,
            schedule_off_minute: parseInt(document.getElementById('offMinute').value) || 0,
            sensor_threshold: parseInt(document.getElementById('sensorThreshold').value) || 35
        };

        fetch('/api/auto-settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(settings)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                autoSettings = data.settings;
                alert('✅ Settings berhasil disimpan!');
                handleSetMode(currentMode);
            }
        })
        .catch(err => alert('❌ Gagal menyimpan settings'));
    }

    // ===== 5. LAMP CONTROL =====
    function handleToggleLamp(lampId, isOn) {
        if (currentMode !== 'manual') { alert('Switch to Manual mode to control lamps'); return; }
        fetch('/api/lamp/control', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ lamp_id: lampId, status: isOn ? 1 : 0 })
        }).then(() => fetchLampData());
    }

    function handleSetBrightness(lampId, brightness) {
        if (currentMode !== 'manual') { alert('Switch to Manual mode to control lamps'); return; }
        fetch('/api/lamp/control', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ lamp_id: lampId, brightness: parseInt(brightness) })
        }).then(() => fetchLampData());
    }

    function handleTurnOnAll() {
        if (currentMode !== 'manual') { alert('Switch to Manual mode'); return; }
        Object.keys(lampNames).forEach(lampId => handleToggleLamp(lampId, true));
    }

    function handleTurnOffAll() {
        if (currentMode !== 'manual') { alert('Switch to Manual mode'); return; }
        Object.keys(lampNames).forEach(lampId => handleToggleLamp(lampId, false));
    }

    function handleSetAllBrightness(value) {
        if (currentMode !== 'manual') { alert('Switch to Manual mode'); return; }
        Object.keys(lampNames).forEach(lampId => handleSetBrightness(lampId, value));
    }

    // ===== 6. RENDER & FETCH (SUDAH DIPERBAIKI HEADER LOKASI KARTU LAMPU) =====
    function renderLampControl(data) {
        const container = document.getElementById('lampControlContainer');
        if (!container) return;
        container.innerHTML = '';
        const isManual = currentMode === 'manual';

        Object.keys(lampNames).forEach((key) => {
            const lamp = data[key];
            const isOn = lamp.status === 1;
            const color = isOn ? '#fbbf24' : '#64748b';
            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-3';
            card.innerHTML = `
                <div class="stat-card" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
                    <!-- D-FLEX KELOLA SPACE & CUT OFF TEXT NAMA JALAN -->
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                        <h6 class="m-0 fw-bold text-white text-truncate" style="max-width: 130px; font-size: 0.85rem;" title="${lampNames[key]}">${lampNames[key]}</h6>
                        <label class="toggle-switch flex-shrink-0">
                            <input type="checkbox" ${isOn ? 'checked' : ''} ${!isManual ? 'disabled' : ''} onchange="handleToggleLamp('${key}', this.checked)">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div style="margin-bottom:15px;">
                        <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:#94a3b8; margin-bottom:5px;">
                            <span>Brightness</span><span style="color:${color}; font-weight:600;">${lamp.brightness}%</span>
                        </div>
                        <input type="range" min="0" max="100" value="${lamp.brightness}" class="form-range" ${!isManual ? 'disabled' : ''} onchange="handleSetBrightness('${key}', this.value)">
                        <div style="text-align:center; font-size:0.75rem; color:#94a3b8; margin-top:5px;">${lamp.brightness}%</div>
                    </div>
                    <div style="font-size:0.85rem; color:#94a3b8;"><i class="fas fa-bolt me-1"></i> Power: ${lamp.power}W</div>
                    ${!isManual ? '<div style="margin-top:10px; font-size:0.75rem; color:#fbbf24;"><i class="fas fa-robot me-1"></i> Auto-controlled</div>' : ''}
                </div>
            `;
            container.appendChild(card);
        });
    }

    function fetchLampData() {
        fetch('/api/lamp')
            .then(r => r.json())
            .then(data => renderLampControl(data))
            .catch(err => console.error('Error fetching lamp data:', err));
    }

    // ===== 7. INIT =====
    document.addEventListener('DOMContentLoaded', () => {
        loadAutoSettings();
        fetchLampData();
        loadActiveMode();
    });

    setInterval(fetchLampData, 15000);

    function applyModeToUI(mode) {
        currentMode = mode;
        
        document.querySelectorAll('.mode-card').forEach(card => {
            card.classList.remove('active');
            card.style.borderColor = 'rgba(255,255,255,0.1)';
            card.style.background = 'rgba(15, 23, 42, 0.6)';
        });
        
        const activeCardId = 'mode-' + mode.replace('_', '-');
        const activeCard = document.getElementById(activeCardId);
        if (activeCard) {
            activeCard.classList.add('active');
            activeCard.style.borderColor = '#3b82f6';
            activeCard.style.background = 'rgba(59,130,246,0.2)';
        }

        document.getElementById('autoSettingsPanel').style.display = (mode !== 'manual') ? 'block' : 'none';
        document.getElementById('scheduleSettings').style.display = (mode === 'auto_schedule') ? 'block' : 'none';
        document.getElementById('sensorSettings').style.display = (mode === 'auto_sensor') ? 'block' : 'none';

        const modeLabels = {
            'manual': 'Manual Control - Anda mengontrol lampu secara langsung',
            'auto_schedule': `Auto Schedule - Lampu nyala ${autoSettings.schedule_on_hour || 17}:${String(autoSettings.schedule_on_minute || 30).padStart(2,'0')} - ${autoSettings.schedule_off_hour || 6}:${String(autoSettings.schedule_off_minute || 0).padStart(2,'0')}`,
            'auto_sensor': `Auto Sensor - Lampu nyala saat cahaya < ${autoSettings.sensor_threshold || 35}`
        };
        document.getElementById('currentModeLabel').textContent = modeLabels[mode];

        const lampContainer = document.getElementById('lampControlContainer');
        if (lampContainer) lampContainer.classList.toggle('lamp-card-disabled', mode !== 'manual');
        document.getElementById('quickActions').style.display = (mode === 'manual') ? 'block' : 'none';
    }

    function loadActiveMode() {
        fetch('/api/lamp')
            .then(r => r.json())
            .then(data => {
                if (data.control_mode) {
                    applyModeToUI(data.control_mode);
                }
            })
            .catch(err => console.error('Gagal load mode:', err));
    }
</script>
@endpush