@extends('layouts.app')
@section('title', 'Smart Environment')
@section('page-title', 'Smart Environment Monitoring')

@push('styles')
<style>
    /* ===== BASE CARD STYLING ===== */
    .dashboard-white-card,
    .stat-top-card {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px !important;
        box-shadow: 0 10px 30px rgba(11, 19, 43, 0.08) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .dashboard-white-card:hover,
    .stat-top-card:hover {
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14) !important;
        transform: translateY(-3px);
    }

    .dashboard-inner-box {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
    }

    .btn-navy-detail {
        background-color: #0b132b !important;
        border-color: #0b132b !important;
        color: #ffffff !important;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-navy-detail:hover {
        background-color: #1e3a8a !important;
        border-color: #1e3a8a !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- BARIS 1: 6 KARTU PARAMETER LINGKUNGAN (TANPA IKON) -->
    <div class="row g-3 mb-4">
        
        <!-- 1. Suhu Saat Ini -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Suhu Saat Ini</small>
                    <h4 class="fw-bold my-1 text-dark" id="envTemp">29.5 °C</h4>
                </div>
                <small class="text-muted" style="font-size: 0.75rem;">Suhu lingkungan</small>
            </div>
        </div>

        <!-- 2. Kelembapan Udara -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Kelembapan</small>
                    <h4 class="fw-bold my-1 text-dark" id="envHumidity">65 %</h4>
                </div>
                <small class="text-muted" style="font-size: 0.75rem;">Kelembapan relatif</small>
            </div>
        </div>

        <!-- 3. Kualitas Udara (AQI) -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Kualitas Udara</small>
                    <h4 class="fw-bold my-1 text-dark" id="envAqi">42 AQI</h4>
                </div>
                <div>
                    <span class="text-dark px-2 py-1 " style="font-size: 0.75rem;" id="envAqiStatus">Baik</span>
                </div>
            </div>
        </div>

        <!-- 4. Status Hujan -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Status Hujan</small>
                    <h4 class="fw-bold my-1 text-dark" id="envRain">Cerah</h4>
                </div>
                <small class="text-muted" style="font-size: 0.75rem;" id="envRainDesc">Tidak Presipitasi</small>
            </div>
        </div>

        <!-- 5. Ketinggian Air Sungai -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Air Sungai</small>
                    <h4 class="fw-bold my-1 text-dark" id="envWaterLevel">120 cm</h4>
                </div>
                <div>
                    <span class=" text-dark px-2 py-1 " style="font-size: 0.75rem;" id="envWaterStatus">Aman</span>
                </div>
            </div>
        </div>

        <!-- 6. Status Sistem IoT -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Status IoT</small>
                    <h4 class="fw-bold my-1 text-dark" id="envIotStatus">Online</h4>
                </div>
                <small class="text-dark fw-bold" style="font-size: 0.75rem;">Terhubung</small>
            </div>
        </div>

    </div>

    <!-- BARIS 2: GRAFIK PEMANTAUAN LINGKUNGAN & LOG TERAKHIR -->
    <div class="row g-4 mb-4 align-items-stretch">
        
        <!-- KOLOM KIRI: GRAFIK UTAMA -->
        <div class="col-lg-8">
            <div class="dashboard-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Grafik Pemantauan Parameter Lingkungan Real-Time</h5>
                        <small class="text-secondary fw-medium">Grafik memperbarui data pemantauan berkala</small>
                    </div>
                </div>

                <div class="dashboard-inner-box">
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="envMultiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

       <!-- KOLOM KANAN: LOG MONITORING TERAKHIR -->
<div class="col-lg-4">
    <div class="dashboard-white-card h-100 d-flex flex-column justify-content-start">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Riwayat Terakhir</h5>
            <a href="#" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.8rem;">Lihat Semua</a>
        </div>

        <div class="dashboard-inner-box flex-grow-1" style="max-height: 360px; overflow-y: auto;">
            <div class="d-flex flex-column gap-3" id="envLogContainer">
                
                <!-- Log Item 1 -->
                <div class="p-3 bg-white border rounded-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Zona Utama Kota</span>
                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem;">Aman</span>
                    </div>
                    <div class="text-secondary fw-medium" style="font-size: 0.78rem; line-height: 1.4;">
                        Suhu: 29.5°C | Air: 120cm | AQI: 42
                    </div>
                    <small class="text-muted d-block mt-2" style="font-size: 0.7rem;">Baru saja</small>
                </div>

                <!-- Contoh Log Item 2 (Opsional jika data bertambah) -->
                <div class="p-3 bg-white border rounded-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark" style="font-size: 0.88rem;">Zona Pesisir Pantai</span>
                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem;">Aman</span>
                    </div>
                    <div class="text-secondary fw-medium" style="font-size: 0.78rem; line-height: 1.4;">
                        Suhu: 30.1°C | Air: 110cm | AQI: 38
                    </div>
                    <small class="text-muted d-block mt-2" style="font-size: 0.7rem;">5 menit yang lalu</small>
                    </div>
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
    const ctx = document.getElementById('envMultiChart')?.getContext('2d');
    let envChart;
    const maxPoints = 10;

    if (ctx) {
        envChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'Suhu (°C)', data: [], borderColor: '#dc2626', backgroundColor: 'rgba(220, 38, 38, 0.05)', tension: 0.4, fill: true },
                    { label: 'Kelembapan (%)', data: [], borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.05)', tension: 0.4, fill: true },
                    { label: 'Tinggi Air (cm)', data: [], borderColor: '#0284c7', backgroundColor: 'rgba(2, 132, 199, 0.05)', tension: 0.4, fill: true }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } } },
                scales: {
                    x: { ticks: { color: '#64748b' }, grid: { color: '#f1f5f9' } },
                    y: { ticks: { color: '#64748b' }, grid: { color: '#f1f5f9' }, min: 0 }
                }
            }
        });
    }

    function fetchEnvironmentData() {
        fetch('/api/environment')
            .then(res => res.json())
            .then(data => {
                if (data) {
                    const temp = data.temperature ?? 29.5;
                    const hum = data.humidity ?? 65;
                    const aqi = data.aqi ?? 42;
                    const isRaining = data.is_raining ?? false;
                    const waterLevel = data.water_level ?? 120;

                    document.getElementById('envTemp').textContent = temp + ' °C';
                    document.getElementById('envHumidity').textContent = hum + ' %';
                    document.getElementById('envAqi').textContent = aqi + ' AQI';
                    document.getElementById('envWaterLevel').textContent = waterLevel + ' cm';

                    const rainElem = document.getElementById('envRain');
                    const rainDesc = document.getElementById('envRainDesc');

                    if (isRaining) {
                        rainElem.textContent = 'Hujan';
                        rainDesc.textContent = 'Presipitasi Terdeteksi';
                    } else {
                        rainElem.textContent = 'Cerah';
                        rainDesc.textContent = 'Tidak Presipitasi';
                    }

                    const waterBadge = document.getElementById('envWaterStatus');
                    if (waterLevel > 200) {
                        waterBadge.textContent = 'Bahaya (Banjir)';
                        waterBadge.className = 'badge bg-danger-subtle text-danger px-2 py-1 rounded-pill';
                    } else if (waterLevel > 150) {
                        waterBadge.textContent = 'Waspada';
                        waterBadge.className = 'badge bg-warning-subtle text-warning px-2 py-1 rounded-pill';
                    } else {
                        waterBadge.textContent = 'Aman';
                        waterBadge.className = 'text-dark px-2 py-1 rounded-pill';
                    }

                    if (envChart) {
                        const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        envChart.data.labels.push(now);
                        envChart.data.datasets[0].data.push(temp);
                        envChart.data.datasets[1].data.push(hum);
                        envChart.data.datasets[2].data.push(waterLevel);

                        if (envChart.data.labels.length > maxPoints) {
                            envChart.data.labels.shift();
                            envChart.data.datasets.forEach(ds => ds.data.shift());
                        }
                        envChart.update();
                    }
                }
            }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchEnvironmentData();
        setInterval(fetchEnvironmentData, 5000);
    });
</script>
@endpush