@extends('layouts.app')
@section('title', 'Control Center')
@section('page-title', 'IoT Control Center')

@push('styles')
<style>
    /* ===== BASE CARD STYLING ===== */
    .control-white-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 20px !important;
        padding: 24px !important;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 
                    0 8px 10px -6px rgba(15, 23, 42, 0.02) !important;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), 
                    box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), 
                    border-color 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        will-change: transform, box-shadow;
        position: relative;
    }

    .control-white-card:hover {
        box-shadow: 0 20px 35px -10px rgba(15, 23, 42, 0.1), 
                    0 10px 15px -5px rgba(15, 23, 42, 0.04) !important;
        transform: translateY(-3px) !important;
        border-color: #cbd5e1 !important;
    }

    .control-inner-box {
        background: #f8fafc !important;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 20px;
    }

    /* ===== MODE BUTTONS STYLING ===== */
    .mode-btn {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #0f172a;
        font-weight: 600;
        padding: 16px 20px;
        border-radius: 14px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        cursor: pointer;
    }

    .mode-btn:hover {
        transform: translateY(-2px);
        border-color: #94a3b8;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
    }

    /* MODE AKTIF MENGGUNAKAN DARK NAVY */
    .mode-btn.active-mode {
        background: linear-gradient(135deg, #0b132b 0%, #1e3a8a 100%) !important;
        color: #ffffff !important;
        border-color: #0b132b !important;
        box-shadow: 0 8px 20px rgba(11, 19, 43, 0.35);
    }

    .mode-btn.active-mode small {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .mode-btn.active-mode .mode-icon-box {
        background: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }

    /* ===== INTERACTIVE SUB-CARDS ===== */
    .interactive-item-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
    }

    .interactive-item-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        transform: translateY(-2px);
    }

    /* BORDER CARD SAAT AKTIF HANYA HIGHLIGHT NORMALLY */
    .interactive-item-card.is-active {
        border-color: #0b132b !important;
    }

    /* JUDUL SEKTOR MENGGUNAKAN TEKS DARK NAVY */
    .text-navy-title {
        color: #0b132b !important;
        font-weight: 700;
        font-size: 0.95rem;
    }

    /* ===== TOMBOL SWITCH ON OFF DENGAN WARNA DARK NAVY ===== */
    .form-check-input:checked {
        background-color: #1e3a8a !important;
        border-color: #0b132b !important;
    }

    /* ===== DISABLED STATE (AUTOMATIC MODE) ===== */
    .disabled-control {
        opacity: 0.55;
        pointer-events: none;
        filter: grayscale(30%);
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- BARIS UTAMA: MODE OPERASI & RELAY SAKELAR LAMPU -->
    <div class="row g-4 align-items-stretch">
        
        <!-- KOLOM KIRI: OPERATION MODES -->
        <div class="col-lg-6">
            <div class="control-white-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.15rem;">Mode Operasi Sistem</h5>
                        <small class="text-secondary fw-medium">Pilih mode kendali perangkat IoT Tegal EcoSense</small>
                    </div>
                    <span class="badge bg-light text-dark border border-secondary-subtle px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem; color: #0b132b !important;">
                        <i class="fas fa-sliders-h me-1"></i> Control Mode
                    </span>
                </div>

                <div class="control-inner-box mt-3">
                    <div class="d-flex flex-column gap-3">
                        <!-- Mode Otomatis -->
                        <button type="button" class="mode-btn active-mode" id="btnModeAuto" onclick="setControlMode('auto')">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mode-icon-box" style="width: 40px; height: 40px; background: rgba(11, 19, 43, 0.08); color: #0b132b; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-robot fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Mode Otomatis (Sensor / Jadwal)</div>
                                    <small class="opacity-75" style="font-size: 0.75rem;">Sistem berjalan otomatis, kontrol manual dikunci</small>
                                </div>
                            </div>
                            <i class="fas fa-check-circle fs-5" id="iconAuto"></i>
                        </button>

                        <!-- Mode Manual -->
                        <button type="button" class="mode-btn" id="btnModeManual" onclick="setControlMode('manual')">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mode-icon-box" style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.1); color: #d97706; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-hand-paper fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Mode Manual (Kontrol Langsung)</div>
                                    <small class="opacity-75" style="font-size: 0.75rem;">Akses penuh ke sakelar relay lampu</small>
                                </div>
                            </div>
                            <i class="fas fa-circle text-muted fs-6" id="iconManual"></i>
                        </button>
                    </div>

                    <div class="mt-4 pt-3 border-top text-secondary" style="font-size: 0.8rem;">
                        <div class="d-flex align-items-center justify-content-between">
                            <span><i class="fas fa-info-circle me-1"></i> Status Sistem: <span class="fw-bold text-dark" id="currentModeLabel">Otomatis</span></span>
                            <span id="modeStatusBadge" class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-lock me-1"></i> Terkunci</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: RELAY SAKELAR LAMPU -->
        <div class="col-lg-6">
            <div class="control-white-card h-100" id="relayCardSection">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.15rem;">Relay Sakelar Lampu</h5>
                        <small class="text-secondary fw-medium">Kontrol daya kelistrikan sektor penerangan</small>
                    </div>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-bolt me-1"></i> Relays
                    </span>
                </div>

                <div class="control-inner-box mt-3">
                    <div class="row g-3">
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="col-md-6">
                            <div class="interactive-item-card d-flex justify-content-between align-items-center" id="relayBox{{ $i }}">
                                <div>
                                    <div class="text-navy-title">Sektor {{ $i }}</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Status: <span id="relay{{ $i }}StatusText" class="fw-semibold text-danger">Mati (Off)</span></small>
                                </div>
                                <div class="form-check form-switch fs-4 m-0">
                                    <input class="form-check-input relay-switch" type="checkbox" role="switch" id="relay{{ $i }}Switch" onchange="toggleRelay({{ $i }})">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="mt-4 pt-3 border-top text-secondary" style="font-size: 0.8rem;">
                        <i class="fas fa-shield-alt text-success me-1"></i> Terhubung langsung ke database MQTT Broker
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function setControlMode(mode) {
        document.querySelectorAll('.mode-btn').forEach(btn => btn.classList.remove('active-mode'));
        
        const btnAuto = document.getElementById('btnModeAuto');
        const btnManual = document.getElementById('btnModeManual');
        const iconAuto = document.getElementById('iconAuto');
        const iconManual = document.getElementById('iconManual');
        const label = document.getElementById('currentModeLabel');
        const badge = document.getElementById('modeStatusBadge');

        iconAuto.className = "fas fa-circle text-muted fs-6";
        iconManual.className = "fas fa-circle text-muted fs-6";

        const relaySwitches = document.querySelectorAll('.relay-switch');
        const relaySection = document.getElementById('relayCardSection');

        if (mode === 'auto') {
            btnAuto.classList.add('active-mode');
            iconAuto.className = "fas fa-check-circle fs-5";
            if (label) label.textContent = 'Otomatis';
            if (badge) {
                badge.className = 'badge bg-warning text-dark px-2 py-1';
                badge.innerHTML = '<i class="fas fa-lock me-1"></i> Terkunci';
            }

            relaySwitches.forEach(sw => sw.disabled = true);
            relaySection.classList.add('disabled-control');

        } else if (mode === 'manual') {
            btnManual.classList.add('active-mode');
            iconManual.className = "fas fa-check-circle fs-5";
            if (label) label.textContent = 'Manual';
            if (badge) {
                badge.className = 'badge bg-success text-white px-2 py-1';
                badge.innerHTML = '<i class="fas fa-lock-open me-1"></i> Aktif';
            }

            relaySwitches.forEach(sw => sw.disabled = false);
            relaySection.classList.remove('disabled-control');
        }

        localStorage.setItem('iot_system_mode', mode);

        fetch('/api/control/mode', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ mode: mode })
        }).catch(() => {});
    }

    function toggleRelay(relayId) {
        const sw = document.getElementById(`relay${relayId}Switch`);
        const statusText = document.getElementById(`relay${relayId}StatusText`);
        const box = document.getElementById(`relayBox${relayId}`);
        const isChecked = sw.checked;
        
        if (isChecked) {
            statusText.textContent = 'Menyala (On)';
            statusText.className = 'fw-bold text-success';
            if (box) box.classList.add('is-active');
        } else {
            statusText.textContent = 'Mati (Off)';
            statusText.className = 'fw-semibold text-danger';
            if (box) box.classList.remove('is-active');
        }

        let relayStates = JSON.parse(localStorage.getItem('iot_relay_states')) || {};
        relayStates[relayId] = isChecked ? 1 : 0;
        localStorage.setItem('iot_relay_states', JSON.stringify(relayStates));

        fetch('/api/control/relay', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ relay: relayId, state: isChecked ? 1 : 0 })
        }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        const currentMode = localStorage.getItem('iot_system_mode') || 'auto';
        setControlMode(currentMode);

        const savedRelays = JSON.parse(localStorage.getItem('iot_relay_states')) || {};
        for (let i = 1; i <= 4; i++) {
            if (savedRelays[i] !== undefined) {
                const sw = document.getElementById(`relay${i}Switch`);
                if (sw) {
                    sw.checked = savedRelays[i] === 1;
                    toggleRelay(i);
                }
            }
        }
    });
</script>
@endpush