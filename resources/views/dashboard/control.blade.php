@extends('layouts.app')
@section('title', 'Control Center')
@section('page-title', 'IoT Control Center')

@push('styles')
<style>
    /* ===== KARTU UTAMA DENGAN TRANSISI SUPER MULUS ===== */
    .dashboard-white-card,
    .stat-top-card,
    .parking-white-card,
    .lamp-white-card,
    .temp-white-card,
    .control-white-card {
        background: #ffffff !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 24px !important;
        box-shadow: 0 10px 30px rgba(11, 19, 43, 0.08) !important;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), 
                    box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), 
                    border-color 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        will-change: transform, box-shadow;
        position: relative;
    }

    /* HOVER HALUS UNTUK KARTU UTAMA */
    .control-white-card:hover,
    .stat-top-card:hover,
    .dashboard-white-card:hover,
    .lamp-white-card:hover,
    .temp-white-card:hover,
    .parking-white-card:hover {
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14) !important;
        transform: translateY(-4px) !important;
        border-color: #94a3b8 !important;
    }

    /* Kontainer dalam kartu */
    .control-inner-box {
        background: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 20px;
    }

    /* Tombol Kontrol Mode Operasi */
    .mode-btn {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #0f172a;
        font-weight: 600;
        padding: 14px 20px;
        border-radius: 12px;
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
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
    }

    /* TAMPILAN MODENYA AKTIF */
    .mode-btn.active-mode {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #1d4ed8 !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    }

    .mode-btn.active-mode small {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    .mode-btn.active-mode .mode-icon-box {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }

    /* WARNA TEKS BIRU KHUSUS SEKTOR & SLOT PARKIR */
    .text-blue-title {
        color: #2563eb !important;
        font-weight: 700;
    }

    /* Item Slot Parkir Manual */
    .parking-slot-control-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px;
        transition: all 0.2s ease;
    }

    .parking-slot-control-item:hover {
        border-color: #cbd5e1;
        background: #ffffff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- BARIS 1: MODE OPERASI & RELAY SAKELAR -->
    <div class="row g-4 mb-4 align-items-stretch">
        
        <!-- BARIS 1 / KOLOM KIRI: OPERATION MODES -->
        <div class="col-lg-6">
            <div class="control-white-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Mode Operasi Sistem</h5>
                        <small class="text-dark opacity-75 fw-semibold">Pilih mode kendali perangkat IoT Tegal EcoSense</small>
                    </div>
                    <span class="badge bg-light text-primary border border-primary px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-sliders-h me-1"></i> Control
                    </span>
                </div>

                <div class="control-inner-box mt-3">
                    <div class="d-flex flex-column gap-3">
                        <!-- Mode Otomatis (Default Active) -->
                        <button type="button" class="mode-btn active-mode" id="btnModeAuto" onclick="setControlMode('auto')">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mode-icon-box" style="width: 35px; height: 35px; background: rgba(37, 99, 235, 0.1); color: #2563eb; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Mode Otomatis (Sensor / Jadwal)</div>
                                    <small class="opacity-75" style="font-size: 0.75rem;">Sistem berjalan sendiri berdasarkan pembacaan sensor</small>
                                </div>
                            </div>
                            <i class="fas fa-check-circle fs-5" id="iconAuto"></i>
                        </button>

                        <!-- Mode Manual -->
                        <button type="button" class="mode-btn" id="btnModeManual" onclick="setControlMode('manual')">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mode-icon-box" style="width: 35px; height: 35px; background: rgba(245, 158, 11, 0.1); color: #d97706; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-hand-paper"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Mode Manual (Kontrol Langsung)</div>
                                    <small class="opacity-75" style="font-size: 0.75rem;">Kendalikan perangkat sakelar secara langsung dari panel</small>
                                </div>
                            </div>
                            <i class="fas fa-circle text-muted fs-6" id="iconManual"></i>
                        </button>

                        <!-- Mode Emergency / Darurat -->
                        <button type="button" class="mode-btn" id="btnModeEmergency" onclick="setControlMode('emergency')">
                            <div class="d-flex align-items-center gap-3">
                                <div class="mode-icon-box" style="width: 35px; height: 35px; background: rgba(239, 68, 68, 0.1); color: #dc2626; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Mode Darurat (Emergency)</div>
                                    <small class="opacity-75" style="font-size: 0.75rem;">Matikan atau amankan seluruh relay secara instan</small>
                                </div>
                            </div>
                            <i class="fas fa-circle text-muted fs-6" id="iconEmergency"></i>
                        </button>
                    </div>

                    <div class="mt-4 pt-3 border-top text-secondary" style="font-size: 0.8rem;">
                        <i class="fas fa-info-circle me-1"></i> Status Sistem Saat Ini: <span class="fw-bold text-dark" id="currentModeLabel">Otomatis</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- BARIS 1 / KOLOM KANAN: RELAY SAKELAR LAMPU -->
        <div class="col-lg-6">
            <div class="control-white-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Relay Sakelar Lampu</h5>
                        <small class="text-dark opacity-75 fw-semibold">Kontrol langsung status kelistrikan sektor lampu</small>
                    </div>
                    <span class="badge bg-light text-warning border border-warning px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-bolt me-1"></i> Relays
                    </span>
                </div>

                <div class="control-inner-box mt-3">
                    <div class="d-flex flex-column gap-3">
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border rounded-3">
                            <div>
                                <div class="text-blue-title" style="font-size: 0.95rem;">Sektor {{ $i }}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Status: <span id="relay{{ $i }}StatusText" class="fw-semibold text-danger">Mati (Off)</span></small>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="relay{{ $i }}Switch" onchange="toggleRelay({{ $i }})">
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="mt-4 pt-3 border-top text-secondary" style="font-size: 0.8rem;">
                        <i class="fas fa-shield-alt text-success me-1"></i> Sistem Relay terhubung dengan basis data MQTT broker.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- BARIS 2: KARTU KONTROL PARKIR LANDSCAPE -->
    <div class="row">
        <div class="col-12">
            <div class="control-white-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Kontrol Manual Smart Parking</h5>
                        <small class="text-dark opacity-75 fw-semibold">Simulasi/Override status keterisian 10 slot parkir</small>
                    </div>
                    <span class="badge bg-light text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-parking me-1"></i> Parking
                    </span>
                </div>

                <div class="control-inner-box mt-3">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
                        @for ($i = 1; $i <= 10; $i++)
                        @php $slotId = 'P' . str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                        <div class="col">
                            <div class="parking-slot-control-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-car text-secondary" id="parkingIcon{{ $slotId }}"></i>
                                    <div>
                                        <span class="text-blue-title d-block" style="font-size: 0.85rem;">{{ $slotId }}</span>
                                        <small class="text-success fw-bold" id="parkingStatusText{{ $slotId }}" style="font-size: 0.7rem;">Available</small>
                                    </div>
                                </div>
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" role="switch" id="parkingSwitch{{ $slotId }}" onchange="toggleParkingSlot('{{ $slotId }}')">
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="mt-4 pt-3 border-top text-secondary" style="font-size: 0.8rem;">
                        <i class="fas fa-info-circle me-1"></i> Mengubah switch akan mengirimkan pembaruan status slot ke dashboard.
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
        const btnEmergency = document.getElementById('btnModeEmergency');
        
        const iconAuto = document.getElementById('iconAuto');
        const iconManual = document.getElementById('iconManual');
        const iconEmergency = document.getElementById('iconEmergency');
        
        const label = document.getElementById('currentModeLabel');

        iconAuto.className = "fas fa-circle text-muted fs-6";
        iconManual.className = "fas fa-circle text-muted fs-6";
        iconEmergency.className = "fas fa-circle text-muted fs-6";

        if (mode === 'auto') {
            btnAuto.classList.add('active-mode');
            iconAuto.className = "fas fa-check-circle fs-5";
            if (label) label.textContent = 'Otomatis';
        } else if (mode === 'manual') {
            btnManual.classList.add('active-mode');
            iconManual.className = "fas fa-check-circle fs-5";
            if (label) label.textContent = 'Manual';
        } else if (mode === 'emergency') {
            btnEmergency.classList.add('active-mode');
            iconEmergency.className = "fas fa-check-circle fs-5";
            if (label) label.textContent = 'Darurat (Emergency)';
        }

        // Kirim permintaan ubah mode ke API
        fetch('/api/control/mode', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ mode: mode })
        }).catch(() => {});
    }

    function toggleRelay(relayId) {
        const sw = document.getElementById(`relay${relayId}Switch`);
        const statusText = document.getElementById(`relay${relayId}StatusText`);
        
        if (sw.checked) {
            statusText.textContent = 'Menyala (On)';
            statusText.className = 'fw-semibold text-success';
        } else {
            statusText.textContent = 'Mati (Off)';
            statusText.className = 'fw-semibold text-danger';
        }

        fetch('/api/control/relay', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ relay: relayId, state: sw.checked ? 1 : 0 })
        }).catch(() => {});
    }

    function toggleParkingSlot(slotId) {
        const sw = document.getElementById(`parkingSwitch${slotId}`);
        const statusText = document.getElementById(`parkingStatusText${slotId}`);
        const icon = document.getElementById(`parkingIcon${slotId}`);

        if (sw.checked) {
            statusText.textContent = 'Occupied';
            statusText.className = 'text-danger fw-bold';
            icon.className = 'fas fa-car text-danger';
        } else {
            statusText.textContent = 'Available';
            statusText.className = 'text-success fw-bold';
            icon.className = 'fas fa-car text-secondary';
        }

        fetch('/api/control/parking', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ slot: slotId, status: sw.checked ? 'occupied' : 'available' })
        }).catch(() => {});
    }
</script>
@endpush