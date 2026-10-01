@extends('layouts.app')
@section('title', 'Smart Parking')
@section('page-title', 'Smart Parking Monitoring')

@section('content')
<!-- STAT CARDS -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
            <div class="icon-box" style="background: rgba(59,130,246,0.2); color:#60a5fa; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <i class="fas fa-th" style="font-size: 1.2rem;"></i>
            </div>
            <div class="value fs-3 fw-bold text-white mb-1" id="totalSlots">8</div>
            <div class="label" style="color: #94a3b8; font-size: 0.85rem;">Total Slots</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
            <div class="icon-box" style="background: rgba(239,68,68,0.2); color:#f87171; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <i class="fas fa-car" style="font-size: 1.2rem;"></i>
            </div>
            <div class="value fs-3 fw-bold text-white mb-1" id="occupiedSlots">-</div>
            <div class="label" style="color: #94a3b8; font-size: 0.85rem;">Occupied</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
            <div class="icon-box" style="background: rgba(16,185,129,0.2); color:#34d399; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
            </div>
            <div class="value fs-3 fw-bold text-white mb-1" id="availableSlots">-</div>
            <div class="label" style="color: #94a3b8; font-size: 0.85rem;">Available</div>
        </div>
    </div>
</div>

<!-- ZONE CARDS -->
<div class="row g-4 mb-4" id="zoneGrid">
    <!-- Zones akan di-render oleh JavaScript -->
</div>

<!-- PARKING VISUAL MAP -->
<div class="data-card" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
    <h6 class="text-white mb-3" style="font-size: 0.95rem; font-weight: 600;"><i class="fas fa-map me-2 text-primary"></i> PARKING LOT VISUAL MAP</h6>
    <div style="display:flex; gap:20px; margin-bottom:20px;">
        <div style="display:flex; align-items:center; gap:8px; font-size:0.85rem;">
            <div style="width:18px; height:18px; background:rgba(16,185,129,0.3); border:2px solid #10b981; border-radius:6px;"></div>
            <span style="color:#94a3b8;">Available</span>
        </div>
        <div style="display:flex; align-items:center; gap:8px; font-size:0.85rem;">
            <div style="width:18px; height:18px; background:rgba(239,68,68,0.3); border:2px solid #ef4444; border-radius:6px;"></div>
            <span style="color:#94a3b8;">Occupied</span>
        </div>
    </div>
    <div id="parkingMap" style="display:flex; flex-wrap:wrap; gap:12px; justify-content:center; padding:20px; background:rgba(15, 23, 42, 0.6); border-radius:12px; border: 1px solid rgba(255, 255, 255, 0.05);">
        <!-- Slots akan di-render oleh JavaScript -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    const zoneNames = {
        'zone_a': 'ZONE A',
        'zone_b': 'ZONE B'
    };
    
    const zoneColors = {
        'zone_a': '#60a5fa',
        'zone_b': '#fbbf24'
    };

    const zoneSlots = {
        'zone_a': [1, 2, 3, 4],
        'zone_b': [5, 6, 7, 8]
    };

    function renderZones(data) {
        const grid = document.getElementById('zoneGrid');
        grid.innerHTML = '';

        Object.keys(data.zones).forEach(key => {
            const zone = data.zones[key];
            const available = zone.total - zone.occupied;
            const pct = Math.round((zone.occupied / zone.total) * 100);
            const color = zoneColors[key];

            grid.innerHTML += `
                <div class="col-md-6">
                    <div class="data-card" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                            <h6 style="margin:0; color:${color}; font-size:1rem; font-weight:700;">${zoneNames[key]}</h6>
                            <span style="font-size:0.85rem; color:#94a3b8;">${zone.total} slots</span>
                        </div>
                        <div style="font-size:2.5rem; font-weight:700; margin-bottom:5px; color:#ffffff;">${available}</div>
                        <div style="color:#94a3b8; font-size:0.9rem; margin-bottom:15px;">Available spots</div>
                        <div style="margin-bottom:8px;">
                            <div style="display:flex; justify-content:space-between; font-size:0.8rem; color:#94a3b8; margin-bottom:5px;">
                                <span>Occupancy</span>
                                <span>${pct}%</span>
                            </div>
                            <div class="waste-bar" style="height:10px; background: rgba(15, 23, 42, 0.6); border-radius: 5px; overflow: hidden;">
                                <div class="waste-bar-fill" style="width:${pct}%; background:${color}; height: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    function renderParkingMap(total, occupied) {
        const map = document.getElementById('parkingMap');
        map.innerHTML = '';
        
        const occupiedSet = new Set();
        while (occupiedSet.size < occupied) {
            occupiedSet.add(Math.floor(Math.random() * total) + 1);
        }

        for (let i = 1; i <= total; i++) {
            const isOccupied = occupiedSet.has(i);
            const slot = document.createElement('div');
            slot.className = `parking-slot ${isOccupied ? 'occupied' : 'available'}`;
            slot.style.cssText = `
                width: 50px; 
                height: 50px; 
                border-radius: 8px; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                font-size: 0.9rem; 
                font-weight: 700;
                border: 2px solid ${isOccupied ? '#ef4444' : '#10b981'};
                background: ${isOccupied ? 'rgba(239, 68, 68, 0.2)' : 'rgba(16, 185, 129, 0.2)'};
                color: ${isOccupied ? '#f87171' : '#34d399'};
                transition: all 0.3s;
                cursor: pointer;
            `;
            slot.textContent = i;
            slot.onmouseover = function() {
                this.style.transform = 'scale(1.1)';
            };
            slot.onmouseout = function() {
                this.style.transform = 'scale(1)';
            };
            map.appendChild(slot);
        }
    }

    function fetchParkingData() {
        fetch('/api/parking')
            .then(r => r.json())
            .then(data => {
                document.getElementById('totalSlots').textContent = data.total_slots;
                document.getElementById('occupiedSlots').textContent = data.occupied;
                document.getElementById('availableSlots').textContent = data.available;
                renderZones(data);
                renderParkingMap(data.total_slots, data.occupied);
            });
    }

    setInterval(fetchParkingData, 15000);
    fetchParkingData();
</script>
@endpush