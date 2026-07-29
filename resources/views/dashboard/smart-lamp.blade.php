@extends('layouts.app')
@section('title', 'Smart Lamp')
@section('page-title', 'Smart Lamp Monitoring')

@section('content')
<!-- 1. STATUS MODE (Existing) -->
<div class="data-card mb-4" style="border-left: 4px solid #3b82f6;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
        <div>
            <h6 style="margin-bottom:5px;"><i class="fas fa-info-circle"></i> System Status</h6>
            <div style="font-size:0.85rem; color:var(--text-secondary);">
                Mode: <strong id="monitorMode" style="color:#3b82f6;">-</strong> | 
                Light Sensor: <strong id="monitorLight" style="color:#f59e0b;">-</strong> |
                Time: <strong id="monitorTime" style="color:#10b981;">-</strong>
            </div>
        </div>
        <div id="autoStatus" style="display:none;">
            <span style="background:rgba(245,158,11,0.2); color:#f59e0b; padding:6px 14px; border-radius:20px; font-size:0.8rem; font-weight:600;">
                <i class="fas fa-robot"></i> AUTO MODE ACTIVE
            </span>
        </div>
    </div>
</div>

<!-- 2. REAL-TIME LIGHT LEVEL CARD (BARU - Disesuaikan dengan style Anda) -->
<div class="data-card mb-4" style="border-left: 4px solid #f59e0b; background: linear-gradient(to right, rgba(245,158,11,0.05), transparent);">
    <h6 style="margin-bottom:15px;"><i class="fas fa-sun" style="color:#f59e0b;"></i> Ambient Light Monitoring</h6>
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
        <div style="flex:1; min-width:200px;">
            <div style="font-size:0.8rem; color:var(--text-secondary); margin-bottom:5px;">Current Light Level</div>
            <div style="display:flex; align-items:baseline; gap:10px;">
                <span id="lightLevelValue" style="font-size:2rem; font-weight:700; color:#f59e0b;">--</span>
                <span style="font-size:1rem; color:var(--text-secondary);">%</span>
            </div>
            <div class="waste-bar" style="margin-top:10px; height:8px;">
                <div id="lightLevelBar" class="waste-bar-fill" style="width:0%; background:#f59e0b; transition: width 0.5s ease;"></div>
            </div>
            <div id="lightStatusText" style="font-size:0.8rem; margin-top:8px; color:#94a3b8;">Menunggu data sensor...</div>
        </div>
        <div style="flex:1; min-width:200px; border-left:1px solid #334155; padding-left:20px;">
            <div style="font-size:0.8rem; color:var(--text-secondary); margin-bottom:10px;">Today's Statistics</div>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.85rem;">
                <span style="color:#94a3b8;">Max:</span>
                <strong id="lightMax" style="color:#10b981;">--%</strong>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.85rem;">
                <span style="color:#94a3b8;">Min:</span>
                <strong id="lightMin" style="color:#ef4444;">--%</strong>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:0.85rem;">
                <span style="color:#94a3b8;">Avg:</span>
                <strong id="lightAvg" style="color:#3b82f6;">--%</strong>
            </div>
        </div>
    </div>
</div>

<!-- 3. LAMP CARDS (Existing) -->
<div class="row g-4" id="lampContainer">
    <div class="col-12 text-center text-muted">Memuat data...</div>
</div>

<!-- 4. POWER CONSUMPTION CHART (Existing) -->
<div class="data-card mt-4">
    <h6><i class="fas fa-chart-line"></i> Lamp Power Consumption History</h6>
    <div style="position: relative; height: 300px; width: 100%;">
        <canvas id="lampChart"></canvas>
    </div>
</div>

<!-- 5. LIGHT SENSOR HISTORY CHART (BARU) -->
<div class="data-card mt-4">
    <h6><i class="fas fa-chart-area" style="color:#f59e0b;"></i> Light Sensor History (24 Hours)</h6>
    <div style="position: relative; height: 300px; width: 100%;">
        <canvas id="lightSensorChart"></canvas>
    </div>
    <!-- Auto Trigger Log (Opsional tapi berguna) -->
    <div style="margin-top:15px; padding:10px; background:rgba(30,41,59,0.5); border-radius:6px; border:1px solid #334155;">
        <div style="font-size:0.8rem; font-weight:600; color:#94a3b8; margin-bottom:8px;"><i class="fas fa-history"></i> Auto-Sensor Trigger Log</div>
        <div id="triggerLogContainer" style="font-size:0.75rem; color:#64748b; max-height:80px; overflow-y:auto;">
            <em>Belum ada data trigger otomatis hari ini.</em>
        </div
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const lampNames = {
        lamp_1: 'Street Lamp A - Jl. Sudirman',
        lamp_2: 'Street Lamp B - Jl. Thamrin',
        lamp_3: 'Street Lamp C - Jl. Gatot Subroto',
        lamp_4: 'Street Lamp D - Jl. Rasuna Said'
    };

    // --- CHART 1: POWER CONSUMPTION (Existing) ---
    const lampCtx = document.getElementById('lampChart')?.getContext('2d');
    let lampChart;
    if (lampCtx) {
        lampChart = new Chart(lampCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'Lamp 1', data: [], borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: 'Lamp 2', data: [], borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: 'Lamp 3', data: [], borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: 'Lamp 4', data: [], borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.1)', tension: 0.4, fill: true, borderWidth: 2 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#94a3b8' } }, title: { display: true, text: 'Power Consumption (Watt)', color: '#94a3b8' } },
                scales: {
                    x: { ticks: { color: '#4b5563', maxTicksLimit: 10 }, grid: { color: '#1e293b' } },
                    y: { ticks: { color: '#4b5563' }, grid: { color: '#1e293b' }, min: 0, max: 10, title: { display: true, text: 'Watt', color: '#94a3b8' } }
                }
            }
        });
    }

    // --- CHART 2: LIGHT SENSOR HISTORY (BARU) ---
    const lightCtx = document.getElementById('lightSensorChart')?.getContext('2d');
    let lightSensorChart;
    let lightStats = { max: null, min: null, sum: 0, count: 0 };
    let triggerLogs = [];
    const THRESHOLD = 35; // Sesuaikan dengan default atau ambil dari API

    if (lightCtx) {
        lightSensorChart = new Chart(lightCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Light Level (%)',
                        data: [],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245,158,11,0.1)',
                        tension: 0.4, fill: true, borderWidth: 2, pointRadius: 3
                    },
                    {
                        label: `Threshold (${THRESHOLD}%)`,
                        data: [], // Akan diisi dinamis
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        pointRadius: 0,
                        tension: 0
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#94a3b8' } } },
                scales: {
                    x: { ticks: { color: '#4b5563', maxTicksLimit: 10 }, grid: { color: '#1e293b' } },
                    y: { 
                        ticks: { color: '#4b5563', callback: (val) => val + '%' }, 
                        grid: { color: '#1e293b' }, min: 0, max: 100 
                    }
                }
            }
        });
    }

    // --- FUNGSI RENDER LAMPU (Existing dengan sedikit penyesuaian) ---
    function renderLamps(data) {
        const container = document.getElementById('lampContainer');
        container.innerHTML = '';

        const modeLabels = {
            'manual': ' Manual',
            'auto_schedule': ' Auto Schedule (Maghrib-Subuh)',
            'auto_sensor': ' Auto Sensor (Cahaya)'
        };
        document.getElementById('monitorMode').textContent = modeLabels[data.control_mode] || data.control_mode;
        document.getElementById('monitorLight').textContent = (data.sensor_light_level || 0) + '%';
        document.getElementById('monitorTime').textContent = data.timestamp;
        
        const isAuto = data.control_mode !== 'manual';
        document.getElementById('autoStatus').style.display = isAuto ? 'block' : 'none';

        // Update Light Card (BARU)
        updateLightCard(data.sensor_light_level || 0);

        Object.keys(lampNames).forEach((key) => {
            const lamp = data[key];
            const isOn = lamp.status === 1;
            const color = isOn ? '#f59e0b' : '#4b5563';

            container.innerHTML += `
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card" style="border-top: 3px solid ${color}">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                            <div class="icon-box" style="background:${isOn ? 'rgba(245,158,11,0.15)' : 'rgba(75,85,99,0.15)'}; color:${color}; margin-bottom:0; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:8px;">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <span style="background:${isOn ? 'rgba(245,158,11,0.2)' : 'rgba(75,85,99,0.2)'}; color:${color}; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600;">
                                ${isOn ? 'ON' : 'OFF'}
                            </span>
                        </div>
                        <h6 style="margin-bottom:10px;">${lampNames[key]}</h6>
                        <div style="margin-bottom:15px;">
                            <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:var(--text-secondary); margin-bottom:5px;">
                                <span>Brightness</span>
                                <span style="color:${color}; font-weight:600;">${lamp.brightness}%</span>
                            </div>
                            <div class="waste-bar">
                                <div class="waste-bar-fill" style="width:${lamp.brightness}%; background:${color}"></div>
                            </div>
                        </div>
                        <div style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:8px;">
                            <i class="fas fa-bolt"></i> Power: ${lamp.power}W
                        </div>
                        ${isAuto ? '<div style="font-size:0.75rem; color:#f59e0b;"><i class="fas fa-robot"></i> Auto-controlled</div>' : '<div style="font-size:0.75rem; color:#3b82f6;"><i class="fas fa-hand-pointer"></i> Manual</div>'}
                    </div>
                </div>
            `;
        });
    }

    // --- FUNGSI UPDATE LIGHT CARD & CHART (BARU) ---
    function updateLightCard(level) {
        const levelNum = parseFloat(level);
        document.getElementById('lightLevelValue').textContent = levelNum.toFixed(1);
        document.getElementById('lightLevelBar').style.width = levelNum + '%';
        
        // Update Stats
        if (lightStats.max === null || levelNum > lightStats.max) lightStats.max = levelNum;
        if (lightStats.min === null || levelNum < lightStats.min) lightStats.min = levelNum;
        lightStats.sum += levelNum;
        lightStats.count++;

        document.getElementById('lightMax').textContent = lightStats.max.toFixed(1) + '%';
        document.getElementById('lightMin').textContent = lightStats.min.toFixed(1) + '%';
        document.getElementById('lightAvg').textContent = (lightStats.sum / lightStats.count).toFixed(1) + '%';

        // Update Status Text & Icon
        const statusEl = document.getElementById('lightStatusText');
        if (levelNum <= THRESHOLD) {
            statusEl.innerHTML = '<i class="fas fa-moon" style="color:#3b82f6;"></i> GELAP (Lampu seharusnya ON)';
            statusEl.style.color = '#3b82f6';
        } else {
            statusEl.innerHTML = '<i class="fas fa-sun" style="color:#f59e0b;"></i> TERANG (Lampu seharusnya OFF)';
            statusEl.style.color = '#f59e0b';
        }

        // Update Chart
        if (lightSensorChart) {
        const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    if (lightSensorChart.data.labels.length >= 30) {
        lightSensorChart.data.labels.shift();
        lightSensorChart.data.datasets[0].data.shift();
            }
            lightSensorChart.data.labels.push(now);
            lightSensorChart.data.datasets[0].data.push(levelNum);
            
            // Isi garis threshold agar sejajar
            lightSensorChart.data.datasets[1].data = Array(lightSensorChart.data.labels.length).fill(THRESHOLD);
            
            lightSensorChart.update('none');
        }

        // Check Trigger Log
        checkTrigger(levelNum);
    }

    function checkTrigger(currentLevel) {
        // Logika sederhana: jika sebelumnya > threshold dan sekarang <= threshold, atau sebaliknya
        const dataLen = lightSensorChart.data.datasets[0].data.length;
        if (dataLen < 2) return;
        
        const prevLevel = lightSensorChart.data.datasets[0].data[dataLen - 2];
        const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        
        if (prevLevel > THRESHOLD && currentLevel <= THRESHOLD) {
            addTriggerLog(now, 'Lampu ON', 'Cahaya turun di bawah threshold');
        } else if (prevLevel <= THRESHOLD && currentLevel > THRESHOLD) {
            addTriggerLog(now, 'Lampu OFF', 'Cahaya naik di atas threshold');
        }
    }

    function addTriggerLog(time, action, reason) {
        // Hindari duplikat log dalam waktu yang sama
        if (triggerLogs.length > 0 && triggerLogs[triggerLogs.length - 1].time === time) return;
        
        triggerLogs.push({ time, action, reason });
        const container = document.getElementById('triggerLogContainer');
        
        let html = '';
        triggerLogs.slice(-3).reverse().forEach(log => { // Tampilkan 3 terakhir
            const color = log.action.includes('ON') ? '#10b981' : '#f59e0b';
            html += `<div style="margin-bottom:4px;"><span style="color:#94a3b8;">${log.time}</span> - <strong style="color:${color};">${log.action}</strong> <span style="color:#64748b;">(${log.reason})</span></div>`;
        });
        container.innerHTML = html;
    }

    // --- FUNGSI FETCH DATA (Existing + Modifikasi) ---
    function fetchLampData() {
        fetch('/api/lamp')
            .then(r => r.json())
            .then(data => {
                renderLamps(data); // Ini sekarang juga memanggil updateLightCard
                
                if (lampChart) {
                const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                lampChart.data.labels.push(now);

                const addNoise = (value) => {
                    const noise = (Math.random() - 0.5) * 0.4; // Variasi ±0.2W
                    return Math.max(0, parseFloat(value) + noise);
                };
                    lampChart.data.labels.push(now);
                    lampChart.data.datasets[0].data.push(data.lamp_1.power);
                    lampChart.data.datasets[1].data.push(data.lamp_2.power);
                    lampChart.data.datasets[2].data.push(data.lamp_3.power);
                    lampChart.data.datasets[3].data.push(data.lamp_4.power);
                    
                    if (lampChart.data.labels.length > 30) {
                        lampChart.data.labels.shift();
                        lampChart.data.datasets.forEach(ds => ds.data.shift());
                    }
                    lampChart.update('none');
                }
            })
            .catch(err => console.error("Gagal mengambil data:", err));
    }

    setInterval(fetchLampData, 5000);
    fetchLampData(); // Initial load
</script>
@endpush