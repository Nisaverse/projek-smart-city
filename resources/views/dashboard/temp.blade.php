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

    <!-- BARIS 1: 6 KARTU PARAMETER LINGKUNGAN -->
    <div class="row g-3 mb-4">
        
        <!-- 1. Suhu Saat Ini -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Suhu Saat Ini</small>
                    <h4 class="fw-bold my-1 text-dark" id="envTemp">-- °C</h4>
                </div>
                <small class="text-muted" style="font-size: 0.75rem;">Suhu lingkungan</small>
            </div>
        </div>

        <!-- 2. Kelembapan Udara -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Kelembapan</small>
                    <h4 class="fw-bold my-1 text-dark" id="envHumidity">-- %</h4>
                </div>
                <small class="text-muted" style="font-size: 0.75rem;">Kelembapan relatif</small>
            </div>
        </div>

        <!-- 3. Kualitas Udara (AQI) -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Kualitas Udara</small>
                    <h4 class="fw-bold my-1 text-dark" id="envAqi">-- AQI</h4>
                </div>
                <div>
                    <span class="text-dark px-2 py-1" style="font-size: 0.75rem;" id="envAqiStatus">-</span>
                </div>
            </div>
        </div>

        <!-- 4. Status Hujan -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Status Hujan</small>
                    <h4 class="fw-bold my-1 text-dark" id="envRain">Menunggu</h4>
                </div>
                <small class="text-muted" style="font-size: 0.75rem;" id="envRainDesc">Belum ada data</small>
            </div>
        </div>

        <!-- 5. Ketinggian Air Sungai -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Air Sungai</small>
                    <h4 class="fw-bold my-1 text-dark" id="envWaterLevel">-- cm</h4>
                </div>
                <div>
                    <span class="text-muted px-2 py-1" style="font-size: 0.75rem;" id="envWaterStatus">-</span>
                </div>
            </div>
        </div>

        <!-- 6. Status Sistem IoT -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-top-card">
                <div>
                    <small class="text-dark fw-bold" style="font-size: 0.75rem;">Status IoT</small>
                    <h4 class="fw-bold my-1 text-dark" id="envIotStatus">Offline</h4>
                </div>
                <small class="text-dark fw-bold" style="font-size: 0.75rem;" id="envIotDesc">Menunggu data</small>
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
        <div class="col-lg-4 align-self-start">
            <div class="dashboard-white-card" style="display: block !important; height: auto !important;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Riwayat Terakhir</h5>
                    <a href="#" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.8rem;">Lihat Semua</a>
                </div>

                <div class="dashboard-inner-box p-3" style="height: auto !important; min-height: unset !important;">
                    <div class="d-flex flex-column gap-2" id="envLogContainer">
                        
                        <!-- Log Item -->
                        <div class="p-3 bg-white border rounded-3 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark" style="font-size: 0.85rem;">Zona Utama Kota</span>
                                <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem;">Terbaru</span>
                            </div>
                            <div class="text-secondary fw-medium" style="font-size: 0.75rem; line-height: 1.4;">
                                Suhu: 31.5°C | Air: 135.5cm | AQI: 48
                            </div>
                            <small class="text-muted d-block mt-2" style="font-size: 0.7rem;">Pukul 11:26:32</small>
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
<!-- Tambahkan Library MQTT.js via CDN agar browser bisa menangkap pesan MQTT -->
<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

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

    // 1. AMBIL DATA DARI DATABASE UNTUK TAMPILAN KARTU & GRAFIK
    function fetchEnvironmentData() {
        fetch('/api/environment')
            .then(res => res.json())
            .then(data => {
                if (data && data.has_data === true) {
                    const temp = data.temperature;
                    const hum = data.humidity;
                    const aqi = data.air_quality;
                    const rainStatus = data.rain_status;
                    const waterLevel = data.water_level;
                    const timeStr = data.timestamp;

                    document.getElementById('envTemp').textContent = temp + ' °C';
                    document.getElementById('envHumidity').textContent = hum + ' %';
                    document.getElementById('envAqi').textContent = aqi + ' AQI';
                    document.getElementById('envRain').textContent = rainStatus;
                    document.getElementById('envWaterLevel').textContent = waterLevel + ' cm';

                    document.getElementById('envIotStatus').textContent = 'Online';
                    document.getElementById('envIotDesc').textContent = 'Terhubung';

                    const aqiStatus = document.getElementById('envAqiStatus');
                    if (aqi <= 50) {
                        aqiStatus.textContent = 'Baik';
                        aqiStatus.className = 'text-dark px-2 py-1 rounded-pill';
                    } else {
                        aqiStatus.textContent = 'Kurang Sehat';
                        aqiStatus.className = 'text-dark px-2 py-1 rounded-pill';
                    }

                    document.getElementById('envRainDesc').textContent = rainStatus.toLowerCase().includes('hujan') ? 'Presipitasi Terdeteksi' : 'Tidak Presipitasi';

                    const waterBadge = document.getElementById('envWaterStatus');
                    if (waterLevel > 200) {
                        waterBadge.textContent = 'Bahaya (Banjir)';
                        waterBadge.className = 'text-danger px-2 py-1 rounded-pill';
                    } else if (waterLevel > 150) {
                        waterBadge.textContent = 'Waspada';
                        waterBadge.className = 'text-warning px-2 py-1 rounded-pill';
                    } else {
                        waterBadge.textContent = 'Aman';
                        waterBadge.className = 'text-dark px-2 py-1 rounded-pill';
                    }

                    const logContainer = document.getElementById('envLogContainer');
                    if (logContainer) {
                        logContainer.innerHTML = `
                            <div class="p-3 bg-white border rounded-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark" style="font-size: 0.85rem;">Zona Utama Kota</span>
                                    <span class="badge bg-success text-white" style="font-size: 0.65rem;">Terbaru</span>
                                </div>
                                <div class="text-secondary" style="font-size: 0.75rem;">
                                    Suhu: ${temp}°C | Air: ${waterLevel}cm | AQI: ${aqi}
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Pukul ${timeStr}</small>
                            </div>
                        `;
                    }

                    if (envChart) {
                        envChart.data.labels.push(timeStr);
                        envChart.data.datasets[0].data.push(temp);
                        envChart.data.datasets[1].data.push(hum);
                        envChart.data.datasets[2].data.push(waterLevel);

                        if (envChart.data.labels.length > maxPoints) {
                            envChart.data.labels.shift();
                            envChart.data.datasets.forEach(ds => ds.data.shift());
                        }
                        envChart.update();
                    }
                } else {
                    document.getElementById('envTemp').textContent = '-- °C';
                    document.getElementById('envHumidity').textContent = '-- %';
                    document.getElementById('envAqi').textContent = '-- AQI';
                    document.getElementById('envRain').textContent = 'Menunggu';
                    document.getElementById('envWaterLevel').textContent = '-- cm';
                    document.getElementById('envIotStatus').textContent = 'Offline';
                    document.getElementById('envIotDesc').textContent = 'Menunggu data';
                }
            }).catch(() => {});
    }

    // 2. KONEKSI MQTT DI BROWSER & JEMBATAN KE DATABASE
    document.addEventListener('DOMContentLoaded', () => {
        fetchEnvironmentData();
        setInterval(fetchEnvironmentData, 5000);

        // Menghubungkan ke broker HiveMQ Cloud via WebSocket
        if (typeof mqtt !== 'undefined') {
            const mqttClient = mqtt.connect('wss://broker.hivemq.com:8884/mqtt', {
                clientId: 'admin_dashboard_' + Math.random().toString(16).substring(2, 8)
            });

            mqttClient.on('connect', function () {
                console.log("✅ Admin terhubung ke MQTT Broker!");
                // Subscribe ke topic telemetri alat ESP32 kamu
                mqttClient.subscribe('aethersense/+/telemetry', function (err) {
                    if (!err) {
                        console.log("📋 Berhasil subscribe ke topic aethersense/+/telemetry");
                    }
                });
            });

            mqttClient.on('message', function (topic, message) {
                let payloadStr = message.toString();
                console.log("📨 Pesan MQTT Masuk:", payloadStr);

                try {
                    let data = JSON.parse(payloadStr);

                    // 👉 KIRIM DATA OTOMATIS KE BACKEND LARAVEL AGAR MASUK KE DATABASE
                    fetch('/api/environment/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            temperature: data.temperature || 0,
                            humidity: data.humidity || 0,
                            air_quality: data.air_quality || 40,
                            rain_status: data.rain_status || 'Cerah',
                            water_level: data.water_level || 100
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        console.log("💾 Data MQTT sukses masuk database MySQL:", res);
                        // Langsung panggil ulang fetch data agar grafik & kartu langsung terupdate
                        fetchEnvironmentData();
                    })
                    .catch(err => {
                        console.error("❌ Gagal menyimpan data ke database:", err);
                    });

                } catch (e) {
                    console.error("⚠️ Format pesan bukan JSON:", e);
                }
            });
        }
    });
</script>
@endpush