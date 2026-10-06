@extends('layouts.app')
@section('title', 'Smart Lamp')
@section('page-title', 'Smart Lamp Monitoring')

@push('styles')
<style>
    /* ===== DEFINISI TEMA WARNA UNTUK SEMUA CARD ===== */
    #dashboardThemeWrapper[data-theme="gradient-tricolor"] {
        --card-bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e3a8a 35%, #854d0e 70%, #065f46 100%);
        --card-border: rgba(251, 191, 36, 0.4);
        --card-glow: rgba(16, 185, 129, 0.25);
        --accent-color: #fde047;
    }

    #dashboardThemeWrapper[data-theme="cyberpunk-purple"] {
        --card-bg-gradient: linear-gradient(135deg, #2e1065 0%, #581c87 50%, #831843 100%);
        --card-border: rgba(236, 72, 153, 0.4);
        --card-glow: rgba(236, 72, 153, 0.3);
        --accent-color: #f472b6;
    }

    #dashboardThemeWrapper[data-theme="emerald-nature"] {
        --card-bg-gradient: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0f172a 100%);
        --card-border: rgba(52, 211, 153, 0.4);
        --card-glow: rgba(16, 185, 129, 0.3);
        --accent-color: #6ee7b7;
    }

    /* ===== BACKGROUND TITIK KELAP-KELIP BESAR ===== */
    .twinkle-bg-wrapper-light {
        position: relative;
        background: #f8fafc;
        min-height: calc(100vh - 100px);
        padding: 25px;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .twinkle-bg-wrapper-light::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: 
            radial-gradient(5px 5px at 50px 60px, #e9f500, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 150px 180px, #a300fa, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 280px 80px, #2563eb, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 390px 220px, #0284c7, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 520px 110px, #00fbff, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 640px 250px, #fcb000, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 780px 90px, #aefd02, rgba(255,255,255,0));
        background-repeat: repeat;
        background-size: 850px 350px;
        animation: lightTwinkleBig 3.5s ease-in-out infinite alternate;
        pointer-events: none;
        opacity: 0.75;
        z-index: 1;
    }

    .twinkle-bg-wrapper-light::after {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: 
            radial-gradient(6px 6px at 90px 220px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 210px 100px, #f59e0b, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 330px 290px, #3b82f6, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 460px 50px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 590px 210px, #f59e0b, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 710px 130px, #3b82f6, rgba(255,255,255,0));
        background-repeat: repeat;
        background-size: 800px 380px;
        animation: lightTwinkleBigAlt 5s ease-in-out infinite alternate;
        pointer-events: none;
        opacity: 0.65;
        z-index: 1;
    }

    .smart-lamp-content {
        position: relative;
        z-index: 2;
    }

    @keyframes lightTwinkleBig {
        0% { opacity: 0.2; transform: scale(0.9) translateY(0px); filter: blur(0px); }
        50% { opacity: 0.85; filter: blur(1px); }
        100% { opacity: 0.3; transform: scale(1.1) translateY(-4px); filter: blur(0px); }
    }

    @keyframes lightTwinkleBigAlt {
        0% { opacity: 0.7; transform: scale(1.05); }
        50% { opacity: 0.2; }
        100% { opacity: 0.8; transform: scale(0.95); }
    }

    /* ===== STYLE APLIKASI TEMA UNTUK SEMUA CARDS DALAM HALAMAN ===== */
    .custom-gradient-card, .lamp-item-card {
        background: var(--card-bg-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 35%, #854d0e 70%, #065f46 100%)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--card-border, rgba(251, 191, 36, 0.3)) !important;
        border-radius: 18px !important;
        padding: 22px !important;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15) !important;
        transition: all 0.4s ease;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .custom-gradient-card:hover, .lamp-item-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 35px var(--card-glow, rgba(0,0,0,0.3)) !important;
    }

    /* AKSEN KHUSUS CARD LAMPU SAAT STATUS ON */
    .lamp-item-card.is-on {
        border-color: var(--accent-color, #fbbf24) !important;
        box-shadow: 0 0 22px rgba(251, 191, 36, 0.35) !important;
    }

    .status-pill {
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .status-pill.on {
        background: rgba(251, 191, 36, 0.25);
        color: #fef08a;
        border: 1px solid #fbbf24;
        box-shadow: 0 0 10px rgba(251, 191, 36, 0.4);
    }

    .status-pill.off {
        background: rgba(100, 116, 139, 0.3);
        color: #cbd5e1;
        border: 1px solid rgba(148, 163, 184, 0.3);
    }

    /* DROPDOWN SELECTOR TEMA */
    .theme-select-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #0f172a;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 10px;
        padding: 6px 12px;
        cursor: pointer;
        outline: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="twinkle-bg-wrapper-light" id="dashboardThemeWrapper" data-theme="gradient-tricolor">
    <div class="smart-lamp-content">

        <!-- DROPDOWN PEMILIH TEMA WARNA UNTUK SEMUA CARD -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-lightbulb text-warning me-2"></i> Smart Lamp Monitoring
            </h5>
            <div class="d-flex align-items-center gap-2">
                <label for="allThemeSelector" class="form-label m-0 font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    <i class="fas fa-palette text-primary me-1"></i> Tema Semua Cards:
                </label>
                <select id="allThemeSelector" class="theme-select-box" onchange="changeAllCardsTheme(this.value)">
                    <option value="gradient-tricolor">🌈 Biru-Kuning-Hijau</option>
                    <option value="cyberpunk-purple">🔮 Cyberpunk Purple</option>
                    <option value="emerald-nature">🍃 Emerald Nature</option>
                </select>
            </div>
        </div>

        <!-- 1. STATUS MODE (IKUT BERUBAH TEMA) -->
        <div class="data-card custom-gradient-card mb-4">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
                <div>
                    <h6 class="text-white mb-2" style="font-size: 1rem; font-weight: 700;">
                        <i class="fas fa-microchip text-warning me-2"></i> System Status
                    </h6>
                    <div style="font-size:0.88rem; color:#f1f5f9; display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
                        <span>Mode: <strong id="monitorMode" style="color:#60a5fa;">-</strong></span>
                        <span style="opacity:0.4;">|</span>
                        <span>Sensor Light: <strong id="monitorLight" style="color:#fde047;">-</strong></span>
                        <span style="opacity:0.4;">|</span>
                        <span>Time: <strong id="monitorTime" style="color:#6ee7b7;">-</strong></span>
                    </div>
                </div>
                <div id="autoStatus" style="display:none;">
                    <span style="background:linear-gradient(135deg, rgba(245,158,11,0.3) 0%, rgba(16,185,129,0.3) 100%); color:#fef08a; padding:8px 18px; border-radius:30px; font-size:0.8rem; font-weight:700; border: 1px solid #fbbf24; box-shadow: 0 0 15px rgba(245,158,11,0.3);">
                        <i class="fas fa-robot me-1"></i> AUTO MODE ACTIVE
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. REAL-TIME LIGHT LEVEL CARD (IKUT BERUBAH TEMA) -->
        <div class="data-card custom-gradient-card mb-4">
            <h6 class="text-white mb-3" style="font-size: 1rem; font-weight: 700;">
                <i class="fas fa-sun me-2" style="color:#fde047;"></i> Ambient Light Monitoring
            </h6>
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
                <div style="flex:1; min-width:220px;">
                    <div style="font-size:0.8rem; color:#cbd5e1; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Current Light Level</div>
                    <div style="display:flex; align-items:baseline; gap:8px;">
                        <span id="lightLevelValue" style="font-size:2.4rem; font-weight:800; color:#fde047; line-height:1;">--</span>
                        <span style="font-size:1.2rem; font-weight:700; color:#fde047;">%</span>
                    </div>
                    <div class="waste-bar" style="margin-top:12px; height:10px; background: rgba(0, 0, 0, 0.3); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                        <div id="lightLevelBar" class="waste-bar-fill" style="width:0%; background: linear-gradient(90deg, #3b82f6 0%, #f59e0b 50%, #10b981 100%); height:100%; transition: width 0.5s ease; border-radius:20px;"></div>
                    </div>
                    <div id="lightStatusText" style="font-size:0.85rem; margin-top:10px; font-weight:600; color:#e2e8f0;">Menunggu data sensor...</div>
                </div>
                <div style="flex:1; min-width:220px; border-left:1px solid rgba(255,255,255,0.2); padding-left:25px;">
                    <div style="font-size:0.8rem; color:#cbd5e1; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">Today's Statistics</div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.88rem;">
                        <span style="color:#cbd5e1;">Max Level:</span>
                        <strong id="lightMax" style="color:#6ee7b7;">--%</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:0.88rem;">
                        <span style="color:#cbd5e1;">Min Level:</span>
                        <strong id="lightMin" style="color:#fca5a5;">--%</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:0.88rem;">
                        <span style="color:#cbd5e1;">Average:</span>
                        <strong id="lightAvg" style="color:#93c5fd;">--%</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. 4 LAMP CARDS GRID (IKUT BERUBAH TEMA) -->
        <div class="row g-4" id="lampContainer">
            <div class="col-12 text-center" style="color: #64748b;">Memuat data...</div>
        </div>

        <!-- 4. POWER CONSUMPTION CHART CARD (IKUT BERUBAH TEMA) -->
        <div class="data-card custom-gradient-card mt-4">
            <h6 class="text-white mb-3" style="font-size: 1rem; font-weight: 700;">
                <i class="fas fa-chart-line text-info me-2"></i> Lamp Power Consumption History
            </h6>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="lampChart"></canvas>
            </div>
        </div>

        <!-- 5. LIGHT SENSOR HISTORY CHART CARD (IKUT BERUBAH TEMA) -->
        <div class="data-card custom-gradient-card mt-4">
            <h6 class="text-white mb-3" style="font-size: 1rem; font-weight: 700;">
                <i class="fas fa-chart-area me-2" style="color:#fde047;"></i> Light Sensor History (24 Hours)
            </h6>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="lightSensorChart"></canvas>
            </div>
            <!-- Auto Trigger Log -->
            <div style="margin-top:18px; padding:14px 18px; background:rgba(15, 23, 42, 0.5); border-radius:12px; border:1px solid rgba(255,255,255,0.15);">
                <div style="font-size:0.82rem; font-weight:700; color:#e2e8f0; margin-bottom:8px;"><i class="fas fa-history me-1 text-warning"></i> Auto-Sensor Trigger Log</div>
                <div id="triggerLogContainer" style="font-size:0.78rem; color:#cbd5e1; max-height:85px; overflow-y:auto;">
                    <em>Belum ada data trigger otomatis hari ini.</em>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // FUNGSI MENGUBAH TEMA WARNA SELURUH CARDS DI HALAMAN
    function changeAllCardsTheme(themeName) {
        const wrapper = document.getElementById('dashboardThemeWrapper');
        if (wrapper) {
            wrapper.setAttribute('data-theme', themeName);
            localStorage.setItem('globalDashboardCardTheme', themeName);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('globalDashboardCardTheme') || 'gradient-tricolor';
        const selector = document.getElementById('allThemeSelector');
        if (selector) selector.value = savedTheme;
        changeAllCardsTheme(savedTheme);
    });

    // LIST NAMA LAMPU
    const lampNames = {
        lamp_1: 'Sektor 1',
        lamp_2: 'Sektor 2',
        lamp_3: 'Sektor 3',
        lamp_4: 'Sektor 4'
    };

    // CHART POWER CONSUMPTION
    const lampCtx = document.getElementById('lampChart')?.getContext('2d');
    let lampChart;
    if (lampCtx) {
        lampChart = new Chart(lampCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'Lamp 1', data: [], borderColor: '#60a5fa', backgroundColor: 'rgba(96,165,250,0.15)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: 'Lamp 2', data: [], borderColor: '#34d399', backgroundColor: 'rgba(52,211,153,0.15)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: 'Lamp 3', data: [], borderColor: '#fbbf24', backgroundColor: 'rgba(251,191,36,0.15)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: 'Lamp 4', data: [], borderColor: '#f87171', backgroundColor: 'rgba(248,113,113,0.15)', tension: 0.4, fill: true, borderWidth: 2 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#f1f5f9' } } },
                scales: {
                    x: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(255, 255, 255, 0.1)' } },
                    y: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(255, 255, 255, 0.1)' }, min: 0, max: 10 }
                }
            }
        });
    }

    // CHART LIGHT SENSOR
    const lightCtx = document.getElementById('lightSensorChart')?.getContext('2d');
    let lightSensorChart;
    let lightStats = { max: null, min: null, sum: 0, count: 0 };
    let triggerLogs = [];
    const THRESHOLD = 35;

    if (lightCtx) {
        lightSensorChart = new Chart(lightCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'Light Level (%)', data: [], borderColor: '#fde047', backgroundColor: 'rgba(253,224,71,0.2)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: `Threshold (${THRESHOLD}%)`, data: [], borderColor: '#f87171', borderWidth: 2, borderDash: [5, 5], fill: false, pointRadius: 0 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#f1f5f9' } } },
                scales: {
                    x: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(255, 255, 255, 0.1)' } },
                    y: { ticks: { color: '#cbd5e1', callback: (val) => val + '%' }, grid: { color: 'rgba(255, 255, 255, 0.1)' }, min: 0, max: 100 }
                }
            }
        });
    }

    // FUNGSI RENDER CARDS LAMPU
    function renderLamps(data) {
        const container = document.getElementById('lampContainer');
        if (!container) return;
        container.innerHTML = '';

        const modeLabels = {
            'manual': ' Manual',
            'auto_schedule': ' Auto Schedule ',
            'auto_sensor': ' Auto Sensor (Cahaya)'
        };
        document.getElementById('monitorMode').textContent = modeLabels[data.control_mode] || data.control_mode;
        document.getElementById('monitorLight').textContent = (data.sensor_light_level || 0) + '%';
        document.getElementById('monitorTime').textContent = data.timestamp;
        
        const isAuto = data.control_mode !== 'manual';
        document.getElementById('autoStatus').style.display = isAuto ? 'block' : 'none';

        updateLightCard(data.sensor_light_level || 0);

        Object.keys(lampNames).forEach((key) => {
            const lamp = data[key];
            const isOn = lamp.status === 1;

            container.innerHTML += `
                <div class="col-md-6 col-lg-3">
                    <div class="lamp-item-card ${isOn ? 'is-on' : ''}">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                            <div style="background:${isOn ? 'rgba(251,191,36,0.25)' : 'rgba(255,255,255,0.1)'}; color:${isOn ? '#fde047' : '#94a3b8'}; width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:14px; box-shadow: ${isOn ? '0 0 15px rgba(251,191,36,0.4)' : 'none'};">
                                <i class="fas fa-lightbulb" style="font-size: 1.3rem;"></i>
                            </div>
                            <span class="status-pill ${isOn ? 'on' : 'off'}">
                                ${isOn ? '• ON' : 'OFF'}
                            </span>
                        </div>
                        <h6 style="margin-bottom:12px; color:#ffffff; font-size:0.95rem; font-weight:700;">${lampNames[key]}</h6>
                        <div style="margin-bottom:15px;">
                            <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#cbd5e1; margin-bottom:6px;">
                                <span>Brightness</span>
                                <span style="color:${isOn ? '#fde047' : '#cbd5e1'}; font-weight:700;">${lamp.brightness}%</span>
                            </div>
                            <div style="height:8px; background: rgba(0, 0, 0, 0.3); border-radius: 10px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                                <div style="width:${lamp.brightness}%; background:${isOn ? 'linear-gradient(90deg, #f59e0b, #10b981)' : '#64748b'}; height:100%; border-radius:10px;"></div>
                            </div>
                        </div>
                        <div style="font-size:0.82rem; color:#f1f5f9; margin-bottom:10px; font-weight:600;">
                            <i class="fas fa-bolt text-warning me-1"></i> Power: ${lamp.power}W
                        </div>
                        ${isAuto ? '<div style="font-size:0.75rem; color:#fde047; font-weight:600;"><i class="fas fa-robot me-1"></i> Auto-controlled</div>' : '<div style="font-size:0.75rem; color:#93c5fd; font-weight:600;"><i class="fas fa-hand-pointer me-1"></i> Manual</div>'}
                    </div>
                </div>
            `;
        });
    }

    function updateLightCard(level) {
        const levelNum = parseFloat(level);
        document.getElementById('lightLevelValue').textContent = levelNum.toFixed(1);
        document.getElementById('lightLevelBar').style.width = levelNum + '%';
        
        if (lightStats.max === null || levelNum > lightStats.max) lightStats.max = levelNum;
        if (lightStats.min === null || levelNum < lightStats.min) lightStats.min = levelNum;
        lightStats.sum += levelNum;
        lightStats.count++;

        document.getElementById('lightMax').textContent = lightStats.max.toFixed(1) + '%';
        document.getElementById('lightMin').textContent = lightStats.min.toFixed(1) + '%';
        document.getElementById('lightAvg').textContent = (lightStats.sum / lightStats.count).toFixed(1) + '%';

        const statusEl = document.getElementById('lightStatusText');
        if (levelNum <= THRESHOLD) {
            statusEl.innerHTML = '<i class="fas fa-moon me-1" style="color:#93c5fd;"></i> GELAP (Lampu seharusnya ON)';
            statusEl.style.color = '#93c5fd';
        } else {
            statusEl.innerHTML = '<i class="fas fa-sun me-1" style="color:#fde047;"></i> TERANG (Lampu seharusnya OFF)';
            statusEl.style.color = '#fde047';
        }

        if (lightSensorChart) {
            const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            if (lightSensorChart.data.labels.length >= 30) {
                lightSensorChart.data.labels.shift();
                lightSensorChart.data.datasets[0].data.shift();
            }
            lightSensorChart.data.labels.push(now);
            lightSensorChart.data.datasets[0].data.push(levelNum);
            lightSensorChart.data.datasets[1].data = Array(lightSensorChart.data.labels.length).fill(THRESHOLD);
            lightSensorChart.update('none');
        }
    }

    function fetchLampData() {
        fetch('/api/lamp')
            .then(r => r.json())
            .then(data => {
                renderLamps(data);
                if (lampChart) {
                    const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
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
    fetchLampData();
</script>
@endpush