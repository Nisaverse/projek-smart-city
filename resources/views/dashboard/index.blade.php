@extends('layouts.app')

@section('title', 'Dashboard Overview - Tegal EcoSense')

@push('styles')
<style>
    /* ===== BACKGROUND UTAMA ===== */
    .dashboard-wrapper {
        width: 100%;
        min-height: 100vh;
        background-color: #f1f5f9;
        padding: 20px;
    }

    /* ===== KARTU METALLIC SILVER DENGAN AKSEN GELEMBUNG (GLOW BULB) ===== */
    .stat-top-card,
    .dashboard-glass-card {
        background: linear-gradient(135deg, #d3d7de 0%, #b8bec7 50%, #a4abb5 100%) !important;
        border: 1px solid #ffffff !important;
        border-radius: 20px !important;
        padding: 22px !important;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12), 
                    inset 0 2px 4px rgba(255, 255, 255, 0.9),
                    inset 0 -2px 4px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden; /* Menjaga efek gelembung melengkung di dalam kartu */
    }

    /* Aksen Gelembung Kilatan Cahaya Putih (3D Inner Glow) di Pojok Kanan Atas */
    .stat-top-card::before,
    .dashboard-glass-card::before {
        content: "";
        position: absolute;
        top: -30px;
        right: -30px;
        width: 130px;
        height: 130px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.45) 40%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .stat-top-card:hover,
    .dashboard-glass-card:hover {
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.18), 
                    inset 0 2px 5px rgba(255, 255, 255, 1) !important;
        transform: translateY(-2px);
    }

    .stat-top-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Typography */
    .stat-title {
        font-weight: 800;
        color: #0f172a;
        font-size: 0.95rem;
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin: 12px 0;
    }

    .stat-subtitle {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
    }

    /* Tabel Aktivitas Perangkat */
    .table-custom-glass {
        background: transparent;
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom-glass th {
        border-bottom: 2px solid rgba(255, 255, 255, 0.7);
        padding: 12px 16px;
        font-size: 0.85rem;
        font-weight: 800;
        color: #0f172a;
    }

    .table-custom-glass td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        padding: 12px 16px;
        font-size: 0.85rem;
        color: #334155;
    }

    .h3.mb-0.text-gray-800,
    .d-sm-flex.align-items-center.justify-content-between.mb-4 > h1 {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">

    <!-- 1. BARIS ATAS: 4 KARTU RINGKASAN STATISTIK -->
    <div class="row g-3 mb-4">
        <!-- Smart Lamps Active -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-top-card">
                <div class="stat-title">Smart Lamps Active</div>
                <div class="stat-value">2 / 4</div>
                <div class="stat-subtitle">Smart Lamps Active</div>
            </div>
        </div>

        <!-- Kelembapan Rata-Rata -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-top-card">
                <div class="stat-title">Kelembapan Rata-Rata</div>
                <div class="stat-value" style="color: #0284c7;">65%</div>
                <div class="stat-subtitle">Kelembapan Rata-Rata</div>
            </div>
        </div>

        <!-- Parking Available -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-top-card">
                <div class="stat-title">Parking Available</div>
                <div class="stat-value" style="color: #16a34a;">3 / 4</div>
                <div class="stat-subtitle">Parking Available</div>
            </div>
        </div>

        <!-- Active Alerts -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-top-card">
                <div class="stat-title">Active Alerts</div>
                <div class="stat-value">0</div>
                <div class="stat-subtitle">Active Alerts</div>
            </div>
        </div>
    </div>

    <!-- 2. BARIS TENGAH: REALTIME SENSOR DATA OVERVIEW & PARKING OCCUPANCY -->
    <div class="row g-4 mb-4">
        <!-- Realtime Sensor Data Overview -->
        <div class="col-lg-7">
            <div class="dashboard-glass-card h-100" style="min-height: 320px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-chart-line text-dark"></i>
                        <h6 class="m-0 font-weight-bold text-dark" style="font-weight: 800; text-transform: uppercase;">Realtime Sensor Data Overview</h6>
                    </div>
                    <small class="text-secondary fw-bold d-flex align-items-center gap-1">
                        <i class="fas fa-sync-alt"></i> Auto Update
                    </small>
                </div>
                <!-- Area Grafik / Chart -->
                <div class="d-flex align-items-center justify-content-center h-75 text-secondary">
                    <!-- Grafik dimasukkan di sini jika menggunakan Chart.js/ApexCharts -->
                </div>
            </div>
        </div>

        <!-- Parking Occupancy -->
        <div class="col-lg-5">
            <div class="dashboard-glass-card h-100" style="min-height: 320px;">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-parking text-dark"></i>
                    <h6 class="m-0 font-weight-bold text-dark" style="font-weight: 800; text-transform: uppercase;">Parking Occupancy</h6>
                </div>
                <!-- Area Preview Parkir -->
                <div class="d-flex align-items-center justify-content-center h-75 text-secondary">
                    <!-- Preview Parkir dimasukkan di sini -->
                </div>
            </div>
        </div>
    </div>

    <!-- 3. BARIS BAWAH: TABEL AKTIVITAS PERANGKAT TERAKHIR -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-glass-card">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-history text-dark"></i>
                    <h6 class="m-0 font-weight-bold text-dark" style="font-weight: 800;">Aktivitas Perangkat Terakhir</h6>
                </div>

                <div class="table-responsive">
                    <table class="table-custom-glass">
                        <thead>
                            <tr>
                                <th>Perangkat</th>
                                <th>Modul</th>
                                <th>Status / Nilai</th>
                                <th>Waktu Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Smart Lamp 01</b></td>
                                <td>Penerangan Jalan</td>
                                <td><span class="badge bg-warning text-dark px-2 py-1">Menyala (80%)</span></td>
                                <td>2 Menit lalu</td>
                            </tr>
                            <tr>
                                <td><b>Sensor Suhu 101</b></td>
                                <td>Environment</td>
                                <td><span class="badge bg-info text-white px-2 py-1">28.5 °C</span></td>
                                <td>1 Min lalu</td>
                            </tr>
                            <tr>
                                <td><b>Slot Parkir P02</b></td>
                                <td>Smart Parking</td>
                                <td><span class="badge bg-danger text-white px-2 py-1">Occupied</span></td>
                                <td>Baru saja</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection