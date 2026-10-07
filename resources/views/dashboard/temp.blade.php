@extends('layouts.app')
<<<<<<< HEAD

@section('title', 'Smart Temperature Monitoring - Tegal EcoSense')

@push('styles')
<style>
    /* ==========================================================================
       1. BACKGROUND TITIK KELIP-KELIP BESAR DI SELURUH LAYAR (TWINKLE BG)
       ========================================================================== */
    .twinkle-bg-wrapper-light {
        position: relative;
        width: 100%;
        min-height: 100vh;
        overflow: hidden;
    }

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
       2. TAMPILAN ELEMENT FRAMELESS (TANPA BINGKAI/KOTAK)
       ========================================================================== */
    .frameless-item {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 10px;
    }

    .glow-yellow-text {
        color: #ca8a04;
        text-shadow: 0 0 12px rgba(234, 179, 8, 0.35);
    }

    .glow-blue-text {
        color: #0284c7;
        text-shadow: 0 0 12px rgba(2, 132, 199, 0.35);
    }

    .glow-red-text {
        color: #e11d48;
        text-shadow: 0 0 12px rgba(225, 29, 72, 0.35);
    }

    .summary-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
=======
@section('title', 'Smart Temperature')
@section('page-title', 'Smart Temperature Monitoring')

@push('styles')
<style>
    /* ===== KARTU UTAMA DENGAN LATAR PUTIH & EFEK GLOW NAVY ===== */
    .temp-white-card {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 24px !important;
        box-shadow: 0 10px 30px rgba(11, 19, 43, 0.08), 0 0 20px rgba(37, 99, 235, 0.12);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .temp-white-card::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 140px;
        height: 140px;
        background: radial-gradient(circle, rgba(11, 19, 43, 0.18) 0%, rgba(37, 99, 235, 0.05) 50%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* ===== KARTU STATISTIK ATAS (RATA 1 UKURAN) ===== */
    .stat-top-card {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px !important;
        box-shadow: 0 10px 30px rgba(11, 19, 43, 0.08), 0 0 20px rgba(37, 99, 235, 0.12);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-top-card::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(11, 19, 43, 0.18) 0%, rgba(37, 99, 235, 0.05) 50%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Log Terakhir Item */
    .log-item-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
    }
</style>
@endpush

@section('content')
<<<<<<< HEAD
<div class="twinkle-bg-wrapper-light">
    <div class="dashboard-relative-content">
        
        <!-- HEADER MODULE SMART TEMP -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2" style="border-radius: 20px; font-weight: 700;">
                    <i class="fas fa-thermometer-half me-1"></i> Climate Sensors
                </span>
                <h4 class="m-0 font-weight-bold text-dark ms-2" style="font-weight: 800;">Temperature & Humidity Monitoring</h4>
            </div>
        </div>

        <!-- 4 KARTU STATISTIK SUHU & KELEMBAPAN (FRAMELESS) -->
        <div class="row g-4 mb-5">
            <!-- SUHU SAAT INI -->
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="summary-icon-wrapper" style="background: rgba(225, 29, 72, 0.15); color: #e11d48;">
                            <i class="fas fa-temperature-high"></i>
                        </div>
                        <span class="badge bg-danger fw-bold" style="font-size:0.75rem; border-radius:10px;">DHT22</span>
                    </div>
                    <div id="currentTemp" class="glow-red-text" style="font-size: 2.6rem; font-weight: 800; line-height: 1; margin: 16px 0 6px 0;">
                        29.5 <span style="font-size: 1.4rem;">°C</span>
=======
<div class="container-fluid px-0">

    <!-- BARIS 1: KARTU RINGKASAN STATISTIK -->
    <div class="row g-4 mb-4 align-items-stretch">
        <!-- Suhu Saat Ini -->
        <div class="col-md-4">
            <div class="stat-top-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary mb-1" style="font-size: 0.85rem; font-weight: 600;">Suhu Saat Ini</div>
                        <h3 class="fw-bold mb-1 text-dark" id="cardCurrentTemp">-- °C</h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(245, 158, 11, 0.1); color: #d97706; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-temperature-high"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.75rem;" id="tempStatusBadge">Belum Ada Data</span>
                </div>
            </div>
        </div>

        <!-- Kelembapan -->
        <div class="col-md-4">
            <div class="stat-top-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary mb-1" style="font-size: 0.85rem; font-weight: 600;">Kelembapan Udara</div>
                        <h3 class="fw-bold mb-1 text-primary" id="cardCurrentHum">-- %</h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(37, 99, 235, 0.1); color: #2563eb; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-tint"></i>
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
                    </div>
                    <div class="text-dark fw-bold" style="font-size: 0.9rem;">Suhu Lingkungan</div>
                </div>
<<<<<<< HEAD
            </div>

            <!-- KELEMBAPAN SAAT INI -->
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="summary-icon-wrapper" style="background: rgba(2, 132, 199, 0.15); color: #0284c7;">
                            <i class="fas fa-water"></i>
                        </div>
                        <span class="badge bg-info text-dark fw-bold" style="font-size:0.75rem; border-radius:10px;">Humidity</span>
                    </div>
                    <div id="currentHumidity" class="glow-blue-text" style="font-size: 2.6rem; font-weight: 800; line-height: 1; margin: 16px 0 6px 0;">
                        65 <span style="font-size: 1.4rem;">%</span>
                    </div>
                    <div class="text-dark fw-bold" style="font-size: 0.9rem;">Kelembapan Udara</div>
                </div>
            </div>

            <!-- RATA-RATA SUHU -->
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="summary-icon-wrapper" style="background: rgba(234, 179, 8, 0.15); color: #ca8a04;">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <span class="badge bg-warning text-dark fw-bold" style="font-size:0.75rem; border-radius:10px;">Hari Ini</span>
                    </div>
                    <div class="glow-yellow-text" style="font-size: 2.6rem; font-weight: 800; line-height: 1; margin: 16px 0 6px 0;">
                        28.8 <span style="font-size: 1.4rem;">°C</span>
                    </div>
                    <div class="text-dark fw-bold" style="font-size: 0.9rem;">Rata-rata Suhu Harian</div>
                </div>
            </div>

            <!-- STATUS IKLIM -->
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="summary-icon-wrapper" style="background: rgba(16, 185, 129, 0.15); color: #059669;">
                            <i class="fas fa-smile"></i>
                        </div>
                        <span class="badge bg-success fw-bold" style="font-size:0.75rem; border-radius:10px;">Kenyamanan</span>
                    </div>
                    <div style="font-size: 2.2rem; font-weight: 800; line-height: 1.2; margin: 16px 0 6px 0; color: #10b981;">
                        Normal
                    </div>
                    <div class="text-dark fw-bold" style="font-size: 0.9rem;">Status Kondisi Ruang</div>
=======
                <div class="mt-3">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.75rem;" id="humStatusBadge">Belum Ada Data</span>
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- GRAFIK HISTORIS SUHU & KELEMBAPAN (FRAMELESS) -->
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-dark m-0" style="font-size: 1rem; font-weight: 800;">
                            <i class="fas fa-chart-area text-danger me-2"></i> RIWAYAT SUHU & KELEMBAPAN (REALTIME)
                        </h6>
                        <small class="text-muted"><i class="fas fa-sync-alt fa-spin me-1"></i> Update tiap 5 detik</small>
                    </div>
                    <div style="position: relative; height: 360px; width: 100%;">
                        <canvas id="temperatureChart"></canvas>
                    </div>
                </div>
=======
        <!-- Status Kondisi & IoT -->
        <div class="col-md-4">
            <div class="stat-top-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-secondary mb-1" style="font-size: 0.85rem; font-weight: 600;">Status Kondisi & IoT</div>
                        <h3 class="fw-bold mb-1 text-warning" id="cardConditionStatus">Menunggu DB</h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-database"></i>
                    </div>
                </div>
                <div class="mt-3" style="font-size: 0.78rem;" id="iotConnectionStatusText">
                    <span class="spinner-grow spinner-grow-sm text-warning me-1" id="iotDot"></span>
                    <span class="text-warning fw-semibold">Belum ada data masuk dari database</span>
                </div>
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
            </div>
        </div>
    </div>

    <!-- BARIS 2: GRAFIK SUHU & LOG TERAKHIR -->
    <div class="row g-4">
        <!-- Kolom Kiri: Grafik Suhu Real-time -->
        <div class="col-lg-8">
            <div class="temp-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Grafik Suhu dan Kelembapan (°C / %)</h5>
                        <small class="text-muted">Grafik akan aktif otomatis setelah telemetri masuk dari database</small>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary active">Hari</button>
                        <button type="button" class="btn btn-outline-secondary">Minggu</button>
                        <button type="button" class="btn btn-outline-secondary">Bulan</button>
                    </div>
                </div>

                <!-- Canvas Grafik Chart.js -->
                <div style="position: relative; height: 280px; width: 100%;" class="mt-3">
                    <canvas id="tempChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Log Terakhir Sensor -->
        <div class="col-lg-4">
            <div class="temp-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0" style="color: #0f172a;">Log Terakhir</h6>
                    <a href="#" class="text-decoration-none text-primary" style="font-size: 0.8rem; font-weight: 600;">Lihat Semua</a>
                </div>

                <div class="mt-3" id="logContainer">
                    <div class="log-item-row">
                        <div>
                            <div class="fw-bold text-dark">Zona Utama Kota</div>
                            <small class="text-muted">Belum ada catatan</small>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-muted" id="logTempVal">-- °C</span> / <span class="text-muted" id="logHumVal">-- %</span>
                            <div><span class="badge rounded-pill bg-secondary" style="font-size: 0.6rem;">Menunggu DB</span></div>
                        </div>
                    </div>
                </div>

                <div class="text-center py-3 text-muted border-top mt-3">
                    <small style="font-size: 0.72rem;">Belum ada data masuk dari MQTT/Database.</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
<<<<<<< HEAD
    function fetchTempSensorData() {
        fetch('/api/environment')
            .then(res => res.json())
            .then(data => {
                if (data.temperature) {
                    const tempElem = document.getElementById('currentTemp');
                    if (tempElem) tempElem.innerHTML = `${data.temperature} <span style="font-size: 1.4rem;">°C</span>`;
                }
                if (data.humidity) {
                    const humElem = document.getElementById('currentHumidity');
                    if (humElem) humElem.innerHTML = `${data.humidity} <span style="font-size: 1.4rem;">%</span>`;
                }
            })
            .catch(err => console.log('Error fetching temp env data:', err));
    }

    function renderTempChart() {
        const tempCanvas = document.getElementById('temperatureChart');
        if (tempCanvas) {
            new Chart(tempCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00'],
                    datasets: [
                        { 
                            label: 'Suhu (°C)', 
                            data: [27.2, 28.0, 28.5, 29.5, 31.0, 30.5, 29.8], 
                            borderColor: '#e11d48', 
                            backgroundColor: 'rgba(225, 29, 72, 0.15)', 
                            fill: true, 
                            tension: 0.4,
                            borderWidth: 3
                        },
                        { 
                            label: 'Kelembapan (%)', 
                            data: [72, 70, 68, 65, 60, 62, 65], 
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
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchTempSensorData();
        setInterval(fetchTempSensorData, 5000);
        renderTempChart();
=======
    // INISIALISASI CHART.JS
    const tempCtx = document.getElementById('tempChart')?.getContext('2d');
    let tempChart;

    if (tempCtx) {
        tempChart = new Chart(tempCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Suhu (°C)',
                        data: [],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.05)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2
                    },
                    {
                        label: 'Kelembapan (%)',
                        data: [],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } }
                },
                scales: {
                    x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(0, 0, 0, 0.05)' }, min: 0, max: 100 }
                }
            }
        });
    }

    function checkDatabaseTempData() {
        fetch('/api/environment')
            .then(res => {
                if (!res.ok) throw new Error('Database kosong / belum ada data');
                return res.json();
            })
            .then(data => {
                // Jika data dari database valid dan ada isinya
                if (data && (data.temperature !== undefined || data.temp !== undefined)) {
                    const temp = data.temperature !== undefined ? data.temperature : data.temp;
                    const hum = data.humidity !== undefined ? data.humidity : data.hum;

                    document.getElementById('cardCurrentTemp').textContent = temp + ' °C';
                    document.getElementById('cardCurrentHum').textContent = hum + ' %';
                    document.getElementById('logTempVal').textContent = temp + ' °C';
                    document.getElementById('logHumVal').textContent = hum + ' %';

                    document.getElementById('cardConditionStatus').textContent = 'Optimal (Online)';
                    document.getElementById('cardConditionStatus').className = 'fw-bold mb-1 text-success';
                    document.getElementById('tempStatusBadge').className = 'badge bg-success text-white px-3 py-1 rounded-pill';
                    document.getElementById('tempStatusBadge').textContent = 'Normal';
                    document.getElementById('humStatusBadge').className = 'badge bg-primary text-white px-3 py-1 rounded-pill';
                    document.getElementById('humStatusBadge').textContent = 'Normal';

                    document.getElementById('iotConnectionStatusText').innerHTML = '<span class="spinner-grow spinner-grow-sm text-success me-1"></span><span class="text-success fw-semibold">Terhubung ke Database (Live)</span>';

                    // Masukkan ke Grafik
                    if (tempChart) {
                        const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        tempChart.data.labels.push(now);
                        tempChart.data.datasets[0].data.push(temp);
                        tempChart.data.datasets[1].data.push(hum);

                        if (tempChart.data.labels.length > 15) {
                            tempChart.data.labels.shift();
                            tempChart.data.datasets.forEach(ds => ds.data.shift());
                        }
                        tempChart.update();
                    }
                }
            })
            .catch(() => {
                // Status saat database / API belum merespons data (tetap menampilkan status menunggu)
                document.getElementById('cardCurrentTemp').textContent = '-- °C';
                document.getElementById('cardCurrentHum').textContent = '-- %';
                document.getElementById('cardConditionStatus').textContent = 'Menunggu DB';
                document.getElementById('iotConnectionStatusText').innerHTML = '<span class="spinner-grow spinner-grow-sm text-warning me-1"></span><span class="text-warning fw-semibold">Belum ada data masuk dari database</span>';
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        checkDatabaseTempData();
        setInterval(checkDatabaseTempData, 5000); // Mengecek pembaruan data setiap 5 detik
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
    });
</script>
@endpush