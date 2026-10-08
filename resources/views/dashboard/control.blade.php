@extends('layouts.app')
@section('title', 'Control Center')
@section('page-title', 'IoT Control Center')

@push('styles')
<style>
    /* ===== KARTU UTAMA & STATISTIK: SILVER METALIK POLOS TANPA BERCAK CAHAYA ===== */
    .control-white-card,
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

    .control-white-card:hover,
    .stat-top-card:hover {
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.22), inset 0 1px 3px rgba(255, 255, 255, 1) !important;
        transform: translateY(-2px);
    }

    /* Kontainer dalam kartu */
    .control-inner-box {
        background: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 20px;
    }

    /* Tombol Kontrol Mode */
    .mode-btn {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #0f172a;
        font-weight: 600;
        padding: 12px 20px;
        border-radius: 10px;
        transition: all 0.2s;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mode-btn.active-mode {
        background: #2563eb !important;
        color: #ffffff !important;
        border-color: #1d4ed8 !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- TWO COLUMN LAYOUT -->
    <div class="row g-4">
        
        <!-- KOLOM KIRI: OPERATION MODES -->
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
                        <!-- Mode Otomatis -->
                        <button type="button" class="mode-btn active-mode" id="btnModeAuto" onclick="setControlMode('auto')">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 35px; height: 35px; background: rgba(37, 99, 235, 0.1); color: #2563eb; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
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
                                <div style="width: 35px; height: 35px; background: rgba(245, 158, 11, 0.1); color: #d97706; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
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
                                <div style="width: 35px; height: 35px; background: rgba(239, 68, 68, 0.1); color: #dc2626; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
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

        <!-- KOLOM KANAN: LAMP RELAY SWITCHES -->
        <div class="col-lg-6">
            <div class="control-white-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 1.1rem;">Relai Sakelar Lampu</h5>
                        <small class="text-dark opacity-75 fw-semibold">Kontrol langsung status kelistrikan sektor lampu</small>
                    </div>
                    <span class="badge bg-light text-warning border border-warning px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                        <i class="fas fa-bolt me-1"></i> Relays
                    </span>
                </div>

                <div class="control-inner-box mt-3">
                    <div class="d-flex flex-column gap-3">
                        <!-- Relay 1 -->
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border rounded-3">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">Relay 01 - Sektor Utara (Alun-Alun)</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Status: <span id="relay1StatusText" class="fw-semibold text-danger">Mati (Off)</span></small>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="relay1Switch" onchange="toggleRelay(1)">
                            </div>
                        </div>

                        <!-- Relay 2 -->
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border rounded-3">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">Relay 02 - Sektor Selatan (Panggung)</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Status: <span id="relay2StatusText" class="fw-semibold text-danger">Mati (Off)</span></small>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="relay2Switch" onchange="toggleRelay(2)">
                            </div>
                        </div>

                        <!-- Relay 3 -->
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border rounded-3">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">Relay 03 - Sektor Timur (Kaligangsa)</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Status: <span id="relay3StatusText" class="fw-semibold text-danger">Mati (Off)</span></small>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="relay3Switch" onchange="toggleRelay(3)">
                            </div>
                        </div>

                        <!-- Relay 4 -->
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border rounded-3">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">Relay 04 - Sektor Barat (Tegal Marina)</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Status: <span id="relay4StatusText" class="fw-semibold text-danger">Mati (Off)</span></small>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="relay4Switch" onchange="toggleRelay(4)">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-secondary" style="font-size: 0.8rem;">
                        <i class="fas fa-shield-alt text-success me-1"></i> Sistem Relay terhubung dengan basis data MQTT broker.
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
        
        const label = document.getElementById('currentModeLabel');

        if (mode === 'auto') {
            btnAuto.classList.add('active-mode');
            label.textContent = 'Otomatis';
        } else if (mode === 'manual') {
            btnManual.classList.add('active-mode');
            label.textContent = 'Manual';
        } else if (mode === 'emergency') {
            btnEmergency.classList.add('active-mode');
            label.textContent = 'Darurat (Emergency)';
        }

        // Kirim state ke API backend jika diperlukan
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
</script>
@endpush