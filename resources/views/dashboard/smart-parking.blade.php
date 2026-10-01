@extends('layouts.app')
@section('title', 'Smart Parking')
@section('page-title', 'Smart Parking Monitoring')

@push('styles')
<style>
    /* ===== DEFINISI TEMA WARNA (SAMA PERSIS DENGAN SMART LAMP) ===== */
    #dashboardThemeWrapper[data-theme="gradient-tricolor"] {
        --card-bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e3a8a 35%, #854d0e 70%, #065f46 100%);
        --card-border: rgba(251, 191, 36, 0.4);
        --card-glow: rgba(16, 185, 129, 0.25);
        --accent-color: #fde047;
    }

    #dashboardThemeWrapper[data-theme="dark-glass"] {
        --card-bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        --card-border: rgba(56, 189, 248, 0.3);
        --card-glow: rgba(14, 165, 233, 0.25);
        --accent-color: #38bdf8;
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

    .smart-parking-content {
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

    /* ===== STYLE CARD ===== */
    .custom-gradient-card, .parking-slot-card {
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

    .custom-gradient-card:hover, .parking-slot-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 35px var(--card-glow, rgba(0,0,0,0.3)) !important;
    }

    .parking-slot-card.is-occupied {
        border-color: #f87171 !important;
        box-shadow: 0 0 22px rgba(248, 113, 113, 0.35) !important;
    }

    .parking-slot-card.is-available {
        border-color: #34d399 !important;
        box-shadow: 0 0 22px rgba(52, 211, 153, 0.35) !important;
    }

    .status-pill {
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .status-pill.occupied {
        background: rgba(248, 113, 113, 0.25);
        color: #fca5a5;
        border: 1px solid #f87171;
    }

    .status-pill.available {
        background: rgba(52, 211, 153, 0.25);
        color: #6ee7b7;
        border: 1px solid #34d399;
    }

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
    <div class="smart-parking-content">

        <!-- DROPDOWN PEMILIH TEMA WARNA -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-parking text-success me-2"></i> Smart Parking Monitoring
            </h5>
            <div class="d-flex align-items-center gap-2">
                <label for="allThemeSelector" class="form-label m-0 font-weight-bold text-secondary" style="font-size: 0.85rem;">
                    <i class="fas fa-palette text-primary me-1"></i> Pilih Tema Semua Cards:
                </label>
                <select id="allThemeSelector" class="theme-select-box" onchange="changeAllCardsTheme(this.value)">
                    <option value="gradient-tricolor">🌈 Biru-Kuning-Hijau</option>
                    <option value="dark-glass">💎 Dark Glassmorphism</option>
                    <option value="cyberpunk-purple">🔮 Cyberpunk Purple</option>
                    <option value="emerald-nature">🍃 Emerald Nature</option>
                </select>
            </div>
        </div>

        <!-- 1. STATUS RINGKASAN PARKIR -->
        <div class="data-card custom-gradient-card mb-4">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
                <div>
                    <h6 class="text-white mb-2" style="font-size: 1rem; font-weight: 700;">
                        <i class="fas fa-car text-warning me-2"></i> Parking Gate & Occupancy Status
                    </h6>
                    <div style="font-size:0.88rem; color:#f1f5f9; display:flex; gap:15px; flex-wrap:wrap; align-items:center;">
                        <span>Gate: <strong id="gateStatus" style="color:#60a5fa;">OPEN</strong></span>
                        <span style="opacity:0.4;">|</span>
                        <span>Available Slots: <strong id="totalAvailable" style="color:#6ee7b7;">-</strong></span>
                        <span style="opacity:0.4;">|</span>
                        <span>Occupied Slots: <strong id="totalOccupied" style="color:#fca5a5;">-</strong></span>
                    </div>
                </div>
                <div>
                    <span style="background:linear-gradient(135deg, rgba(59,130,246,0.3) 0%, rgba(16,185,129,0.3) 100%); color:#93c5fd; padding:8px 18px; border-radius:30px; font-size:0.8rem; font-weight:700; border: 1px solid #3b82f6;">
                        <i class="fas fa-broadcast-tower me-1"></i> LIVE PARKING SYSTEM
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. GRID SLOT PARKIR (4 SLOT) -->
        <div class="row g-4" id="parkingContainer">
            <div class="col-12 text-center" style="color: #64748b;">Memuat data slot parkir...</div>
        </div>

        <!-- 3. GRAFIK KETERISIAN PARKIR -->
        <div class="data-card custom-gradient-card mt-4">
            <h6 class="text-white mb-3" style="font-size: 1rem; font-weight: 700;">
                <i class="fas fa-chart-line text-info me-2"></i> Parking Occupancy History (24 Hours)
            </h6>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="parkingChart"></canvas>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function changeAllCardsTheme(themeName) {
        const wrapper = document.getElementById('dashboardThemeWrapper');
        if (wrapper) {
            wrapper.setAttribute('data-theme', themeName);
            localStorage.setItem('globalParkingTheme', themeName);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('globalParkingTheme') || 'gradient-tricolor';
        const selector = document.getElementById('allThemeSelector');
        if (selector) selector.value = savedTheme;
        changeAllCardsTheme(savedTheme);
    });

    const slotNames = {
        slot_1: 'Parking Slot A-1',
        slot_2: 'Parking Slot A-2',
        slot_3: 'Parking Slot B-1',
        slot_4: 'Parking Slot B-2'
    };

    // CHART PARKIR
    const parkingCtx = document.getElementById('parkingChart')?.getContext('2d');
    let parkingChart;
    if (parkingCtx) {
        parkingChart = new Chart(parkingCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    { label: 'Slot Terisi', data: [], borderColor: '#f87171', backgroundColor: 'rgba(248,113,113,0.15)', tension: 0.4, fill: true, borderWidth: 2 },
                    { label: 'Slot Kosong', data: [], borderColor: '#34d399', backgroundColor: 'rgba(52,211,153,0.15)', tension: 0.4, fill: true, borderWidth: 2 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#f1f5f9' } } },
                scales: {
                    x: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(255, 255, 255, 0.1)' } },
                    y: { ticks: { color: '#cbd5e1' }, grid: { color: 'rgba(255, 255, 255, 0.1)' }, min: 0, max: 4 }
                }
            }
        });
    }

    function renderParkingSlots(data) {
        const container = document.getElementById('parkingContainer');
        if (!container) return;
        container.innerHTML = '';

        let availableCount = 0;
        let occupiedCount = 0;

        Object.keys(slotNames).forEach((key) => {
            const slot = data[key];
            const isOccupied = slot.status === 'occupied';
            
            if (isOccupied) occupiedCount++;
            else availableCount++;

            container.innerHTML += `
                <div class="col-md-6 col-lg-3">
                    <div class="parking-slot-card ${isOccupied ? 'is-occupied' : 'is-available'}">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                            <div style="background:${isOccupied ? 'rgba(248,113,113,0.25)' : 'rgba(52,211,153,0.25)'}; color:${isOccupied ? '#fca5a5' : '#6ee7b7'}; width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:14px; box-shadow: 0 0 15px ${isOccupied ? 'rgba(248,113,113,0.4)' : 'rgba(52,211,153,0.4)'};">
                                <i class="fas ${isOccupied ? 'fa-car' : 'fa-parking'}" style="font-size: 1.3rem;"></i>
                            </div>
                            <span class="status-pill ${isOccupied ? 'occupied' : 'available'}">
                                ${isOccupied ? '• TERISI' : '• KOSONG'}
                            </span>
                        </div>
                        <h6 style="margin-bottom:12px; color:#ffffff; font-size:0.95rem; font-weight:700;">${slotNames[key]}</h6>
                        <div style="font-size:0.85rem; color:#cbd5e1; margin-bottom:8px;">
                            <div>Distance: <strong style="color:#ffffff;">${slot.distance} cm</strong></div>
                        </div>
                        <div style="font-size:0.78rem; color:#fde047; font-weight:600;">
                            <i class="fas fa-clock me-1"></i> Updated: ${slot.last_update || 'Baru saja'}
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('totalAvailable').textContent = availableCount + ' Slot';
        document.getElementById('totalOccupied').textContent = occupiedCount + ' Slot';
        document.getElementById('gateStatus').textContent = data.gate_status || 'OPEN';
    }

    function fetchParkingData() {
        fetch('/api/parking')
            .then(r => r.json())
            .then(data => {
                renderParkingSlots(data);
                if (parkingChart) {
                    const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    
                    let occ = 0, avl = 0;
                    Object.keys(slotNames).forEach(key => {
                        if (data[key].status === 'occupied') occ++;
                        else avl++;
                    });

                    parkingChart.data.labels.push(now);
                    parkingChart.data.datasets[0].data.push(occ);
                    parkingChart.data.datasets[1].data.push(avl);
                    
                    if (parkingChart.data.labels.length > 30) {
                        parkingChart.data.labels.shift();
                        parkingChart.data.datasets.forEach(ds => ds.data.shift());
                    }
                    parkingChart.update('none');
                }
            })
            .catch(err => console.error("Gagal mengambil data parking:", err));
    }

    setInterval(fetchParkingData, 5000);
    fetchParkingData();
</script>
@endpush