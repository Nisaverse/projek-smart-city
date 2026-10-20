@extends('layouts.app')
@section('title', 'Smart Lamp')
@section('page-title', 'Smart Lamp Monitoring')

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

    .lamp-white-card:hover,
    .stat-top-card:hover {
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.22), inset 0 1px 3px rgba(255, 255, 255, 1) !important;
        transform: translateY(-2px);
    }

    /* ===== KONTAINER LAYOUT SEKTOR LAMPU ===== */
    .lamp-layout-container {
        background: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 20px;
    }

    /* ===== KARTU SEKTOR LAMPU KECIL ===== */
    .sector-box {
        background: #ffffff !important;
        border: 1.5px solid #64748b !important;
        border-radius: 14px !important;
        padding: 18px !important;
        box-shadow: 0 8px 25px rgba(11, 19, 43, 0.4), 0 0 20px rgba(30, 58, 138, 0.3) !important;
        transition: transform 0.3s ease;
        position: relative;
        height: 100%;
    }

    .sector-box:hover {
        transform: translateY(-2px);
    }

    .sector-box.is-active { border-left: 4px solid #facc15 !important; }
    .sector-box.is-inactive, .sector-box.is-nodata { border-left: 4px solid #64748b !important; }

    /* Badge Status di Card Sektor */
    .sector-badge-status {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }

    .status-active { background: #fef08a !important; color: #000000 !important; }
    .status-inactive, .status-nodata { background: #e2e8f0 !important; color: #334155 !important; }

    .custom-progress {
        height: 6px;
        border-radius: 10px;
        background: #cbd5e1;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- BARIS 1: KARTU RINGKASAN STATISTIK -->
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
                    <h3 class="fw-bold mb-2 text-dark" id="cardActiveCount">0</h3>
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
        L01: { name: 'Sektor Utara' },
        L02: { name: 'Sektor Selatan' },
        L03: { name: 'Sektor Timur' },
        L04: { name: 'Sektor Barat' }
    };

    const sectorKeys = Object.keys(sectorDataInfo);

    function renderLampUI(data = {}) {
        const gridContainer = document.getElementById('lampSectorsGrid');
        const tableBody = document.getElementById('detailSectorTableBody');
        
        gridContainer.innerHTML = '';
        tableBody.innerHTML = '';

        let activeCount = 0;
        let inactiveCount = 0;

        const updateTime = data.timestamp || new Date().toLocaleTimeString('id-ID');

        sectorKeys.forEach(sectorId => {
            const sectorInfo = data[sectorId] || { status: 'inactive' };
            const timeStr = sectorInfo.updated_at || updateTime;

            let statusClass = 'is-inactive';
            let badgeClass = 'status-inactive';
            let statusText = 'Inactive';

            // Penanganan fleksibel untuk status 'active' (baik string maupun angka 1)
            if (sectorInfo.status === 'active' || sectorInfo.status === 1 || sectorInfo.status === true) {
                statusClass = 'is-active';
                badgeClass = 'status-active';
                statusText = 'Active';
                activeCount++;
            } else {
                inactiveCount++;
            }

            // Render ke Grid Kartu Sektor Lampu
            gridContainer.innerHTML += `
                <div class="col-md-6">
                    <div class="sector-box ${statusClass}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.9rem;">${sectorId}</span>
                            <span class="sector-badge-status ${badgeClass}">${statusText}</span>
                        </div>
                        <h6 class="fw-bold mb-1" style="font-size: 0.95rem; color: #0f172a;">${sectorDataInfo[sectorId].name}</h6>
                        <div style="font-size: 0.8rem; color: #475569;" class="mt-2">
                            Status: ${statusText === 'Active' ? 'Menyala' : 'Padam'}
                        </div>
                    </div>
                </div>
            `;

            // Render ke Tabel Detail Sektor Lampu
            tableBody.innerHTML += `
                <tr class="border-bottom">
                    <td class="fw-bold text-dark">${sectorId}</td>
                    <td>${sectorTextBadge(sectorInfo.status)}</td>
                    <td class="text-end text-dark opacity-75" style="font-size: 0.75rem;">${timeStr}</td>
                </tr>
            `;
        });

        // Update Kartu Statistik Atas
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
            document.getElementById('iotStatusText').textContent = 'Terhubung';
        } else {
            document.getElementById('iotMainStatus').textContent = '-';
            document.getElementById('iotStatusText').textContent = 'Menunggu data perangkat';
        }
    }

    function sectorTextBadge(status) {
        if (status === 'active' || status === 1 || status === true) {
            return '<span class="text-dark fw-semibold" style="background: #fef08a; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem;">Active</span>';
        }
        return '<span class="text-secondary fw-semibold" style="background: #e2e8f0; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem;">Inactive</span>';
    }

    function fetchLampDataFromDB() {
        fetch('/api/smart-lamp')
            .then(res => res.json())
            .then(data => renderLampUI(data))
            .catch(() => renderLampUI({}));
    }

    // Filter Pencarian Tabel Sektor Lampu
    document.addEventListener('DOMContentLoaded', () => {
        fetchLampDataFromDB();
        setInterval(fetchLampDataFromDB, 3000);

        const searchInput = document.getElementById('searchSectorInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#detailSectorTableBody tr');
                rows.forEach(row => {
                    const sectorText = row.cells[0].textContent.toLowerCase();
                    row.style.display = sectorText.includes(filter) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush