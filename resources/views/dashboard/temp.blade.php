@extends('layouts.app')
@section('title', 'Smart Temperature')
@section('page-title', 'Smart Temperature Monitoring')

@push('styles')
<style>
    /* ===== KARTU UTAMA & STATISTIK (PUTIH BERSIH) ===== */
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
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Suhu Saat Ini</div>
                    <h3 class="fw-bold mb-1 text-dark" id="cardCurrentTemp">-- °C</h3>
                </div>
                <div class="text-dark opacity-75 mt-2" style="font-size: 0.75rem; font-weight: 600;">Suhu lingkungan wilayah</div>
            </div>
        </div>

        <!-- Kelembapan Udara -->
        <div class="col-md-4">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Kelembapan Udara</div>
                    <h3 class="fw-bold mb-1 text-dark" id="cardCurrentHum">-- %</h3>
                </div>
                <div class="text-dark opacity-75 mt-2" style="font-size: 0.75rem; font-weight: 600;">Kelembapan relatif wilayah</div>
            </div>
        </div>

        <!-- Status IoT -->
        <div class="col-md-4">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Status IoT</div>
                    <h3 class="fw-bold mb-1 text-dark" id="iotMainStatus">-</h3>
                </div>
                <div style="font-size: 0.8rem;" class="text-dark fw-bold mt-2">
                    <span id="iotStatusText">Menunggu data perangkat</span>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 2: GRAFIK SUHU & LOG TERAKHIR -->
    <div class="row g-4">
        <!-- Kolom Kiri: Grafik Suhu & Kelembapan (Tombol Hari/Minggu Dihapus, Grafik Mengalir dari Kiri) -->
        <div class="col-lg-8">
            <div class="temp-white-card">
                <div class="mb-3">
                    <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Grafik Suhu dan Kelembapan (°C / %)</h5>
                    <small class="text-dark opacity-75 fw-semibold">Grafik otomatis memperbarui data pemantauan berkala</small>
                </div>

                <!-- Canvas Grafik Chart.js dengan Navy Gelap -->
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
    // INISIALISASI CHART.JS DENGAN MENGATUR BATAS MAKSIMAL TITIK DATA (SLIDING WINDOW)
    const tempCtx = document.getElementById('tempChart')?.getContext('2d');
    let tempChart;
    const maxDataPoints = 10; // Batas jumlah titik di grafik agar mengalir rapi dari kiri ke kanan

    if (tempCtx) {
        tempChart = new Chart(tempCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Suhu (°C)',
                        data: [],
                        borderColor: '#0b132b',
                        backgroundColor: 'rgba(11, 19, 43, 0.12)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2.5
                    },
                    {
                        label: 'Kelembapan (%)',
                        data: [],
                        borderColor: '#1e3a8a',
                        backgroundColor: 'rgba(30, 58, 138, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2.5
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

                    document.getElementById('iotMainStatus').textContent = 'Online';
                    document.getElementById('iotStatusText').textContent = 'Terhubung';

                    // Update grafik secara dinamis mengalir dari kiri ke kanan
                    if (tempChart) {
                        const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        
                        tempChart.data.labels.push(now);
                        tempChart.data.datasets[0].data.push(temp);
                        tempChart.data.datasets[1].data.push(hum);

                        // Jika data melebihi batas max, hapus data terlama di sebelah kiri
                        if (tempChart.data.labels.length > maxDataPoints) {
                            tempChart.data.labels.shift();
                            tempChart.data.datasets[0].data.shift();
                            tempChart.data.datasets[1].data.shift();
                        }

                        tempChart.update();
                    }
                }
            })
            .catch(() => {
                document.getElementById('cardCurrentTemp').textContent = '-- °C';
                document.getElementById('cardCurrentHum').textContent = '-- %';
                document.getElementById('iotMainStatus').textContent = '-';
                document.getElementById('iotStatusText').textContent = 'Menunggu data perangkat';
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        checkDatabaseTempData();
        setInterval(checkDatabaseTempData, 5000);
    });
</script>
@endpush