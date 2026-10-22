@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@push('styles')
<style>
    /* ===== TOP BAR STICKY ===== */
    .top-bar {
        position: sticky !important;
        top: 0 !important;
        z-index: 1000 !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        background: #ffffff !important;
        border-bottom: 1px solid var(--border-color);
    }

    /* ===== KARTU UTAMA & STATISTIK ===== */
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

    /* ===== TOMBOL LIHAT DETAIL ===== */
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
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(11, 19, 43, 0.25);
    }

    /* ===== INNER CONTAINER ===== */
    .dashboard-inner-box {
        background: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 20px;
    }

    /* ===== BANNER KOTA TEGAL & GLASS CARD ===== */
    .city-banner-card {
        background: linear-gradient(rgba(11, 19, 43, 0.65), rgba(11, 19, 43, 0.82)), url('{{ asset("image/alun-alun.jpeg") }}') center/cover no-repeat !important;
        border: 1px solid #64748b !important;
        border-radius: 20px !important;
        padding: 40px 35px !important;
        min-height: 380px !important;
        display: flex;
        align-items: center;
        color: #ffffff !important;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.35) !important;
        width: 100% !important;
        overflow: hidden;
    }

    .banner-glass-card {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(12px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(12px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        border-radius: 16px !important;
        padding: 12px 16px !important; /* Padding atas-bawah ditipisin */
        color: #ffffff !important;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3) !important;
        max-width: 270px !important; /* Lebar kotak diperkecil jadi lebih ramping */
        margin-left: auto;
    }

    .banner-glass-divider {
        border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        margin: 6px 0 !important; /* Jarak garis pemisah dideketin */
    }

    .banner-glass-card h4, 
    .banner-glass-card div, 
    .banner-glass-card span {
        color: #ffffff !important;
    }

    .banner-glass-card .text-muted,
    .banner-glass-card small {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .banner-glass-divider {
        border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
        margin: 12px 0;
    }

    .animate-banner-content {
        animation: bannerPopUpExtraSlow 2.0s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        transform: translateY(70px) scale(0.92);
    }

    @keyframes bannerPopUpExtraSlow {
        0% {
            opacity: 0;
            transform: translateY(70px) scale(0.92);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* ===== KARTU SLOT PARKIR & SEKTOR LAMPU ===== */
    .slot-box, 
    .sector-box {
        background: #ffffff !important;
        border: 1.5px solid #64748b !important;
        border-radius: 12px !important;
        padding: 16px 18px !important;
        box-shadow: 0 8px 25px rgba(11, 19, 43, 0.35), 0 0 15px rgba(30, 58, 138, 0.2) !important;
        transition: transform 0.3s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .slot-box:hover, 
    .sector-box:hover {
        transform: translateY(-2px);
    }

    /* Badge Status */
    .slot-badge-status,
    .sector-badge-status {
        font-size: 0.65rem !important;
        font-weight: 700;
        padding: 4px 10px !important;
        border-radius: 12px;
        display: inline-block;
        white-space: nowrap;
    }

    .status-available { background: rgba(16, 185, 129, 0.15); color: #059669; }
    .status-occupied { background: rgba(239, 68, 68, 0.15); color: #dc2626; }
    .status-active { background: #dbeafe !important; color: #1d4ed8 !important; }
    .status-inactive { background: #e2e8f0 !important; color: #334155 !important; }

    @media (min-width: 992px) {
        .col-custom-5 {
            flex: 0 0 20% !important;
            max-width: 20% !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- SELAMAT DATANG & WAKTU -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #0f172a;">Selamat Datang, Admin  👋</h4>
            <p class="text-secondary mb-0" style="font-size: 0.85rem;">Pantau dan kelola seluruh sistem Smart City secara real-time.</p>
        </div>
        <div class="text-end d-none d-md-block">
            <span class="fw-semibold text-dark" id="realtimeClock" style="font-size: 0.9rem;">-- / -- / ---- --:--:--</span>
        </div>
    </div>

    <!-- BANNER KOTA TEGAL -->
    <div class="city-banner-card mb-4">
        <div class="row align-items-center g-4 w-100 m-0">
            <!-- SISI KIRI: DESKRIPSI -->
            <div class="col-lg-7 animate-banner-content px-0">
                <span class="badge bg-primary text-white px-3 py-1 rounded-pill mb-2" style="font-size: 0.75rem; font-weight: 600;">Tegal EcoSense IoT Hub</span>
                <h3 class="fw-bold text-white mb-2" style="font-size: 1.35rem; line-height: 1.4;">Mengenal Konsep Smart City Kota Tegal</h3>
                <p class="text-light opacity-90 mb-0" style="font-size: 0.85rem; line-height: 1.55; max-width: 900px;">
                    Smart City (Kota Cerdas) adalah kerangka kerja berbasis teknologi informasi dan Internet of Things (IoT) yang dirancang untuk mengoptimalkan infrastruktur perkotaan, meningkatkan efisiensi pelayanan publik, serta memantau kondisi lingkungan secara <em>real-time</em> demi mewujudkan Kota Tegal yang modern, berkelanjutan, dan nyaman bagi masyarakat.
                </p>
            </div>

            <!-- SISI KANAN: KARTU STATISTIK TRANSPARAN (GLASSMORPHISM) -->
            <div class="col-lg-5 animate-banner-content px-0 pe-lg-2">
                <div class="banner-glass-card">
                    
                    <!-- 1. Kualitas Udara -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-wind text-info" style="font-size: 0.9rem;"></i>
                            <span class="fw-semibold" style="font-size: 0.78rem;">Kualitas Udara</span>
                        </div>
                        <span class="badge bg-success text-white px-2 py-0.5 rounded-pill" style="font-size: 0.6rem;">Online</span>
                    </div>
                    <h5 class="fw-bold mb-0 mt-1" style="font-size: 1.15rem;" id="bannerAqi">48 AQI</h5>
                    <div class="mt-0.5">
                        <span class="text-white px-2 py-0.5 rounded-pill" style="font-size: 0.72rem;" id="bannerAqiStatus">Baik</span>
                    </div>

                    <div class="banner-glass-divider"></div>

                    <!-- 2. Kelembapan -->
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-tint text-primary" style="font-size: 1.1rem;"></i>
                            <span class="fw-semibold" style="font-size: 0.85rem;">Kelembapan</span>
                        </div>
                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem;">Online</span>
                    </div>
                    <h4 class="fw-bold mb-0" style="font-size: 1.4rem;" id="bannerHumidity">70 %</h4>
                    <small style="font-size: 0.72rem;">Kelembapan relatif</small>

                    <div class="banner-glass-divider"></div>

                    <!-- 3. Status Hujan -->
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-cloud-showers-heavy text-info" style="font-size: 1.1rem;"></i>
                            <span class="fw-semibold" style="font-size: 0.85rem;">Status Hujan</span>
                        </div>
                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem;">Online</span>
                    </div>
                    <h4 class="fw-bold mb-0" style="font-size: 1.3rem;" id="bannerRain">Hujan Ringan</h4>
                    <small style="font-size: 0.72rem;" id="bannerRainDesc">Presipitasi Terdeteksi</small>

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
                <div class="mt-3 pt-2 border-top border-secondary">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.7rem;" id="dashParkingStatusBadge">Offline</span>
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
                <div class="mt-3 pt-2 border-top border-secondary">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.7rem;" id="dashLampStatusBadge">Offline</span>
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
                <div class="mt-3 pt-2 border-top border-secondary">
                    <span class="badge bg-secondary text-white px-3 py-1 rounded-pill" style="font-size: 0.7rem;" id="dashTempStatusBadge">Offline</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 1: SMART PARKING LAYOUT -->
    <div class="dashboard-white-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Smart Parking - Layout Area Parkir</h5>
                <small class="text-dark opacity-75 fw-semibold">10 titik slot pemantauan sensor ultrasonik</small>
            </div>
        </div>

        <div class="dashboard-inner-box mt-3">
            <div class="row g-2 g-md-3" id="dashParkingGrid">
                <!-- Diisi via Javascript -->
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
            <div class="d-flex align-items-center gap-3" style="font-size: 0.78rem; color: #334155;">
                <div><span class="badge rounded-pill bg-success me-1">&nbsp;</span> Available</div>
                <div><span class="badge rounded-pill bg-danger me-1">&nbsp;</span> Occupied</div>
            </div>
            <a href="{{ url('/smart-parking') }}" class="btn btn-navy-detail btn-sm px-4 rounded-pill">
                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <!-- SECTION 2: SMART LAMP LAYOUT -->
    <div class="dashboard-white-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Layout Sektor Lampu</h5>
                <small class="text-secondary fw-semibold">4 sektor utama penerangan wilayah Kota Tegal</small>
            </div>
        </div>

        <div class="dashboard-inner-box mt-3">
            <div class="row g-3" id="dashLampGrid">
                <!-- Diisi via Javascript -->
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
            <div class="d-flex align-items-center gap-3" style="font-size: 0.78rem; color: #334155;">
                <div><span class="badge rounded-pill bg-primary me-1">&nbsp;</span> Active (Menyala)</div>
                <div><span class="badge rounded-pill bg-secondary me-1">&nbsp;</span> Inactive (Padam)</div>
            </div>
            <a href="{{ url('/smart-lamp') }}" class="btn btn-navy-detail btn-sm px-4 rounded-pill">
                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <!-- SECTION 3: SMART TEMPERATURE & HUMIDITY CHART -->
    <div class="dashboard-white-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Grafik Pemantauan Parameter Lingkungan Real-Time</h5>
                <small class="text-dark opacity-75 fw-semibold">Grafik memperbarui data pemantauan berkala</small>
            </div>
        </div>

        <div class="dashboard-inner-box mt-3">
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="dashTempChart"></canvas>
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-3 pt-3 border-top border-secondary">
            <a href="{{ url('/smart-temp') }}" class="btn btn-navy-detail btn-sm px-4 rounded-pill">
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
    const maxDataPoints = 10;

    if (dashTempCtx) {
        dashTempChart = new Chart(dashTempCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'Suhu (°C)', data: [], borderColor: '#dc2626', backgroundColor: 'rgba(220, 38, 38, 0.05)', tension: 0.4, fill: true, borderWidth: 2.5 },
                    { label: 'Kelembapan (%)', data: [], borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.05)', tension: 0.4, fill: true, borderWidth: 2.5 },
                    { label: 'Tinggi Air (cm)', data: [], borderColor: '#0284c7', backgroundColor: 'rgba(2, 132, 199, 0.05)', tension: 0.4, fill: true, borderWidth: 2.5 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#0f172a', font: { weight: 'bold' } } } },
                scales: {
                    x: { ticks: { color: '#475569' }, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    y: { ticks: { color: '#475569' }, grid: { color: 'rgba(0, 0, 0, 0.05)' }, min: 0 }
                }
            }
        });
    }

    const sectorDetails = {
        L01: { name: 'Sektor Utara' },
        L02: { name: 'Sektor Selatan' },
        L03: { name: 'Sektor Timur' },
        L04: { name: 'Sektor Barat' }
    };

    function renderLampGrid(data = {}) {
        const grid = document.getElementById('dashLampGrid');
        if (!grid) return;
        
        grid.innerHTML = '';
        let activeCount = 0;

        Object.keys(sectorDetails).forEach(secId => {
            const sectorInfo = data[secId] || { status: 'inactive' };
            const isActive = sectorInfo.status === 'active' || sectorInfo.status === 1 || sectorInfo.status === true;

            let badgeClass = 'status-inactive';
            let statusText = 'Inactive';
            let statusDesc = 'Status: Padam';

            if (isActive) {
                badgeClass = 'status-active';
                statusText = 'Active';
                statusDesc = 'Status: Menyala / Terhubung';
                activeCount++;
            }

            grid.innerHTML += `
                <div class="col-md-6">
                    <div class="sector-box">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.9rem;">${secId}</span>
                            <span class="sector-badge-status ${badgeClass}">${statusText}</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="font-size: 0.95rem; color: #0f172a;">${sectorDetails[secId].name}</h6>
                        <div style="font-size: 0.8rem; color: #475569;" class="mt-2">
                            ${statusDesc}
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('dashLampSummary').textContent = activeCount + ' Sektor';
        if (activeCount > 0) {
            document.getElementById('dashLampStatusBadge').className = 'badge bg-success text-white px-3 py-1 rounded-pill';
            document.getElementById('dashLampStatusBadge').textContent = 'Online';
        } else {
            document.getElementById('dashLampStatusBadge').className = 'badge bg-secondary text-white px-3 py-1 rounded-pill';
            document.getElementById('dashLampStatusBadge').textContent = 'Offline';
        }
    }

    function renderParkingGrid(apiResponse = {}) {
        const grid = document.getElementById('dashParkingGrid');
        if (!grid) return;

        grid.innerHTML = '';
        let availableCount = 0;
        const slots = ['P01', 'P02', 'P03', 'P04', 'P05', 'P06', 'P07', 'P08', 'P09', 'P10'];
        const slotsData = apiResponse.slots || apiResponse;

        slots.forEach(slotId => {
            const slotInfo = slotsData[slotId] || { status: 'available' };
            const isOccupied = slotInfo.status === 'occupied';

            let badgeClass = 'status-available';
            let statusText = 'Available';

            if (isOccupied) {
                badgeClass = 'status-occupied';
                statusText = 'Occupied';
            } else {
                availableCount++;
            }

            grid.innerHTML += `
                <div class="col-6 col-sm-4 col-custom-5">
                    <div class="slot-box">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.85rem;">${slotId}</span>
                            <span class="slot-badge-status ${badgeClass}">${statusText}</span>
                        </div>
                        <div style="font-size: 0.72rem; color: #64748b;" class="text-truncate">
                            <i class="fas fa-car me-1"></i> ${statusText}
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('dashParkingSummary').textContent = availableCount + ' / 10';
        document.getElementById('dashParkingStatusBadge').className = 'badge bg-success text-white px-3 py-1 rounded-pill';
        document.getElementById('dashParkingStatusBadge').textContent = 'Online';
    }

    function fetchDashboardData() {
        // 1. Fetch Smart Parking
        fetch('/api/parking')
            .then(res => res.json())
            .then(data => renderParkingGrid(data))
            .catch(() => renderParkingGrid({}));

        // 2. Fetch Smart Lamp
        fetch('/api/lamp')
            .then(res => res.json())
            .then(data => renderLampGrid(data))
            .catch(() => {
                fetch('/api/smart-lamp')
                    .then(res => res.json())
                    .then(data => renderLampGrid(data))
                    .catch(() => renderLampGrid({}));
            });

        // 3. Fetch Smart Environment / Temperature & Update Chart + Banner Glass
        fetch('/api/environment')
            .then(res => res.json())
            .then(data => {
                if (data && data.has_data === true) {
                    const temp = data.temperature;
                    const hum = data.humidity;
                    const aqi = data.air_quality ?? 48;
                    const rainStatus = data.rain_status ?? 'Cerah';
                    const waterLevel = data.water_level ?? 120;
                    const timeStr = data.timestamp || new Date().toLocaleTimeString('id-ID');

                    // Update Top Card Summary
                    document.getElementById('dashTempSummary').textContent = temp + ' °C';
                    document.getElementById('dashTempStatusBadge').className = 'badge bg-success text-white px-3 py-1 rounded-pill';
                    document.getElementById('dashTempStatusBadge').textContent = 'Online';

                    // Update Banner Glass Card (Transparan)
                    if (document.getElementById('bannerAqi')) document.getElementById('bannerAqi').textContent = aqi + ' AQI';
                    if (document.getElementById('bannerHumidity')) document.getElementById('bannerHumidity').textContent = hum + ' %';
                    if (document.getElementById('bannerRain')) document.getElementById('bannerRain').textContent = rainStatus;
                    if (document.getElementById('bannerRainDesc')) {
                        document.getElementById('bannerRainDesc').textContent = rainStatus.toLowerCase().includes('hujan') ? 'Presipitasi Terdeteksi' : 'Tidak Presipitasi';
                    }

                    // Update Grafik Real-Time
                    if (dashTempChart) {
                        dashTempChart.data.labels.push(timeStr);
                        dashTempChart.data.datasets[0].data.push(temp);
                        dashTempChart.data.datasets[1].data.push(hum);
                        dashTempChart.data.datasets[2].data.push(waterLevel);

                        if (dashTempChart.data.labels.length > maxDataPoints) {
                            dashTempChart.data.labels.shift();
                            dashTempChart.data.datasets.forEach(ds => ds.data.shift());
                        }
                        dashTempChart.update();
                    }
                }
            }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderParkingGrid({});
        renderLampGrid({});
        fetchDashboardData();
        setInterval(fetchDashboardData, 5000);
    });
</script>
@endpush