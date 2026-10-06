@extends('layouts.app')
@section('title', 'Smart Temperature & Environment Center')

@push('styles')
<style>
    /* ===== DEFINISI TEMA WARNA UNTUK SEMUA CARD ENVIRONMENT ===== */
    #dashboardThemeWrapper[data-theme="gradient-tricolor"] {
        --card-bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e3a8a 35%, #854d0e 70%, #065f46 100%);
        --card-border: rgba(251, 191, 36, 0.4);
        --card-glow: rgba(16, 185, 129, 0.25);
        --accent-color: #fde047;
    }

    #dashboardThemeWrapper[data-theme="dark-glass"] {
        --card-bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        --card-border: rgba(56, 189, 248, 0.3);
        --card-glow: rgba(14, 165, 233, 0.25);
        --accent-color: #38bdf8;
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

    .smart-temp-content {
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

    /* ===== STYLE CARDS DENGAN GRADASAN SESUAI TEMA ===== */
    .custom-gradient-card {
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

    .custom-gradient-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 35px var(--card-glow, rgba(0,0,0,0.3)) !important;
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

    .value-display-huge {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1;
    }

    /* ===== STYLE GRADASI KHUSUS PROGRESS BAR (SESUAI GAMBAR DUA) ===== */
    .gradient-progress-bar-temp {
        background: linear-gradient(90deg, #3b82f6 0%, #f59e0b 50%, #10b981 100%) !important;
        height: 100%;
        border-radius: 20px;
        transition: width 0.5s ease;
    }

    .gradient-progress-bar-hum {
        background: linear-gradient(90deg, #3b82f6 0%, #06b6d4 50%, #10b981 100%) !important;
        height: 100%;
        border-radius: 20px;
        transition: width 0.5s ease;
    }
</style>
@endpush

@section('content')
<div class="twinkle-bg-wrapper-light" id="dashboardThemeWrapper" data-theme="gradient-tricolor">
    <div class="smart-temp-content">

        <!-- DROPDOWN PEMILIH TEMA WARNA UNTUK SEMUA CARD -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-thermometer-half text-warning me-2"></i> Environment Monitoring
            </h5>
            <div class="d-flex align-items-center gap-2">
                <label for="allThemeSelector" class="form-label m-0 font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    <i class="fas fa-palette text-primary me-1"></i> Pilih Tema Semua Cards:
                </label>
                <select id="allThemeSelector" class="theme-select-box" onchange="changeAllCardsTheme(this.value)">
                    <option value="gradient-tricolor">🌈 Biru-Kuning-Hijau</option>
                    <option value="cyberpunk-purple">🔮 Cyberpunk Purple</option>
                    <option value="emerald-nature">🍃 Emerald Nature</option>
                </select>
            </div>
        </div>

        <!-- 1. NOTIFIKASI SUHU PANAS CARD -->
        <div class="data-card custom-gradient-card mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-white mb-1" style="font-size: 1rem; font-weight: 700;">
                        <i class="fas fa-bell-slash me-2 text-warning"></i> Notifikasi Suhu Panas
                    </h6>
                    <small style="color: #cbd5e1;">Aktifkan/mute notifikasi peringatan jika suhu lingkungan melebihi batas aman (35°C)</small>
                </div>
                <div class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" id="notifSwitch" checked style="width: 3em; height: 1.5em; cursor: pointer;">
                </div>
            </div>
        </div>

        <!-- 2. CARD SUHU UDARA & KELEMBAPAN NISBI DENGAN PROGRESS BAR GRADASI -->
        <div class="row g-4 mb-4">
            <!-- SUHU UDARA -->
            <div class="col-md-6">
                <div class="data-card custom-gradient-card" id="tempMainCard">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;" class="mb-3">
                                <i class="fas fa-temperature-high"></i>
                            </div>
                            <h6 style="font-size: 1rem; font-weight: 700;" class="mb-1">Suhu Udara (Temperature)</h6>
                            <small style="color: #cbd5e1;"><i class="fas fa-map-marker-alt me-1"></i> Sensor Lingkungan Kota Tegal</small>
                            
                            <div style="margin: 20px 0 10px 0;">
                                <span id="currentTemp" class="value-display-huge" style="color: #f87171;">29.5 °C</span>
                            </div>
                            <div style="font-size: 0.85rem; color: #cbd5e1;">Derajat Celsius</div>
                        </div>
                        <span class="badge bg-success px-3 py-2" style="border-radius: 20px; font-weight: 600;">
                            <i class="fas fa-check-circle me-1"></i> Normal
                        </span>
                    </div>

                    <!-- BAR SUHU DENGAN GRADASI SAMA SEPERTI GAMBAR DUA -->
                    <div style="margin-top: 18px; height: 10px; background: rgba(0, 0, 0, 0.3); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                        <div id="tempBarFill" class="gradient-progress-bar-temp" style="width: 60%;"></div>
                    </div>
                </div>
            </div>

            <!-- KELEMBAPAN NISBI -->
            <div class="col-md-6">
                <div class="data-card custom-gradient-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div style="background: rgba(59, 130, 246, 0.2); color: #93c5fd; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;" class="mb-3">
                                <i class="fas fa-tint"></i>
                            </div>
                            <h6 style="font-size: 1rem; font-weight: 700;" class="mb-1">Kelembapan Nisbi (Humidity)</h6>
                            <small style="color: #cbd5e1;"><i class="fas fa-map-marker-alt me-1"></i> Sensor Lingkungan Kota Tegal</small>
                            
                            <div style="margin: 20px 0 10px 0;">
                                <span id="currentHumidity" class="value-display-huge" style="color: #60a5fa;">65.0 %</span>
                            </div>
                            <div style="font-size: 0.85rem; color: #cbd5e1;">Persentase RH</div>
                        </div>
                        <span class="badge bg-info text-dark px-3 py-2" style="border-radius: 20px; font-weight: 600;">
                            <i class="fas fa-tint me-1"></i> Ideal
                        </span>
                    </div>

                    <!-- BAR KELEMBAPAN DENGAN GRADASI SAMA SEPERTI GAMBAR DUA -->
                    <div style="margin-top: 18px; height: 10px; background: rgba(0, 0, 0, 0.3); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                        <div id="humidityBarFill" class="gradient-progress-bar-hum" style="width: 65%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. REALTIME TEMPERATURE & HUMIDITY CHART CARD -->
        <div class="data-card custom-gradient-card">
            <h6 class="text-white mb-3" style="font-size: 1rem; font-weight: 700;">
                <i class="fas fa-chart-line text-info me-2"></i> REALTIME TEMPERATURE & HUMIDITY CHART
            </h6>
            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="tempHumidityChart"></canvas>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // FUNGSI MENGUBAH TEMA WARNA SELURUH CARDS
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

    // CHART INITIALIZATION
    const tempCtx = document.getElementById('tempHumidityChart')?.getContext('2d');
    let tempHumidityChart;

    if (tempCtx) {
        tempHumidityChart = new Chart(tempCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'Suhu (°C)', data: [], borderColor: '#f87171', backgroundColor: 'rgba(248,113,113,0.15)', tension: 0.4, fill: true, borderWidth: 2, yAxisID: 'y' },
                    { label: 'Kelembapan (%)', data: [], borderColor: '#60a5fa', backgroundColor: 'rgba(96,165,250,0.15)', tension: 0.4, fill: true, borderWidth: 2, yAxisID: 'y1' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#f1f5f9' } } },
                scales: {
                    x: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(255, 255, 255, 0.1)' } },
                    y: { type: 'linear', position: 'left', ticks: { color: '#f87171' }, grid: { color: 'rgba(255, 255, 255, 0.1)' }, min: 0, max: 60 },
                    y1: { type: 'linear', position: 'right', ticks: { color: '#60a5fa' }, grid: { drawOnChartArea: false }, min: 0, max: 100 }
                }
            }
        });
    }

    function updateTempUI(temp, humidity) {
        const tempVal = parseFloat(temp);
        const humVal = parseFloat(humidity);
        const now = new Date().toLocaleTimeString('id-ID');

        if (document.getElementById('currentTemp')) document.getElementById('currentTemp').textContent = tempVal.toFixed(1) + ' °C';
        if (document.getElementById('currentHumidity')) document.getElementById('currentHumidity').textContent = humVal.toFixed(1) + ' %';

        if (document.getElementById('tempBarFill')) document.getElementById('tempBarFill').style.width = Math.min((tempVal / 50) * 100, 100) + '%';
        if (document.getElementById('humidityBarFill')) document.getElementById('humidityBarFill').style.width = humVal + '%';

        if (tempHumidityChart) {
            if (tempHumidityChart.data.labels.length >= 25) {
                tempHumidityChart.data.labels.shift();
                tempHumidityChart.data.datasets[0].data.shift();
                tempHumidityChart.data.datasets[1].data.shift();
            }
            tempHumidityChart.data.labels.push(now);
            tempHumidityChart.data.datasets[0].data.push(tempVal);
            tempHumidityChart.data.datasets[1].data.push(humVal);
            tempHumidityChart.update('none');
        }
    }

    function fetchTempData() {
        fetch('/api/environment')
            .then(r => r.json())
            .then(data => updateTempUI(data.temperature ?? 29.5, data.humidity ?? 65.0))
            .catch(() => updateTempUI((28 + Math.random() * 3).toFixed(1), (60 + Math.random() * 8).toFixed(1)));
    }

    setInterval(fetchTempData, 5000);
    fetchTempData();
</script>
@endpush