@extends('layouts.app')
@section('title', 'Tegal EcoSense - Smart City Overview')

@push('styles')
<style>
    /* ===== DEFINISI TEMA WARNA UNTUK SEMUA CARD OVERVIEW ===== */
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
            radial-gradient(5px 5px at 50px 60px, #3b82f6, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 150px 180px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 280px 80px, #2563eb, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 390px 220px, #0284c7, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 520px 110px, #3b82f6, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 640px 250px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 780px 90px, #2563eb, rgba(255,255,255,0));
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

    .smart-overview-content {
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

    /* ===== STYLE CARDS GELAP SESUAI TEMA DINOVERWRITE DARI BACKGROUND PUTIH ===== */
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

    /* ICON CONTAINER DI KARTU RINGKASAN ATAS */
    .summary-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .value-stat-large {
        font-size: 2.2rem;
        font-weight: 800;
        line-height: 1;
        margin: 14px 0 6px 0;
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
    <div class="smart-overview-content">

        <!-- HEADER SELECTOR TEMA WARNA & PILL LOKASI -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2" style="border-radius: 20px; font-weight: 700;">
                    <i class="fas fa-map-marker-alt me-1"></i> Kota Tegal
                </span>
                <h5 class="m-0 font-weight-bold text-dark ms-2">Smart City Monitoring</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="allThemeSelector" class="form-label m-0 font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    <i class="fas fa-palette text-primary me-1"></i> Tema Cards:
                </label>
                <select id="allThemeSelector" class="theme-select-box" onchange="changeAllCardsTheme(this.value)">
                    <option value="gradient-tricolor">🌈 Biru-Kuning-Hijau</option>
                    <option value="cyberpunk-purple">🔮 Cyberpunk Purple</option>
                    <option value="emerald-nature">🍃 Emerald Nature</option>
                </select>
            </div>
        </div>

        <!-- 1. BARIS 4 KARTU RINGKASAN UTAMA (IKUT BERUBAH TEMA) -->
        <div class="row g-4 mb-4">
          <!-- SMART LAMPS ACTIVE -->
        <div class="col-md-6 col-lg-3">
             <div class="data-card custom-gradient-card">
                 <div class="summary-icon-box" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                     <i class="fas fa-lightbulb"></i>
                 </div>
                 <div id="activeLampsCount" class="value-stat-large" style="color: #60a5fa;">-- / 4</div>
                 <div style="font-size: 0.88rem; color: #cbd5e1; font-weight: 600;">Smart Lamps Active</div>
            </div>
        </div>

            <!-- KELEMBAPAN & SUHU -->
            <div class="col-md-6 col-lg-3">
                <div class="data-card custom-gradient-card">
                    <div class="summary-icon-box" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5;">
                        <i class="fas fa-temperature-high"></i>
                    </div>
                    <div class="value-stat-large" style="color: #f87171;">65%</div>
                    <div style="font-size: 0.88rem; color: #cbd5e1; font-weight: 600;">Kelembapan: 65%</div>
                </div>
            </div>

            <!-- PARKING AVAILABLE -->
            <div class="col-md-6 col-lg-3">
                <div class="data-card custom-gradient-card">
                    <div class="summary-icon-box" style="background: rgba(245, 158, 11, 0.2); color: #fde047;">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="value-stat-large" style="color: #fde047;">5 / 10</div>
                    <div style="font-size: 0.88rem; color: #cbd5e1; font-weight: 600;">Parking Available</div>
                </div>
            </div>

            <!-- ACTIVE ALERTS -->
            <div class="col-md-6 col-lg-3">
                <div class="data-card custom-gradient-card">
                    <div class="summary-icon-box" style="background: rgba(248, 113, 113, 0.2); color: #f87171;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="value-stat-large" style="color: #34d399;">0</div>
                    <div style="font-size: 0.88rem; color: #cbd5e1; font-weight: 600;">Active Alerts</div>
                </div>
            </div>
        </div>

        <!-- 2. BARIS GRAFIK SENSOR DATA & PARKING OCCUPANCY (IKUT BERUBAH TEMA) -->
        <div class="row g-4">
            <!-- REALTIME SENSOR DATA CHART -->
            <div class="col-lg-8">
                <div class="data-card custom-gradient-card">
                    <h6 class="text-white mb-3" style="font-size: 1rem; font-weight: 700;">
                        <i class="fas fa-chart-line text-info me-2"></i> REALTIME SENSOR DATA
                    </h6>
                    <div style="position: relative; height: 310px; width: 100%;">
                        <canvas id="overviewSensorChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- PARKING OCCUPANCY CHART -->
            <div class="col-lg-4">
                <div class="data-card custom-gradient-card">
                    <h6 class="text-white mb-3" style="font-size: 1rem; font-weight: 700;">
                        <i class="fas fa-chart-pie text-warning me-2"></i> PARKING OCCUPANCY
                    </h6>
                    <div style="position: relative; height: 310px; width: 100%; display: flex; align-items: center; justify-content: center;">
                        <canvas id="parkingOccupancyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // FUNGSI MENGUBAH TEMA WARNA SELURUH CARDS OVERVIEW
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

    // 1. CHART REALTIME SENSOR DATA
    const sensorCtx = document.getElementById('overviewSensorChart')?.getContext('2d');
    let overviewSensorChart;

    if (sensorCtx) {
        overviewSensorChart = new Chart(sensorCtx, {
            type: 'line',
            data: {
                labels: ['10:00', '10:05', '10:10', '10:15', '10:20', '10:25', '10:30'],
                datasets: [
                    {
                        label: 'Lamp Brightness (%)',
                        data: [60, 60, 60, 60, 60, 60, 60],
                        borderColor: '#38bdf8',
                        backgroundColor: 'rgba(56, 189, 248, 0.15)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Suhu (°C)',
                        data: [29, 29.5, 30, 29.8, 29.5, 29.2, 29.5],
                        borderColor: '#f87171',
                        backgroundColor: 'rgba(248, 113, 113, 0.15)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#f1f5f9' } } },
                scales: {
                    x: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(255, 255, 255, 0.1)' } },
                    y: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(255, 255, 255, 0.1)' }, min: 0, max: 100 }
                }
            }
        });
    }

    // 2. CHART PARKING OCCUPANCY (DONUT CHART)
    const parkingCtx = document.getElementById('parkingOccupancyChart')?.getContext('2d');
    if (parkingCtx) {
        new Chart(parkingCtx, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Terisi'],
                datasets: [{
                    data: [3, 1],
                    backgroundColor: ['#10b981', '#f87171'],
                    borderColor: 'transparent',
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#f1f5f9', padding: 20 }
                    }
                },
                cutout: '70%'
            }
        });
    }
</script>
@endpush