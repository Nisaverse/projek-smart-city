@extends('layouts.app')
@section('title', 'Smart Temperature')
@section('page-title', 'Temperature & Humidity Monitoring')

@section('content')
<!-- MUTE NOTIFICATION TOGGLE -->
<div class="data-card mb-4" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15); position: relative; z-index: 100;">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div style="pointer-events: none;">
            <h6 class="text-white mb-1" style="font-size: 0.95rem; font-weight: 600;"><i class="fas fa-bell-slash text-primary me-2"></i> Notifikasi Suhu Panas</h6>
            <p style="font-size:0.85rem; color:#94a3b8; margin:0;">
                Aktifkan/mute notifikasi peringatan jika suhu lingkungan melebihi batas aman (35°C)
            </p>
        </div>
        <div class="toggle-switch" style="pointer-events: auto; z-index: 1000; position: relative;">
            <input type="checkbox" id="tempNotificationToggle" checked onchange="toggleTempNotifications(this.checked)">
            <span class="toggle-slider"></span>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION CONTAINER -->
<div id="toastContainer" style="position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:10px; max-width:350px; pointer-events:none;"></div>

<!-- ENVIRONMENT CARDS (SUHU & KELEMBAPAN) -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <div class="icon-box" style="background: rgba(239, 68, 68, 0.2); color: #f87171; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-temperature-high" style="font-size: 1.3rem;"></i>
                </div>
                <span id="tempBadge" style="background:rgba(16,185,129,0.2); color:#34d399; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600;">
                    ✅ Normal
                </span>
            </div>
            <h6 style="margin-bottom:5px; color:#ffffff; font-size:0.95rem; font-weight:600;">Suhu Udara (Temperature)</h6>
            <div style="font-size:0.85rem; color:#94a3b8; margin-bottom:15px;">
                <i class="fas fa-map-marker-alt me-1"></i> Sensor Lingkungan Kota Tegal
            </div>
            <div style="text-align:center; margin: 20px 0;">
                <div style="font-size:3rem; font-weight:700; color:#f87171;" id="tempVal">-- °C</div>
                <div style="font-size:0.85rem; color:#94a3b8;">Derajat Celsius</div>
            </div>
            <div style="height:8px; background: rgba(15, 23, 42, 0.6); border-radius: 5px; overflow: hidden;">
                <div id="tempBar" style="width:0%; background:#f87171; height:100%; transition: width 0.5s ease;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <div class="icon-box" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-tint" style="font-size: 1.3rem;"></i>
                </div>
                <span id="humidityBadge" style="background:rgba(16,185,129,0.2); color:#34d399; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:600;">
                    💧 Ideal
                </span>
            </div>
            <h6 style="margin-bottom:5px; color:#ffffff; font-size:0.95rem; font-weight:600;">Kelembapan Nisbi (Humidity)</h6>
            <div style="font-size:0.85rem; color:#94a3b8; margin-bottom:15px;">
                <i class="fas fa-map-marker-alt me-1"></i> Sensor Lingkungan Kota Tegal
            </div>
            <div style="text-align:center; margin: 20px 0;">
                <div style="font-size:3rem; font-weight:700; color:#60a5fa;" id="humidityVal">-- %</div>
                <div style="font-size:0.85rem; color:#94a3b8;">Persentase RH</div>
            </div>
            <div style="height:8px; background: rgba(15, 23, 42, 0.6); border-radius: 5px; overflow: hidden;">
                <div id="humidityBar" style="width:0%; background:#60a5fa; height:100%; transition: width 0.5s ease;"></div>
            </div>
        </div>
    </div>
</div>

<!-- REALTIME SUHU CHART -->
<div class="data-card mt-4" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
    <h6 class="text-white mb-3" style="font-size: 0.95rem; font-weight: 600;"><i class="fas fa-chart-line text-primary me-2"></i> REALTIME TEMPERATURE & HUMIDITY CHART</h6>
    <div style="position: relative; height: 280px; width: 100%;">
        <canvas id="envChart"></canvas>
    </div>
</div>

<!-- ALERT HISTORY / RIWAYAT -->
<div class="data-card mt-4" style="background: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 16px; padding: 20px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.15);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
        <h6 class="text-white m-0" style="font-size: 0.95rem; font-weight: 600;"><i class="fas fa-history text-warning me-2"></i> RIWAYAT PERINGATAN SUHU PANAS</h6>
        <button onclick="clearHistory()" style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.2); color:#cbd5e1; padding:6px 14px; border-radius:8px; font-size:0.8rem; cursor:pointer; transition: all 0.2s;">
            <i class="fas fa-trash-alt me-1"></i> Clear History
        </button>
    </div>
    <div id="alertHistory" style="max-height:300px; overflow-y:auto;">
        <div style="text-align:center; color:#94a3b8; padding:25px; font-size:0.85rem;">
            <i class="fas fa-inbox" style="font-size:1.8rem; margin-bottom:8px; display:block; color:#64748b;"></i>
            Belum ada riwayat peringatan
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .toggle-switch {
        position: relative;
        z-index: 1000 !important;
        pointer-events: auto !important;
        cursor: pointer !important;
    }

    .toggle-switch input { cursor: pointer !important; }
    .toggle-slider { cursor: pointer !important; }

    .data-card {
        position: relative;
        z-index: 1;
    }

    /* Toast Notification Styles */
    .toast-notification {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #ef4444;
        border-left: 4px solid #ef4444;
        border-radius: 12px;
        padding: 15px 20px;
        color: #e2e8f0;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        animation: slideInRight 0.4s ease-out;
        position: relative;
        overflow: hidden;
        pointer-events: auto;
    }

    .toast-notification::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, #ef4444, #f59e0b);
        animation: progressLine 5s linear forwards;
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }

    @keyframes progressLine {
        from { width: 100%; }
        to { width: 0%; }
    }

    .toast-notification.removing {
        animation: slideOutRight 0.3s ease-in forwards;
    }

    .toast-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .toast-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #f87171;
    }

    .toast-message {
        font-size: 0.85rem;
        color: #94a3b8;
        margin-left: 10px;
    }

    .toast-close {
        position: absolute;
        top: 8px; right: 10px;
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        font-size: 1.2rem;
        z-index: 10;
    }

    .toast-close:hover { color: #f87171; }

    /* Alert History Styles */
    .alert-entry {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        transition: background 0.2s;
    }

    .alert-entry:hover { background: rgba(239, 68, 68, 0.05); }
    .alert-entry:last-child { border-bottom: none; }

    .alert-badge {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .alert-info { flex: 1; }
    .alert-title { font-weight: 600; font-size: 0.9rem; color: #f8fafc; margin-bottom: 3px; }
    .alert-desc { font-size: 0.8rem; color: #94a3b8; }
    .alert-time { font-size: 0.75rem; color: #94a3b8; white-space: nowrap; }
    .alert-level {
        font-size: 0.75rem; font-weight: 700;
        color: #f87171; background: rgba(239, 68, 68, 0.2);
        padding: 4px 10px; border-radius: 6px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let tempAlertNotified = false;
    let alertHistoryData = JSON.parse(localStorage.getItem('tempAlertHistory') || '[]');
    let tempNotificationsEnabled = true;

    // ===== 1. LOAD STATUS MUTE SAAT HALAMAN DIBUKA =====
    document.addEventListener('DOMContentLoaded', function() {
        const savedStatus = localStorage.getItem('tempNotificationsEnabled');
        if (savedStatus !== null) {
            tempNotificationsEnabled = savedStatus === 'true';
        }
        
        const toggle = document.getElementById('tempNotificationToggle');
        if (toggle) {
            toggle.checked = tempNotificationsEnabled;
        }
        
        renderHistory();
        fetchEnvData();
    });

    // ===== 2. FUNGSI TOGGLE MUTE =====
    function toggleTempNotifications(enabled) {
        tempNotificationsEnabled = enabled;
        localStorage.setItem('tempNotificationsEnabled', enabled);
        
        showFeedbackToast(
            enabled ? '🔔 Notifikasi Suhu diaktifkan' : '🔕 Notifikasi Suhu dimute',
            enabled ? '#34d399' : '#fbbf24'
        );
    }

    // ===== 3. FEEDBACK TOAST =====
    function showFeedbackToast(message, bgColor) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.style.borderLeftColor = bgColor;
        
        toast.innerHTML = `
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            <div class="toast-header">
                <div class="toast-title" style="color: ${bgColor}">${message}</div>
            </div>
        `;
        container.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    }

    // ===== 4. CHART =====
    const envCtx = document.getElementById('envChart')?.getContext('2d');
    let envChart;
    if (envCtx) {
        envChart = new Chart(envCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Temperature (°C)',
                        data: [],
                        borderColor: '#f87171',
                        backgroundColor: 'rgba(248, 113, 113, 0.15)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        yAxisID: 'yTemp'
                    },
                    {
                        label: 'Humidity (%)',
                        data: [],
                        borderColor: '#60a5fa',
                        backgroundColor: 'rgba(96, 165, 250, 0.15)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        yAxisID: 'yHumidity'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#94a3b8' } } },
                scales: {
                    x: { ticks: { color: '#94a3b8', maxTicksLimit: 10 }, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                    yTemp: { type: 'linear', position: 'left', ticks: { color: '#f87171' }, grid: { color: 'rgba(255, 255, 255, 0.05)' }, min: 0, max: 50 },
                    yHumidity: { type: 'linear', position: 'right', ticks: { color: '#60a5fa' }, grid: { drawOnChartArea: false }, min: 0, max: 100 }
                }
            }
        });
    }

    // ===== 5. UPDATE UI SUHU & KELEMBAPAN =====
    function renderEnvData(temp, humidity) {
        const tempNum = parseFloat(temp);
        const humNum = parseFloat(humidity);

        document.getElementById('tempVal').textContent = tempNum.toFixed(1) + ' °C';
        document.getElementById('humidityVal').textContent = humNum.toFixed(1) + ' %';

        document.getElementById('tempBar').style.width = Math.min(100, (tempNum / 50) * 100) + '%';
        document.getElementById('humidityBar').style.width = humNum + '%';

        const tempBadge = document.getElementById('tempBadge');
        if (tempNum > 35) {
            tempBadge.textContent = '🔥 Panas Ekstrem';
            tempBadge.style.background = 'rgba(239, 68, 68, 0.2)';
            tempBadge.style.color = '#f87171';
        } else if (tempNum > 30) {
            tempBadge.textContent = '⚠️ Hangat';
            tempBadge.style.background = 'rgba(245, 158, 11, 0.2)';
            tempBadge.style.color = '#fbbf24';
        } else {
            tempBadge.textContent = '✅ Normal';
            tempBadge.style.background = 'rgba(16, 185, 129, 0.2)';
            tempBadge.style.color = '#34d399';
        }

        checkNotification(tempNum);

        // Update Chart Realtime
        if (envChart) {
            const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            if (envChart.data.labels.length >= 15) {
                envChart.data.labels.shift();
                envChart.data.datasets[0].data.shift();
                envChart.data.datasets[1].data.shift();
            }
            envChart.data.labels.push(now);
            envChart.data.datasets[0].data.push(tempNum);
            envChart.data.datasets[1].data.push(humNum);
            envChart.update('none');
        }
    }

    // ===== 6. CEK & TAMPILKAN NOTIFIKASI SUHU =====
    function checkNotification(temp) {
        const isMuted = localStorage.getItem('tempNotificationsEnabled') === 'false';
        if (isMuted) return;

        if (temp > 35 && !tempAlertNotified) {
            tempAlertNotified = true;
            showTempToast(temp);
            addToHistory(temp);
        }

        if (temp <= 35) {
            tempAlertNotified = false;
        }
    }

    // ===== 7. TAMPILKAN TOAST SUHU =====
    function showTempToast(temp) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            <div class="toast-header">
                <div class="toast-title">🔥 PERINGATAN SUHU PANAS!</div>
            </div>
            <div class="toast-message">
                Suhu udara lingkungan terdeteksi mencapai <strong>${temp}°C</strong>!
            </div>
        `;
        container.appendChild(toast);

        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }

    // ===== 8. TAMBAH KE RIWAYAT =====
    function addToHistory(temp) {
        const now = new Date();
        const timeStr = now.toLocaleString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });

        const entry = {
            id: Date.now(),
            title: 'Peringatan Suhu Ekstrem',
            desc: 'Suhu lingkungan melebihi ambang batas aman',
            temp: temp,
            time: timeStr
        };

        alertHistoryData.unshift(entry);
        if (alertHistoryData.length > 50) alertHistoryData = alertHistoryData.slice(0, 50);

        localStorage.setItem('tempAlertHistory', JSON.stringify(alertHistoryData));
        renderHistory();
    }

    // ===== 9. RENDER RIWAYAT =====
    function renderHistory() {
        const container = document.getElementById('alertHistory');

        if (alertHistoryData.length === 0) {
            container.innerHTML = `
                <div style="text-align:center; color:#94a3b8; padding:25px; font-size:0.85rem;">
                    <i class="fas fa-inbox" style="font-size:1.8rem; margin-bottom:8px; display:block; color:#64748b;"></i>
                    Belum ada riwayat alert
                </div>
            `;
            return;
        }

        container.innerHTML = alertHistoryData.map(entry => `
            <div class="alert-entry">
                <div class="alert-badge"><i class="fas fa-temperature-high"></i></div>
                <div class="alert-info">
                    <div class="alert-title">${entry.title}</div>
                    <div class="alert-desc">${entry.desc}</div>
                </div>
                <div class="alert-level">${entry.temp}°C</div>
                <div class="alert-time">${entry.time}</div>
            </div>
        `).join('');
    }

    // ===== 10. CLEAR HISTORY =====
    function clearHistory() {
        if (confirm('Yakin ingin menghapus semua riwayat peringatan suhu?')) {
            alertHistoryData = [];
            localStorage.removeItem('tempAlertHistory');
            renderHistory();
        }
    }

    // ===== 11. FETCH DATA =====
    function fetchEnvData() {
        fetch('/api/environment')
            .then(r => r.json())
            .then(data => {
                renderEnvData(data.temperature, data.humidity);
            })
            .catch(() => {
                // Fallback jika API belum siap
                const simTemp = 29 + (Math.random() * 8 - 4);
                const simHum = 65 + (Math.random() * 10 - 5);
                renderEnvData(simTemp, simHum);
            });
    }

    setInterval(fetchEnvData, 10000);
</script>
@endpush