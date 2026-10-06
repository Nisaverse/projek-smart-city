@extends('layouts.app')
@section('title', 'System Control Center')

@push('styles')
<style>
    /* ===== DEFINISI TEMA WARNA UNTUK SEMUA CARD CONTROL ===== */
    #dashboardThemeWrapper[data-theme="gradient-tricolor"] {
        --card-bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e3a8a 35%, #854d0e 70%, #065f46 100%);
        --card-border: rgba(251, 191, 36, 0.4);
        --card-glow: rgba(16, 185, 129, 0.25);
        --accent-color: #fde047;
    }

    #dashboardThemeWrapper[data-theme="cyberpunk-purple"] {
        --card-bg-gradient: linear-gradient(135deg, #2e1065 0%, #581c87 50%, #831843 100%);
        --card-border: rgba(236, 72, 153, 0.4);
        --card-glow: rgba(236, 72, 153, 0.3);
        --accent-color: #f472b6;
    }

    #dashboardThemeWrapper[data-theme="emerald-nature"] {
        --card-bg-gradient: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0f172a 100%);
        --card-border: rgba(52, 211, 153, 0.4);
        --card-glow: rgba(16, 185, 129, 0.3);
        --accent-color: #6ee7b7;
    }

    /* ===== BACKGROUND TITIK KELAP-KELIP BESAR ===== */
    .twinkle-bg-wrapper-light {
        position: relative;
        background: #f8fafc;
        min-height: calc(100vh - 100px);
        padding: 25px;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .twinkle-bg-wrapper-light::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: 
            radial-gradient(5px 5px at 50px 60px, #3b82f6, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 150px 180px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 280px 80px, #2563eb, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 390px 220px, #0284c7, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 520px 110px, #3b82f6, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 640px 250px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 780px 90px, #2563eb, rgba(255,255,255,0));
        background-repeat: repeat;
        background-size: 850px 350px;
        animation: lightTwinkleBig 3.5s ease-in-out infinite alternate;
        pointer-events: none;
        opacity: 0.75;
        z-index: 1;
    }

    .twinkle-bg-wrapper-light::after {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: 
            radial-gradient(6px 6px at 90px 220px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 210px 100px, #f59e0b, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 330px 290px, #3b82f6, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 460px 50px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 590px 210px, #f59e0b, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 710px 130px, #3b82f6, rgba(255,255,255,0));
        background-repeat: repeat;
        background-size: 800px 380px;
        animation: lightTwinkleBigAlt 5s ease-in-out infinite alternate;
        pointer-events: none;
        opacity: 0.65;
        z-index: 1;
    }

    .smart-control-content {
        position: relative;
        z-index: 2;
    }

    @keyframes lightTwinkleBig {
        0% { opacity: 0.2; transform: scale(0.9) translateY(0px); filter: blur(0px); }
        50% { opacity: 0.85; filter: blur(1px); }
        100% { opacity: 0.3; transform: scale(1.1) translateY(-4px); filter: blur(0px); }
    }

    @keyframes lightTwinkleBigAlt {
        0% { opacity: 0.7; transform: scale(1.05); }
        50% { opacity: 0.2; }
        100% { opacity: 0.8; transform: scale(0.95); }
    }

    /* ===== STYLE CARDS DENGAN GRADASI DINAMIS ===== */
    .custom-gradient-card {
        background: var(--card-bg-gradient, linear-gradient(135deg, #0f172a 0%, #1e3a8a 35%, #854d0e 70%, #065f46 100%)) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--card-border, rgba(251, 191, 36, 0.3)) !important;
        border-radius: 18px !important;
        padding: 22px !important;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15) !important;
        transition: all 0.4s ease;
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .custom-gradient-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 35px var(--card-glow, rgba(0,0,0,0.3)) !important;
    }

    /* ITEM CARD DALAM KONTROL (SEPERTI OPTION KONTROL) */
    .control-subcard {
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        padding: 18px;
        transition: all 0.3s ease;
        color: #ffffff;
        height: 100%;
        cursor: pointer;
    }

    .control-subcard:hover, .control-subcard.active {
        background: rgba(255, 255, 255, 0.12);
        border-color: var(--accent-color, #fde047);
        box-shadow: 0 0 15px rgba(253, 224, 71, 0.2);
    }

    /* BANNER NOTIFIKASI MODE AKTIF */
    .active-mode-banner {
        background: rgba(15, 23, 42, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 14px 20px;
        color: #f1f5f9;
        font-size: 0.9rem;
    }

    /* ITEM DEVICE LAMP CARD CONTROL */
    .device-control-box {
        background: rgba(15, 23, 42, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        padding: 16px;
        transition: all 0.3s ease;
    }

    .device-control-box:hover {
        border-color: rgba(255, 255, 255, 0.3);
    }

    /* DROPDOWN SELECTOR TEMA */
    .theme-select-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #0f172a;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 10px;
        padding: 6px 12px;
        cursor: pointer;
        outline: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="twinkle-bg-wrapper-light" id="dashboardThemeWrapper" data-theme="gradient-tricolor">
    <div class="smart-control-content">

        <!-- DROPDOWN PEMILIH TEMA WARNA UNTUK SEMUA CARD -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-sliders-h text-warning me-2"></i> System Control Panel
            </h5>
            <div class="d-flex align-items-center gap-2">
                <label for="allThemeSelector" class="form-label m-0 font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    <i class="fas fa-palette text-primary me-1"></i> Pilih Tema Semua Cards:
                </label>
                <select id="allThemeSelector" class="theme-select-box" onchange="changeAllCardsTheme(this.value)">
                    <option value="gradient-tricolor">🌈 Biru-Kuning-Hijau</option>
                    <option value="cyberpunk-purple">🔮 Cyberpunk Purple</option>
                    <option value="emerald-nature">🍃 Emerald Nature</option>
                </select>
            </div>
        </div>

        <!-- 1. CONTROL MODE CARD -->
        <div class="data-card custom-gradient-card mb-4">
            <div class="mb-3">
                <h6 class="text-white mb-1" style="font-size: 1.05rem; font-weight: 700;">
                    <i class="fas fa-cogs text-warning me-2"></i> Control Mode
                </h6>
                <small style="color: #cbd5e1;">Pilih mode operasi lampu. Mode otomatis akan mengontrol lampu berdasarkan jadwal atau sensor cahaya.</small>
            </div>

            <div class="row g-3 mt-1">
                <!-- MANUAL CONTROL -->
                <div class="col-md-4">
                    <div class="control-subcard active" id="cardModeManual" onclick="selectControlMode('manual')">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <i class="fas fa-wrench text-warning fs-5"></i>
                        </div>
                        <h6 style="font-weight: 700; font-size: 0.95rem;" class="mb-1">Manual Control</h6>
                        <small style="color: #cbd5e1; font-size: 0.8rem;">Kontrol penuh via panel ini</small>
                    </div>
                </div>

                <!-- AUTO SCHEDULE -->
                <div class="col-md-4">
                    <div class="control-subcard" id="cardModeSchedule" onclick="selectControlMode('auto_schedule')">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <i class="fas fa-clock text-info fs-5"></i>
                            <div class="form-check form-switch m-0" onclick="event.stopPropagation()">
                                <input class="form-check-input" type="checkbox" id="switchSchedule" onchange="toggleModeSwitch('auto_schedule', this.checked)">
                            </div>
                        </div>
                        <h6 style="font-weight: 700; font-size: 0.95rem;" class="mb-1">Auto Schedule</h6>
                        <small style="color: #cbd5e1; font-size: 0.8rem;">Nyala sesuai jadwal (bisa diatur)</small>
                    </div>
                </div>

                <!-- AUTO SENSOR -->
                <div class="col-md-4">
                    <div class="control-subcard" id="cardModeSensor" onclick="selectControlMode('auto_sensor')">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <i class="fas fa-cloud-moon text-primary fs-5"></i>
                            <div class="form-check form-switch m-0" onclick="event.stopPropagation()">
                                <input class="form-check-input" type="checkbox" id="switchSensor" onchange="toggleModeSwitch('auto_sensor', this.checked)">
                            </div>
                        </div>
                        <h6 style="font-weight: 700; font-size: 0.95rem;" class="mb-1">Auto Sensor</h6>
                        <small style="color: #cbd5e1; font-size: 0.8rem;">Nyala saat gelap (threshold bisa diatur)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. SMART LAMP CONTROL CARD -->
        <div class="data-card custom-gradient-card">
            <h6 class="text-white mb-3" style="font-size: 1.05rem; font-weight: 700;">
                <i class="fas fa-lightbulb text-warning me-2"></i> Smart Lamp Control
            </h6>

            <!-- BANNER INFORMASI MODE AKTIF -->
            <div class="active-mode-banner mb-4 d-flex align-items-center gap-2">
                <i class="fas fa-info-circle text-info"></i>
                <span>Mode aktif: <strong id="activeModeBannerText" style="color: #fde047;">Manual Control - Anda mengontrol lampu secara langsung</strong></span>
            </div>

            <!-- SWITCH DEVICE LAMPU -->
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="device-control-box d-flex justify-content-between align-items-center">
                        <span style="font-size: 0.88rem; font-weight: 600;">Street Lamp A</span>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="lampA_switch" style="width: 2.5em; height: 1.3em; cursor: pointer;">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="device-control-box d-flex justify-content-between align-items-center">
                        <span style="font-size: 0.88rem; font-weight: 600;">Street Lamp B</span>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="lampB_switch" checked style="width: 2.5em; height: 1.3em; cursor: pointer;">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="device-control-box d-flex justify-content-between align-items-center">
                        <span style="font-size: 0.88rem; font-weight: 600;">Street Lamp C</span>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="lampC_switch" style="width: 2.5em; height: 1.3em; cursor: pointer;">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="device-control-box d-flex justify-content-between align-items-center">
                        <span style="font-size: 0.88rem; font-weight: 600;">Street Lamp D</span>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="lampD_switch" checked style="width: 2.5em; height: 1.3em; cursor: pointer;">
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
    // FUNGSI MENGUBAH TEMA WARNA SELURUH CARDS DI HALAMAN CONTROL
    function changeAllCardsTheme(themeName) {
        const wrapper = document.getElementById('dashboardThemeWrapper');
        if (wrapper) {
            wrapper.setAttribute('data-theme', themeName);
            localStorage.setItem('globalDashboardCardTheme', themeName);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('globalDashboardCardTheme') || 'gradient-tricolor';
        const selector = document.getElementById('allThemeSelector');
        if (selector) selector.value = savedTheme;
        changeAllCardsTheme(savedTheme);
    });

    // FUNGSI PILIH MODE KONTROL
    function selectControlMode(mode) {
        document.querySelectorAll('.control-subcard').forEach(el => el.classList.remove('active'));
        
        const banner = document.getElementById('activeModeBannerText');
        const swSchedule = document.getElementById('switchSchedule');
        const swSensor = document.getElementById('switchSensor');

        if (mode === 'manual') {
            document.getElementById('cardModeManual').classList.add('active');
            banner.textContent = 'Manual Control - Anda mengontrol lampu secara langsung';
            if (swSchedule) swSchedule.checked = false;
            if (swSensor) swSensor.checked = false;
        } else if (mode === 'auto_schedule') {
            document.getElementById('cardModeSchedule').classList.add('active');
            banner.textContent = 'Auto Schedule - Lampu dikontrol otomatis berdasarkan jadwal';
            if (swSchedule) swSchedule.checked = true;
            if (swSensor) swSensor.checked = false;
        } else if (mode === 'auto_sensor') {
            document.getElementById('cardModeSensor').classList.add('active');
            banner.textContent = 'Auto Sensor - Lampu dikontrol otomatis berdasarkan sensor cahaya';
            if (swSchedule) swSchedule.checked = false;
            if (swSensor) swSensor.checked = true;
        }

        // KIRIM KE API (OPSIONAL)
        fetch('/api/control/mode', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ mode: mode })
        }).catch(err => console.log('Mode updated locally'));
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