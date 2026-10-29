<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tegal EcoSense - Observatorium Iklim Mikro Alun-Alun</title>

  <!-- Bootstrap 5 CSS & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* --- CSS UTAMA --- */
    body {
      background-color: #0b1329;
      color: #1a1a1a;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow-x: hidden;
      margin: 0;
      padding: 0;
    }

    .card, .zone-card, .hero-observatorium {
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 1rem !important;
      transition: all 0.3s ease-in-out;
    }

    /* --- WELCOME SCREEN --- */
    #welcomeSection {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100vh;
      color: #ffffff;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 15px 40px 25px 40px;
      background-image: linear-gradient(rgba(11, 19, 41, 0.25), rgba(11, 19, 41, 0.68)), url('image/alun-alun-tegal.jpeg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      transition: opacity 0.6s ease, transform 0.6s ease, background-image 1s ease-in-out;
      box-sizing: border-box;
    }

    .bg-overlay {
      position: absolute !important;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(rgba(11, 19, 41, 0.25), rgba(11, 19, 41, 0.68));
      z-index: 1;
    }
    #welcomeSection > * { position: relative; z-index: 2; }
    .welcome-top-center { display: flex; flex-direction: column; align-items: center; text-align: center; }

    /* CSS BULATAN PUTIH LOGO HALAMAN DEPAN */
    .welcome-logos-row {
      display: flex !important;
      flex-direction: row !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 15px !important;
      flex-wrap: nowrap !important;
      margin-bottom: 12px;
    }

    .welcome-logos-row .logo-badge-item {
      width: 54px !important;
      height: 54px !important;
      min-width: 54px !important;
      min-height: 54px !important;
      flex: 0 0 54px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 8px !important;
      margin: 0 !important;
      background: #ffffff !important;
      border: 2px solid rgba(255, 255, 255, 0.9) !important;
      border-radius: 50% !important;
      box-sizing: border-box;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .welcome-logos-row .logo-badge-item img {
      display: block !important;
      width: 100% !important;
      height: 100% !important;
      object-fit: contain !important;
      object-position: center !important;
      filter: none !important;
      margin: auto !important;
      padding: 0 !important;
    }

    .welcome-gov-title { font-size: 0.95rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 2px; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.4); }
    .welcome-jawa-script { font-size: 0.95rem; letter-spacing: 2px; color: #ffffff; margin-bottom: 0; font-family: sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.5); }
    .welcome-center { max-width: 600px; margin-top: 5px; }
    .welcome-title { font-size: 3rem; font-weight: 800; letter-spacing: 2px; margin-bottom: 10px; line-height: 1.1; text-shadow: 0 2px 6px rgba(0,0,0,0.4); }
    .welcome-desc { font-size: 0.88rem; line-height: 1.5; color: #f1f5f9; margin-bottom: 18px; text-shadow: 0 1px 3px rgba(0,0,0,0.4); }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(25px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-top-info { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
    .animate-title { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards; }
    .animate-desc { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.4s forwards; }
    .animate-btn { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.6s forwards; }

    .btn-masuk-situs {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 12px 35px;
      border-radius: 4px;
      font-weight: 700;
      font-size: 0.9rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      cursor: pointer;
      transition: background 0.2s, transform 0.2s;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }
    .btn-masuk-situs:hover { background-color: #bb2d3b; transform: translateY(-2px); }

    .welcome-preview-container { display: flex; gap: 12px; align-items: center; justify-content: flex-end; margin-bottom: 10px; }
    .preview-card { width: 105px; height: 110px; border-radius: 12px; overflow: hidden; position: relative; cursor: pointer; border: 2px solid rgba(255,255,255,0.4); transition: transform 0.3s, border-color 0.3s; background-size: cover; background-position: center; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
    .preview-card:hover { transform: translateY(-5px); border-color: #0dcaf0; }
    .preview-card-overlay { position: absolute; bottom: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent); display: flex; flex-direction: column; justify-content: flex-end; padding: 8px; font-size: 0.70rem; font-weight: 600; }
    .preview-nav-arrow { width: 34px; height: 34px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.5); background: rgba(0,0,0,0.35); color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; backdrop-filter: blur(4px); }
    .preview-nav-arrow:hover { background: white; color: black; }

    #mainWebsiteContent { display: none; opacity: 0; transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1); }

    /* --- ANIMASI ELEGANT PER SECTION (TANPA BLUR) --- */
    @keyframes heroBadgeGrow {
      from { opacity: 0; transform: scale(0.85); filter: blur(4px); }
      to { opacity: 1; transform: scale(1); filter: blur(0); }
    }
    @keyframes heroSlideLeft {
      from { opacity: 0; transform: translateX(-35px); }
      to { opacity: 1; transform: translateX(0); }
    }
    @keyframes heroSlideRight {
      from { opacity: 0; transform: translateX(35px); }
      to { opacity: 1; transform: translateX(0); }
    }
    @keyframes heroFadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .hero-pro-badge { animation: heroBadgeGrow 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .hero-pro-title { opacity: 0; animation: heroSlideLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; }
    .hero-pro-desc { opacity: 0; animation: heroSlideRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.35s forwards; }
    .hero-pro-btn { opacity: 0; animation: heroFadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards; }

    @keyframes tentangSlideLeft {
      0% { opacity: 0; transform: translateX(-60px); }
      100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes tentangSlideRight {
      0% { opacity: 0; transform: translateX(60px); }
      100% { opacity: 1; transform: translateX(0); }
    }
    .tentang-pro-left, .tentang-pro-right { opacity: 0; }
    .tentang-active .tentang-pro-left { animation: tentangSlideLeft 1.1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .tentang-active .tentang-pro-right { animation: tentangSlideRight 1.1s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards; }

    #tentang .p-5 {
      background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
      border: 1px solid rgba(13, 110, 253, 0.15) !important;
      box-shadow: 0 10px 30px rgba(11, 19, 41, 0.05);
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
      position: relative;
      overflow: hidden;
    }
    #tentang .p-5:hover {
      transform: translateY(-6px);
      border-color: rgba(13, 110, 253, 0.4) !important;
      box-shadow: 0 20px 40px rgba(13, 110, 253, 0.12) !important;
    }

    @keyframes kawasanZoomSmooth {
      0% { opacity: 0; transform: scale(0.92); }
      100% { opacity: 1; transform: scale(1); }
    }
    .animate-kawasan-zoom {
      transform-origin: center center !important;
      animation: kawasanZoomSmooth 1s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
      will-change: opacity, transform;
    }

    @keyframes layananSlideFromLeft {
      0% { opacity: 0; transform: translateX(-45px); }
      100% { opacity: 1; transform: translateX(0); }
    }
    @keyframes layananSlideFromRight {
      0% { opacity: 0; transform: translateX(45px); }
      100% { opacity: 1; transform: translateX(0); }
    }
    .animate-layanan-left {
      animation: layananSlideFromLeft 1s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
      will-change: opacity, transform;
    }
    .animate-layanan-right {
      animation: layananSlideFromRight 1s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
      will-change: opacity, transform;
    }

    /* --- ANIMASI KUSTOM BARU UNTUK LOKASI (SLIDE UP & FADE BERSIH TANPA BLUR) --- */
    @keyframes lokasiSlideUpSmooth {
      0% { opacity: 0; transform: translateY(40px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .animate-lokasi-up {
      animation: lokasiSlideUpSmooth 1.1s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
      will-change: opacity, transform;
    }

    @keyframes telemetriFadeUpSmooth {
      0% { opacity: 0; transform: translateY(24px) scale(0.96); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-telemetri-smooth {
      animation: telemetriFadeUpSmooth 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
      will-change: opacity, transform;
    }

    /* CSS KARTU TELEMETRI 5 KOLOM */
    @media (min-width: 992px) {
      .col-lg-is-5 { flex: 0 0 auto; width: 20%; }
    }
    @keyframes cardEntrance {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    .telemetri-card-item { opacity: 0; animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .telemetri-card-item:nth-child(1) { animation-delay: 0.1s; }
    .telemetri-card-item:nth-child(2) { animation-delay: 0.2s; }
    .telemetri-card-item:nth-child(3) { animation-delay: 0.3s; }
    .telemetri-card-item:nth-child(4) { animation-delay: 0.4s; }
    .telemetri-card-item:nth-child(5) { animation-delay: 0.5s; }

    .telemetri-card-navy {
      background: linear-gradient(145deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 58, 138, 0.6) 100%) !important;
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border-radius: 1.25rem;
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #ffffff;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
    }
    .telemetri-card-navy:hover {
      transform: translateY(-8px);
      border-color: rgba(56, 189, 248, 0.6);
      box-shadow: 0 20px 45px rgba(13, 202, 240, 0.25) !important;
    }
    .telemetri-card-navy::before {
      content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3.5px;
      background: linear-gradient(90deg, #38bdf8, #818cf8);
    }

    /* CHATBOT CSS */
    .chat-float-btn {
      position: fixed; bottom: 25px; right: 25px; background-color: #2AABEE; color: white;
      width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center;
      justify-content: center; font-size: 1.6rem; box-shadow: 0 4px 20px rgba(42, 171, 238, 0.4);
      cursor: pointer; z-index: 1000; transition: transform 0.2s, background-color 0.2s;
    }
    .chat-float-btn:hover { transform: scale(1.1); background-color: #229ED9; }

    .chat-window {
      position: fixed; bottom: 95px; right: 25px; width: 375px; max-width: calc(100vw - 30px);
      max-height: calc(100vh - 120px); height: 520px; background: #8FA2B5;
      background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 0);
      background-size: 12px 12px; border-radius: 18px; box-shadow: 0 12px 35px rgba(0,0,0,0.25);
      display: none; flex-direction: column; z-index: 9999; overflow: hidden; border: 1px solid rgba(255,255,255,0.2);
    }
    .tg-chat-header { background: #517DA2; color: white; padding: 10px 14px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex-shrink: 0; min-height: 55px; }
    .tg-avatar { width: 40px; height: 40px; background: #2AABEE; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: white; overflow: hidden; }
    .tg-title { font-size: 0.95rem; font-weight: 700; line-height: 1.1; }
    .tg-status { font-size: 0.72rem; color: #B2D4F0; font-weight: 400; }
    .tg-chat-body { flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; }
    .tg-msg { max-width: 82%; position: relative; font-size: 0.85rem; line-height: 1.4; padding: 8px 12px 20px 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.15); word-wrap: break-word; }
    .tg-msg-bot { align-self: flex-start; background: #FFFFFF; color: #222222; border-radius: 12px 12px 12px 2px; }
    .tg-msg-user { align-self: flex-end; background: #EFFDDE; color: #111111; border-radius: 12px 12px 2px 12px; }
    .tg-time { position: absolute; bottom: 3px; right: 8px; font-size: 0.65rem; color: #888888; display: flex; align-items: center; gap: 3px; }
    .tg-msg-user .tg-time { color: #559145; }
    .tg-keyboard-panel { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 12px; box-shadow: 0 -4px 12px rgba(0,0,0,0.08); animation: slideUp 0.2s ease-in-out; }
    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .tg-inline-keyboard { display: grid; grid-template-columns: 1fr; gap: 6px; }
    .tg-btn-inline { background: #F0F4F7; border: 1px solid #D6E0E8; color: #2AABEE; font-size: 0.8rem; font-weight: 600; padding: 8px 10px; border-radius: 8px; text-align: center; cursor: pointer; transition: background 0.15s, transform 0.1s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .tg-btn-inline:hover { background: #E1EBF2; color: #1D88C2; }
    .tg-btn-inline:active { transform: scale(0.98); }
    .tg-chat-footer { background: #FFFFFF; padding: 10px 14px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #E0E0E0; }
    .tg-btn-cmd { background: #F0F4F8; color: #517DA2; border: none; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .tg-btn-cmd:hover { background: #E2EBF3; }
  </style>
</head>
<body>

  <!-- 1. LANDING SCREEN -->
  @include('partials.welcome')

  <!-- 2. KONTEN DASHBOARD UTAMA -->
  <div id="mainWebsiteContent">

    <!-- NAVBAR -->
    @include('partials.navbar')

    <!-- BERANDA / HERO -->
    @include('partials.hero')

    <!-- TELEMETRI 5 KARTU -->
    @include('partials.telemetri')

    <!-- TENTANG -->
    @include('partials.tentang')

    <!-- KAWASAN -->
    @include('partials.kawasan')

    <!-- LAYANAN -->
    @include('partials.layanan')

    <!-- LOKASI -->
    @include('partials.lokasi')

    <!-- FOOTER -->
    @include('partials.footer')

  </div>

  <!-- CHATBOT BOT SITY -->
  @include('partials.chatbot')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JAVASCRIPT KONTROL ANIMASI & ENGINE CHATBOT -->
  <script>
    function triggerHeroAnimation() {
      const badge = document.querySelector('.hero-pro-badge');
      const title = document.querySelector('.hero-pro-title');
      const desc = document.querySelector('.hero-pro-desc');
      const btn = document.querySelector('.hero-pro-btn');
      const elements = [badge, title, desc, btn];
      elements.forEach(el => { if (el) el.style.animation = 'none'; });
      void document.body.offsetHeight;
      elements.forEach(el => { if (el) el.style.animation = ''; });
    }

    function triggerTentangAnimation() {
      const tentangSection = document.getElementById('tentang');
      if (!tentangSection) return;
      tentangSection.classList.remove('tentang-active');
      void tentangSection.offsetWidth;
      tentangSection.classList.add('tentang-active');
    }

    function triggerKawasanAnimation() {
      const kawasanSection = document.getElementById('kawasan');
      if (!kawasanSection) return;
      const targetElements = kawasanSection.querySelectorAll('h1, h2, h3, h4, p, .lead, .card, .btn');
      targetElements.forEach((el, index) => {
        el.classList.remove('animate-kawasan-zoom');
        el.style.animationDelay = '0s';
        void el.offsetWidth;
        const delay = (index * 0.08).toFixed(2);
        el.style.animationDelay = `${delay}s`;
        el.classList.add('animate-kawasan-zoom');
      });
    }

    function triggerLayananAnimation() {
      const layananSection = document.getElementById('layanan');
      if (!layananSection) return;
      const targetElements = layananSection.querySelectorAll('h1, h2, h3, h4, p, .card');
      targetElements.forEach((el, index) => {
        el.classList.remove('animate-layanan-left', 'animate-layanan-right');
        el.style.animationDelay = '0s';
        void el.offsetWidth;
        const delay = (index * 0.1).toFixed(2);
        el.style.animationDelay = `${delay}s`;
        
        if (index % 2 === 0) {
          el.classList.add('animate-layanan-left');
        } else {
          el.classList.add('animate-layanan-right');
        }
      });
    }

    function triggerLokasiAnimation() {
      const lokasiSection = document.getElementById('kontak');
      if (!lokasiSection) return;
      const elements = lokasiSection.querySelectorAll('h1, h2, h3, h4, p, .badge, .card, iframe');
      elements.forEach((el, index) => {
        el.classList.remove('animate-lokasi-up');
        el.style.animationDelay = '0s';
        void el.offsetWidth;
        const delay = (index * 0.09).toFixed(2);
        el.style.animationDelay = `${delay}s`;
        el.classList.add('animate-lokasi-up');
      });
    }

    function triggerTelemetriAnimation() {
      const telemetriSection = document.getElementById('telemetri');
      if (!telemetriSection) return;
      const cards = telemetriSection.querySelectorAll('.card');
      cards.forEach((card, index) => {
        card.classList.remove('animate-telemetri-smooth');
        card.style.animationDelay = '0s';
        void card.offsetWidth;
        const delay = (index * 0.12).toFixed(2);
        card.style.animationDelay = `${delay}s`;
        card.classList.add('animate-telemetri-smooth');
      });
    }

    const backgroundImages = [
      "image/alun-alun-tegal.jpeg",
      "image/balaikota.jpeg",
      "image/masjid agung.jpeg",
      "image/taman pancasila.jpeg",
      "image/stasiun.jpeg",
      "image/water ledeng.jpeg"
    ];

    let currentBgIndex = 0;
    const welcomeSec = document.getElementById('welcomeSection');
    const heroDashboard = document.querySelector('.hero-observatorium');

    setInterval(() => {
      currentBgIndex = (currentBgIndex + 1) % backgroundImages.length;
      let newBgUrl = `linear-gradient(rgba(11, 19, 41, 0.25), rgba(11, 19, 41, 0.68)), url('${backgroundImages[currentBgIndex]}')`;
      if (welcomeSec) welcomeSec.style.backgroundImage = newBgUrl;
      if (heroDashboard) heroDashboard.style.backgroundImage = newBgUrl;
    }, 3000);

    function enterWebsite() {
      const mainContent = document.getElementById('mainWebsiteContent');
      if(!welcomeSec || !mainContent) return;
      welcomeSec.style.opacity = '0';
      welcomeSec.style.transform = 'scale(1.04)';
      setTimeout(() => {
        welcomeSec.style.display = 'none';
        mainContent.style.display = 'block';
        triggerHeroAnimation();
        setTimeout(() => {
          mainContent.style.opacity = '1';
          window.scrollTo(0, 0);
        }, 50);
      }, 500);
    }

    function returnToWelcome() {
      const mainContent = document.getElementById('mainWebsiteContent');
      if(!welcomeSec || !mainContent) return;
      mainContent.style.opacity = '0';
      setTimeout(() => {
        mainContent.style.display = 'none';
        welcomeSec.style.display = 'flex';
        welcomeSec.style.opacity = '1';
        welcomeSec.style.transform = 'scale(1)';
        window.scrollTo(0, 0);
      }, 500);
    }

    // --- ENGINE UTAMA CHATBOT (SITY) ---
    function getTimeNow() {
      const d = new Date();
      return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
    }

    function toggleChatWindow() {
      const chatWin = document.getElementById('chatWindow');
      if (!chatWin) return;
      if (chatWin.style.display === 'flex') {
        chatWin.style.display = 'none';
      } else {
        chatWin.style.display = 'flex';
      }
    }

    async function sendMessage() {
      const chatInput = document.getElementById('chatInput');
      const chatBody = document.getElementById('chatBody');
      if (!chatInput || !chatBody) return;

      const message = chatInput.value.trim();
      if (!message) return;

      // 1. Tampilkan pesan pengirim
      const userMsgHtml = `
        <div class="tg-msg tg-msg-user">
          ${message}
          <div class="tg-time">${getTimeNow()}</div>
        </div>
      `;
      chatBody.insertAdjacentHTML('beforeend', userMsgHtml);

      chatInput.value = '';
      chatBody.scrollTop = chatBody.scrollHeight;

      // 2. Indikator Loading
      const loadingId = 'loading-' + Date.now();
      const loadingHtml = `
        <div class="tg-msg tg-msg-bot" id="${loadingId}">
          <i>🤖 Sity sedang mengetik...</i>
          <div class="tg-time">${getTimeNow()}</div>
        </div>
      `;
      chatBody.insertAdjacentHTML('beforeend', loadingHtml);
      chatBody.scrollTop = chatBody.scrollHeight;

      try {
        // 3. Kirim ke Webhook n8n
        const response = await fetch('http://localhost:5678/webhook/chat-smartcity', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ message: message })
        });

        const data = await response.json();
        document.getElementById(loadingId)?.remove();

        // 4. Balasan AI n8n
        let aiReply = "Maaf, terjadi kendala saat memproses jawaban.";
        if (Array.isArray(data) && data.length > 0) {
          aiReply = data[0].output || data[0].text || data[0].message || data[0].response || JSON.stringify(data[0]);
        } else if (typeof data === 'object') {
          aiReply = data.output || data.text || data.message || data.response || JSON.stringify(data);
        }

        const botMsgHtml = `
          <div class="tg-msg tg-msg-bot">
            <b>🤖 Sity Smartcity Bot</b><br>
            ${aiReply}
            <div class="tg-time">${getTimeNow()}</div>
          </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', botMsgHtml);
        chatBody.scrollTop = chatBody.scrollHeight;

      } catch (err) {
        console.error("Error chatbot:", err);
        document.getElementById(loadingId)?.remove();

        const errorMsgHtml = `
          <div class="tg-msg tg-msg-bot" style="background:#fee2e2; color:#991b1b;">
            <b>🤖 Sity Smartcity Bot</b><br>
            Gagal terhubung ke server AI Chatbot. Pastikan workflow n8n statusnya Active/Publish.
            <div class="tg-time">${getTimeNow()}</div>
          </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', errorMsgHtml);
        chatBody.scrollTop = chatBody.scrollHeight;
      }
    }

    function fetchPublicEnvironmentData() {
      fetch('/api/public-environment')
        .then(response => response.json())
        .then(result => {
          if (result.status === 'success') {
            let d = result.data;
            if(document.getElementById('temp-value')) document.getElementById('temp-value').innerText = d.temperature + ' °C';
            if(document.getElementById('humidity-value')) document.getElementById('humidity-value').innerText = d.humidity + ' %';
            if(document.getElementById('aqi-value')) document.getElementById('aqi-value').innerText = d.air_quality + ' AQI';
            if(document.getElementById('rain-value')) document.getElementById('rain-value').innerText = d.rain_status;
            if(document.getElementById('water-value')) document.getElementById('water-value').innerText = d.water_level + ' cm';
          }
        })
        .catch(error => console.error('Gagal memuat data monitoring:', error));
    }

    // INISIALISASI EVENT LISTENER UTAMA
    document.addEventListener("DOMContentLoaded", () => {
      const sendBtn = document.getElementById('sendBtn');
      const chatInput = document.getElementById('chatInput');
      const initTime = document.getElementById('initialTime');

      if (initTime) initTime.innerText = getTimeNow();

      if (sendBtn) {
        sendBtn.addEventListener('click', function(e) {
          e.preventDefault();
          sendMessage();
        });
      }

      if (chatInput) {
        chatInput.addEventListener('keydown', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
          }
        });
      }

      const navLinks = document.querySelectorAll('#navbarNav .nav-link[href^="#"]');
      const sections = document.querySelectorAll('#mainWebsiteContent section[id]');

      function setActiveMenu(id) {
        navLinks.forEach(link => {
          link.classList.toggle('active', link.getAttribute('href') === '#' + id);
        });
      }

      navLinks.forEach(link => {
        link.addEventListener('click', function (event) {
          const targetId = this.getAttribute('href').substring(1);
          const target = document.getElementById(targetId);
          const navbar = document.querySelector('#mainWebsiteContent .navbar');
          const navbarCollapse = document.getElementById('navbarNav');

          if (!target || !navbar) return;
          event.preventDefault();
          setActiveMenu(targetId);

          if (targetId === 'beranda') triggerHeroAnimation();
          if (targetId === 'tentang') triggerTentangAnimation();
          if (targetId === 'kawasan') triggerKawasanAnimation();
          if (targetId === 'layanan') triggerLayananAnimation();
          if (targetId === 'telemetri') triggerTelemetriAnimation();
          if (targetId === 'kontak') triggerLokasiAnimation();

          const isMobile = window.matchMedia('(max-width: 767.98px)').matches;
          const scrollToTarget = () => {
            const navbarHeight = navbar.getBoundingClientRect().height;
            const targetTop = target.getBoundingClientRect().top + window.pageYOffset;
            const scrollTop = Math.max(0, targetTop - navbarHeight);
            window.scrollTo({ top: scrollTop, behavior: 'smooth' });
          };

          if (isMobile && navbarCollapse && navbarCollapse.classList.contains('show') && window.bootstrap) {
            const collapse = bootstrap.Collapse.getInstance(navbarCollapse) || new bootstrap.Collapse(navbarCollapse, { toggle: false });
            collapse.hide();
            navbarCollapse.addEventListener('hidden.bs.collapse', scrollToTarget, { once: true });
          } else {
            scrollToTarget();
          }

          history.pushState(null, '', '#' + targetId);
        });
      });

      let isBerandaAnimated = true;
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          const targetId = entry.target.id;
          if (entry.isIntersecting) {
            setActiveMenu(targetId);
            if (targetId === 'tentang') entry.target.classList.add('tentang-active');
            if (targetId === 'kawasan') triggerKawasanAnimation();
            if (targetId === 'layanan') triggerLayananAnimation();
            if (targetId === 'telemetri') triggerTelemetriAnimation();
            if (targetId === 'kontak') triggerLokasiAnimation();
            if (targetId === 'beranda' && !isBerandaAnimated) {
              triggerHeroAnimation();
              isBerandaAnimated = true;
            }
          } else {
            if (targetId === 'tentang') entry.target.classList.remove('tentang-active');
            if (targetId === 'beranda') isBerandaAnimated = false;
          }
        });
      }, { root: null, rootMargin: '-90px 0px -30% 0px', threshold: 0.15 });

      sections.forEach(section => observer.observe(section));

      fetchPublicEnvironmentData();
      setInterval(fetchPublicEnvironmentData, 5000);
    });
  </script>
</body>
</html>