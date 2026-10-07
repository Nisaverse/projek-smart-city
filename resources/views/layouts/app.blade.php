<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart City IoT - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- LENIS SMOOTH SCROLL CSS CDN -->
    <link rel="stylesheet" href="https://unpkg.com/lenis@1.1.18/dist/lenis.css">


    <!-- ===== IMPORT MQTT.JS CDN ===== -->
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

    <style>
    :root {
        --sidebar-width: 260px;
        --bg-dark: #f8fafc;
        --bg-card: #ffffff;
        --bg-sidebar: #0b132b; /* Warna Navy Gelap */
        --accent-blue: #2563eb;
        --accent-green: #10b981;
        --accent-yellow: #f59e0b;
        --accent-red: #ef4444;
        --text-primary: #0f172a;
        --text-secondary: #64748b;
        --border-color: #1e293b;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #ffffff !important; /* Diubah menjadi putih polos */
        color: var(--text-primary);
        min-height: 100vh;
    }

    /* ===== SIDEBAR NAVY ===== */
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--bg-sidebar) !important;
        border-right: 1px solid #1e293b;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        transition: transform 0.3s ease;
        overflow: hidden;
    }

    .sidebar-header {
        padding: 22px 20px;
        border-bottom: 1px solid #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .sidebar-header h4 {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.3rem;
        margin: 0;
    }

    .sidebar-header span {
        color: #94a3b8;
        font-weight: 500;
        font-size: 0.9rem;
    }

    /* KHUSUS NAVIGASI MENU YANG BISA DI-SCROLL */
    .sidebar-nav {
        padding: 15px 0;
        flex: 1;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch; 
    }

    .nav-label {
        padding: 12px 20px 6px;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #ffffff !important; /* Tulisan menu berwarna putih */
        text-decoration: none;
        transition: all 0.2s;
        margin: 4px 12px;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .nav-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff !important;
    }

    .nav-item.active {
        background: var(--accent-blue);
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .nav-item i {
        width: 24px;
        margin-right: 12px;
        font-size: 1rem;
        text-align: center;
        color: #ffffff !important; /* Ikon menu berwarna putih */
    }

    /* ===== ESP CONNECT DI SIDEBAR ===== */
    .esp-connect {
        padding: 15px 20px;
        border-top: 1px solid #1e293b;
        background: rgba(0,0,0,0.15);
        flex-shrink: 0;
        margin: 12px;
        border-radius: 10px;
    }

    .esp-status {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }

    .esp-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: var(--accent-green);
        animation: pulse 2s infinite;
    }

    .esp-dot.offline { background: var(--accent-red); animation: none; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .esp-info { color: #94a3b8; font-size: 0.75rem; }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 30px;
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0;
    }

    .top-bar h5 { font-weight: 600; }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 35px; height: 35px;
        border-radius: 50%;
        background: var(--accent-blue);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .content-area { padding: 25px 30px; padding-bottom: 60px; }

    /* ===== CARDS & TOGGLE ===== */
    .stat-card, .data-card {
        background: var(--bg-card);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    /* ===== MQTT STATUS BAR ===== */
    .mqtt-bar {
        position: fixed;
        bottom: 0;
        left: var(--sidebar-width);
        right: 0;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 8px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        color: var(--text-secondary);
        z-index: 999;
    }

    .mqtt-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }

    .mqtt-dot.connected { background: var(--accent-green); }
    .mqtt-dot.disconnected { background: var(--accent-red); }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .sidebar { 
            transform: translateX(-100%); 
            box-shadow: 5px 0 15px rgba(0,0,0,0.3);
        }
        .sidebar.show { transform: translateX(0); }
        
        .main-content { 
            margin-left: 0; 
            width: 100%;
        }
        
        .mqtt-bar { 
            left: 0; 
            padding: 8px 15px;
        }
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(2px);
    }
    .sidebar-overlay.show {
        display: block;
        opacity: 1;
    }
</style>
    @stack('styles')
</head>

<body>
    <!-- OVERLAY UNTUK MOBILE -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-city"></i> SiSity</h4>
            <small>Control & Monitoring</small>
        </div>

        <nav class="sidebar-nav" data-lenis-prevent>
            <div class="nav-label">Main Menu</div>
            <a href="{{ route('dashboard') }}" 
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               @if(request()->routeIs('dashboard')) onclick="event.preventDefault();" @endif>
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            <div class="nav-label">IoT Devices</div>
            <a href="{{ route('smart-lamp') }}" 
               class="nav-item {{ request()->routeIs('smart-lamp') ? 'active' : '' }}"
               @if(request()->routeIs('smart-lamp')) onclick="event.preventDefault();" @endif>
                <i class="fas fa-lightbulb"></i> Smart Lamp
            </a>
            
            <a href="{{ route('smart-temp') }}" 
               class="nav-item {{ request()->routeIs('smart-temp') ? 'active' : '' }}"
               @if(request()->routeIs('smart-temp')) onclick="event.preventDefault();" @endif>
                <i class="fas fa-temperature-high"></i> Smart Temp
            </a>
            
            <a href="{{ route('smart-parking') }}" 
               class="nav-item {{ request()->routeIs('smart-parking') ? 'active' : '' }}"
               @if(request()->routeIs('smart-parking')) onclick="event.preventDefault();" @endif>
                <i class="fas fa-car"></i> Smart Parking
            </a>

            <div class="nav-label">Control</div>
            <a href="{{ route('control') }}" 
               class="nav-item {{ request()->routeIs('control') ? 'active' : '' }}"
               @if(request()->routeIs('control')) onclick="event.preventDefault();" @endif>
                <i class="fas fa-sliders-h"></i> Control Center
            </a>

            <div class="nav-label">System</div>
            <a href="#" class="nav-item" id="mqttConnectBtn">
                <i class="fas fa-wifi"></i> MQTT Broker
            </a>
        </nav>

        <!-- Logout Button -->
        <div style="margin-top: auto; padding: 15px; border-top: 1px solid var(--border-color); flex-shrink: 0;">
            <button type="button" class="btn btn-danger w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
            <div class="text-center mt-2" style="font-size: 0.75rem; color: var(--text-secondary);">
                <i class="fas fa-user-shield"></i> {{ session('admin_username', 'Admin') }}
            </div>
        </div>

        <!-- ESP CONNECT STATUS -->
        <div class="esp-connect">
            <div class="esp-status">
                <div class="esp-dot" id="espDot"></div>
                <div>
                    <div style="font-weight:600; font-size:0.85rem; color:var(--text-primary);">ESP32 Connected</div>
                    <div class="esp-info" id="espInfo">Node: ESP-SMARTCITY-01</div>
                </div>
            </div>
            <div style="margin-top:8px;">
                <small style="color:var(--text-secondary); font-size:0.7rem;">
                    IP: 192.168.1.100 | RSSI: -45dBm
                </small>
            </div>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div style="display:flex; align-items:center; gap:15px;">
                <button class="btn btn-sm d-md-none" id="sidebarToggleBtn" style="color:var(--text-primary);">
                    <i class="fas fa-bars fs-5"></i>
                </button>
                <h5 class="m-0">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="user-info">
                <span style="font-size:0.85rem; font-weight:600;">Admin</span>
                <div class="user-avatar">AD</div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- ===== MQTT STATUS BAR ===== -->
    <div class="mqtt-bar">
        <div>
            <span class="mqtt-dot disconnected" id="mqttDot"></span>
            MQTT: <span id="mqttStatus">Disconnected</span>
        </div>
        <div id="mqttLastMsg">Last message: -</div>
        <div id="clockDisplay"></div>
    </div>

    <!-- ===== GLOBAL TEMP NOTIFICATION CONTAINER ===== -->
    <div id="globalTempNotification" style="position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:10px; max-width:350px; pointer-events:none;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- LENIS SMOOTH SCROLL JS CDN -->
    <script src="https://unpkg.com/lenis@1.1.18/dist/lenis.min.js"></script>

    <script>
        // ===== INITIALIZE LENIS SMOOTH SCROLL =====
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            smoothWheel: true
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);

        // ===== MEMPERTAHANKAN POSISI SCROLL SIDEBAR =====
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarNav = document.querySelector('.sidebar-nav');
            if (sidebarNav) {
                // Restore posisi scroll saat halaman selesai dimuat
                const savedScroll = sessionStorage.getItem('sidebarScrollPos');
                if (savedScroll !== null) {
                    sidebarNav.scrollTop = parseInt(savedScroll, 10);
                }

                // Simpan posisi scroll saat elemen menu di-scroll
                sidebarNav.addEventListener('scroll', () => {
                    sessionStorage.setItem('sidebarScrollPos', sidebarNav.scrollTop);
                });
            }
        });

        // ===== CLOCK =====
        function updateClock() {
            const now = new Date();
            document.getElementById('clockDisplay').textContent = now.toLocaleString('id-ID');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ===== SIDEBAR TOGGLE LOGIC =====
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggleBtn');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

        // ===== MQTT CLIENT =====
        let mqttClient = null;
        const MQTT_BROKER = "{{ config('app.mqtt_broker', 'wss://broker.hivemq.com:8884/mqtt') }}";
        const MQTT_TOPIC = "aethersense/+/telemetry";

        function connectMQTT() {
            if (typeof mqtt === 'undefined') return;

            const clientId = 'web_' + Math.random().toString(16).substr(2, 8);
            mqttClient = mqtt.connect(MQTT_BROKER, { clientId: clientId });

            mqttClient.on('connect', () => {
                document.getElementById('mqttDot').className = 'mqtt-dot connected';
                document.getElementById('mqttStatus').textContent = 'Connected';
                mqttClient.subscribe(MQTT_TOPIC);
            });

            mqttClient.on('message', (topic, message) => {
                document.getElementById('mqttLastMsg').textContent =
                    'Last: ' + topic + ' → ' + message.toString().substring(0, 50);
                if (typeof handleMQTTMessage === 'function') {
                    handleMQTTMessage(topic, message.toString());
                }
            });

            mqttClient.on('error', () => {
                document.getElementById('mqttDot').className = 'mqtt-dot disconnected';
                document.getElementById('mqttStatus').textContent = 'Error';
            });

            mqttClient.on('close', () => {
                document.getElementById('mqttDot').className = 'mqtt-dot disconnected';
                document.getElementById('mqttStatus').textContent = 'Disconnected';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (typeof mqtt !== 'undefined') {
                connectMQTT();
            }
        });

        document.getElementById('mqttConnectBtn')?.addEventListener('click', (e) => {
            e.preventDefault();
            if (mqttClient && mqttClient.connected) {
                mqttClient.end();
            } else {
                connectMQTT();
            }
        });

        // ===== GLOBAL ENVIRONMENT / TEMP NOTIFICATION SCRIPT =====
        let globalTempNotified = false;
        let lastTempCheck = 0;

        function showGlobalTempNotification(temp) {
            const container = document.getElementById('globalTempNotification');
            const toast = document.createElement('div');
            toast.style.cssText = `
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                border: 1px solid #ef4444;
                border-left: 4px solid #ef4444;
                border-radius: 10px;
                padding: 15px 20px;
                color: #e2e8f0;
                box-shadow: 0 10px 40px rgba(239, 68, 68, 0.3);
                animation: slideInRight 0.4s ease-out;
                pointer-events: auto;
                position: relative;
                overflow: hidden;
                margin-bottom: 10px;
            `;

            toast.innerHTML = `
                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                    <div style="display:flex; align-items:center; gap:10px; flex:1;">
                        <div style="width:32px; height:32px; border-radius:50%; background:rgba(239,68,68,0.2); display:flex; align-items:center; justify-content:center; font-size:1rem; color:#f87171; flex-shrink:0;">
                            <i class="fas fa-temperature-high"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:0.95rem; color:#ef4444; margin-bottom:4px;">
                                🔥 PERINGATAN SUHU PANAS!
                            </div>
                            <div style="font-size:0.85rem; color:#94a3b8;">
                                Suhu lingkungan terdeteksi mencapai <strong>${temp}°C</strong>.
                            </div>
                        </div>
                    </div>
                    <button onclick="this.closest('div[style*=\"background\"]').remove()" 
                            style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.2rem; padding:0; width:28px; height:28px; display:flex; align-items:center; justify-content:center; border-radius:4px; transition:all 0.2s;">
                        ×
                    </button>
                </div>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'slideOutRight 0.3s ease-in forwards';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 10000);
        }

        function checkGlobalTempAlerts() {
            const now = Date.now();
            if (now - lastTempCheck < 30000) return;
            lastTempCheck = now;

            fetch('/api/environment')
                .then(r => r.json())
                .then(data => {
                    const temp = parseFloat(data.temperature);
                    const isHot = temp > 35;

                    if (isHot && !globalTempNotified) {
                        globalTempNotified = true;
                        showGlobalTempNotification(temp);
                        addToGlobalTempHistory(temp);
                    }

                    if (!isHot) {
                        globalTempNotified = false;
                    }
                })
                .catch(err => console.log('Environment check error:', err));
        }

        function addToGlobalTempHistory(temp) {
            const now = new Date();
            const timeStr = now.toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            let history = JSON.parse(localStorage.getItem('globalTempAlertHistory') || '[]');
            history.unshift({
                title: 'Peringatan Suhu Ekstrem',
                desc: 'Suhu lingkungan melebihi batas aman',
                temp: temp,
                time: timeStr,
                timestamp: now.getTime()
            });

            if (history.length > 100) history = history.slice(0, 100);
            localStorage.setItem('globalTempAlertHistory', JSON.stringify(history));
        }

        setInterval(checkGlobalTempAlerts, 30000);

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(checkGlobalTempAlerts, 2000);
        });
    </script>
    @stack('scripts')

    <!-- MODAL KONFIRMASI LOGOUT -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #1e293b; color: #f1f5f9; border: 1px solid #334155; border-radius: 10px;">
                <div class="modal-header" style="border-bottom: 1px solid #334155;">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i> Konfirmasi Logout
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body" style="font-size: 0.95rem;">
                    Apakah Anda yakin ingin keluar dari sistem SmartCity?
                    <br>
                    <small class="text-muted" style="font-size: 0.8rem;">Anda harus login kembali untuk mengakses dashboard.</small>
                </div>
                
                <div class="modal-footer" style="border-top: 1px solid #334155;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-1"></i> Ya, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>