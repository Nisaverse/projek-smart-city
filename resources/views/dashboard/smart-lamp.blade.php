@extends('layouts.app')

@section('title', 'Smart Lamp Monitoring - Tegal EcoSense')

@push('styles')
<style>
    /* ==========================================================================
       1. BACKGROUND TITIK KELIP-KELIP BESAR DI SELURUH LAYAR (TWINKLE BG)
       ========================================================================== */
    .twinkle-bg-wrapper-light {
        position: relative;
        width: 100%;
        min-height: 100vh;
        overflow: hidden;
    }

    .twinkle-bg-wrapper-light::before,
    .twinkle-bg-wrapper-light::after {
        content: "";
        position: absolute;
        top: -10%;
        left: -10%;
        width: 120%;
        height: 120%;
        pointer-events: none;
        z-index: 0;
    }

   .twinkle-bg-wrapper-light::before {
        background-image: 
            radial-gradient(5px 5px at 50px 60px, #e9f500, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 150px 180px, #a300fa, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 280px 80px, #2563eb, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 390px 220px, #0284c7, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 520px 110px, #00fbff, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 640px 250px, #fcb000, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 780px 90px, #aefd02, rgba(255,255,255,0));
        background-repeat: repeat;
        background-size: 850px 350px;
        /* Disesuaikan nama animasinya ke floatTwinkle1 */
        animation: floatTwinkle1 3.5s ease-in-out infinite alternate;
        opacity: 0.75;
    }

    /* Lapisan 2: Titik Kelip-Kelip Silang */
    .twinkle-bg-wrapper-light::after {
        background-image: 
            radial-gradient(6px 6px at 90px 220px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 210px 100px, #f59e0b, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 330px 290px, #3b82f6, rgba(255,255,255,0)),
            radial-gradient(5px 5px at 460px 50px, #0dcaf0, rgba(255,255,255,0)),
            radial-gradient(6px 6px at 590px 210px, #f59e0b, rgba(255,255,255,0)),
            radial-gradient(4px 4px at 710px 130px, #3b82f6, rgba(255,255,255,0));
        background-repeat: repeat;
        background-size: 800px 380px;
        /* Disesuaikan nama animasinya ke floatTwinkle2 */
        animation: floatTwinkle2 5s ease-in-out infinite alternate;
        opacity: 0.65;
    }

    @keyframes floatTwinkle1 {
        0% { opacity: 0.3; transform: scale(0.95) translateY(0px); }
        50% { opacity: 0.9; transform: scale(1.15) translateY(-10px); }
        100% { opacity: 0.4; transform: scale(1) translateY(5px); }
    }

    @keyframes floatTwinkle2 {
        0% { opacity: 0.8; transform: scale(1.05) translateY(0px); }
        50% { opacity: 0.3; transform: scale(0.9) translateY(12px); }
        100% { opacity: 0.9; transform: scale(1.18) translateY(-8px); }
    }

    .dashboard-relative-content {
        position: relative;
        z-index: 1;
    }

    /* ==========================================================================
       2. TAMPILAN ELEMENT FRAMELESS (TANPA BINGKAI/KOTAK)
       ========================================================================== */
    .frameless-item {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 10px;
    }

    .glow-yellow-text {
        color: #ca8a04;
        text-shadow: 0 0 12px rgba(234, 179, 8, 0.35);
    }

    .glow-blue-text {
        color: #0284c7;
        text-shadow: 0 0 12px rgba(2, 132, 199, 0.35);
    }

    .lamp-control-btn {
        border-radius: 12px;
        padding: 8px 16px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="twinkle-bg-wrapper-light">
        
        <!-- HEADER MODULE SMART LAMP -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="border-radius: 20px; font-weight: 700;">
                    <i class="fas fa-lightbulb me-1"></i> IoT Control
                </span>
                <h4 class="m-0 font-weight-bold text-dark ms-2" style="font-weight: 800;">Smart Lamp Monitoring</h4>
            </div>
        </div>

        <!-- SYSTEM STATUS (FRAMELESS) -->
        <div class="frameless-item mb-4">
            <h6 class="text-dark fw-bold mb-2">
                <i class="fas fa-microchip text-warning me-2"></i>System Status
            </h6>
            <div class="d-flex gap-4 align-items-center flex-wrap text-dark font-monospace fw-semibold" style="font-size: 0.95rem;">
                <div>Mode: <span class="badge bg-primary px-2 py-1">Manual</span></div>
                <div>Sensor Light: <span class="glow-yellow-text fw-bold">65%</span></div>
                <div>Time: <span class="text-success fw-bold" id="liveTime">10:30:03</span></div>
            </div>
        </div>

        <!-- AMBIENT LIGHT MONITORING (FRAMELESS) -->
        <div class="frameless-item mb-5">
            <h6 class="text-dark fw-bold mb-3">
                <i class="fas fa-sun text-warning me-2"></i>Ambient Light Monitoring
            </h6>
            
            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <small class="text-muted fw-bold d-block mb-1">CURRENT LIGHT LEVEL</small>
                    <div class="glow-yellow-text" style="font-size: 3rem; font-weight: 800; line-height: 1;">
                        65.0 <span style="font-size: 1.5rem;">%</span>
                    </div>
                    
                    <!-- PROGRESS BAR INTENSITAS CAHAYA -->
                    <div class="progress mt-3" style="height: 10px; background: rgba(0,0,0,0.08); border-radius: 10px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 65%; border-radius: 10px;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="mt-2 text-dark fw-bold" style="font-size: 0.88rem;">
                        <i class="fas fa-sun text-warning me-1"></i> TERANG (Lampu seharusnya OFF)
                    </div>
                </div>

                <div class="col-md-6 border-start border-2 ps-md-4">
                    <small class="text-muted fw-bold d-block mb-2">TODAY'S STATISTICS</small>
                    <div class="d-flex justify-content-between mb-2 text-dark font-monospace">
                        <span>Max Level:</span>
                        <span class="text-success fw-bold">65.0%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-dark font-monospace">
                        <span>Min Level:</span>
                        <span class="text-danger fw-bold">65.0%</span>
                    </div>
                    <div class="d-flex justify-content-between text-dark font-monospace">
                        <span>Average:</span>
                        <span class="text-primary fw-bold">65.0%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KONTROL KARTU DENGAN DAFTAR LAMPU (FRAMELESS GRID) -->
        <h6 class="text-dark fw-bold mb-3"><i class="fas fa-toggle-on text-primary me-2"></i>Lampu Individual</h6>
        <div class="row g-4">
            @for ($i = 1; $i <= 4; $i++)
            <div class="col-md-6 col-lg-3">
                <div class="frameless-item">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark"><i class="fas fa-lightbulb me-1 text-warning"></i> Lampu {{ $i }}</span>
                        <span class="badge bg-danger">OFF</span>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-outline-primary lamp-control-btn"><i class="fas fa-power-off me-1"></i> Saklar</button>
                    </div>
                </div>
            </div>
            @endfor
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // SCRIPT LIVE TIME CLOCK
    setInterval(() => {
        const now = new Date();
        const timeStr = now.toTimeString().split(' ')[0];
        const timeElem = document.getElementById('liveTime');
        if (timeElem) timeElem.textContent = timeStr;
    }, 1000);
</script>
@endpush