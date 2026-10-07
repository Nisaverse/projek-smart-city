@extends('layouts.app')
@section('title', 'System Control Center')
@section('page-title', 'System Control Center')

@push('styles')
<style>
    /* ===== KARTU UTAMA DENGAN LATAR PUTIH & EFEK GLOW NAVY ===== */
    .control-white-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        padding: 24px !important;
        box-shadow: 0 10px 30px rgba(11, 19, 43, 0.05), 0 0 15px rgba(11, 19, 43, 0.03);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .control-white-card::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(11, 19, 43, 0.12) 0%, rgba(37, 99, 235, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* ===== KOTAK PILIHAN MODE KONTROL (VERTIKAL/GRID RAPI) ===== */
    .mode-option-box {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .mode-option-box:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }

    .mode-option-box.active {
        background: rgba(37, 99, 235, 0.04);
        border-color: #2563eb;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.1);
    }

    /* ===== KOTAK KONTROL DEVICE LAMPU ===== */
    .device-control-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        transition: all 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .device-control-box:hover {
        border-color: #94a3b8;
        box-shadow: 0 4px 12px rgba(11, 19, 43, 0.04);
    }

    /* Banner informasi mode aktif */
    .active-mode-banner {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 14px 18px;
        color: #166534;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- PERUBAHAN LAYOUT: DISUSUN MENJADI 2 KOLOM UTAMA (KIRI: MODE, KANAN: SAKLAR PERANGKAT) -->
    <div class="row g-4">
        
        <!-- KOLOM KIRI: PENGATURAN MODE OPERASI -->
        <div class="col-lg-5">
            <div class="control-white-card d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">
                            <i class="fas fa-cogs text-primary me-2"></i> Mode Operasi
                        </h5>
                        <span class="badge bg-light text-primary border border-primary px-2.5 py-1.5 rounded-pill" style="font-size: 0.7rem;">
                            System Control
                        </span>
                    </div>
                    <p class="text-muted mb-4" style="font-size: 0.85rem;">Pilih metode kendali operasional sistem pencahayaan perkotaan secara real-time.</p>

                    <!-- Pilihan Mode Berbentuk Susunan Vertikal yang Elegan -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        
                        <!-- Manual Control -->
                        <div class="mode-option-box active" id="cardModeManual" onclick="selectControlMode('manual')">
                            <div style="width: 45px; height: 45px; background: rgba(37,99,235,0.1); color: #2563eb; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 0.95rem;">Manual Control</h6>
                                    <span class="badge bg-primary text-white" id="badgeManual" style="font-size: 0.65rem;">Aktif</span>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Kendali langsung melalui saklar panel utama.</p>
                            </div>
                        </div>

                        <!-- Auto Sensor -->
                        <div class="mode-option-box" id="cardModeSensor" onclick="selectControlMode('auto_sensor')">
                            <div style="width: 45px; height: 45px; background: rgba(16,185,129,0.1); color: #10b981; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                                <i class="fas fa-cloud-moon"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-1" style="color: #0f172a; font-size: 0.95rem;">Auto Sensor</h6>
                                    <div class="form-check form-switch m-0" onclick="event.stopPropagation()">
                                        <input class="form-check-input" type="checkbox" id="switchSensor" onchange="toggleModeSwitch('auto_sensor', this.checked)" style="width: 2.3em; height: 1.2em; cursor: pointer;">
                                    </div>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 0.78rem;">Otomatis menyesuaikan intensitas cahaya sekitar.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Status Banner di Bagian Bawah Kolom Kiri -->
                <div class="active-mode-banner d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-success fs-5 flex-shrink: 0;"></i>
                    <span>Status: <strong id="activeModeBannerText" style="color: #15803d;">Manual Control - Kontrol saklar langsung aktif</strong></span>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: PANEL SAKLAR PERANGKAT LAMPU -->
        <div class="col-lg-7">
            <div class="control-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">
                            <i class="fas fa-lightbulb text-warning me-2"></i> Smart Lamp Control Panel
                        </h5>
                        <small class="text-muted">Manajemen relay perangkat penerangan jalan</small>
                    </div>
                    <span class="badge bg-light text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i> Relay Connected
                    </span>
                </div>

                <p class="text-muted mb-4" style="font-size: 0.85rem;">Aktifkan atau matikan titik sektor lampu secara individual.</p>

                <!-- Grid Saklar Perangkat (2 Kolom ke Bawah) -->
                <div class="row g-3">
                    
                    <!-- L01 -->
                    <div class="col-md-6">
                        <div class="device-control-box">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">Sektor L01</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Alun-Alun Kota</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="lampL01_switch" style="width: 2.5em; height: 1.3em; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <!-- L02 -->
                    <div class="col-md-6">
                        <div class="device-control-box">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">Sektor L02</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Kawasan Panggung</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="lampL02_switch" checked style="width: 2.5em; height: 1.3em; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <!-- L03 -->
                    <div class="col-md-6">
                        <div class="device-control-box">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">Sektor L03</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Wilayah Kaligangsa</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="lampL03_switch" style="width: 2.5em; height: 1.3em; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <!-- L04 -->
                    <div class="col-md-6">
                        <div class="device-control-box">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">Sektor L04</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Tegal Marina</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="lampL04_switch" checked style="width: 2.5em; height: 1.3em; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function selectControlMode(mode) {
        document.querySelectorAll('.mode-option-box').forEach(el => el.classList.remove('active'));
        
        const banner = document.getElementById('activeModeBannerText');
        const swSensor = document.getElementById('switchSensor');

        if (mode === 'manual') {
            document.getElementById('cardModeManual').classList.add('active');
            banner.textContent = 'Manual Control - Kontrol saklar langsung aktif';
            if (swSensor) swSensor.checked = false;
        } else if (mode === 'auto_sensor') {
            document.getElementById('cardModeSensor').classList.add('active');
            banner.textContent = 'Auto Sensor - Sistem otomatis membaca cahaya lingkungan';
            if (swSensor) swSensor.checked = true;
        }

        fetch('/api/control/mode', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
            body: JSON.stringify({ mode: mode })
        }).catch(() => console.log('Mode updated locally'));
    }

    function toggleModeSwitch(mode, isChecked) {
        if (isChecked) {
            selectControlMode(mode);
        } else {
            selectControlMode('manual');
        }
    }
</script>
@endpush