@extends('layouts.app')
@section('title', 'Smart Parking')
@section('page-title', 'Smart Parking Monitoring')

@push('styles')
<style>
    /* ===== KARTU UTAMA & STATISTIK: SILVER METALIK POLOS TANPA BERCAK CAHAYA ===== */
    .parking-white-card,
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

    /* Khusus kartu atas disamakan tingginya & rata */
    .stat-top-card {
        padding: 20px !important;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .parking-white-card:hover,
    .stat-top-card:hover {
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.22), inset 0 1px 3px rgba(255, 255, 255, 1) !important;
        transform: translateY(-2px);
    }

    /* ===== KONTAINER LAYOUT SLOT PARKIR ===== */
    .parking-layout-container {
        background: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 20px;
    }

    /* ===== KARTU SLOT PARKIR KECIL ===== */
    .slot-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 4px 12px rgba(11, 19, 43, 0.03);
        transition: all 0.3s ease;
        position: relative;
        height: 100%;
    }

    .slot-box:hover {
        border-color: #94a3b8;
        box-shadow: 0 6px 18px rgba(11, 19, 43, 0.08);
    }

    .slot-box.is-available { border-left: 4px solid #10b981; }
    .slot-box.is-occupied { border-left: 4px solid #ef4444; }
    .slot-box.is-nodata { border-left: 4px solid #cbd5e1; }

    .slot-badge-status {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }

    .status-available { background: rgba(16, 185, 129, 0.1); color: #059669; }
    .status-occupied { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
    .status-nodata { background: rgba(203, 213, 225, 0.3); color: #64748b; }

    .custom-progress {
        height: 6px;
        border-radius: 10px;
        background: #64748b;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- BARIS 1: KARTU RINGKASAN STATISTIK -->
    <div class="row g-4 mb-4 align-items-stretch">
        <!-- Total Slot -->
        <div class="col-md-3">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Total Slot</div>
                    <h3 class="fw-bold mb-1 text-dark" id="cardTotalSlot">10</h3>
                </div>
                <div class="text-dark opacity-75 mt-2" style="font-size: 0.75rem; font-weight: 600;">Total area parkir yang tersedia</div>
            </div>
        </div>

        <!-- Available (Kosong) -->
        <div class="col-md-3">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Available</div>
                    <h3 class="fw-bold mb-2 text-success" id="cardAvailableCount">0</h3>
                    <div class="custom-progress mb-2">
                        <div id="barAvailable" class="progress-bar bg-success" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="text-dark opacity-75 mt-2" style="font-size: 0.75rem; font-weight: 600;" id="textAvailableDesc">Slot kosong (0%)</div>
            </div>
        </div>

        <!-- Occupied (Terisi) -->
        <div class="col-md-3">
            <div class="stat-top-card">
                <div>
                    <div class="text-dark mb-1" style="font-size: 0.85rem; font-weight: 700;">Occupied</div>
                    <h3 class="fw-bold mb-2 text-danger" id="cardOccupiedCount">0</h3>
                    <div class="custom-progress mb-2">
                        <div id="barOccupied" class="progress-bar bg-danger" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="text-dark opacity-75 mt-2" style="font-size: 0.75rem; font-weight: 600;" id="textOccupiedDesc">Slot terisi (0%)</div>
            </div>
        </div>

        <!-- Status IoT -->
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

    <!-- BARIS 2: LAYOUT SLOT PARKIR & DETAIL TABEL -->
    <div class="row g-4">
        <!-- Kolom Kiri: Layout 10 Slot Parkir -->
        <div class="col-lg-8">
            <div class="parking-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Layout Area Parkir</h5>
                        <small class="text-dark opacity-75 fw-semibold">10 titik slot pemantauan sensor ultrasonik</small>
                    </div>
                    <span class="badge bg-light text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i> Real-time
                    </span>
                </div>

                <!-- Kontainer Slot Parkir -->
                <div class="parking-layout-container mt-3">
                    <div class="row g-3" id="parkingSlotsGrid">
                        <!-- Diisi via Javascript -->
                    </div>
                </div>

                <!-- Keterangan Legend -->
                <div class="d-flex align-items-center gap-4 mt-4 pt-3 border-top" style="font-size: 0.8rem; color: #334155;">
                    <div><span class="badge rounded-pill bg-success me-1">&nbsp;</span> Available (Kosong)</div>
                    <div><span class="badge rounded-pill bg-danger me-1">&nbsp;</span> Occupied (Terisi)</div>
                    <div><span class="badge rounded-pill bg-dark me-1">&nbsp;</span> No Data</div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Slot & Aktivitas Terbaru -->
        <div class="col-lg-4">
            <!-- Tabel Detail Slot -->
            <div class="parking-white-card mb-4">
                <h6 class="fw-bold mb-3" style="color: #0f172a;">Detail Slot Parkir</h6>
                <div class="mb-3">
                    <input type="text" id="searchSlotInput" class="form-control form-control-sm" placeholder="Cari slot...">
                </div>
                <div style="max-height: 220px; overflow-y: auto;" class="pe-1">
                    <table class="table table-sm align-middle" style="font-size: 0.82rem;">
                        <thead>
                            <tr class="text-dark border-bottom fw-bold">
                                <th>Slot</th>
                                <th>Status</th>
                                <th class="text-end">Waktu Update</th>
                            </tr>
                        </thead>
                        <tbody id="detailSlotTableBody">
                            <!-- Diisi via Javascript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="parking-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold m-0" style="color: #0f172a;">Aktivitas Terbaru</h6>
                    <a href="#" class="text-decoration-none text-primary" style="font-size: 0.8rem; font-weight: 600;">Lihat Semua</a>
                </div>
                <div class="text-center py-4 text-dark opacity-75">
                    <i class="fas fa-clock fa-2x mb-2 text-dark opacity-50"></i>
                    <p class="mb-0" style="font-size: 0.85rem; font-weight: 600;">Belum ada aktivitas</p>
                    <small style="font-size: 0.72rem;">Data aktivitas parkir akan muncul setelah perangkat IoT mengirimkan informasi.</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const slotDataInfo = {
        P01: { name: 'Slot Parkir Utama 01' },
        P02: { name: 'Slot Parkir Utama 02' },
        P03: { name: 'Slot Parkir Utama 03' },
        P04: { name: 'Slot Parkir Utama 04' },
        P05: { name: 'Slot Parkir Utama 05' },
        P06: { name: 'Slot Parkir Utama 06' },
        P07: { name: 'Slot Parkir Utama 07' },
        P08: { name: 'Slot Parkir Utama 08' },
        P09: { name: 'Slot Parkir Utama 09' },
        P10: { name: 'Slot Parkir Utama 10' }
    };

    const slotKeys = Object.keys(slotDataInfo);

    function renderParkingUI(data) {
        const gridContainer = document.getElementById('parkingSlotsGrid');
        const tableBody = document.getElementById('detailSlotTableBody');
        
        gridContainer.innerHTML = '';
        tableBody.innerHTML = '';

        let availableCount = 0;
        let occupiedCount = 0;

        slotKeys.forEach(slotId => {
            const slotInfo = data[slotId] || { status: 'nodata', time: 'Belum ada data' };
            
            let statusClass = 'is-nodata';
            let badgeClass = 'status-nodata';

            if (slotInfo.status === 'available') {
                statusClass = 'is-available';
                badgeClass = 'status-available';
                availableCount++;
            } else if (slotInfo.status === 'occupied') {
                statusClass = 'is-occupied';
                badgeClass = 'status-occupied';
                occupiedCount++;
            }

            gridContainer.innerHTML += `
                <div class="col-md-4 col-lg-2.4" style="flex: 0 0 20%; max-width: 20%;">
                    <div class="slot-box ${statusClass}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-primary" style="font-size: 0.85rem;">${slotId}</span>
                            <span class="slot-badge-status ${badgeClass}" style="font-size: 0.6rem; padding: 2px 6px;">${slotInfo.status === 'available' ? 'Kosong' : (slotInfo.status === 'occupied' ? 'Terisi' : '-')}</span>
                        </div>
                        <div style="font-size: 0.75rem; color: #64748b;" class="text-truncate">
                            <i class="fas fa-car me-1"></i> ${slotInfo.status === 'available' ? 'Tersedia' : (slotInfo.status === 'occupied' ? 'Terisi' : 'Offline')}
                        </div>
                    </div>
                </div>
            `;

            tableBody.innerHTML += `
                <tr class="border-bottom">
                    <td class="fw-bold text-dark">${slotId}</td>
                    <td>${slotTextBadge(slotInfo.status)}</td>
                    <td class="text-end text-dark opacity-75" style="font-size: 0.75rem;">${slotInfo.time || 'Belum ada data'}</td>
                </tr>
            `;
        });

        document.getElementById('cardAvailableCount').textContent = availableCount;
        document.getElementById('cardOccupiedCount').textContent = occupiedCount;
        
        const pctAvailable = Math.round((availableCount / slotKeys.length) * 100);
        const pctOccupied = Math.round((occupiedCount / slotKeys.length) * 100);

        document.getElementById('barAvailable').style.width = pctAvailable + '%';
        document.getElementById('textAvailableDesc').textContent = `Slot kosong (${pctAvailable}%)`;

        document.getElementById('barOccupied').style.width = pctOccupied + '%';
        document.getElementById('textOccupiedDesc').textContent = `Slot terisi (${pctOccupied}%)`;

        if (availableCount > 0 || occupiedCount > 0) {
            document.getElementById('iotMainStatus').textContent = 'Online';
            document.getElementById('iotStatusText').textContent = 'Perangkat terhubung';
            document.getElementById('iotDot').className = 'spinner-grow spinner-grow-sm text-success me-1';
        }
    }

    function slotTextBadge(status) {
        if (status === 'available') return '<span class="text-success fw-semibold">Kosong</span>';
        if (status === 'occupied') return '<span class="text-danger fw-semibold">Terisi</span>';
        return '<span class="text-muted">-</span>';
    }

    function fetchParkingDataFromDB() {
        fetch('/api/parking')
            .then(res => res.json())
            .then(data => renderParkingUI(data))
            .catch(() => renderParkingUI({}));
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchParkingDataFromDB();
        setInterval(fetchParkingDataFromDB, 5000);
    });
</script>
@endpush