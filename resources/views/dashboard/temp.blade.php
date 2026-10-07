@extends('layouts.app')
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
    }
</style>
@endpush

@section('content')
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
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.75rem;" id="humStatusBadge">Belum Ada Data</span>
                </div>
            </div>
        </div>

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
    });
</script>
@endpush