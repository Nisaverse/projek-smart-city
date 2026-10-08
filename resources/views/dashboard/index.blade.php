@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@push('styles')
<style>
    /* ===== KARTU UTAMA & STATISTIK: SILVER METALIK POLOS ===== */
    .dashboard-white-card,
    .stat-top-card {
        background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 50%, #e2e8f0 100%) !important;
        background-color: #94a3b8 !important;
        border: 1px solid #64748b !important;
        border-radius: 16px !important;
        padding: 24px !important;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.15), inset 0 1px 2px rgba(255, 255, 255, 0.9), inset 0 -2px 5px rgba(71, 85, 105, 0.3) !important;
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

    .dashboard-white-card:hover,
    .stat-top-card:hover {
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.22), inset 0 1px 3px rgba(255, 255, 255, 1) !important;
        transform: translateY(-2px);
    }

    /* Inner Container Putih di Dalam Card */
    .dashboard-inner-box {
        background: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 20px;
    }

    /* Banner Kota Tegal / Smart City (Dibuat Lebih Tinggi & Lega ke Bawah) */
    .city-banner-card {
        background: linear-gradient(rgba(11, 19, 43, 0.78), rgba(11, 19, 43, 0.88)), url('https://images.unsplash.com/photo-1514565131-fce0801e5785?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat !important;
        border: 1px solid #64748b !important;
        border-radius: 16px !important;
        padding: 42px 35px !important;
        color: #ffffff !important;
        box-shadow: 0 14px 40px rgba(15, 23, 42, 0.25) !important;
    }

    /* Slot Parkir Kecil di Dashboard */
    .slot-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(11, 19, 43, 0.03);
        height: 100%;
    }
    .slot-box.is-available { border-left: 4px solid #10b981; }
    .slot-box.is-occupied { border-left: 4px solid #ef4444; }
    .slot-box.is-nodata { border-left: 4px solid #cbd5e1; }

    /* Sektor Lampu Kecil di Dashboard */
    .sector-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 18px;
        height: 100%;
    }
    .sector-box.is-active { border-left: 4px solid #f59e0b; }
    .sector-box.is-inactive { border-left: 4px solid #64748b; }
    .sector-box.is-nodata { border-left: 4px solid #cbd5e1; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- SELAMAT DATANG & WAKTU -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a;">Selamat Datang, Admin 👋</h4>
            <p class="text-secondary mb-0" style="font-size: 0.85rem;">Pantau dan kelola seluruh sistem Smart City secara real-time.</p>
        </div>
        <div class="text-end d-none d-md-block">
            <span class="fw-semibold text-dark" id="realtimeClock" style="font-size: 0.9rem;">-- / -- / ---- --:--:--</span>
        </div>
    </div>

    <!-- BANNER PENJELASAN SMART CITY & KOTA TEGAL (LEGA KE BAWAH) -->
    <div class="city-banner-card mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-9">
                <span class="badge bg-primary text-white px-3 py-1 rounded-pill mb-3" style="font-size: 0.78rem; font-weight: 600;">Tegal EcoSense IoT Hub</span>
                <h2 class="fw-bold text-white mb-3" style="font-size: 1.65rem;">Mengenal Konsep Smart City Kota Tegal</h2>
                <p class="text-light opacity-90 mb-0" style="font-size: 0.95rem; line-height: 1.65;">
                    Smart City (Kota Cerdas) adalah kerangka kerja berbasis teknologi informasi dan Internet of Things (IoT) yang dirancang untuk mengoptimalkan infrastruktur perkotaan, meningkatkan efisiensi pelayanan publik, serta memantau kondisi lingkungan secara <em>real-time</em> demi mewujudkan Kota Tegal yang modern, berkelanjutan, dan nyaman bagi masyarakat.
                </p>
            </div>
            <div class="col-lg-3 text-lg-end">
                <div class="p-3.5 rounded-3 w-100 text-start" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.25);">
                    <div class="small text-light opacity-75 mb-2" style="font-size: 0.78rem;">Status Jaringan Kota</div>
                    <div class="fw-bold text-white d-flex align-items-center" style="font-size: 0.95rem;">
                        <i class="fas fa-circle text-success me-2" style="font-size: 0.65rem;"></i> Broker MQTT Aktif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 1: 3 KARTU RINGKASAN UTAMA -->
    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-md-4">
            <div class="stat-top-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Smart Parking</div>
                        <h3 class="fw-bold mb-1 text-dark" id="dashParkingSummary">-- / 10</h3>
                        <small class="text-dark opacity-75" style="font-size: 0.75rem;">Slot Tersedia</small>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(37, 99, 235, 0.2); color: #1d4ed8; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-parking"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-between align-items-center pt-2 border-top border-secondary">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.7rem;" id="dashParkingStatusBadge">Offline</span>
                    <a href="{{ url('/dashboard/smart-parking') }}" class="text-decoration-none fw-bold text-dark" style="font-size: 0.8rem;">Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-top-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Smart Lamp</div>
                        <h3 class="fw-bold mb-1 text-dark" id="dashLampSummary">--</h3>
                        <small class="text-dark opacity-75" style="font-size: 0.75rem;">Sektor Menyala</small>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(245, 158, 11, 0.2); color: #b45309; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-between align-items-center pt-2 border-top border-secondary">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.7rem;" id="dashLampStatusBadge">Offline</span>
                    <a href="{{ url('/dashboard/smart-lamp') }}" class="text-decoration-none fw-bold text-dark" style="font-size: 0.8rem;">Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-top-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Smart Temperature</div>
                        <h3 class="fw-bold mb-1 text-dark" id="dashTempSummary">-- °C</h3>
                        <small class="text-dark opacity-75" style="font-size: 0.75rem;">Suhu Rata-rata Lingkungan</small>
                    </div>
                    <div style="width: 45px; height: 45px; background: rgba(16, 185, 129, 0.2); color: #047857; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas fa-temperature-high"></i>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-between align-items-center pt-2 border-top border-secondary">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.7rem;" id="dashTempStatusBadge">Offline</span>
                    <a href="{{ url('/dashboard/smart-temp') }}" class="text-decoration-none fw-bold text-dark" style="font-size: 0.8rem;">Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECTION 1: SMART PARKING LAYOUT ===== -->
    <div class="dashboard-white-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Smart Parking - Layout Area Parkir</h5>
                <small class="text-dark opacity-75 fw-semibold">10 titik slot pemantauan sensor ultrasonik</small>
            </div>
            <span class="badge bg-light text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i> Real-time
            </span>
        </div>

        <div class="dashboard-inner-box mt-3">
            <div class="row g-3" id="dashParkingGrid">
                <!-- Diisi via Javascript -->
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
            <div class="d-flex align-items-center gap-3" style="font-size: 0.78rem; color: #334155;">
                <div><span class="badge rounded-pill bg-success me-1">&nbsp;</span> Available</div>
                <div><span class="badge rounded-pill bg-danger me-1">&nbsp;</span> Occupied</div>
                <div><span class="badge rounded-pill bg-dark me-1">&nbsp;</span> No Data</div>
            </div>
            <a href="{{ url('/dashboard/smart-parking') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <!-- ===== SECTION 2: SMART LAMP LAYOUT ===== -->
    <div class="dashboard-white-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Smart Lamp - Layout Sektor Lampu</h5>
                <small class="text-dark opacity-75 fw-semibold">4 sektor utama penerangan wilayah Kota Tegal</small>
            </div>
            <span class="badge bg-light text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i> Real-time
            </span>
        </div>

        <div class="dashboard-inner-box mt-3">
            <div class="row g-3" id="dashLampGrid">
                <!-- Diisi via Javascript -->
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
            <div class="d-flex align-items-center gap-3" style="font-size: 0.78rem; color: #334155;">
                <div><span class="badge rounded-pill bg-warning me-1">&nbsp;</span> Active (Menyala)</div>
                <div><span class="badge rounded-pill bg-secondary me-1">&nbsp;</span> Inactive (Padam)</div>
                <div><span class="badge rounded-pill bg-dark me-1">&nbsp;</span> No Data</div>
            </div>
            <a href="{{ url('/dashboard/smart-lamp') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <!-- ===== SECTION 3: SMART TEMPERATURE & HUMIDITY CHART ===== -->
    <div class="dashboard-white-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Smart Temperature - Grafik Suhu dan Kelembapan</h5>
                <small class="text-dark opacity-75 fw-semibold">Pemantauan parameter lingkungan secara real-time</small>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-dark active">Hari</button>
                <button type="button" class="btn btn-outline-dark">Minggu</button>
            </div>
        </div>

        <div class="dashboard-inner-box mt-3">
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="dashTempChart"></canvas>
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-3 pt-3 border-top border-secondary">
            <a href="{{ url('/dashboard/smart-temp') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateClock() {
        const now = new Date();
        document.getElementById('realtimeClock').textContent = now.toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + now.toLocaleTimeString('id-ID');
    }
    setInterval(updateClock, 1000);
    updateClock();

    const dashTempCtx = document.getElementById('dashTempChart')?.getContext('2d');
    let dashTempChart;

    if (dashTempCtx) {
        dashTempChart = new Chart(dashTempCtx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                datasets: [
                    { label: 'Suhu (°C)', data: [null, null, null, null, null, null], borderColor: '#f59e0b', backgroundColor: 'rgba(245, 158, 11, 0.1)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: 'Kelembapan (%)', data: [null, null, null, null, null, null], borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.1)', tension: 0.4, fill: true, borderWidth: 2 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } } },
                scales: {
                    x: { ticks: { color: '#475569' }, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    y: { ticks: { color: '#475569' }, grid: { color: 'rgba(0, 0, 0, 0.05)' }, min: 0, max: 100 }
                }
            }
        });
    }

    function fetchDashboardData() {
        // 1. Fetch Smart Parking
        fetch('/api/parking')
            .then(res => res.json())
            .then(data => {
                const grid = document.getElementById('dashParkingGrid');
                grid.innerHTML = '';
                let available = 0;
                const slots = ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P08', 'P09', 'P10'];

                slots.forEach(slotId => {
                    const info = data[slotId] || { status: 'nodata' };
                    let cls = 'is-nodata';
                    let stText = 'Offline';
                    if (info.status === 'available') { cls = 'is-available'; stText = 'Tersedia'; available++; }
                    else if (info.status === 'occupied') { cls = 'is-occupied'; stText = 'Terisi'; }

                    grid.innerHTML += `
                        <div class="col-md-4 col-lg-2.4" style="flex: 0 0 20%; max-width: 20%;">
                            <div class="slot-box ${cls}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="fw-bold text-primary" style="font-size: 0.85rem;">${slotId}</span>
                                    <span class="badge ${info.status === 'available' ? 'bg-success' : (info.status === 'occupied' ? 'bg-danger' : 'bg-secondary')} text-white" style="font-size: 0.55rem;">${stText}</span>
                                </div>
                                <div style="font-size: 0.72rem; color: #64748b;" class="text-truncate">
                                    <i class="fas fa-car me-1"></i> ${stText}
                                </div>
                            </div>
                        </div>
                    `;
                });

                document.getElementById('dashParkingSummary').textContent = available + ' / 10';
                document.getElementById('dashParkingStatusBadge').className = 'badge bg-success text-white px-3 py-1 rounded-pill';
                document.getElementById('dashParkingStatusBadge').textContent = 'Online';
            }).catch(() => {});

        // 2. Fetch Smart Lamp (Diperbaiki agar merender layout sektor dengan benar)
        fetch('/api/smart-lamp')
            .then(res => res.json())
            .then(data => {
                const grid = document.getElementById('dashLampGrid');
                grid.innerHTML = '';
                let active = 0;
                const sectors = {
                    L01: 'Sektor Utara (Alun-Alun)',
                    L02: 'Sektor Selatan (Panggung)',
                    L03: 'Sektor Timur (Kaligangsa)',
                    L04: 'Sektor Barat (Tegal Marina)'
                };

                Object.keys(sectors).forEach(secId => {
                    const info = data[secId] || { status: 'nodata' };
                    let cls = 'is-nodata';
                    let stText = 'Belum terhubung';
                    if (info.status === 'active') { cls = 'is-active'; stText = 'Menyala'; active++; }
                    else if (info.status === 'inactive') { cls = 'is-inactive'; stText = 'Padam'; }

                    grid.innerHTML += `
                        <div class="col-md-6">
                            <div class="sector-box ${cls}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="fw-bold text-primary" style="font-size: 0.9rem;">${secId}</span>
                                    <span class="badge ${info.status === 'active' ? 'bg-warning text-dark' : 'bg-secondary text-white'}" style="font-size: 0.65rem;">${stText}</span>
                                </div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: #0f172a;">${sectors[secId]}</h6>
                                <div style="font-size: 0.78rem; color: #475569;" class="mt-2">
                                    <i class="fas fa-lightbulb text-warning me-1"></i> Status: ${stText}
                                </div>
                            </div>
                        </div>
                    `;
                });

                document.getElementById('dashLampSummary').textContent = active + ' Sektor';
                document.getElementById('dashLampStatusBadge').className = 'badge bg-success text-white px-3 py-1 rounded-pill';
                document.getElementById('dashLampStatusBadge').textContent = 'Online';
            }).catch(() => {
                // Fallback jika API belum aktif agar layout 4 sektor tetap tampil di dashboard
                const grid = document.getElementById('dashLampGrid');
                if (grid && grid.innerHTML === '') {
                    const sectors = {
                        L01: 'Sektor Utara (Alun-Alun)',
                        L02: 'Sektor Selatan (Panggung)',
                        L03: 'Sektor Timur (Kaligangsa)',
                        L04: 'Sektor Barat (Tegal Marina)'
                    };
                    Object.keys(sectors).forEach(secId => {
                        grid.innerHTML += `
                            <div class="col-md-6">
                                <div class="sector-box is-nodata">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="fw-bold text-primary" style="font-size: 0.9rem;">${secId}</span>
                                        <span class="badge bg-secondary text-white" style="font-size: 0.65rem;">No Data</span>
                                    </div>
                                    <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: #0f172a;">${sectors[secId]}</h6>
                                    <div style="font-size: 0.78rem; color: #475569;" class="mt-2">
                                        <i class="fas fa-lightbulb text-warning me-1"></i> Status: Belum terhubung
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
            });

        // 3. Fetch Smart Temperature
        fetch('/api/environment')
            .then(res => res.json())
            .then(data => {
                if (data && data.temperature !== undefined) {
                    document.getElementById('dashTempSummary').textContent = data.temperature + ' °C';
                    document.getElementById('dashTempStatusBadge').className = 'badge bg-success text-white px-3 py-1 rounded-pill';
                    document.getElementById('dashTempStatusBadge').textContent = 'Online';

                    if (dashTempChart) {
                        const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        dashTempChart.data.labels.push(now);
                        dashTempChart.data.datasets[0].data.push(data.temperature);
                        dashTempChart.data.datasets[1].data.push(data.humidity || 50);
                        dashTempChart.update();
                    }
                }
            }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchDashboardData();
        setInterval(fetchDashboardData, 5000);
    });
</script>
@endpush