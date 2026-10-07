@extends('layouts.app')

@section('title', 'Dashboard Overview - Tegal EcoSense')

@push('styles')
<style>
    /* ==========================================================================
       1. BACKGROUND TITIK KELIP-KELIP BESAR DI SELURUH LAYAR (TANPA BINGKAI)
       ========================================================================== */
    .twinkle-bg-wrapper-light {
        position: relative;
        width: 100%;
        min-height: 100vh;
        overflow: hidden;
    }

    /* Bintang / Titik Kelip-Kelip Besar Melayang */
    .twinkle-bg-wrapper-light::before,
    .twinkle-bg-wrapper-light::after {
        content: "";
        position: absolute;
        top: -10%;
        left: -10%;
        width: 120%;
        height: 120%;
        pointer-events: none;
        z-index: 0;
    }

   .twinkle-bg-wrapper-light::before {
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
        /* Disesuaikan nama animasinya ke floatTwinkle1 */
        animation: floatTwinkle1 3.5s ease-in-out infinite alternate;
        opacity: 0.75;
    }

    /* Lapisan 2: Titik Kelip-Kelip Silang */
    .twinkle-bg-wrapper-light::after {
        background-image: 
            radial-gradient(6px 6px at 90px 220px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 210px 100px, #f59e0b, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 330px 290px, #3b82f6, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 460px 50px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 590px 210px, #f59e0b, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 710px 130px, #3b82f6, rgba(255,255,255,0));
        background-repeat: repeat;
        background-size: 800px 380px;
        /* Disesuaikan nama animasinya ke floatTwinkle2 */
        animation: floatTwinkle2 5s ease-in-out infinite alternate;
        opacity: 0.65;
    }


    @keyframes floatTwinkle1 {
        0% { opacity: 0.3; transform: scale(0.95) translateY(0px); }
        50% { opacity: 0.9; transform: scale(1.15) translateY(-10px); }
        100% { opacity: 0.4; transform: scale(1) translateY(5px); }
    }

    @keyframes floatTwinkle2 {
        0% { opacity: 0.8; transform: scale(1.05) translateY(0px); }
        50% { opacity: 0.3; transform: scale(0.9) translateY(12px); }
        100% { opacity: 0.9; transform: scale(1.18) translateY(-8px); }
    }

    .dashboard-relative-content {
        position: relative;
        z-index: 1;
    }

    /* ==========================================================================
       2. TAMPILAN ELEMENT FRAMELESS (TANPA KOTAK / TANPA BINGKAI)
       ========================================================================== */
    .frameless-item {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 10px;
    }

    .summary-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .glow-yellow-text {
        color: #eab308;
        text-shadow: 0 0 12px rgba(234, 179, 8, 0.4);
    }

    .glow-blue-text {
        color: #0284c7;
        text-shadow: 0 0 12px rgba(2, 132, 199, 0.4);
    }
</style>
@endpush

@section('content')
<!-- WRAPPER BACKGROUND TWINKLE SELURUH HALAMAN -->
<div class="twinkle-bg-wrapper-light">
    <div class="dashboard-relative-content">
        
        <!-- HEADER DASHBOARD UTAMA -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2" style="border-radius: 20px; font-weight: 700;">
                    <i class="fas fa-map-marker-alt me-1"></i> Kota Tegal
                </span>
                <h4 class="m-0 font-weight-bold text-dark ms-2" style="font-weight: 800;">Smart City Monitoring</h4>
            </div>
        </div>

        <!-- 4 MONITORING UTAMA (TANPA BINGKAI/KOTAK) -->
        <div class="row g-4 mb-5">
            <!-- SMART LAMPS ACTIVE -->
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="summary-icon-wrapper" style="background: rgba(234, 179, 8, 0.15); color: #ca8a04;">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <span class="badge bg-warning text-dark fw-bold" style="font-size:0.75rem; border-radius:10px;">Realtime</span>
                    </div>
                    <div id="activeLampsCount" class="glow-yellow-text" style="font-size: 2.6rem; font-weight: 800; line-height: 1; margin: 16px 0 6px 0;">
                        2 / 4
                    </div>
                    <div class="text-dark fw-bold" style="font-size: 0.9rem;">Smart Lamps Active</div>
                </div>
            </div>

            <!-- KELEMBAPAN -->
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="summary-icon-wrapper" style="background: rgba(2, 132, 199, 0.15); color: #0284c7;">
                            <i class="fas fa-tint"></i>
                        </div>
                        <span class="badge bg-info text-dark fw-bold" style="font-size:0.75rem; border-radius:10px;">Environment</span>
                    </div>
                    <div id="dashHumidityValue" class="glow-blue-text" style="font-size: 2.6rem; font-weight: 800; line-height: 1; margin: 16px 0 6px 0;">
                        65%
                    </div>
                    <div class="text-dark fw-bold" style="font-size: 0.9rem;">Kelembapan: 65%</div>
                </div>
            </div>

            <!-- PARKING AVAILABLE -->
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="summary-icon-wrapper" style="background: rgba(234, 179, 8, 0.15); color: #ca8a04;">
                            <i class="fas fa-car"></i>
                        </div>
                        <span class="badge bg-success fw-bold" style="font-size:0.75rem; border-radius:10px;">Tersedia</span>
                    </div>
                    <div id="dashParkingValue" class="glow-yellow-text" style="font-size: 2.6rem; font-weight: 800; line-height: 1; margin: 16px 0 6px 0;">
                        3 / 4
                    </div>
                    <div class="text-dark fw-bold" style="font-size: 0.9rem;">Parking Available</div>
                </div>
            </div>

            <!-- ACTIVE ALERTS -->
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="summary-icon-wrapper" style="background: rgba(16, 185, 129, 0.15); color: #059669;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <span class="badge bg-secondary fw-bold" style="font-size:0.75rem; border-radius:10px;">Status Aman</span>
                    </div>
                    <div style="font-size: 2.6rem; font-weight: 800; line-height: 1; margin: 16px 0 6px 0; color: #10b981;">
                        0
                    </div>
                    <div class="text-dark fw-bold" style="font-size: 0.9rem;">Active Alerts</div>
                </div>
            </div>
        </div>

        <!-- GRAFIK AREA (TANPA BINGKAI/KOTAK) -->
        <div class="row g-4">
            <!-- REALTIME SENSOR DATA CHART -->
            <div class="col-lg-8">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-dark m-0" style="font-size: 1rem; font-weight: 800;">
                            <i class="fas fa-chart-line text-warning me-2"></i> REALTIME SENSOR DATA
                        </h6>
                        <small class="text-muted"><i class="fas fa-sync-alt fa-spin me-1"></i> Auto Update</small>
                    </div>
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="overviewSensorChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- PARKING OCCUPANCY CHART -->
            <div class="col-lg-4">
                <div class="frameless-item">
                    <h6 class="text-dark mb-3" style="font-size: 1rem; font-weight: 800;">
                        <i class="fas fa-chart-pie text-warning me-2"></i> PARKING OCCUPANCY
                    </h6>
                    <div style="position: relative; height: 320px; width: 100%; display: flex; align-items: center; justify-content: center;">
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
    function fetchDashboardSensorData() {
        fetch('/api/lamp')
            .then(res => res.json())
            .then(data => {
                let active = 0;
                let total = 0;
                ['lamp_1', 'lamp_2', 'lamp_3', 'lamp_4'].forEach(key => {
                    if (data[key]) {
                        total++;
                        if (data[key].status === 1) active++;
                    }
                });
                const elem = document.getElementById('activeLampsCount');
                if (elem) elem.textContent = `${active} / ${total || 4}`;
            })
            .catch(err => console.log('Error fetching lamp:', err));

        fetch('/api/environment')
            .then(res => res.json())
            .then(data => {
                const elem = document.getElementById('dashHumidityValue');
                if (elem && data.humidity) elem.textContent = data.humidity + '%';
            })
            .catch(err => console.log('Error fetching env:', err));
    }

    function renderCharts() {
        const sensorCanvas = document.getElementById('overviewSensorChart');
        if (sensorCanvas) {
            new Chart(sensorCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['10:00', '10:05', '10:10', '10:15', '10:20', '10:25'],
                    datasets: [
                        { 
                            label: 'Lamp Brightness (%)', 
                            data: [80, 80, 60, 75, 80, 85], 
                            borderColor: '#ca8a04', 
                            backgroundColor: 'rgba(234, 179, 8, 0.15)', 
                            fill: true, 
                            tension: 0.4,
                            borderWidth: 3
                        },
                        { 
                            label: 'Suhu (°C)', 
                            data: [28.5, 29, 29.5, 30, 29.8, 29.2], 
                            borderColor: '#0284c7', 
                            backgroundColor: 'rgba(2, 132, 199, 0.15)', 
                            fill: true, 
                            tension: 0.4,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#1e293b', font: { weight: 'bold' } } }
                    },
                    scales: {
                        x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(0,0,0,0.05)' } },
                        y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(0,0,0,0.05)' } }
                    }
                }
            });
        }

        const parkingCanvas = document.getElementById('parkingOccupancyChart');
        if (parkingCanvas) {
            new Chart(parkingCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Terisi'],
                    datasets: [{ 
                        data: [3, 1], 
                        backgroundColor: ['#eab308', '#0284c7'], 
                        borderWidth: 0 
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#1e293b', font: { weight: 'bold' } } }
                    },
                    cutout: '70%'
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchDashboardSensorData();
        setInterval(fetchDashboardSensorData, 5000);
        renderCharts();
    });
</script>
@endpush