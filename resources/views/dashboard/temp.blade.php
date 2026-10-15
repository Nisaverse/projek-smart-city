@extends('layouts.app')
@section('title', 'Smart Temperature')
@section('page-title', 'Smart Temperature Monitoring')

@push('styles')
<style>
    /* Mengubah semua card utama & statistik menjadi putih bersih */
    .dashboard-white-card,
    .stat-top-card,
    .parking-white-card,
    .lamp-white-card,
    .temp-white-card {
        background: #ffffff !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 24px !important;
        box-shadow: 0 10px 30px rgba(11, 19, 43, 0.08) !important;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    /* Khusus kartu atas disamakan tingginya & rata */
    .stat-top-card {
        padding: 20px !important;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .temp-white-card:hover,
    .stat-top-card:hover {
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.22), inset 0 1px 3px rgba(255, 255, 255, 1) !important;
        transform: translateY(-2px);
    }

    /* Log Terakhir Item */
    .log-item-row {
        background: #ffffff;
        border: 1px solid #cbd5e1;
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
                        <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Suhu Saat Ini</div>
                        <h3 class="fw-bold mb-1 text-dark" id="cardCurrentTemp">-- °C</h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(245, 158, 11, 0.2); color: #b45309; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
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
                        <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Kelembapan Udara</div>
                        <h3 class="fw-bold mb-1 text-dark" id="cardCurrentHum">-- %</h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(37, 99, 235, 0.2); color: #1d4ed8; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
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
                        <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Status Kondisi & IoT</div>
                        <h3 class="fw-bold mb-1 text-danger" id="cardConditionStatus">Offline</h3>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(239, 68, 68, 0.2); color: #b91c1c; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-database"></i>
                    </div>
                </div>
                <div class="mt-3" style="font-size: 0.78rem;" id="iotConnectionStatusText">
                    <span class="spinner-grow spinner-grow-sm text-danger me-1" id="iotDot"></span>
                    <span class="text-danger fw-semibold">Belum ada data masuk dari database</span>
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
                        <small class="text-dark opacity-75 fw-semibold">Grafik siap merekam data setelah masuk dari database</small>
                    </div>
                    <!-- Tombol Periode: Hari & Minggu Saja -->
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-dark active">Hari</button>
                        <button type="button" class="btn btn-outline-dark">Minggu</button>
                    </div>
                </div>

                <!-- Canvas Grafik Chart.js (Kerangka tampil, garis diam menunggu data) -->
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

                <div class="text-center py-3 text-dark opacity-75 border-top mt-3">
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
    // INISIALISASI KERANGKA CHART.JS (TAMPIL KOTAK & SUMBU, NAMUN GARIS DIAM)
    const tempCtx = document.getElementById('tempChart')?.getContext('2d');
    let tempChart;

    if (tempCtx) {
        tempChart = new Chart(tempCtx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'], // Kerangka jam tampil
                datasets: [
                    {
                        label: 'Suhu (°C)',
                        data: [null, null, null, null, null, null], // Kosong agar garis tidak jalan
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2
                    },
                    {
                        label: 'Kelembapan (%)',
                        data: [null, null, null, null, null, null], // Kosong agar garis tidak jalan
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
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
                    x: { ticks: { color: '#475569' }, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    y: { ticks: { color: '#475569' }, grid: { color: 'rgba(0, 0, 0, 0.05)' }, min: 0, max: 100 }
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

                    // Update grafik saat data riil masuk
                    if (tempChart) {
                        const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        tempChart.data.labels.push(now);
                        tempChart.data.datasets[0].data.push(temp);
                        tempChart.data.datasets[1].data.push(hum);
                        tempChart.update();
                    }
                }
            })
            .catch(() => {
                // Tetap berada pada status OFFLINE & grafik diam jika database belum merespons
                document.getElementById('cardCurrentTemp').textContent = '-- °C';
                document.getElementById('cardCurrentHum').textContent = '-- %';
                document.getElementById('cardConditionStatus').textContent = 'Offline';
                document.getElementById('cardConditionStatus').className = 'fw-bold mb-1 text-danger';
                document.getElementById('tempStatusBadge').className = 'badge bg-secondary text-white px-3 py-1 rounded-pill';
                document.getElementById('tempStatusBadge').textContent = 'Belum Ada Data';
                document.getElementById('humStatusBadge').className = 'badge bg-secondary text-white px-3 py-1 rounded-pill';
                document.getElementById('humStatusBadge').textContent = 'Belum Ada Data';
                document.getElementById('iotConnectionStatusText').innerHTML = '<span class="spinner-grow spinner-grow-sm text-danger me-1"></span><span class="text-danger fw-semibold">Belum ada data masuk dari database</span>';
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        checkDatabaseTempData();
        setInterval(checkDatabaseTempData, 5000);
    });
</script>
@endpush