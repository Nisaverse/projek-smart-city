@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@push('styles')
<style>
    /* ===== KARTU UTAMA & STATISTIK (PUTIH BERSIH DENGAN GLOW) ===== */
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
        box-shadow: 0 12px 35px rgba(11, 19, 43, 0.1) !important;
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
        box-shadow: 0 18px 45px rgba(11, 19, 43, 0.18), 0 0 20px rgba(11, 19, 43, 0.08) !important;
        transform: translateY(-2px);
    }

    /* ===== INNER CONTAINER PUTIH ===== */
    .dashboard-inner-box {
        background: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 20px;
    }

   /* ===== BANNER KOTA TEGAL (FULL SCREEN TINGGI & LEBAR MEGAH) ===== */
    .city-banner-card {
        background: linear-gradient(rgba(11, 19, 43, 0.65), rgba(11, 19, 43, 0.82)), url('{{ asset("image/alun-alun.jpeg") }}') center/cover no-repeat !important;
        border: 1px solid #64748b !important;
        border-radius: 20px !important;
        padding: 70px 45px !important;
        min-height: 400px !important;
        display: flex;
        align-items: center;
        color: #ffffff !important;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.35) !important;
        width: 100% !important;
    }

    /* ===== SLOT PARKIR KECIL DI DASHBOARD (DENGAN EFEK GLOW DARK NAVY) ===== */
    .slot-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 16px;
        height: 100%;
        box-shadow: 0 6px 20px rgba(11, 19, 43, 0.08), 0 0 12px rgba(11, 19, 43, 0.06);
        transition: all 0.3s ease;
    }
    .slot-box:hover {
        box-shadow: 0 10px 25px rgba(11, 19, 43, 0.15), 0 0 18px rgba(11, 19, 43, 0.1);
        transform: translateY(-2px);
    }
    .slot-box.is-available { border-left: 4px solid #10b981; }
    .slot-box.is-occupied { border-left: 4px solid #ef4444; }
    .slot-box.is-nodata { border-left: 4px solid #cbd5e1; }

   /* ===== EFEK GLOW DARK NAVY PEKAT & MENYALA DI KARTU SLOT & SEKTOR ===== */
    .dashboard-inner-box .col-md-3,
    .dashboard-inner-box .col-md-6,
    .dashboard-inner-box div[id^="slot-"],
    .dashboard-inner-box div[id^="dash-lamp-"],
    .slot-box, 
    .sector-box {
        background: #ffffff !important;
        border: 1px solid #94a3b8 !important;
        border-radius: 14px !important;
        box-shadow: 0 10px 30px rgba(11, 19, 43, 0.35), 0 0 20px rgba(30, 58, 138, 0.25) !important;
        transition: all 0.3s ease;
    }

    .dashboard-inner-box div[id^="slot-"]:hover,
    .slot-box:hover, 
    .sector-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(11, 19, 43, 0.5), 0 0 30px rgba(37, 99, 235, 0.4) !important;
        border-color: #3b82f6 !important;
    }
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

   <!-- BANNER KOTA TEGAL / SMART CITY -->
<div class="city-banner-card mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-12">
            <span class="badge bg-primary text-white px-3 py-1 rounded-pill mb-2" style="font-size: 0.75rem; font-weight: 600;">Tegal EcoSense IoT Hub</span>
            <h3 class="fw-bold text-white mb-2" style="font-size: 1.35rem; line-height: 1.4;">Mengenal Konsep Smart City Kota Tegal</h3>
            <p class="text-light opacity-90 mb-0" style="font-size: 0.85rem; line-height: 1.55; max-width: 900px;">
                Smart City (Kota Cerdas) adalah kerangka kerja berbasis teknologi informasi dan Internet of Things (IoT) yang dirancang untuk mengoptimalkan infrastruktur perkotaan, meningkatkan efisiensi pelayanan publik, serta memantau kondisi lingkungan secara <em>real-time</em> demi mewujudkan Kota Tegal yang modern, berkelanjutan, dan nyaman bagi masyarakat.
            </p>
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
                <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Layout Sektor Lampu</h5>
                <small class="text-secondary fw-semibold">4 sektor utama penerangan wilayah Kota Tegal</small>
            </div>
        </div>

        <div class="dashboard-inner-box mt-3">
            <div class="row g-3">
                
                <!-- Sektor L01 -->
                <div class="col-md-6">
                    <div class="sector-box p-3 border rounded-3 bg-white shadow-sm" id="dash-lamp-L01" style="border-left: 4px solid #cbd5e1; height: 100%;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.9rem;">L01</span>
                            <span class="badge bg-secondary text-white" id="dash-badge-L01" style="font-size: 0.65rem;">No Data</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: #0f172a;">Sektor Utara (Alun-Alun)</h6>
                        <div style="font-size: 0.78rem; color: #475569;" class="mt-2" id="dash-text-L01">
                            <i class="fas fa-lightbulb text-warning me-1"></i> Status: Belum terhubung
                        </div>
                    </div>
                </div>

                <!-- Sektor L02 -->
                <div class="col-md-6">
                    <div class="sector-box p-3 border rounded-3 bg-white shadow-sm" id="dash-lamp-L02" style="border-left: 4px solid #cbd5e1; height: 100%;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.9rem;">L02</span>
                            <span class="badge bg-secondary text-white" id="dash-badge-L02" style="font-size: 0.65rem;">No Data</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: #0f172a;">Sektor Selatan (Panggung)</h6>
                        <div style="font-size: 0.78rem; color: #475569;" class="mt-2" id="dash-text-L02">
                            <i class="fas fa-lightbulb text-warning me-1"></i> Status: Belum terhubung
                        </div>
                    </div>
                </div>

                <!-- Sektor L03 -->
                <div class="col-md-6">
                    <div class="sector-box p-3 border rounded-3 bg-white shadow-sm" id="dash-lamp-L03" style="border-left: 4px solid #cbd5e1; height: 100%;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.9rem;">L03</span>
                            <span class="badge bg-secondary text-white" id="dash-badge-L03" style="font-size: 0.65rem;">No Data</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: #0f172a;">Sektor Timur (Kaligangsa)</h6>
                        <div style="font-size: 0.78rem; color: #475569;" class="mt-2" id="dash-text-L03">
                            <i class="fas fa-lightbulb text-warning me-1"></i> Status: Belum terhubung
                        </div>
                    </div>
                </div>

                <!-- Sektor L04 -->
                <div class="col-md-6">
                    <div class="sector-box p-3 border rounded-3 bg-white shadow-sm" id="dash-lamp-L04" style="border-left: 4px solid #cbd5e1; height: 100%;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.9rem;">L04</span>
                            <span class="badge bg-secondary text-white" id="dash-badge-L04" style="font-size: 0.65rem;">No Data</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="font-size: 0.9rem; color: #0f172a;">Sektor Barat (Tegal Marina)</h6>
                        <div style="font-size: 0.78rem; color: #475569;" class="mt-2" id="dash-text-L04">
                            <i class="fas fa-lightbulb text-warning me-1"></i> Status: Belum terhubung
                        </div>
                    </div>
                </div>

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
            <a href="{{ url('/dashboard/temp') }}" class="btn btn-primary btn-sm px-4 rounded-pill fw-semibold" style="font-size: 0.8rem;">
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

        // 2 Fetch Smart Lamp untuk Dashboard
        fetch('/api/smart-lamp')
            .then(res => res.json())
            .then(data => {
                let activeCount = 0;
                const sectors = ['L01', 'L02', 'L03', 'L04'];

                sectors.forEach(secId => {
                    const info = data[secId] || { status: 'nodata' };
                    const cardBox = document.getElementById(`dash-lamp-${secId}`);
                    const badge = document.getElementById(`dash-badge-${secId}`);
                    const textDesc = document.getElementById(`dash-text-${secId}`);

                    if (cardBox && badge && textDesc) {
                        if (info.status === 'active') {
                            cardBox.style.borderLeft = '4px solid #f59e0b';
                            badge.className = 'badge bg-warning text-dark';
                            badge.textContent = 'Menyala';
                            textDesc.innerHTML = `<i class="fas fa-lightbulb text-warning me-1"></i> Status: Menyala`;
                            activeCount++;
                        } else if (info.status === 'inactive') {
                            cardBox.style.borderLeft = '4px solid #64748b';
                            badge.className = 'badge bg-secondary text-white';
                            badge.textContent = 'Padam';
                            textDesc.innerHTML = `<i class="fas fa-lightbulb text-muted me-1"></i> Status: Padam`;
                        } else {
                            cardBox.style.borderLeft = '4px solid #cbd5e1';
                            badge.className = 'badge bg-dark text-white';
                            badge.textContent = 'No Data';
                            textDesc.innerHTML = `<i class="fas fa-lightbulb text-warning me-1"></i> Status: Belum terhubung`;
                        }
                    }
                });

                document.getElementById('dashLampSummary').textContent = activeCount + ' Sektor';
                document.getElementById('dashLampStatusBadge').className = 'badge bg-success text-white px-3 py-1 rounded-pill';
                document.getElementById('dashLampStatusBadge').textContent = 'Online';
            })
            .catch(() => {});

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