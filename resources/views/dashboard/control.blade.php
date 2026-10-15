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

    .mode-btn.active-mode {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border-color: #1e40af !important;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
    }

    .mode-btn.active-mode small {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .mode-btn.active-mode .mode-icon-box {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }

    /* ===== INTERACTIVE SUB-CARDS (RELAY & PARKING) ===== */
    .interactive-item-card {
        background: #ffffff;
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

    .interactive-item-card.is-active {
        border-color: #93c5fd;
        background: #f0f9ff;
    }

    .interactive-item-card.is-occupied {
        border-color: #fca5a5;
        background: #fff1f2;
    }

    .text-blue-title {
        color: #2563eb !important;
        font-weight: 700;
        font-size: 0.95rem;
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

    <!-- BARIS 1: MODE OPERASI & RELAY SAKELAR -->
    <div class="row g-4 mb-4 align-items-stretch">
        
        <!-- KOLOM KIRI: OPERATION MODES -->
        <div class="col-lg-6">
            <div class="control-white-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.15rem;">Mode Operasi Sistem</h5>
                        <small class="text-secondary fw-medium">Pilih mode kendali perangkat IoT Tegal EcoSense</small>
                    </div>
                    <span class="badge bg-blue-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-sliders-h me-1"></i> Control Mode
                    </span>
                </div>

                <div class="control-inner-box mt-3">
                    <div class="d-flex flex-column gap-3">
                        <!-- Mode Otomatis -->
                        <button type="button" class="mode-btn active-mode" id="btnModeAuto" onclick="setControlMode('auto')">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mode-icon-box" style="width: 40px; height: 40px; background: rgba(37, 99, 235, 0.1); color: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
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
                                    <small class="opacity-75" style="font-size: 0.75rem;">Akses penuh ke sakelar relay & slot parkir</small>
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
                                    <div class="text-blue-title">Sektor {{ $i }}</div>
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

    <!-- BARIS 2: KARTU KONTROL PARKIR LANDSCAPE -->
    <div class="row">
        <div class="col-12">
            <div class="control-white-card" id="parkingCardSection">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.15rem;">Kontrol Manual Smart Parking</h5>
                        <small class="text-secondary fw-medium">Simulasi status keterisian 10 slot parkir kendaraan</small>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-parking me-1"></i> Parking Grid
                    </span>
                </div>

                <div class="control-inner-box mt-3">
                    <!-- GRID 5 KOLOM X 2 BARIS (LANDSCAPE) -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
                        @for ($i = 1; $i <= 10; $i++)
                        @php $slotId = 'P' . str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                        <div class="col">
                            <div class="interactive-item-card d-flex justify-content-between align-items-center" id="parkingBox{{ $slotId }}">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-car text-secondary" id="parkingIcon{{ $slotId }}"></i>
                                    <div>
                                        <span class="text-blue-title d-block">{{ $slotId }}</span>
                                        <small class="text-success fw-bold" id="parkingStatusText{{ $slotId }}" style="font-size: 0.7rem;">Available</small>
                                    </div>
                                </div>
                                <div class="form-check form-switch fs-5 m-0">
                                    <input class="form-check-input parking-switch" type="checkbox" role="switch" id="parkingSwitch{{ $slotId }}" onchange="toggleParkingSlot('{{ $slotId }}')">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="mt-4 pt-3 border-top text-secondary" style="font-size: 0.8rem;">
                        <i class="fas fa-info-circle me-1"></i> Perubahan sakelar dikirim otomatis ke Dashboard Tegal EcoSense
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // 1. LOGIKA MODE OPERASI SISTEM
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
        const parkingSwitches = document.querySelectorAll('.parking-switch');
        const relaySection = document.getElementById('relayCardSection');
        const parkingSection = document.getElementById('parkingCardSection');

        if (mode === 'auto') {
            btnAuto.classList.add('active-mode');
            iconAuto.className = "fas fa-check-circle fs-5";
            if (label) label.textContent = 'Otomatis';
            if (badge) {
                badge.className = 'badge bg-warning text-dark px-2 py-1';
                badge.innerHTML = '<i class="fas fa-lock me-1"></i> Terkunci';
            }

            // Kunci sakelar di Mode Otomatis
            relaySwitches.forEach(sw => sw.disabled = true);
            parkingSwitches.forEach(sw => sw.disabled = true);
            relaySection.classList.add('disabled-control');
            parkingSection.classList.add('disabled-control');

        } else if (mode === 'manual') {
            btnManual.classList.add('active-mode');
            iconManual.className = "fas fa-check-circle fs-5";
            if (label) label.textContent = 'Manual';
            if (badge) {
                badge.className = 'badge bg-success text-white px-2 py-1';
                badge.innerHTML = '<i class="fas fa-lock-open me-1"></i> Aktif';
            }

            // Buka kunci sakelar di Mode Manual
            relaySwitches.forEach(sw => sw.disabled = false);
            parkingSwitches.forEach(sw => sw.disabled = false);
            relaySection.classList.remove('disabled-control');
            parkingSection.classList.remove('disabled-control');
        }

        // Simpan mode ke LocalStorage untuk Dashboard
        localStorage.setItem('iot_system_mode', mode);

        fetch('/api/control/mode', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ mode: mode })
        }).catch(() => {});
    }

    // 2. KONTROL RELAY SAKELAR LAMPU
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

        // Sinkronkan ke LocalStorage untuk Dashboard
        let relayStates = JSON.parse(localStorage.getItem('iot_relay_states')) || {};
        relayStates[relayId] = isChecked ? 1 : 0;
        localStorage.setItem('iot_relay_states', JSON.stringify(relayStates));

        fetch('/api/control/relay', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ relay: relayId, state: isChecked ? 1 : 0 })
        }).catch(() => {});
    }

    // 3. KONTROL SLOT PARKIR MANUAL
    function toggleParkingSlot(slotId) {
        const sw = document.getElementById(`parkingSwitch${slotId}`);
        const statusText = document.getElementById(`parkingStatusText${slotId}`);
        const icon = document.getElementById(`parkingIcon${slotId}`);
        const box = document.getElementById(`parkingBox${slotId}`);
        const isChecked = sw.checked;

        if (isChecked) {
            statusText.textContent = 'Occupied';
            statusText.className = 'text-danger fw-bold';
            icon.className = 'fas fa-car text-danger';
            if (box) box.classList.add('is-occupied');
        } else {
            statusText.textContent = 'Available';
            statusText.className = 'text-success fw-bold';
            icon.className = 'fas fa-car text-secondary';
            if (box) box.classList.remove('is-occupied');
        }

        // Sinkronkan ke LocalStorage untuk Dashboard
        let parkingStates = JSON.parse(localStorage.getItem('iot_parking_states')) || {};
        parkingStates[slotId] = isChecked ? 'occupied' : 'available';
        localStorage.setItem('iot_parking_states', JSON.stringify(parkingStates));

        fetch('/api/control/parking', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ slot: slotId, status: isChecked ? 'occupied' : 'available' })
        }).catch(() => {});
    }

    // INITIAL LOAD
    document.addEventListener('DOMContentLoaded', () => {
        const currentMode = localStorage.getItem('iot_system_mode') || 'auto';
        setControlMode(currentMode);
    });
</script>
@endpush