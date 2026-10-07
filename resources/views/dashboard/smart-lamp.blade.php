@extends('layouts.app')
@section('title', 'Smart Lamp')
@section('page-title', 'Smart Lamp Monitoring')

@push('styles')
<style>
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
    }
</style>
@endpush

@section('content')
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
                </div>
            </div>

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
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush