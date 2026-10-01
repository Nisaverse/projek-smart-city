@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')

@push('styles')
<style>
    /* ===== GLASSMORPHISM CARD STYLING ===== */
    .glass-card {
        background: rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card:hover {
        border-color: rgba(59, 130, 246, 0.35);
        transform: translateY(-3px);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    /* Badge Tag Kota Tegal */
    .badge-tegal {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 20px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Custom Scrollbar untuk Riwayat Alert */
    #globalTempHistory::-webkit-scrollbar {
        width: 5px;
    }
    #globalTempHistory::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }
    #globalTempHistory::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 4px;
    }
    #globalTempHistory::-webkit-scrollbar-thumb:hover {
        background: rgba(239, 68, 68, 0.5);
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <!-- Header Penanda Kota Tegal & Sambutan -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="badge-tegal mb-2">
                <i class="fas fa-map-marker-alt"></i> Kota Tegal
            </div>
            <h4 class="fw-bold m-0 text-light" style="letter-spacing: -0.3px;">Smart City Monitoring</h4>
        </div>
    </div>

    <!-- KARTU STATISTIK (GLASSMORPHISM) -->
    <div class="row g-4 mb-4">
        <!-- Smart Lamp Active -->
        <div class="col-sm-6 col-xl-3">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                </div>
                <div class="value fs-2 fw-bold text-light mb-1" id="activeLamps">-</div>
                <div class="label text-secondary fs-7">Smart Lamps Active</div>
            </div>
        </div>

        <!-- Suhu & Kelembapan -->
        <div class="col-sm-6 col-xl-3">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);">
                        <i class="fas fa-temperature-high"></i>
                    </div>
                </div>
                <div class="value fs-2 fw-bold text-light mb-1" id="dashTemp">--°C</div>
                <div class="label text-secondary fs-7" id="dashHum">Suhu & Kelembapan (--%)</div>
            </div>
        </div>

        <!-- Parking Available -->
        <div class="col-sm-6 col-xl-3">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); box-shadow: 0 0 15px rgba(245, 158, 11, 0.2);">
                        <i class="fas fa-car"></i>
                    </div>
                </div>
                <div class="value fs-2 fw-bold text-light mb-1" id="parkingAvailable">-</div>
                <div class="label text-secondary fs-7">Parking Available</div>
            </div>
        </div>

        <!-- Active Alerts -->
        <div class="col-sm-6 col-xl-3">
            <div class="glass-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="stat-icon" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="value fs-2 fw-bold text-light mb-1" id="alertsCount">-</div>
                <div class="label text-secondary fs-7">Active Alerts</div>
            </div>
        </div>
    </div>

    <!-- GRAFIK SENSOR & PARKIR -->
    <div class="row g-4 mb-4">
        <!-- Grafik Line Sensor -->
        <div class="col-lg-8">
            <div class="glass-card h-100">
                <h6 class="text-light fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                    <i class="fas fa-chart-line text-primary"></i> REALTIME SENSOR DATA
                </h6>
                <div style="position: relative; height: 220px;">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Pie Parkir -->
        <div class="col-lg-4">
            <div class="glass-card h-100">
                <h6 class="text-light fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                    <i class="fas fa-chart-pie text-success"></i> PARKING OCCUPANCY
                </h6>
                <div style="position: relative; height: 220px;" class="d-flex align-items-center justify-content-center">
                    <canvas id="parkingChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- RIWAYAT PERINGATAN SUHU EKSTREM -->
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-25">
            <h6 class="m-0 text-light fw-bold d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                <i class="fas fa-bell text-warning"></i> RIWAYAT PERINGATAN SUHU EKSTREM
            </h6>
            <button onclick="clearGlobalTempHistory()" class="btn btn-sm btn-outline-danger rounded-3 fs-7 py-1 px-3">
                <i class="fas fa-trash-alt me-1"></i> Clear History
            </button>
        </div>
        
        <div id="globalTempHistory" style="max-height: 250px; overflow-y: auto;">
            <div class="text-center text-secondary py-4 fs-7">
                <i class="fas fa-inbox fs-3 mb-2 d-block opacity-50"></i>
                Belum ada riwayat peringatan suhu
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ===== MAIN CHART CONFIG =====
    const mainCtx = document.getElementById('mainChart').getContext('2d');
    const mainChart = new Chart(mainCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                { 
                    label: 'Lamp Brightness', 
                    data: [], 
                    borderColor: '#38bdf8', 
                    backgroundColor: 'rgba(56, 189, 248, 0.1)', 
                    fill: true, 
                    tension: 0.4, 
                    borderWidth: 2,
                    pointBackgroundColor: '#38bdf8'
                },
                { 
                    label: 'Suhu (°C)', 
                    data: [], 
                    borderColor: '#f87171', 
                    backgroundColor: 'rgba(248, 113, 113, 0.1)', 
                    fill: true, 
                    tension: 0.4, 
                    borderWidth: 2,
                    pointBackgroundColor: '#f87171'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    labels: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } } 
                } 
            },
            scales: {
                x: { 
                    ticks: { color: '#64748b', maxTicksLimit: 8, font: { size: 10 } }, 
                    grid: { color: 'rgba(255, 255, 255, 0.05)' } 
                },
                y: { 
                    ticks: { color: '#64748b', font: { size: 10 } }, 
                    grid: { color: 'rgba(255, 255, 255, 0.05)' }, 
                    min: 0, 
                    max: 100 
                }
            }
        }
    });

    // ===== PARKING CHART CONFIG =====
    const parkCtx = document.getElementById('parkingChart').getContext('2d');
    const parkingChart = new Chart(parkCtx, {
        type: 'doughnut',
        data: {
            labels: ['Occupied', 'Available'],
            datasets: [{
                data: [25, 25],
                backgroundColor: ['#f87171', '#10b981'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom', 
                    labels: { color: '#94a3b8', padding: 15, font: { family: 'Plus Jakarta Sans', size: 11 } } 
                } 
            }
        }
    });

    // ===== FETCH DASHBOARD DATA =====
    function fetchDashboardData() {
        fetch('/api/dashboard')
            .then(r => r.json())
            .then(data => {
                document.getElementById('activeLamps').textContent = data.active_lamps || '-';
                document.getElementById('parkingAvailable').textContent = data.parking_available || '-';
                document.getElementById('alertsCount').textContent = data.alerts || '0';

                // Update Suhu & Kelembapan
                if (data.temperature !== undefined) {
                    document.getElementById('dashTemp').textContent = data.temperature + '°C';
                }
                if (data.humidity !== undefined) {
                    document.getElementById('dashHum').textContent = 'Kelembapan: ' + data.humidity + '%';
                }

                // Update Grafik Realtime
                const now = new Date().toLocaleTimeString('id-ID');
                mainChart.data.labels.push(now);
                mainChart.data.datasets[0].data.push(data.avg_lamp_brightness || 0);
                mainChart.data.datasets[1].data.push(data.temperature || 0);

                if (mainChart.data.labels.length > 15) {
                    mainChart.data.labels.shift();
                    mainChart.data.datasets.forEach(ds => ds.data.shift());
                }
                mainChart.update('none');

                fetch('/api/parking')
                    .then(r => r.json())
                    .then(parking => {
                        parkingChart.data.datasets[0].data = [parking.occupied, parking.available];
                        parkingChart.update();
                    });
            })
            .catch(err => console.log('Fetch error:', err));
    }

    // ===== RENDER RIWAYAT SUHU =====
    function renderGlobalTempHistory() {
        const container = document.getElementById('globalTempHistory');
        if (!container) return;

        let history = JSON.parse(localStorage.getItem('globalTempAlertHistory') || '[]');

        if (history.length === 0) {
            container.innerHTML = `
                <div class="text-center text-secondary py-4 fs-7">
                    <i class="fas fa-inbox fs-3 mb-2 d-block opacity-50"></i>
                    Belum ada riwayat peringatan suhu
                </div>
            `;
            return;
        }

        container.innerHTML = history.map(entry => `
            <div class="d-flex align-items-center gap-3 p-3 border-bottom border-secondary border-opacity-10">
                <div class="stat-icon" style="width: 38px; height: 38px; background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); font-size: 0.9rem;">
                    <i class="fas fa-temperature-high"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold text-light fs-7 mb-1">
                        ${entry.title || 'Peringatan Suhu Ekstrem'}
                    </div>
                    <div class="text-secondary fs-8">
                        ${entry.desc || 'Suhu lingkungan melebihi batas aman'}
                    </div>
                </div>
                <div class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-25 px-2 py-1 fs-8 fw-bold">
                    ${entry.temp}°C
                </div>
                <div class="text-secondary fs-8 text-nowrap">
                    ${entry.time}
                </div>
            </div>
        `).join('');
    }

    function clearGlobalTempHistory() {
        if (confirm('Yakin ingin menghapus semua riwayat peringatan suhu?')) {
            localStorage.removeItem('globalTempAlertHistory');
            renderGlobalTempHistory();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderGlobalTempHistory();
    });

    setInterval(fetchDashboardData, 15000);
    fetchDashboardData();
</script>
@endpush