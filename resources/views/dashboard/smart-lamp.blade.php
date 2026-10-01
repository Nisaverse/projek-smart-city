@extends('layouts.app')

@section('title', 'Smart Lamp Monitoring')
@section('page-title', 'Smart Lamp Monitoring')

@push('styles')
<style>
    /* ===== GLASSMORPHISM CARD ===== */
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        border-color: rgba(59, 130, 246, 0.3);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
    }

    /* ===== LAMP CARD STYLING ===== */
    .lamp-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .lamp-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        background: rgba(255, 255, 255, 0.05);
        color: #64748b;
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.3s ease;
    }

    /* Glow Effect saat Lampu ON */
    .lamp-card.active {
        border-color: rgba(245, 158, 11, 0.4);
        background: rgba(245, 158, 11, 0.05);
    }

    .lamp-card.active .lamp-icon-box {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.6);
        border-color: #f59e0b;
    }

    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.on {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-badge.off {
        background: rgba(100, 116, 139, 0.2);
        color: #94a3b8;
        border: 1px solid rgba(100, 116, 139, 0.3);
    }

    /* Info Label */
    .info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        font-size: 0.95rem;
        color: #cbd5e1;
    }

    .info-item i {
        width: 20px;
        color: #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <!-- BARIS KARTU ATAS -->
    <div class="row g-4 mb-4">
        <!-- System Status Card -->
        <div class="col-lg-5">
            <div class="glass-card h-100">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="fas fa-cog text-primary fs-5"></i>
                    <h6 class="m-0 fw-bold text-light">System Status</h6>
                </div>

                <div class="info-item">
                    <i class="fas fa-hand-pointer"></i>
                    <span>Mode: <strong id="systemMode" class="text-light">Manual</strong></span>
                </div>

                <div class="info-item">
                    <i class="fas fa-sun"></i>
                    <span>Light Sensor: <strong id="lightSensorVal" class="text-warning">65.0%</strong></span>
                </div>

                <div class="info-item mb-0">
                    <i class="fas fa-clock"></i>
                    <span>Time: <strong id="systemTime" class="text-light">12:49:20</strong></span>
                </div>
            </div>
        </div>

        <!-- Ambient Light Monitoring Card -->
        <div class="col-lg-7">
            <div class="glass-card h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fas fa-sun text-warning fs-5"></i>
                            <h6 class="m-0 fw-bold text-light">Ambient Light Monitoring</h6>
                        </div>
                        <small class="text-secondary">Current Light Level</small>
                        <div class="display-5 fw-bold text-light my-1" id="currentLightText">65.0<span class="fs-4">%</span></div>
                    </div>

                    <!-- Ringkasan Statistik -->
                    <div class="text-end" style="min-width: 140px;">
                        <small class="text-secondary d-block mb-1">Today's Statistics</small>
                        <div class="d-flex justify-content-between text-secondary fs-7 mb-1">
                            <span>Max:</span> <strong class="text-light" id="statMax">65.0%</strong>
                        </div>
                        <div class="d-flex justify-content-between text-secondary fs-7 mb-1">
                            <span>Min:</span> <strong class="text-light" id="statMin">65.0%</strong>
                        </div>
                        <div class="d-flex justify-content-between text-secondary fs-7">
                            <span>Avg:</span> <strong class="text-light" id="statAvg">65.0%</strong>
                        </div>
                    </div>
                </div>

                <!-- Canvas Chart -->
                <div style="height: 140px; position: relative;">
                    <canvas id="ambientChart"></canvas>
                </div>

                <!-- Indicator Alert Banner -->
                <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center gap-2 text-warning fs-7">
                    <i class="fas fa-sun fs-6"></i>
                    <span><strong>TERANG</strong> (Lampu seharusnya OFF)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS KONTROL LAMPU JALAN -->
    <div class="row g-4">
        <!-- Street Lamp A -->
        <div class="col-md-6 col-xl-3">
            <div class="lamp-card active" id="cardLampA">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="lamp-icon-box">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <span class="status-badge on" id="badgeLampA">ON</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h6 class="fw-bold text-light mb-0">Street Lamp A</h6>
                        <small class="text-secondary fs-7">Jl. Utama</small>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="switchLampA" checked onchange="toggleLamp('A', this.checked)">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Street Lamp B -->
        <div class="col-md-6 col-xl-3">
            <div class="lamp-card active" id="cardLampB">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="lamp-icon-box">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <span class="status-badge on" id="badgeLampB">ON</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h6 class="fw-bold text-light mb-0">Street Lamp B</h6>
                        <small class="text-secondary fs-7">Jl. Sudirman</small>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="switchLampB" checked onchange="toggleLamp('B', this.checked)">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Street Lamp C -->
        <div class="col-md-6 col-xl-3">
            <div class="lamp-card" id="cardLampC">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="lamp-icon-box">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <span class="status-badge off" id="badgeLampC">OFF</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h6 class="fw-bold text-light mb-0">Street Lamp C</h6>
                        <small class="text-secondary fs-7">Jl. Gatot Subroto</small>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="switchLampC" onchange="toggleLamp('C', this.checked)">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Street Lamp D -->
        <div class="col-md-6 col-xl-3">
            <div class="lamp-card" id="cardLampD">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="lamp-icon-box">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <span class="status-badge off" id="badgeLampD">OFF</span>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <h6 class="fw-bold text-light mb-0">Street Lamp D</h6>
                        <small class="text-secondary fs-7">Jl. Ahmad Yani</small>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="switchLampD" onchange="toggleLamp('D', this.checked)">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Library Chart.js & Paho MQTT -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js"></script>

<script>
    // ===== CONFIGURASI GRAFIK CHART.JS =====
    const ctx = document.getElementById('ambientChart').getContext('2d');
    
    // Gradient Warna Oranye/Emas Glow
    const gradient = ctx.createLinearGradient(0, 0, 0, 140);
    gradient.addColorStop(0, 'rgba(245, 158, 11, 0.4)');
    gradient.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

    const chartData = {
        labels: ['12:40', '12:42', '12:44', '12:46', '12:48', '12:49'],
        datasets: [{
            label: 'Light Level (%)',
            data: [45, 52, 48, 58, 62, 65],
            borderColor: '#f59e0b',
            borderWidth: 2,
            pointBackgroundColor: '#fbbf24',
            pointBorderColor: '#ffffff',
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            backgroundColor: gradient,
            tension: 0.4
        }]
    };

    const ambientChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#64748b', font: { size: 10 } }
                },
                y: {
                    min: 0,
                    max: 100,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    ticks: { color: '#64748b', font: { size: 10 } }
                }
            }
        }
    });

    // ===== LOGIKA TOGGLE SAKELAR LAMPU =====
    function toggleLamp(id, isChecked) {
        const card = document.getElementById(`cardLamp${id}`);
        const badge = document.getElementById(`badgeLamp${id}`);

        if (isChecked) {
            card.classList.add('active');
            badge.className = 'status-badge on';
            badge.textContent = 'ON';
        } else {
            card.classList.remove('active');
            badge.className = 'status-badge off';
            badge.textContent = 'OFF';
        }

        // Kirim perintah MQTT ke ESP32
        if (typeof mqttClient !== 'undefined' && mqttClient && mqttClient.connected) {
            const topic = `smartcity/lamp/${id.toLowerCase()}/command`;
            const payload = isChecked ? "ON" : "OFF";
            mqttClient.publish(topic, payload);
        }
    }

    // ===== HANDLE REALTIME MQTT DATA INCOMING =====
    function handleMQTTMessage(topic, payload) {
        // Contoh penanganan topik data sensor LDR
        if (topic === 'smartcity/sensor/light') {
            const val = parseFloat(payload).toFixed(1);
            document.getElementById('lightSensorVal').textContent = val + '%';
            document.getElementById('currentLightText').innerHTML = val + '<span class="fs-4">%</span>';

            // Push ke Chart
            const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            ambientChart.data.labels.push(now);
            ambientChart.data.datasets[0].data.push(val);
            
            if (ambientChart.data.labels.length > 10) {
                ambientChart.data.labels.shift();
                ambientChart.data.datasets[0].data.shift();
            }
            ambientChart.update();
        }
    }
</script>
@endpush