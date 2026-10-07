@extends('layouts.app')

@section('title', 'Smart Lamp Monitoring - Tegal EcoSense')

@push('styles')
<style>
<<<<<<< HEAD
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

    .lamp-control-btn {
        border-radius: 12px;
        padding: 8px 16px;
        font-weight: 700;
        transition: all 0.3s ease;
=======
    /* ===== KARTU UTAMA & STATISTIK: WARNA ABU-ABU SILVER INDUSTRIAL ===== */
    .lamp-white-card,
    .stat-top-card {
        background: linear-gradient(135deg, #c5c9d0 0%, #b8bcc2 50%, #d0d4dc 100%) !important;
        background-color: #b8bcc2 !important;
        border: 1px solid #8e949d !important;
        border-radius: 16px !important;
        padding: 24px !important;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.15), inset 0 1px 2px rgba(255, 255, 255, 0.9), inset 0 -2px 5px rgba(100, 116, 139, 0.3) !important;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    /* Khusus kartu atas disamakan tingginya */
    .stat-top-card {
        padding: 20px !important;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Efek Kilau Cahaya Logam di sudut kartu */
    .lamp-white-card::after,
    .stat-top-card::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 160px;
        height: 160px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.85) 0%, rgba(184, 188, 194, 0.5) 40%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .lamp-white-card:hover,
    .stat-top-card:hover {
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.22), inset 0 1px 3px rgba(255, 255, 255, 1) !important;
        transform: translateY(-2px);
    }

    /* ===== KONTAINER LAYOUT SEKTOR LAMPU ===== */
    .lamp-layout-container {
        background: #ffffff !important;
        border: 1px solid #94a3b8;
        border-radius: 12px;
        padding: 20px;
    }

    /* ===== KARTU SEKTOR LAMPU KECIL ===== */
    .sector-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 18px;
        box-shadow: 0 4px 12px rgba(11, 19, 43, 0.03);
        transition: all 0.3s ease;
        position: relative;
        height: 100%;
    }

    .sector-box:hover {
        border-color: #64748b;
        box-shadow: 0 6px 18px rgba(11, 19, 43, 0.08);
    }

    .sector-box.is-active { border-left: 4px solid #f59e0b; }
    .sector-box.is-inactive { border-left: 4px solid #64748b; }
    .sector-box.is-nodata { border-left: 4px solid #cbd5e1; }

    .sector-badge-status {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }

    .status-active { background: rgba(245, 158, 11, 0.15); color: #b45309; }
    .status-inactive { background: rgba(100, 116, 139, 0.15); color: #334155; }
    .status-nodata { background: rgba(203, 213, 225, 0.4); color: #475569; }

    .custom-progress {
        height: 6px;
        border-radius: 10px;
        background: #8e949d;
        overflow: hidden;
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
    }
</style>
@endpush

@section('content')
<<<<<<< HEAD
<div class="twinkle-bg-wrapper-light">
        
        <!-- HEADER MODULE SMART LAMP -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="border-radius: 20px; font-weight: 700;">
                    <i class="fas fa-lightbulb me-1"></i> IoT Control
                </span>
                <h4 class="m-0 font-weight-bold text-dark ms-2" style="font-weight: 800;">Smart Lamp Monitoring</h4>
            </div>
        </div>

        <!-- SYSTEM STATUS (FRAMELESS) -->
        <div class="frameless-item mb-4">
            <h6 class="text-dark fw-bold mb-2">
                <i class="fas fa-microchip text-warning me-2"></i>System Status
            </h6>
            <div class="d-flex gap-4 align-items-center flex-wrap text-dark font-monospace fw-semibold" style="font-size: 0.95rem;">
                <div>Mode: <span class="badge bg-primary px-2 py-1">Manual</span></div>
                <div>Sensor Light: <span class="glow-yellow-text fw-bold">65%</span></div>
                <div>Time: <span class="text-success fw-bold" id="liveTime">10:30:03</span></div>
            </div>
        </div>

        <!-- AMBIENT LIGHT MONITORING (FRAMELESS) -->
        <div class="frameless-item mb-5">
            <h6 class="text-dark fw-bold mb-3">
                <i class="fas fa-sun text-warning me-2"></i>Ambient Light Monitoring
            </h6>
            
            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <small class="text-muted fw-bold d-block mb-1">CURRENT LIGHT LEVEL</small>
                    <div class="glow-yellow-text" style="font-size: 3rem; font-weight: 800; line-height: 1;">
                        65.0 <span style="font-size: 1.5rem;">%</span>
                    </div>
                    
                    <!-- PROGRESS BAR INTENSITAS CAHAYA -->
                    <div class="progress mt-3" style="height: 10px; background: rgba(0,0,0,0.08); border-radius: 10px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 65%; border-radius: 10px;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="mt-2 text-dark fw-bold" style="font-size: 0.88rem;">
                        <i class="fas fa-sun text-warning me-1"></i> TERANG (Lampu seharusnya OFF)
                    </div>
                </div>

                <div class="col-md-6 border-start border-2 ps-md-4">
                    <small class="text-muted fw-bold d-block mb-2">TODAY'S STATISTICS</small>
                    <div class="d-flex justify-content-between mb-2 text-dark font-monospace">
                        <span>Max Level:</span>
                        <span class="text-success fw-bold">65.0%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-dark font-monospace">
                        <span>Min Level:</span>
                        <span class="text-danger fw-bold">65.0%</span>
                    </div>
                    <div class="d-flex justify-content-between text-dark font-monospace">
                        <span>Average:</span>
                        <span class="text-primary fw-bold">65.0%</span>
                    </div>
=======
<div class="container-fluid px-0">

    <!-- BARIS 1: KARTU RINGKASAN STATISTIK (SILVER INDUSTRIAL) -->
    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-md-3">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Total Sektor</div>
                    <h3 class="fw-bold mb-1 text-dark" id="cardTotalSector">4</h3>
                </div>
                <div class="text-dark opacity-75 mt-2" style="font-size: 0.75rem; font-weight: 600;">Total sektor lampu wilayah</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Aktif (On)</div>
                    <h3 class="fw-bold mb-2 text-warning" id="cardActiveCount">0</h3>
                    <div class="custom-progress mb-2">
                        <div id="barActive" class="progress-bar bg-warning" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="text-dark opacity-75 mt-2" style="font-size: 0.75rem; font-weight: 600;" id="textActiveDesc">Sektor menyala (0%)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Mati (Off)</div>
                    <h3 class="fw-bold mb-2 text-dark" id="cardInactiveCount">0</h3>
                    <div class="custom-progress mb-2">
                        <div id="barInactive" class="progress-bar bg-secondary" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="text-dark opacity-75 mt-2" style="font-size: 0.75rem; font-weight: 600;" id="textInactiveDesc">Sektor padam (0%)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Status IoT</div>
                    <h3 class="fw-bold mb-1 text-dark" id="iotMainStatus">-</h3>
                </div>
                <div style="font-size: 0.8rem;" class="text-dark fw-bold mt-2">
                    <span id="iotDot" class="spinner-grow spinner-grow-sm text-warning me-1" role="status"></span>
                    <span id="iotStatusText">Menunggu data perangkat</span>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 2: LAYOUT SEKTOR LAMPU & DETAIL TABEL -->
    <div class="row g-4">
        <!-- Kolom Kiri: Layout 4 Sektor Lampu -->
        <div class="col-lg-8">
            <div class="lamp-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Layout Sektor Lampu</h5>
                        <small class="text-dark opacity-75 fw-semibold">4 sektor utama penerangan wilayah Kota Tegal</small>
                    </div>
                    <span class="badge bg-light text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i> Real-time
                    </span>
                </div>

                <!-- Kontainer Sektor -->
                <div class="lamp-layout-container mt-3">
                    <div class="row g-3" id="lampSectorsGrid">
                        <!-- Diisi via Javascript -->
                    </div>
                </div>

                <!-- Keterangan Legend -->
                <div class="d-flex align-items-center gap-4 mt-4 pt-3 border-top" style="font-size: 0.8rem; color: #334155;">
                    <div><span class="badge rounded-pill bg-warning me-1">&nbsp;</span> Active (Menyala)</div>
                    <div><span class="badge rounded-pill bg-secondary me-1">&nbsp;</span> Inactive (Padam)</div>
                    <div><span class="badge rounded-pill bg-dark me-1">&nbsp;</span> No Data</div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Sektor & Aktivitas Terbaru -->
        <div class="col-lg-4">
            <!-- Tabel Detail Sektor -->
            <div class="lamp-white-card mb-4">
                <h6 class="fw-bold mb-3" style="color: #0f172a;">Detail Sektor</h6>
                <div class="mb-3">
                    <input type="text" id="searchSectorInput" class="form-control form-control-sm" placeholder="Cari sektor...">
                </div>
                <div style="max-height: 220px; overflow-y: auto;" class="pe-1">
                    <table class="table table-sm align-middle" style="font-size: 0.82rem;">
                        <thead>
                            <tr class="text-dark border-bottom fw-bold">
                                <th>Sektor</th>
                                <th>Status</th>
                                <th class="text-end">Waktu Update</th>
                            </tr>
                        </thead>
                        <tbody id="detailSectorTableBody">
                            <!-- Diisi via Javascript -->
                        </tbody>
                    </table>
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
                </div>
            </div>

<<<<<<< HEAD
        <!-- KONTROL KARTU DENGAN DAFTAR LAMPU (FRAMELESS GRID) -->
        <h6 class="text-dark fw-bold mb-3"><i class="fas fa-toggle-on text-primary me-2"></i>Lampu Individual</h6>
        <div class="row g-4">
            @for ($i = 1; $i <= 4; $i++)
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark"><i class="fas fa-lightbulb me-1 text-warning"></i> Lampu {{ $i }}</span>
                        <span class="badge bg-danger">OFF</span>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-outline-primary lamp-control-btn"><i class="fas fa-power-off me-1"></i> Saklar</button>
                    </div>
=======
            <!-- Aktivitas Terbaru -->
            <div class="lamp-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0" style="color: #0f172a;">Aktivitas Terbaru</h6>
                    <a href="#" class="text-decoration-none text-primary" style="font-size: 0.8rem; font-weight: 600;">Lihat Semua</a>
                </div>
                <div class="text-center py-4 text-dark opacity-75">
                    <i class="fas fa-clock fa-2x mb-2 text-dark opacity-50"></i>
                    <p class="mb-0" style="font-size: 0.85rem; font-weight: 600;">Belum ada aktivitas</p>
                    <small style="font-size: 0.72rem;">Data aktivitas lampu akan muncul setelah perangkat IoT mengirimkan informasi.</small>
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
                </div>
            </div>
            @endfor
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
<<<<<<< HEAD
    // SCRIPT LIVE TIME CLOCK
    setInterval(() => {
        const now = new Date();
        const timeStr = now.toTimeString().split(' ')[0];
        const timeElem = document.getElementById('liveTime');
        if (timeElem) timeElem.textContent = timeStr;
    }, 1000);
=======
    const sectorDataInfo = {
        L01: { name: 'Sektor Utara (Alun-Alun)' },
        L02: { name: 'Sektor Selatan (Panggung)' },
        L03: { name: 'Sektor Timur (Kaligangsa)' },
        L04: { name: 'Sektor Barat (Tegal Marina)' }
    };

    const sectorKeys = Object.keys(sectorDataInfo);

    function renderLampUI(data) {
        const gridContainer = document.getElementById('lampSectorsGrid');
        const tableBody = document.getElementById('detailSectorTableBody');
        
        gridContainer.innerHTML = '';
        tableBody.innerHTML = '';

        let activeCount = 0;
        let inactiveCount = 0;

        sectorKeys.forEach(sectorId => {
            const sectorInfo = data[sectorId] || { status: 'nodata', time: 'Belum ada data' };
            
            let statusClass = 'is-nodata';
            let badgeClass = 'status-nodata';
            let statusText = 'No Data';

            if (sectorInfo.status === 'active') {
                statusClass = 'is-active';
                badgeClass = 'status-active';
                statusText = 'Active (Menyala)';
                activeCount++;
            } else if (sectorInfo.status === 'inactive') {
                statusClass = 'is-inactive';
                badgeClass = 'status-inactive';
                statusText = 'Inactive (Padam)';
                inactiveCount++;
            }

            gridContainer.innerHTML += `
                <div class="col-md-6">
                    <div class="sector-box ${statusClass}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.9rem;">${sectorId}</span>
                            <span class="sector-badge-status ${badgeClass}">${statusText}</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="font-size: 0.95rem; color: #0f172a;">${sectorDataInfo[sectorId].name}</h6>
                        <div style="font-size: 0.8rem; color: #475569;" class="mt-2">
                            <i class="fas fa-lightbulb text-warning me-1"></i> Status: ${sectorInfo.status === 'active' ? 'Menyala' : (sectorInfo.status === 'inactive' ? 'Padam' : 'Belum terhubung')}
                        </div>
                    </div>
                </div>
            `;

            tableBody.innerHTML += `
                <tr class="border-bottom">
                    <td class="fw-bold text-dark">${sectorId}</td>
                    <td>${sectorTextBadge(sectorInfo.status)}</td>
                    <td class="text-end text-dark opacity-75" style="font-size: 0.75rem;">${sectorInfo.time || 'Belum ada data'}</td>
                </tr>
            `;
        });

        document.getElementById('cardActiveCount').textContent = activeCount;
        document.getElementById('cardInactiveCount').textContent = inactiveCount;
        
        const percentActive = Math.round((activeCount / sectorKeys.length) * 100);
        const percentInactive = Math.round((inactiveCount / sectorKeys.length) * 100);

        document.getElementById('barActive').style.width = percentActive + '%';
        document.getElementById('textActiveDesc').textContent = `Sektor menyala (${percentActive}%)`;

        document.getElementById('barInactive').style.width = percentInactive + '%';
        document.getElementById('textInactiveDesc').textContent = `Sektor padam (${percentInactive}%)`;

        if (activeCount > 0 || inactiveCount > 0) {
            document.getElementById('iotMainStatus').textContent = 'Online';
            document.getElementById('iotStatusText').textContent = 'Perangkat terhubung';
            document.getElementById('iotDot').className = 'spinner-grow spinner-grow-sm text-success me-1';
        }
    }

    function sectorTextBadge(status) {
        if (status === 'active') return '<span class="text-warning fw-semibold">Menyala</span>';
        if (status === 'inactive') return '<span class="text-secondary fw-semibold">Padam</span>';
        return '<span class="text-muted">-</span>';
    }

    function fetchLampDataFromDB() {
        fetch('/api/smart-lamp')
            .then(res => res.json())
            .then(data => renderLampUI(data))
            .catch(() => renderLampUI({}));
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchLampDataFromDB();
        setInterval(fetchLampDataFromDB, 5000);
    });
>>>>>>> 931034a0237139a8dd18b2daebfce7c14b442c46
</script>
@endpush