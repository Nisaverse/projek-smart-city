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

    /* --- ANIMASI ELEGANT PER SECTION --- */
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

    /* CSS KARTU TELEMETRI GRID */
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
    .telemetri-card-item:nth-child(6) { animation-delay: 0.6s; }
    .telemetri-card-item:nth-child(7) { animation-delay: 0.7s; }

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
      position: fixed !important; bottom: 25px !important; right: 25px !important; top: auto !important; left: auto !important;
      background-color: #2AABEE; color: white;
      width: 60px; height: 60px; border-radius: 50%; display: none; align-items: center;
      justify-content: center; font-size: 1.6rem; box-shadow: 0 4px 20px rgba(42, 171, 238, 0.4);
      cursor: pointer; z-index: 1000 !important; transition: transform 0.2s, background-color 0.2s;
    }
    .chat-float-btn:hover { transform: scale(1.1); background-color: #229ED9; }

    .chat-window {
      position: fixed;
      bottom: 95px;
      right: 25px;
      width: 360px;
      max-width: calc(100vw - 30px);
      height: 460px;
      background: #8FA2B5;
      background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 0);
      background-size: 12px 12px; border-radius: 18px; box-shadow: 0 12px 35px rgba(0,0,0,0.25);
      display: none; flex-direction: column; z-index: 1050; overflow: hidden; border: 1px solid rgba(255,255,255,0.2);
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
    .tg-chat-footer { background: #FFFFFF; padding: 10px 14px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #E0E0E0; }

    /* --- PENGATURAN CHATBOT DI TENGAH LAYAR MOBILE --- */
    @media (max-width: 768px) {
      .chat-window {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 90vw !important;
        height: 65vh !important;
        max-height: 480px !important;
        bottom: auto !important;
        right: auto !important;
        border-radius: 20px !important;
        box-shadow: 0 15px 40px rgba(0,0,0,0.4) !important;
      }

      body { font-size: 1.05rem !important; }
      h1 { font-size: 2rem !important; }
      h2 { font-size: 1.65rem !important; }
      h3 { font-size: 1.35rem !important; }
      p, .lead, span, div { font-size: 1.05rem !important; line-height: 1.7 !important; }

      section, .container { padding-left: 20px !important; padding-right: 20px !important; }

      #welcomeSection { padding: 20px 15px !important; justify-content: space-around !important; text-align: center; }
      .welcome-title { font-size: 2.2rem !important; line-height: 1.25 !important; }
      .welcome-desc { font-size: 1rem !important; }
      .welcome-preview-container { justify-content: center !important; flex-wrap: wrap !important; }

      .telemetri-card-navy { margin-bottom: 16px !important; }
    }
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

    <!-- TELEMETRI 7 KARTU (LINGKUNGAN & IOT) -->
    <section id="telemetri" style="background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%); color: #ffffff; padding-top: 5rem; padding-bottom: 7rem;">
      <div class="container-fluid px-4 px-lg-5 py-3">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
          <h6 class="text-info fw-bold text-uppercase" style="letter-spacing: 1.5px; font-size: 0.85rem;">Live Kondisi Lingkungan</h6>
          <h2 class="fw-bold text-white mb-2">Kondisi Lingkungan Saat Ini</h2>
          <p class="text-light opacity-75">Pantauan langsung kondisi cuaca, kualitas lingkungan, serta status perangkat Smart City kawasan Alun-Alun Kota Tegal.</p>
        </div>

        <div class="row g-4 justify-content-center">

          <!-- 1. Suhu Saat Ini -->
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 telemetri-card-item">
            <div class="card telemetri-card-navy p-4 h-100 d-flex flex-column justify-content-between" style="min-height: 230px;">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="text-light opacity-75 small mb-1" style="font-size: 0.8rem; font-weight: 600;">Suhu Saat Ini</div>
                  <h3 class="fw-bold text-white mb-0" style="font-size: 2rem; letter-spacing: -0.5px;" id="temp-value">27.4 &deg;C</h3>
                </div>
                <div class="shadow-sm" style="width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(251, 191, 36, 0.18); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.35);">
                  <i class="fa-solid fa-temperature-half fs-5"></i>
                </div>
              </div>
              <div class="text-light opacity-75 border-top pt-3 mt-2 d-flex align-items-center" style="font-size: 0.78rem; border-color: rgba(255, 255, 255, 0.12) !important;">
                <span><i class="fa-solid fa-circle-dot text-warning me-2" style="font-size: 0.55rem;"></i> Suhu saat ini</span>
              </div>
            </div>
          </div>

          <!-- 2. Kelembaban -->
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 telemetri-card-item">
            <div class="card telemetri-card-navy p-4 h-100 d-flex flex-column justify-content-between" style="min-height: 230px;">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="text-light opacity-75 small mb-1" style="font-size: 0.8rem; font-weight: 600;">Kelembaban</div>
                  <h3 class="fw-bold text-white mb-0" style="font-size: 2rem; letter-spacing: -0.5px;" id="humidity-value">50 %</h3>
                </div>
                <div class="shadow-sm" style="width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(56, 189, 248, 0.18); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.35);">
                  <i class="fa-solid fa-droplet fs-5"></i>
                </div>
              </div>
              <div class="text-light opacity-75 border-top pt-3 mt-2 d-flex align-items-center" style="font-size: 0.78rem; border-color: rgba(255, 255, 255, 0.12) !important;">
                <span><i class="fa-solid fa-circle-dot text-info me-2" style="font-size: 0.55rem;"></i> Kelembaban saat ini</span>
              </div>
            </div>
          </div>

          <!-- 3. Kualitas Udara -->
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 telemetri-card-item">
            <div class="card telemetri-card-navy p-4 h-100 d-flex flex-column justify-content-between" style="min-height: 230px;">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="text-light opacity-75 small mb-1" style="font-size: 0.8rem; font-weight: 600;">Kualitas Udara</div>
                  <h3 class="fw-bold text-white mb-0" style="font-size: 2rem; letter-spacing: -0.5px;" id="aqi-value">42 AQI</h3>
                </div>
                <div class="shadow-sm" style="width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(74, 222, 128, 0.18); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.35);">
                  <i class="fa-solid fa-wind fs-5"></i>
                </div>
              </div>
              <div class="text-light opacity-75 border-top pt-3 mt-2 d-flex align-items-center" style="font-size: 0.78rem; border-color: rgba(255, 255, 255, 0.12) !important;">
                <span><i class="fa-solid fa-circle-dot text-success me-2" style="font-size: 0.55rem;"></i> Kualitas udara saat ini</span>
              </div>
            </div>
          </div>

          <!-- 4. Status Hujan -->
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 telemetri-card-item">
            <div class="card telemetri-card-navy p-4 h-100 d-flex flex-column justify-content-between" style="min-height: 230px;">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="text-light opacity-75 small mb-1" style="font-size: 0.8rem; font-weight: 600;">Status Hujan</div>
                  <h3 class="fw-bold text-white mb-0" style="font-size: 1.6rem; line-height: 1.2;" id="rain-value">Mendung</h3>
                </div>
                <div class="shadow-sm" style="width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(148, 163, 184, 0.18); color: #cbd5e1; border: 1px solid rgba(148, 163, 184, 0.35);">
                  <i class="fa-solid fa-cloud-rain fs-5"></i>
                </div>
              </div>
              <div class="text-light opacity-75 border-top pt-3 mt-2 d-flex align-items-center" style="font-size: 0.78rem; border-color: rgba(255, 255, 255, 0.12) !important;">
                <span><i class="fa-solid fa-circle-dot text-light me-2" style="font-size: 0.55rem;"></i> Status hujan saat ini</span>
              </div>
            </div>
          </div>

          <!-- 5. Air Sungai -->
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 telemetri-card-item">
            <div class="card telemetri-card-navy p-4 h-100 d-flex flex-column justify-content-between" style="min-height: 230px;">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="text-light opacity-75 small mb-1" style="font-size: 0.8rem; font-weight: 600;">Air Sungai</div>
                  <h3 class="fw-bold text-white mb-0" style="font-size: 2rem; letter-spacing: -0.5px;" id="water-value">120 cm</h3>
                </div>
                <div class="shadow-sm" style="width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(13, 202, 240, 0.18); color: #0dcaf0; border: 1px solid rgba(13, 202, 240, 0.35);">
                  <i class="fa-solid fa-water fs-5"></i>
                </div>
              </div>
              <div class="text-light opacity-75 border-top pt-3 mt-2 d-flex align-items-center" style="font-size: 0.78rem; border-color: rgba(255, 255, 255, 0.12) !important;">
                <span><i class="fa-solid fa-circle-dot text-info me-2" style="font-size: 0.55rem;"></i> Tinggi air sungai saat ini</span>
              </div>
            </div>
          </div>

          <!-- 6. Smart Lamp -->
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 telemetri-card-item">
            <div class="card telemetri-card-navy p-4 h-100 d-flex flex-column justify-content-between" style="min-height: 230px;">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="text-light opacity-75 small mb-1" style="font-size: 0.8rem; font-weight: 600;">Smart Lamp</div>
                  <h3 class="fw-bold text-white mb-0" style="font-size: 1.5rem; line-height: 1.2;" id="smart-lamp-value">3 / 4 Sektor Aktif</h3>
                </div>
                <div class="shadow-sm" style="width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(234, 179, 8, 0.18); color: #eab308; border: 1px solid rgba(234, 179, 8, 0.35);">
                  <i class="fa-solid fa-lightbulb fs-5"></i>
                </div>
              </div>
              <div class="text-light opacity-75 border-top pt-3 mt-2 d-flex align-items-center" style="font-size: 0.78rem; border-color: rgba(255, 255, 255, 0.12) !important;">
                <span><i class="fa-solid fa-circle-dot text-warning me-2" style="font-size: 0.55rem;"></i> Sektor lampu aktif saat ini</span>
              </div>
            </div>
          </div>

          <!-- 7. Smart Parking -->
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 telemetri-card-item">
            <div class="card telemetri-card-navy p-4 h-100 d-flex flex-column justify-content-between" style="min-height: 230px;">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="text-light opacity-75 small mb-1" style="font-size: 0.8rem; font-weight: 600;">Smart Parking</div>
                  <h3 class="fw-bold text-white mb-0" style="font-size: 1.5rem; line-height: 1.2;" id="smart-parking-value">10 Slot</h3>
                </div>
                <div class="shadow-sm" style="width: 44px; height: 44px; border-radius: 14px; display: flex; align-items: center; justify-content: center; background: rgba(59, 130, 246, 0.18); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.35);">
                  <i class="fa-solid fa-square-parking fs-5"></i>
                </div>
              </div>
              <div class="text-light opacity-75 border-top pt-3 mt-2 d-flex align-items-center" style="font-size: 0.78rem; border-color: rgba(255, 255, 255, 0.12) !important;">
                <span><i class="fa-solid fa-circle-dot text-primary me-2" style="font-size: 0.55rem;"></i> Slot parkir tersedia saat ini</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- TENTANG -->
    @include('partials.tentang')

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
    function setChatbotVisibility(isVisible) {
      const chatBtn = document.querySelector('.chat-float-btn');
      const chatWin = document.getElementById('chatWindow');
      if (chatBtn) chatBtn.style.display = isVisible ? 'flex' : 'none';
      if (!isVisible && chatWin) chatWin.style.display = 'none';
    }

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

    // PRELOAD GAMBAR AGAR TIDAK KEDIP HITAM
    backgroundImages.forEach((src) => {
      const img = new Image();
      img.src = src;
    });

    let currentBgIndex = 0;
    const welcomeSec = document.getElementById('welcomeSection');
    const heroDashboard = document.querySelector('.hero-observatorium');

    setInterval(() => {
      currentBgIndex = (currentBgIndex + 1) % backgroundImages.length;
      const newBgUrl = `linear-gradient(rgba(11, 19, 41, 0.25), rgba(11, 19, 41, 0.68)), url('${backgroundImages[currentBgIndex]}')`;

      if (welcomeSec) welcomeSec.style.backgroundImage = newBgUrl;
      if (heroDashboard) heroDashboard.style.backgroundImage = newBgUrl;
    }, 4000);

    function enterWebsite() {
      const mainContent = document.getElementById('mainWebsiteContent');
      if (!welcomeSec || !mainContent) return;
      welcomeSec.style.opacity = '0';
      welcomeSec.style.transform = 'scale(1.04)';
      setTimeout(() => {
        welcomeSec.style.display = 'none';
        mainContent.style.display = 'block';
        setChatbotVisibility(true);
        triggerHeroAnimation();
        setTimeout(() => {
          mainContent.style.opacity = '1';
          window.scrollTo(0, 0);
        }, 50);
      }, 500);
    }

    function returnToWelcome() {
      const mainContent = document.getElementById('mainWebsiteContent');
      if (!welcomeSec || !mainContent) return;
      setChatbotVisibility(false);
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
      chatWin.style.display = chatWin.style.display === 'flex' ? 'none' : 'flex';
    }

    async function sendMessage() {
      const chatInput = document.getElementById('chatInput');
      const chatBody = document.getElementById('chatBody');
      if (!chatInput || !chatBody) return;

      const message = chatInput.value.trim();
      if (!message) return;

      const userMsgHtml = `
        <div class="tg-msg tg-msg-user">
          ${message}
          <div class="tg-time">${getTimeNow()}</div>
        </div>
      `;
      chatBody.insertAdjacentHTML('beforeend', userMsgHtml);

      chatInput.value = '';
      chatBody.scrollTop = chatBody.scrollHeight;

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
        const response = await fetch('https://industry-routing-plaintiff-downtown.trycloudflare.com/webhook/chat-smartcity', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ message: message })
        });

        const data = await response.json();
        document.getElementById(loadingId)?.remove();

        let aiReply = "Maaf, terjadi kendala saat memproses jawaban.";
        if (Array.isArray(data) && data.length > 0) {
          aiReply = data[0].output || data[0].text || data[0].message || data[0].response || JSON.stringify(data[0]);
        } else if (typeof data === 'object' && data !== null) {
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

    function hasValidNumber(val) {
      return val !== undefined && val !== null && val !== '' && val !== '--' && !Number.isNaN(Number(val));
    }

    function fetchPublicEnvironmentData() {
      fetch('/api/public-environment', { cache: 'no-store' })
        .then(response => response.json())
        .then(result => {
          if (result.status === 'success' && result.data) {
            const d = result.data;

            const tempEl = document.getElementById('temp-value');
            const humEl  = document.getElementById('humidity-value');
            const aqiEl  = document.getElementById('aqi-value');
            const rainEl = document.getElementById('rain-value');
            const watEl  = document.getElementById('water-value');
            const lampEl = document.getElementById('smart-lamp-value');
            const parkEl = document.getElementById('smart-parking-value');

            if (tempEl && hasValidNumber(d.temperature)) {
              tempEl.innerText = `${Number(d.temperature).toFixed(1)} °C`;
            }
            if (humEl && hasValidNumber(d.humidity)) {
              humEl.innerText = `${Math.round(Number(d.humidity))} %`;
            }
            if (aqiEl && hasValidNumber(d.air_quality)) {
              aqiEl.innerText = `${Number(d.air_quality)} AQI`;
            }
            if (rainEl && d.rain_status) {
              rainEl.innerText = d.rain_status;
            }
            if (watEl && hasValidNumber(d.water_level)) {
              watEl.innerText = `${Number(d.water_level)} cm`;
            }

            // Smart Lamp: jumlah sektor lampu yang aktif dari backend admin
            if (lampEl) {
              const aktif = d.lamps_active ?? d.smart_lamp ?? d.active_lamp_sectors;
              const totalSektor = d.lamps_total ?? d.total_lamp_sectors ?? 4;
              if (hasValidNumber(aktif)) {
                lampEl.innerText = `${aktif} / ${totalSektor} Sektor Aktif`;
              } else if (typeof aktif === 'string' && aktif.trim() !== '') {
                lampEl.innerText = aktif;
              }
            }

            // Smart Parking: slot tersedia dari kapasitas parkir
            if (parkEl) {
              const maxSlot = hasValidNumber(d.parking_total) ? Number(d.parking_total) : 10;
              const availRaw = d.parking_avail ?? d.smart_parking;
              if (hasValidNumber(availRaw)) {
                const slotKosong = Math.min(Math.max(Number(availRaw), 0), maxSlot);
                parkEl.innerText = `${slotKosong} / ${maxSlot} Slot Kosong`;
              } else if (typeof availRaw === 'string' && availRaw.trim() !== '') {
                parkEl.innerText = availRaw;
              }
            }
          }
        })
        .catch(error => console.error('Gagal memuat data monitoring:', error));
    }

    document.addEventListener("DOMContentLoaded", () => {
      setChatbotVisibility(false);

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