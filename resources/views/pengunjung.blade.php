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
    body {
      background-color: #0b1329;
      color: #1a1a1a;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow-x: hidden;
      margin: 0;
      padding: 0;
    }


    /* --- HALAMAN DEPAN (LANDING / WELCOME SCREEN) --- */
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
      padding: 30px 60px;
      background-image: linear-gradient(rgba(11, 19, 41, 0.45), rgba(11, 19, 41, 0.88)), url('image/alun-alun-tegal.jpeg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      transition: opacity 0.6s ease, transform 0.6s ease, background-image 1s ease-in-out;
    }

    .bg-overlay {
      position: absolute !important;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(rgba(11, 19, 41, 0.45), rgba(11, 19, 41, 0.88));
      z-index: 1;
    }

    #welcomeSection > * {
      position: relative;
      z-index: 2;
    }
    
    /* Bagian Atas Tengah (Logo & Pemkot) - Tanpa Kotak Background */
    .welcome-top-center {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }
    .welcome-logos-row {
      display: flex !important;
      flex-direction: row !important;
      align-items: center;
      justify-content: center;
      gap: 20px;
      margin-bottom: 12px;
      flex-wrap: nowrap;
    }
    .logo-badge-item {
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: transparent; /* Kotak dihilangkan sepenuhnya */
      border: none;
    }
    .logo-badge-item img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }
    .welcome-gov-title {
      font-size: 0.95rem;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-bottom: 2px;
      color: #ffffff;
    }
    .welcome-jawa-script {
      font-size: 0.95rem;
      letter-spacing: 2px;
      color: #ffffff;
      margin-bottom: 0;
      font-family: sans-serif;
      text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    /* Bagian Tengah */
    .welcome-center {
      max-width: 650px;
      margin-top: 40px;
    }
    .welcome-title {
      font-size: 3.5rem;
      font-weight: 800;
      letter-spacing: 2px;
      margin-bottom: 15px;
      line-height: 1.1;
    }
    .welcome-desc {
      font-size: 0.95rem;
      line-height: 1.6;
      color: #e2e8f0;
      margin-bottom: 25px;
    }
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
    .btn-masuk-situs:hover {
      background-color: #bb2d3b;
      transform: translateY(-2px);
    }

    /* Mini Preview Cards di kanan bawah */
    .welcome-preview-container {
      display: flex;
      gap: 15px;
      align-items: center;
      justify-content: flex-end;
    }
    .preview-card {
      width: 140px;
      height: 180px;
      border-radius: 12px;
      overflow: hidden;
      position: relative;
      cursor: pointer;
      border: 2px solid rgba(255,255,255,0.3);
      transition: transform 0.3s, border-color 0.3s;
      background-size: cover;
      background-position: center;
    }
    .preview-card:hover {
      transform: translateY(-5px);
      border-color: #0dcaf0;
    }
    .preview-card-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 10px;
      font-size: 0.75rem;
      font-weight: 600;
    }
    .preview-nav-arrow {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 1px solid rgba(255,255,255,0.4);
      background: rgba(0,0,0,0.3);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      backdrop-filter: blur(4px);
    }
    .preview-nav-arrow:hover {
      background: white;
      color: black;
    }

    /* --- ISI WEBSITE UTAMA (DASHBOARD) --- */
  #mainWebsiteContent {
    display: none;
    opacity: 0;
    transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .hero-observatorium {
    min-height: 100vh;
    background-image: linear-gradient(rgba(11, 19, 41, 0.5), rgba(11, 19, 41, 0.85)), url('image/alun-alun-tegal.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: #ffffff;
    padding: 30px 50px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    transition: background-image 1s ease-in-out;
  }

  .top-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    position: relative;
    z-index: 2;
  }

  .nav-links a {
    color: #cbd5e1;
    text-decoration: none;
    margin-right: 30px;
    font-size: 0.85rem;
    letter-spacing: 1.2px;
    font-weight: 600;
    text-transform: uppercase;
    transition: color 0.3s ease;
  }

  .nav-links a:hover {
    color: #38bdf8;
  }

  .brand-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 700;
    letter-spacing: 2px;
    font-size: 1.15rem;
  }

  .brand-logo-circle {
    width: 36px;
    height: 36px;
    background: rgba(13, 202, 240, 0.1);
    border: 2px solid #0dcaf0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 15px rgba(13, 202, 240, 0.3);
  }

  .badge-connected {
    background: rgba(25, 135, 84, 0.15);
    border: 1px solid rgba(25, 135, 84, 0.4);
    color: #4ade80;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 1px;
    backdrop-filter: blur(8px);
  }

  .hero-content {
    position: relative;
    z-index: 2;
  }

  /* --- SECTION ZONA INTERAKTIF --- */
  .section-book-carousel {
    background-color: #f8fafc;
    padding: 120px 0;
    position: relative;
    overflow: hidden;
  }

  .bg-huge-text-left, .bg-huge-text-right {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 9rem;
    font-weight: 900;
    color: #e2e8f0;
    opacity: 0.4;
    z-index: 1;
    white-space: nowrap;
    user-select: none;
    pointer-events: none;
  }

  .bg-huge-text-left { left: -30px; }
  .bg-huge-text-right { right: -30px; }

  .zone-tabs-container {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 60px;
    position: relative;
    z-index: 10;
    flex-wrap: wrap;
  }

  .zone-tab-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 12px 24px;
    border-radius: 35px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 6px rgba(0,0,0,0.02);
  }

  .zone-tab-item:hover {
    border-color: #cbd5e1;
    color: #0f172a;
    transform: translateY(-2px);
  }

  .zone-tab-item.active {
    background: #0b1329;
    color: #ffffff;
    border-color: #0b1329;
    box-shadow: 0 10px 25px rgba(11, 19, 41, 0.25);
    transform: translateY(-2px);
  }

  .book-card-wrapper {
    position: relative;
    max-width: 480px;
    margin: 0 auto;
    z-index: 5;
  }

  .book-card-item {
    background-size: cover;
    background-position: center;
    border-radius: 32px;
    padding: 35px;
    color: white;
    box-shadow: 0 25px 50px -12px rgba(11, 19, 41, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.25);
    min-height: 460px;
    display: none;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.4s ease;
  }

  .book-card-item.active {
    display: flex;
    animation: fadeInCard 0.6s ease forwards;
  }

  @keyframes fadeInCard {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .carousel-nav-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 520px;
    margin: 45px auto 0 auto;
    position: relative;
    z-index: 10;
    padding: 0 15px;
  }

  .nav-arrow-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #0b1329;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }

  .nav-arrow-btn:hover {
    background: #0b1329;
    color: white;
    border-color: #0b1329;
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(11, 19, 41, 0.2);
  }

  .section-light {
    background-color: #ffffff;
    padding: 110px 0;
    border-bottom: 1px solid #f1f5f9;
  }

  .section-dark-box {
    background: linear-gradient(135deg, #0b1329 0%, #1e293b 100%);
    color: #ffffff;
    border-radius: 32px;
    padding: 70px;
    box-shadow: 0 20px 40px rgba(11, 19, 41, 0.15);
  }


    /* ========================================================== */
    /* --- STYLING CHATBOT TELEGRAM SMART CITY (SITY BOT) --- */
    /* ========================================================== */
    .chat-float-btn {
      position: fixed;
      bottom: 25px;
      right: 25px;
      background-color: #2AABEE;
      color: white;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.6rem;
      box-shadow: 0 4px 20px rgba(42, 171, 238, 0.4);
      cursor: pointer;
      z-index: 1000;
      transition: transform 0.2s, background-color 0.2s;
    }
    .chat-float-btn:hover {
      transform: scale(1.1);
      background-color: #229ED9;
    }

    .chat-window {
      position: fixed;
      bottom: 95px;
      right: 25px;
      width: 375px;
      max-width: calc(100vw - 30px);
      max-height: calc(100vh - 120px);
      height: 520px;
      background: #8FA2B5;
      background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 0);
      background-size: 12px 12px;
      border-radius: 18px;
      box-shadow: 0 12px 35px rgba(0,0,0,0.25);
      display: none;
      flex-direction: column;
      z-index: 9999;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,0.2);
    }

    .tg-chat-header {
      background: #517DA2;
      color: white;
      padding: 10px 14px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      flex-shrink: 0;
      min-height: 55px;
    }
    .tg-avatar {
      width: 40px;
      height: 40px;
      background: #2AABEE;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: white;
      overflow: hidden;
    }
    .tg-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .tg-title {
      font-size: 0.95rem;
      font-weight: 700;
      line-height: 1.1;
    }
    .tg-status {
      font-size: 0.72rem;
      color: #B2D4F0;
      font-weight: 400;
    }

    .tg-chat-body {
      flex: 1;
      padding: 15px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .tg-msg {
      max-width: 82%;
      position: relative;
      font-size: 0.85rem;
      line-height: 1.4;
      padding: 8px 12px 20px 12px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.15);
      word-wrap: break-word;
    }

    .tg-msg-bot {
      align-self: flex-start;
      background: #FFFFFF;
      color: #222222;
      border-radius: 12px 12px 12px 2px;
    }

    .tg-msg-user {
      align-self: flex-end;
      background: #EFFDDE;
      color: #111111;
      border-radius: 12px 12px 2px 12px;
    }

    .tg-time {
      position: absolute;
      bottom: 3px;
      right: 8px;
      font-size: 0.65rem;
      color: #888888;
      display: flex;
      align-items: center;
      gap: 3px;
    }
    .tg-msg-user .tg-time {
      color: #559145;
    }

    /* Panel Menu Pop-up Pertanyaan */
    .tg-keyboard-panel {
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
      padding: 12px;
      box-shadow: 0 -4px 12px rgba(0,0,0,0.08);
      animation: slideUp 0.2s ease-in-out;
    }

    @keyframes slideUp {
      from { transform: translateY(100%); }
      to { transform: translateY(0); }
    }

    .tg-inline-keyboard {
      display: grid;
      grid-template-columns: 1fr;
      gap: 6px;
    }
    .tg-btn-inline {
      background: #F0F4F7;
      border: 1px solid #D6E0E8;
      color: #2AABEE;
      font-size: 0.8rem;
      font-weight: 600;
      padding: 8px 10px;
      border-radius: 8px;
      text-align: center;
      cursor: pointer;
      transition: background 0.15s, transform 0.1s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .tg-btn-inline:hover {
      background: #E1EBF2;
      color: #1D88C2;
    }
    .tg-btn-inline:active {
      transform: scale(0.98);
    }

    .tg-chat-footer {
      background: #FFFFFF;
      padding: 10px 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-top: 1px solid #E0E0E0;
    }
    .tg-btn-cmd {
      background: #F0F4F8;
      color: #517DA2;
      border: none;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }
    .tg-btn-cmd:hover {
      background: #E2EBF3;
    }

    /* ==========================================================
       TAMPILAN SECTION NAVIGASI - DESKTOP
       Saat menu Tentang/Kawasan/Layanan/Kontak diklik, section
       diposisikan tepat di bawah navbar tanpa mengubah struktur
       atau kode utama lainnya.
       ========================================================== */
    @media (min-width: 768px) {
      html {
        scroll-behavior: smooth;
        scroll-padding-top: 88px;
      }

      #tentang,
      #kawasan,
      #layanan,
      #kontak {
        scroll-margin-top: 88px;
      }

      /* Tentang dibuat mengikuti tampilan pada screenshot */
      #tentang {
        padding-top: 68px !important;
        padding-bottom: 68px !important;
        display: flex;
        align-items: center;
      }

      #tentang > .container {
        width: 100%;
      }

      #tentang .row {
        align-items: center !important;
      }

      #tentang h6 {
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
      }

      #tentang h2 {
        font-size: 2.55rem;
        line-height: 1.12;
        margin-bottom: 38px !important;
      }

      #tentang p {
        font-size: 1.08rem;
        line-height: 1.62;
      }

      #tentang .p-5 {
        padding: 42px !important;
        border-radius: 24px !important;
      }

      #tentang .p-5 h4 {
        font-size: 1.55rem;
        margin-bottom: 24px !important;
      }

      #tentang .p-5 p {
        font-size: 1.08rem;
        line-height: 1.65;
      }

      /* Section lain tetap menggunakan layout asli.
         Hanya posisi scroll yang diberi jarak dari navbar. */
      #kawasan > .container,
      #layanan > .container,
      #kontak > .container {
        width: 100%;
      }
    }

    /* ==========================================================
       RESPONSIVE MOBILE PATCH
       Hanya aktif pada layar <= 767.98px.
       Tampilan laptop/desktop tidak diubah.
       ========================================================== */

    @media (max-width: 767.98px) {

      /* ---------- GLOBAL ---------- */
      html {
        width: 100%;
        overflow-x: hidden;
        scroll-behavior: smooth;
      }

      body {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        -webkit-text-size-adjust: 100%;
      }

      .container {
        width: 100%;
        max-width: 100%;
        padding-left: 18px;
        padding-right: 18px;
      }

      .row {
        --bs-gutter-x: 1.25rem;
      }


      /* ========================================================
         LANDING / WELCOME SCREEN
         ======================================================== */
      #welcomeSection {
        width: 100%;
        height: 100dvh;
        min-height: 600px;
        padding: 22px 18px 28px;
        justify-content: center;
        overflow: hidden;
        background-position: center center;
      }

      #welcomeSection .bg-overlay {
        width: 100%;
        height: 100%;
      }

      .welcome-top-center {
        position: absolute;
        top: max(22px, env(safe-area-inset-top));
        left: 18px;
        right: 18px;
        width: auto;
      }

      .welcome-logos-row {
        width: 100%;
        gap: 10px;
        margin-bottom: 9px;
        justify-content: center;
        flex-wrap: nowrap !important;
      }

      .logo-badge-item {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
      }

      .welcome-gov-title {
        font-size: 0.70rem;
        line-height: 1.3;
        letter-spacing: 1.1px;
      }

      .welcome-jawa-script {
        font-size: 0.68rem;
        line-height: 1.4;
        letter-spacing: 1px;
      }

      .welcome-center {
        width: 100%;
        max-width: 100%;
        margin: 105px 0 0;
        text-align: left;
      }

      .welcome-title {
        font-size: clamp(2.05rem, 12vw, 3rem);
        line-height: 0.98;
        letter-spacing: 1px;
        margin-bottom: 14px;
        overflow-wrap: anywhere;
      }

      .welcome-desc {
        max-width: 100%;
        font-size: 0.80rem;
        line-height: 1.6;
        margin-bottom: 20px;
      }

      .welcome-center .d-flex {
        width: 100%;
      }

      .btn-masuk-situs {
        width: 100%;
        min-height: 46px;
        padding: 12px 18px;
        font-size: 0.78rem;
      }

      .welcome-preview-container {
        display: none !important;
      }

      /* ========================================================
         NAVBAR
         ======================================================== */
      #mainWebsiteContent .navbar {
        min-height: 58px;
        padding-top: 7px;
        padding-bottom: 7px;
      }

      #mainWebsiteContent .navbar > .container {
        padding-left: 15px;
        padding-right: 15px;
      }

      .navbar-brand {
        max-width: calc(100% - 55px);
        margin-right: 0;
        font-size: 0.82rem;
        letter-spacing: 0.2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .navbar-brand i {
        margin-right: 5px !important;
      }

      .navbar-toggler {
        padding: 5px 8px;
        border-radius: 8px;
        font-size: 0.9rem;
      }

      .navbar-collapse {
        margin-top: 7px;
        padding: 8px 5px 10px;
        background: rgba(255,255,255,0.98);
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.10);
      }

      .navbar-nav {
        width: 100%;
        align-items: stretch !important;
      }

      .navbar-nav .nav-item {
        width: 100%;
      }

      .navbar-nav .nav-link {
        width: 100%;
        padding: 9px 10px;
        font-size: 0.85rem;
        border-radius: 8px;
      }

      .navbar-nav .nav-link:hover,
      .navbar-nav .nav-link.active {
        background: #f1f5f9;
      }

      .navbar-nav .nav-item.ms-lg-3 {
        margin-left: 0 !important;
        margin-top: 7px !important;
      }

      .navbar-nav .nav-item.ms-lg-3 .btn {
        width: 100%;
      }

      /* ========================================================
         HERO / BERANDA
         ======================================================== */
      .hero-observatorium {
        min-height: 100dvh;
        height: auto !important;
        padding: 105px 0 55px !important;
        background-position: center center !important;
        justify-content: center;
      }

      .hero-observatorium .container {
        width: 100%;
      }

      .hero-observatorium .row {
        min-height: calc(100dvh - 160px);
        align-items: center !important;
      }

      .hero-observatorium .col-lg-8 {
        width: 100%;
      }

      .hero-observatorium .badge {
        max-width: 100%;
        white-space: normal;
        text-align: left;
        line-height: 1.35;
        font-size: 0.68rem;
      }

      .hero-observatorium h1,
      .hero-observatorium .display-4 {
        font-size: clamp(2rem, 10vw, 2.65rem);
        line-height: 1.12;
        margin-bottom: 16px !important;
      }

      .hero-observatorium p.lead {
        max-width: 100% !important;
        font-size: 0.88rem;
        line-height: 1.65;
      }

      .hero-observatorium .d-flex {
        width: 100%;
        flex-direction: column;
        gap: 10px !important;
      }

      .hero-observatorium .d-flex a {
        width: 100%;
        min-height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 11px 16px !important;
      }

      /* ========================================================
         SECTION UMUM
         ======================================================== */
      #mainWebsiteContent section.py-5 {
        padding-top: 60px !important;
        padding-bottom: 60px !important;
      }

      #mainWebsiteContent section[id="tentang"],
      #mainWebsiteContent section[id="layanan"],
      #mainWebsiteContent section[id="kontak"] {
        padding-top: 65px !important;
        padding-bottom: 65px !important;
      }

      #mainWebsiteContent section .text-center.mx-auto {
        max-width: 100% !important;
      }

      #mainWebsiteContent section h2 {
        font-size: 1.65rem;
        line-height: 1.25;
      }

      #mainWebsiteContent section h6 {
        font-size: 0.72rem;
        letter-spacing: 1px;
      }

      /* ========================================================
         TENTANG
         ======================================================== */
      #tentang .row {
        --bs-gutter-y: 2rem;
      }

      #tentang h2 {
        margin-bottom: 18px !important;
      }

      #tentang p {
        font-size: 0.88rem;
        line-height: 1.7;
      }

      #tentang .p-5 {
        padding: 23px !important;
      }

      #tentang .p-5 h4 {
        font-size: 1.08rem;
        line-height: 1.35;
      }

      #tentang .p-5 p {
        font-size: 0.84rem;
      }

      #tentang .col-sm-6 {
        width: 100%;
      }

      /* ========================================================
         KAWASAN
         ======================================================== */
      #kawasan {
        overflow: hidden;
      }

      #kawasan .container.py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
      }

      #kawasan .row {
        --bs-gutter-y: 1rem;
      }

      #kawasan .card {
        width: 100%;
        height: 300px !important;
        min-height: 300px;
        padding: 20px !important;
        border-radius: 18px !important;
      }

      #kawasan .card > .d-flex {
        width: 100%;
        gap: 8px;
        align-items: flex-start !important;
      }

      #kawasan .card .badge {
        max-width: 62%;
        white-space: normal;
        line-height: 1.25;
        font-size: 0.62rem !important;
        padding: 6px 8px !important;
      }

      #kawasan .card .badge:first-child {
        max-width: 32%;
      }

      #kawasan .card h3 {
        font-size: 1.30rem;
        line-height: 1.2;
      }

      #kawasan .card p {
        font-size: 0.76rem;
        line-height: 1.5;
      }

      /* ========================================================
         LAYANAN
         ======================================================== */
      #layanan .row {
        --bs-gutter-y: 1rem;
      }

      #layanan .card {
        padding: 22px !important;
        border-radius: 17px !important;
      }

      #layanan .card .fs-2 {
        font-size: 1.8rem !important;
      }

      #layanan .card h4 {
        font-size: 1.08rem;
      }

      #layanan .card p {
        font-size: 0.84rem;
        line-height: 1.65;
      }
      /* ========================================================
         KONTAK
         ======================================================== */
      #kontak .row {
        --bs-gutter-y: 1rem;
      }

      #kontak .p-4 {
        padding: 20px !important;
      }

      #kontak h4 {
        font-size: 1.10rem;
      }

      #kontak .form-control {
        min-height: 44px;
        font-size: 0.86rem;
      }

      #kontak textarea.form-control {
        min-height: 110px;
      }

      #kontak .col-lg-6:last-child > div {
        min-height: 300px !important;
        height: 300px !important;
      }

      #kontak iframe {
        width: 100%;
        height: 100%;
        min-height: 300px;
      }

      /* ========================================================
         FOOTER
         ======================================================== */
      #mainWebsiteContent footer {
        padding: 20px 15px !important;
      }

      #mainWebsiteContent footer p {
        font-size: 0.68rem !important;
        line-height: 1.6;
      }

      /* ========================================================
         CHATBOT
         ======================================================== */
      .chat-float-btn {
        width: 52px;
        height: 52px;
        right: 14px;
        bottom: max(14px, env(safe-area-inset-bottom));
        font-size: 1.3rem;
        z-index: 1100;
      }

      .chat-window {
        width: calc(100vw - 20px);
        max-width: calc(100vw - 20px);
        height: min(600px, calc(100dvh - 90px));
        max-height: calc(100dvh - 90px);
        right: 10px;
        bottom: 76px;
        border-radius: 16px;
      }

      .tg-chat-header {
        min-height: 52px;
        padding: 8px 11px;
      }

      .tg-avatar {
        width: 36px;
        height: 36px;
        flex: 0 0 36px;
      }

      .tg-title {
        font-size: 0.83rem;
      }

      .tg-status {
        font-size: 0.63rem;
      }

      .tg-chat-header .fa-xmark {
        font-size: 1.1rem !important;
      }

      .tg-chat-body {
        padding: 11px;
        gap: 9px;
      }

      .tg-msg {
        max-width: 88%;
        font-size: 0.78rem;
        line-height: 1.5;
        padding: 8px 10px 20px 10px;
      }

      .tg-time {
        font-size: 0.60rem;
        bottom: 3px;
        right: 7px;
      }

      .tg-keyboard-panel {
        max-height: 48%;
        overflow-y: auto;
        padding: 9px;
      }

      .tg-keyboard-panel .mb-2 {
        margin-bottom: 7px !important;
      }

      .tg-inline-keyboard {
        gap: 5px;
      }

      .tg-btn-inline {
        min-height: 38px;
        padding: 7px 8px;
        font-size: 0.70rem;
        line-height: 1.25;
      }

      .tg-chat-footer {
        padding: 8px 9px;
      }

      .tg-chat-footer > .d-flex {
        width: 100%;
        gap: 6px !important;
      }

      .tg-btn-cmd {
        flex: 1 1 0;
        min-height: 36px;
        padding: 6px 7px;
        font-size: 0.68rem;
        white-space: nowrap;
      }

      /* ---------- SMALL PHONES ---------- */
      @media (max-width: 380px) {

        #welcomeSection {
          min-height: 560px;
          padding-left: 15px;
          padding-right: 15px;
        }

        .welcome-top-center {
          left: 15px;
          right: 15px;
        }

        .welcome-logos-row {
          gap: 7px;
        }

        .logo-badge-item {
          width: 34px;
          height: 34px;
          flex-basis: 34px;
        }

        .welcome-gov-title {
          font-size: 0.64rem;
        }

        .welcome-jawa-script {
          font-size: 0.60rem;
        }

        .welcome-center {
          margin-top: 92px;
        }

        .welcome-title {
          font-size: 1.95rem;
        }

        .welcome-desc {
          font-size: 0.76rem;
        }

        .hero-observatorium h1,
        .hero-observatorium .display-4 {
          font-size: 1.90rem;
        }

        #kawasan .card {
          height: 285px !important;
          min-height: 285px;
        }

        .chat-window {
          width: calc(100vw - 14px);
          max-width: calc(100vw - 14px);
          right: 7px;
          bottom: 72px;
        }

        .tg-msg {
          max-width: 92%;
          font-size: 0.75rem;
        }
      }

      /* ---------- LANDSCAPE PHONE ---------- */
      @media (max-width: 767.98px) and (orientation: landscape) {

        #welcomeSection {
          min-height: 480px;
          height: 100dvh;
          padding-top: 15px;
          padding-bottom: 15px;
        }

        .welcome-top-center {
          top: 12px;
        }

        .welcome-center {
          margin-top: 65px;
          max-width: 650px;
        }

        .welcome-title {
          font-size: 2rem;
          margin-bottom: 8px;
        }

        .welcome-desc {
          font-size: 0.72rem;
          line-height: 1.4;
          margin-bottom: 10px;
        }

        .btn-masuk-situs {
          min-height: 38px;
          padding: 8px 16px;
        }

        .hero-observatorium {
          padding-top: 80px !important;
          padding-bottom: 35px !important;
        }

        .hero-observatorium .row {
          min-height: auto;
        }

        .hero-observatorium h1,
        .hero-observatorium .display-4 {
          font-size: 2rem;
        }

        .hero-observatorium p.lead {
          font-size: 0.78rem;
        }

        .hero-observatorium .d-flex {
          flex-direction: row;
        }

        .hero-observatorium .d-flex a {
          width: auto;
          flex: 1;
        }

        .chat-window {
          height: calc(100dvh - 80px);
          max-height: calc(100dvh - 80px);
        }
      }
    }

    /* ==========================================================
       MOBILE FINAL POLISH
       Hanya berlaku pada layar HP (maks. 767.98px).
       Tidak mengubah tampilan laptop/desktop.
       ========================================================== */
    @media (max-width: 767.98px) {
      html, body {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden !important;
      }

      body {
        font-size: 15px;
      }

      #mainWebsiteContent {
        width: 100%;
        overflow-x: hidden;
      }

      /* ---------- NAVBAR ---------- */
      #mainWebsiteContent .navbar {
        min-height: 60px;
        padding: 8px 0;
      }

      #mainWebsiteContent .navbar > .container {
        width: 100%;
        max-width: none;
        padding-left: 14px;
        padding-right: 14px;
      }

      #mainWebsiteContent .navbar-brand {
        display: flex;
        align-items: center;
        min-width: 0;
        max-width: calc(100% - 52px);
        margin-right: 0;
        font-size: 0.88rem;
        font-weight: 700;
      }

      #mainWebsiteContent .navbar-brand i {
        flex: 0 0 auto;
        font-size: 1rem;
      }

      #mainWebsiteContent .navbar-toggler {
        flex: 0 0 auto;
        border: 1px solid rgba(0,0,0,.14);
        padding: 6px 9px;
        border-radius: 9px;
        box-shadow: none !important;
      }

      #mainWebsiteContent .navbar-collapse {
        margin-top: 8px;
        padding: 7px;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        box-shadow: 0 10px 28px rgba(0,0,0,.10);
      }

      #mainWebsiteContent .navbar-nav {
        gap: 2px;
      }

      #mainWebsiteContent .navbar-nav .nav-link {
        display: flex;
        align-items: center;
        min-height: 42px;
        padding: 9px 12px;
        border-radius: 9px;
        font-size: 0.88rem;
        font-weight: 500;
      }

      #mainWebsiteContent .navbar-nav .nav-link.active {
        color: #0d6efd !important;
        background: #eef5ff;
        font-weight: 700;
      }

      #mainWebsiteContent .navbar-nav .nav-item.ms-lg-3 {
        margin-top: 7px !important;
        padding-top: 7px;
        border-top: 1px solid #edf0f2;
      }

      #mainWebsiteContent .navbar-nav .nav-item.ms-lg-3 .btn {
        min-height: 42px;
        width: 100%;
        font-size: 0.86rem;
      }

      /* ---------- SEMUA SECTION ---------- */
      #mainWebsiteContent section {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        scroll-margin-top: 70px;
      }

      #mainWebsiteContent section > .container {
        width: 100%;
        max-width: 100%;
        padding-left: 16px;
        padding-right: 16px;
      }

      #mainWebsiteContent section.py-5,
      #mainWebsiteContent section[id="tentang"],
      #mainWebsiteContent section[id="kawasan"],
      #mainWebsiteContent section[id="layanan"],
      #mainWebsiteContent section[id="kontak"] {
        padding-top: 58px !important;
        padding-bottom: 58px !important;
      }

      #mainWebsiteContent section .mb-5 {
        margin-bottom: 30px !important;
      }

      #mainWebsiteContent section h6 {
        margin-bottom: 9px;
        font-size: 0.70rem;
        line-height: 1.4;
        letter-spacing: 1px;
      }

      #mainWebsiteContent section h2 {
        margin-bottom: 13px !important;
        font-size: clamp(1.45rem, 7vw, 1.85rem);
        line-height: 1.22;
      }

      #mainWebsiteContent section p {
        font-size: 0.88rem;
        line-height: 1.7;
      }

      /* ---------- BERANDA ---------- */
      #mainWebsiteContent .hero-observatorium {
        width: 100%;
        min-height: 100svh;
        height: auto !important;
        padding: 92px 0 42px !important;
        display: flex;
        align-items: center;
        background-position: center center !important;
      }

      #mainWebsiteContent .hero-observatorium > .container {
        padding-left: 18px;
        padding-right: 18px;
      }

      #mainWebsiteContent .hero-observatorium .row {
        min-height: 0;
        width: 100%;
      }

      #mainWebsiteContent .hero-observatorium .badge {
        display: inline-block;
        max-width: 100%;
        padding: 7px 11px !important;
        font-size: 0.67rem;
        line-height: 1.35;
        white-space: normal;
      }

      #mainWebsiteContent .hero-observatorium h1,
      #mainWebsiteContent .hero-observatorium .display-4 {
        max-width: 100%;
        margin-bottom: 14px !important;
        font-size: clamp(1.9rem, 10vw, 2.55rem);
        line-height: 1.1;
        overflow-wrap: anywhere;
      }

      #mainWebsiteContent .hero-observatorium p.lead {
        max-width: 100% !important;
        margin-bottom: 21px !important;
        font-size: 0.88rem;
        line-height: 1.65;
      }

      #mainWebsiteContent .hero-observatorium .d-flex {
        width: 100%;
        flex-direction: column;
        gap: 9px !important;
      }

      #mainWebsiteContent .hero-observatorium .d-flex a {
        width: 100%;
        min-height: 45px;
        padding: 10px 14px !important;
        font-size: 0.84rem;
      }

      /* ---------- TENTANG ---------- */
      #mainWebsiteContent #tentang .row {
        --bs-gutter-x: 0;
        --bs-gutter-y: 28px;
      }

      #mainWebsiteContent #tentang h2 {
        margin-bottom: 17px !important;
      }

      #mainWebsiteContent #tentang p {
        margin-bottom: 14px;
        font-size: 0.88rem;
        line-height: 1.72;
      }

      #mainWebsiteContent #tentang .col-sm-6 {
        width: 100%;
      }

      #mainWebsiteContent #tentang .p-5 {
        width: 100%;
        padding: 21px !important;
        border-radius: 17px !important;
      }

      #mainWebsiteContent #tentang .p-5 h4 {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 10px !important;
        font-size: 1.08rem;
        line-height: 1.35;
      }

      #mainWebsiteContent #tentang .p-5 p {
        font-size: 0.84rem;
        line-height: 1.65;
      }

      /* ---------- KAWASAN ---------- */
      #mainWebsiteContent #kawasan .container.py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
      }

      #mainWebsiteContent #kawasan .text-center.mx-auto {
        margin-bottom: 25px !important;
      }

      #mainWebsiteContent #kawasan .row {
        --bs-gutter-x: 0;
        --bs-gutter-y: 15px;
      }

      #mainWebsiteContent #kawasan .col-md-4 {
        width: 100%;
      }

      #mainWebsiteContent #kawasan .card {
        width: 100%;
        height: 275px !important;
        min-height: 275px;
        padding: 18px !important;
        border-radius: 17px !important;
      }

      #mainWebsiteContent #kawasan .card > .d-flex {
        align-items: flex-start !important;
        gap: 7px;
      }

      #mainWebsiteContent #kawasan .card .badge {
        max-width: 67%;
        padding: 6px 8px !important;
        font-size: 0.59rem !important;
        line-height: 1.25;
        white-space: normal;
        text-align: center;
      }

      #mainWebsiteContent #kawasan .card .badge:first-child {
        max-width: 31%;
      }

      #mainWebsiteContent #kawasan .card h3 {
        margin-bottom: 7px !important;
        font-size: 1.18rem;
        line-height: 1.2;
      }

      #mainWebsiteContent #kawasan .card p {
        font-size: 0.75rem;
        line-height: 1.5;
      }

      /* ---------- LAYANAN ---------- */
      #mainWebsiteContent #layanan .row {
        --bs-gutter-x: 0;
        --bs-gutter-y: 14px;
      }

      #mainWebsiteContent #layanan .col-md-4 {
        width: 100%;
      }

      #mainWebsiteContent #layanan .card {
        width: 100%;
        padding: 20px !important;
        border-radius: 17px !important;
      }

      #mainWebsiteContent #layanan .card .fs-2 {
        margin-bottom: 10px !important;
        font-size: 1.7rem !important;
      }

      #mainWebsiteContent #layanan .card h4 {
        margin-bottom: 9px !important;
        font-size: 1.04rem;
        line-height: 1.3;
      }

      #mainWebsiteContent #layanan .card p {
        font-size: 0.82rem;
        line-height: 1.62;
      }

      /* ---------- KONTAK ---------- */
      #mainWebsiteContent #kontak .row {
        --bs-gutter-x: 0;
        --bs-gutter-y: 15px;
      }

      #mainWebsiteContent #kontak .col-lg-6 {
        width: 100%;
      }

      #mainWebsiteContent #kontak .p-4 {
        padding: 19px !important;
        border-radius: 16px !important;
      }

      #mainWebsiteContent #kontak h4 {
        margin-bottom: 17px !important;
        font-size: 1.05rem;
      }

      #mainWebsiteContent #kontak .form-control {
        min-height: 43px;
        padding: 9px 11px;
        font-size: 0.83rem;
      }

      #mainWebsiteContent #kontak textarea.form-control {
        min-height: 105px;
      }

      #mainWebsiteContent #kontak button[type="submit"] {
        min-height: 43px;
        font-size: 0.84rem;
      }

      #mainWebsiteContent #kontak .col-lg-6:last-child > div {
        width: 100%;
        min-height: 270px !important;
        height: 270px !important;
        border-radius: 16px !important;
      }

      #mainWebsiteContent #kontak iframe {
        width: 100%;
        height: 100%;
        min-height: 270px;
      }

      /* ---------- FOOTER ---------- */
      #mainWebsiteContent footer {
        padding: 18px 14px !important;
      }

      #mainWebsiteContent footer p {
        margin-bottom: 0;
        font-size: 0.66rem !important;
        line-height: 1.55;
      }

      /* ---------- CHATBOT ---------- */
      #mainWebsiteContent .chat-float-btn {
        width: 52px;
        height: 52px;
        right: 13px;
        bottom: calc(13px + env(safe-area-inset-bottom));
        font-size: 1.25rem;
      }

      #mainWebsiteContent .chat-window {
        width: calc(100vw - 18px);
        max-width: calc(100vw - 18px);
        height: min(600px, calc(100dvh - 88px));
        max-height: calc(100dvh - 88px);
        right: 9px;
        bottom: calc(72px + env(safe-area-inset-bottom));
        border-radius: 16px;
      }
    }

    /* HP sangat kecil */
    @media (max-width: 380px) {
      #mainWebsiteContent .navbar-brand {
        font-size: 0.80rem;
      }

      #mainWebsiteContent .hero-observatorium {
        padding-top: 88px !important;
      }

      #mainWebsiteContent .hero-observatorium h1,
      #mainWebsiteContent .hero-observatorium .display-4 {
        font-size: 1.82rem;
      }

      #mainWebsiteContent #kawasan .card {
        height: 260px !important;
        min-height: 260px;
      }
    }

    /* ==========================================================
       FINAL NAVIGATION ALIGNMENT
       Posisi section saat menu diklik diatur oleh JavaScript agar
       bagian atas section benar-benar berada tepat di bawah navbar.
       ========================================================== */
    @media (min-width: 768px) {
      html {
        scroll-behavior: smooth;
      }

      /* Jangan memberi offset tambahan pada anchor browser.
         Offset dihitung langsung dari tinggi navbar oleh JavaScript. */
      #mainWebsiteContent section[id] {
        scroll-margin-top: 0 !important;
      }

      #mainWebsiteContent #kawasan,
      #mainWebsiteContent #layanan,
      #mainWebsiteContent #kontak {
        display: block !important;
        min-height: 0 !important;
      }

      /* Tentang mengikuti posisi normal section, bukan dipusatkan
         secara vertikal ketika anchor dibuka. */
      #mainWebsiteContent #tentang {
        display: block !important;
        min-height: 0 !important;
        padding-top: 24px !important;
      }
    }

    @media (max-width: 767.98px) {
      html {
        scroll-behavior: smooth;
      }

      #mainWebsiteContent section[id] {
        scroll-margin-top: 0 !important;
      }

      #mainWebsiteContent #kawasan,
      #mainWebsiteContent #layanan,
      #mainWebsiteContent #kontak {
        min-height: 0 !important;
      }
    }


    /* ==========================================================
       MOBILE LANDING HEADER - LOGO & IDENTITAS DI ATAS/TENGAH
       Hanya berlaku untuk layar HP. Desktop/laptop tidak berubah.
       ========================================================== */
    @media (max-width: 767.98px) {
      #welcomeSection {
        position: fixed;
        inset: 0;
        height: 100dvh;
        min-height: 600px;
        padding: 18px 18px 28px;
        justify-content: center;
      }

      /* Kelompok logo + Pemerintah Kota Tegal + aksara Jawa
         selalu berada di bagian atas dan benar-benar di tengah. */
      #welcomeSection .welcome-top-center {
        position: absolute;
        top: max(18px, env(safe-area-inset-top));
        left: 50%;
        right: auto;
        width: min(92%, 430px);
        transform: translateX(-50%);
        margin: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        text-align: center;
        z-index: 5;
      }

      #welcomeSection .welcome-logos-row {
        width: 100%;
        display: flex !important;
        align-items: center;
        justify-content: center;
        gap: clamp(8px, 3vw, 13px);
        margin: 0 0 8px;
        padding: 0;
        flex-wrap: nowrap !important;
      }

      #welcomeSection .logo-badge-item {
        width: clamp(34px, 10vw, 40px);
        height: clamp(34px, 10vw, 40px);
        flex: 0 0 clamp(34px, 10vw, 40px);
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      #welcomeSection .logo-badge-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
      }

      #welcomeSection .welcome-gov-title {
        width: 100%;
        margin: 0 0 2px;
        font-size: clamp(0.68rem, 2.8vw, 0.82rem);
        line-height: 1.25;
        letter-spacing: clamp(0.8px, 0.5vw, 1.3px);
        text-align: center;
        white-space: nowrap;
      }

      #welcomeSection .welcome-jawa-script {
        width: 100%;
        margin: 0;
        font-size: clamp(0.60rem, 2.5vw, 0.72rem);
        line-height: 1.35;
        letter-spacing: 0.8px;
        text-align: center;
        white-space: nowrap;
      }

      /* Konten utama diturunkan sedikit agar tidak bertabrakan
         dengan identitas di bagian atas. */
      #welcomeSection .welcome-center {
        width: 100%;
        max-width: 650px;
        margin: 105px auto 0;
        position: relative;
        z-index: 3;
      }
    }

    @media (max-width: 380px) {
      #welcomeSection .welcome-top-center {
        top: max(15px, env(safe-area-inset-top));
        width: 94%;
      }

      #welcomeSection .welcome-logos-row {
        gap: 7px;
        margin-bottom: 6px;
      }

      #welcomeSection .logo-badge-item {
        width: 34px;
        height: 34px;
        flex-basis: 34px;
      }

      #welcomeSection .welcome-gov-title {
        font-size: 0.64rem;
        letter-spacing: 0.9px;
      }

      #welcomeSection .welcome-jawa-script {
        font-size: 0.58rem;
        letter-spacing: 0.7px;
      }

      #welcomeSection .welcome-center {
        margin-top: 92px;
      }
    }
    /* ==========================================================
       FINAL LOGO NORMALIZATION
       Menyamakan ukuran kotak logo sekaligus menyesuaikan ukuran
       visual masing-masing logo agar terlihat seimbang.
       ========================================================== */
    .welcome-logos-row {
      display: flex !important;
      flex-direction: row !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 20px !important;
      flex-wrap: nowrap !important;
    }

    .welcome-logos-row .logo-badge-item {
      width: 60px !important;
      height: 60px !important;
      flex: 0 0 60px !important;
      padding: 0 !important;
      margin: 0 !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      background: transparent !important;
      border: none !important;
    }

    .welcome-logos-row .logo-badge-item img {
      display: block !important;
      object-fit: contain !important;
      object-position: center !important;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
      margin: auto !important;
    }

    /* Ukuran visual desktop disesuaikan berdasarkan bentuk logo */
    .welcome-logos-row .logo-badge-item:nth-child(1) img {
      width: 56px !important;
      height: 56px !important;
    }

    .welcome-logos-row .logo-badge-item:nth-child(2) img {
      width: 52px !important;
      height: 56px !important;
    }

    .welcome-logos-row .logo-badge-item:nth-child(3) img {
      width: 60px !important;
      height: 42px !important;
    }

    .welcome-logos-row .logo-badge-item:nth-child(4) img {
      width: 56px !important;
      height: 56px !important;
    }

    /* ---------- MOBILE ---------- */
    @media (max-width: 767.98px) {
      #welcomeSection .welcome-logos-row {
        width: 100% !important;
        gap: clamp(8px, 3vw, 13px) !important;
        margin-bottom: 8px !important;
        padding: 0 !important;
        justify-content: center !important;
        flex-wrap: nowrap !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item {
        width: 44px !important;
        height: 44px !important;
        flex: 0 0 44px !important;
        padding: 0 !important;
        margin: 0 !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item:nth-child(1) img {
        width: 42px !important;
        height: 42px !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item:nth-child(2) img {
        width: 39px !important;
        height: 42px !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item:nth-child(3) img {
        width: 44px !important;
        height: 31px !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item:nth-child(4) img {
        width: 42px !important;
        height: 42px !important;
      }
    }

    @media (max-width: 380px) {
      #welcomeSection .welcome-logos-row {
        gap: 7px !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item {
        width: 38px !important;
        height: 38px !important;
        flex: 0 0 38px !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item:nth-child(1) img {
        width: 36px !important;
        height: 36px !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item:nth-child(2) img {
        width: 34px !important;
        height: 36px !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item:nth-child(3) img {
        width: 38px !important;
        height: 27px !important;
      }

      #welcomeSection .welcome-logos-row .logo-badge-item:nth-child(4) img {
        width: 36px !important;
        height: 36px !important;
      }
    }


    /* =========================================================
       FINAL LOGO STYLE - BULATAN PUTIH SEPERTI REFERENSI
       ========================================================= */
    #welcomeSection .welcome-logos-row {
      display: flex !important;
      flex-direction: row !important;
      align-items: center;
      justify-content: center;
      gap: 12px;
      flex-wrap: nowrap !important;
    }

    #welcomeSection .logo-badge-item {
      width: 58px !important;
      height: 58px !important;
      min-width: 58px !important;
      min-height: 58px !important;
      flex: 0 0 58px !important;
      display: flex !important;
      align-items: center;
      justify-content: center;
      padding: 6px !important;
      margin: 0 !important;
      background: #ffffff !important;
      border: 2px solid rgba(255, 255, 255, 0.95) !important;
      border-radius: 50% !important;
      box-sizing: border-box;
      overflow: hidden;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.28);
    }

    #welcomeSection .logo-badge-item img {
      display: block;
      width: 100% !important;
      height: 100% !important;
      max-width: 100%;
      max-height: 100%;
      object-fit: contain !important;
      filter: none !important;
    }

    #welcomeSection .logo-badge-item:nth-child(2) img {
      width: 92% !important;
      height: 92% !important;
    }

    @media (max-width: 767.98px) {
      #welcomeSection .welcome-logos-row {
        gap: 8px !important;
      }

      #welcomeSection .logo-badge-item {
        width: 46px !important;
        height: 46px !important;
        min-width: 46px !important;
        min-height: 46px !important;
        flex-basis: 46px !important;
        padding: 5px !important;
        border-width: 1.5px !important;
      }
    }

    @media (max-width: 380px) {
      #welcomeSection .welcome-logos-row {
        gap: 6px !important;
      }

      #welcomeSection .logo-badge-item {
        width: 42px !important;
        height: 42px !important;
        min-width: 42px !important;
        min-height: 42px !important;
        flex-basis: 42px !important;
        padding: 4px !important;
      }
    }


    /* FINAL LOGO NORMALIZATION - urutan dan ukuran mengikuti gambar referensi */
    .welcome-logos-row {
      display:flex !important;
      flex-direction:row !important;
      align-items:center !important;
      justify-content:center !important;
      gap:18px !important;
      flex-wrap:nowrap !important;
    }
    .welcome-logos-row .logo-badge-item {
      width:64px !important;
      height:64px !important;
      flex:0 0 64px !important;
      padding:0 !important;
      margin:0 !important;
      display:flex !important;
      align-items:center !important;
      justify-content:center !important;
      background:transparent !important;
      border:0 !important;
      border-radius:0 !important;
      overflow:visible !important;
    }
    .welcome-logos-row .logo-badge-item img {
      display:block !important;
      max-width:none !important;
      max-height:none !important;
      object-fit:contain !important;
      object-position:center !important;
      margin:0 !important;
      padding:0 !important;
      filter:drop-shadow(0 2px 3px rgba(0,0,0,.25)) !important;
    }
    /* Semua area logo sama 64x64; rasio sumber dinormalisasi agar tinggi visual seragam. */
    .welcome-logos-row .logo-badge-item:nth-child(1) img { width:62px !important; height:62px !important; }
    .welcome-logos-row .logo-badge-item:nth-child(2) img { width:64px !important; height:54px !important; }
    .welcome-logos-row .logo-badge-item:nth-child(3) img { width:60px !important; height:60px !important; }
    .welcome-logos-row .logo-badge-item:nth-child(4) img { width:58px !important; height:64px !important; }

    @media (max-width:767.98px) {
      .welcome-logos-row { gap:9px !important; }
      .welcome-logos-row .logo-badge-item { width:48px !important; height:48px !important; flex:0 0 48px !important; }
      .welcome-logos-row .logo-badge-item:nth-child(1) img { width:46px !important; height:46px !important; }
      .welcome-logos-row .logo-badge-item:nth-child(2) img { width:48px !important; height:40px !important; }
      .welcome-logos-row .logo-badge-item:nth-child(3) img { width:45px !important; height:45px !important; }
      .welcome-logos-row .logo-badge-item:nth-child(4) img { width:43px !important; height:48px !important; }
    }
    @media (max-width:380px) {
      .welcome-logos-row { gap:6px !important; }
      .welcome-logos-row .logo-badge-item { width:43px !important; height:43px !important; flex-basis:43px !important; }
      .welcome-logos-row .logo-badge-item:nth-child(1) img { width:41px !important; height:41px !important; }
      .welcome-logos-row .logo-badge-item:nth-child(2) img { width:43px !important; height:36px !important; }
      .welcome-logos-row .logo-badge-item:nth-child(3) img { width:40px !important; height:40px !important; }
      .welcome-logos-row .logo-badge-item:nth-child(4) img { width:39px !important; height:43px !important; }
    }

</style>
</head>
<body>

<!-- 1. HALAMAN DEPAN / LANDING SCREEN -->
  <section id="welcomeSection">
    <div class="bg-overlay"></div>
    
    <!-- Bagian Atas Tengah: 4 Logo (Menyamping Tanpa Kotak) & Aksara Jawa Putih -->
    <div class="welcome-top-center">
      <div class="welcome-logos-row">
        <div class="logo-badge-item" title="Kota Tegal">
          <img src="image/Kota-Tegal-logo.png" alt="Kota Tegal">
        </div>
        <div class="logo-badge-item" title="Doktor TJ">
          <img src="image/DDI.png" alt="Doktor TJ">
        </div>
        <div class="logo-badge-item" title="Smart City">
          <img src="image/logo_smartcity_rm.png" alt="Smart City">
        </div>
        <div class="logo-badge-item" title="UHN">
          <img src="image/uhn logo.png" alt="UHN">
        </div>
      </div>
      <div class="welcome-gov-title">Pemerintah Kota Tegal</div>
      <div class="welcome-jawa-script">ꦱ꧀ꦩꦂꦠ꧀ꦱꦶꦠꦶꦏꦺꦴꦠꦠꦼꦒꦭ꧀</div>
    </div>

    <!-- Bagian Tengah -->
    <div class="welcome-center">
      <h1 class="welcome-title">SMARTCITY KOTA TEGAL</h1>
      <p class="welcome-desc">Observatorium iklim mikro publik dan pemantauan kualitas udara real-time di kawasan Alun-Alun Kota Tegal berbasis sensor IoT ESP32 untuk mewujudkan kota cerdas yang berkelanjutan dan nyaman bagi warga.</p>
      
      <div class="d-flex align-items-center">
        <button class="btn-masuk-situs" onclick="enterWebsite()">MASUK SITUS &rarr;</button>
      </div>
    </div>

    <!-- Bagian Kanan Bawah: Preview Carousel Cards -->
    <div class="welcome-preview-container d-none d-md-flex">
      <div class="preview-nav-arrow" onclick="prevPreview()"><i class="fa-solid fa-chevron-left"></i></div>
      
      <div class="preview-card" style="background-image: url('image/alun-alun-tegal.jpeg');">
        <div class="preview-card-overlay">
          <span>Alun-Alun</span>
          <span class="text-info">32.0&deg;C</span>
        </div>
      </div>
      <div class="preview-card" style="background-image: url('image/balaikota.jpeg');">
        <div class="preview-card-overlay">
          <span>Balaikota</span>
          <span class="text-warning">33.5&deg;C</span>,
        </div>
      </div>
      <div class="preview-card" style="background-image: url('image/masjid agung.jpeg');">
        <div class="preview-card-overlay">
          <span>Masjid Agung</span>
          <span class="text-success">29.8&deg;C</span>
        </div>
      </div>

      <div class="preview-nav-arrow" onclick="nextPreview()"><i class="fa-solid fa-chevron-right"></i></div>
    </div>
  </section>


  <!-- ISI WEBSITE UTAMA (DASHBOARD) -->
  <div id="mainWebsiteContent">

    <!-- NAVBAR UTAMA -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top bg-white shadow-sm" style="z-index: 1050;">
      <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#beranda">
          <i class="fa-solid fa-city me-2"></i> TEGAL ECOSENSE
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav align-items-center">
            <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
            <li class="nav-item"><a class="nav-link" href="#kawasan">Kawasan</a></li>
            <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
            <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
              <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="returnToWelcome()">Keluar Situs</button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- 1. BERANDA (TAMPILAN ALUN-ALUN & HERO) -->
    <section class="hero-observatorium" id="beranda" style="height: 100vh; background-image: linear-gradient(rgba(11, 19, 41, 0.4), rgba(11, 19, 41, 0.75)), url('image/alun-alun-tegal.jpeg'); background-size: cover; background-position: center; display: flex; align-items: center; color: #fff; padding-top: 80px;">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <span class="badge bg-primary bg-opacity-25 text-info px-3 py-2 rounded-pill mb-3 fw-semibold">
              <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> Smart City Alun-Alun Kota Tegal
            </span>
            <h1 class="display-4 fw-bold mb-3">Membangun Kota Pintar untuk Masa Depan</h1>
            <p class="lead text-light opacity-75 mb-4" style="max-width: 650px;">
              Platform inovatif pemantauan iklim mikro publik, kualitas udara real-time, dan tata kelola kawasan berbasis sensor IoT di pusat Kota Tegal.
            </p>
            <div class="d-flex gap-3">
              <a href="#layanan" class="btn btn-primary px-4 py-3 rounded-pill fw-semibold shadow-sm">Jelajahi Layanan <i class="fa-solid fa-arrow-right ms-2"></i></a>
              <a href="#tentang" class="btn btn-outline-light px-4 py-3 rounded-pill fw-semibold">Pelajari Lebih Lanjut</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 2. TENTANG SMART CITY -->
    <section class="py-5 bg-white" id="tentang" style="padding-top: 100px; padding-bottom: 100px;">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-6">
            <h6 class="text-primary fw-bold text-uppercase tracking-wide">Tentang Inisiatif</h6>
            <h2 class="fw-bold mb-4">Apa itu Smart City & Tegal Ecosense?</h2>
            <p class="text-muted mb-3">Smart City adalah kerangka kerja penataan kota yang menggunakan teknologi informasi dan perangkat Internet of Things (IoT) untuk meningkatkan efisiensi pelayanan publik, mendukung kelestarian lingkungan, dan meningkatkan kenyamanan warga.</p>
            <p class="text-muted mb-4">Tegal Ecosense hadir sebagai wujud nyata implementasi kawasan pintar di area publik Alun-Alun Kota Tegal, mengintegrasikan pemantauan lingkungan secara transparan dan terukur.</p>
            <div class="row g-3">
              <div class="col-sm-6">
                <div class="d-flex align-items-center gap-2 fw-semibold text-dark">
                  <i class="fa-solid fa-check-circle text-success"></i> Real-time Monitoring
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-center gap-2 fw-semibold text-dark">
                  <i class="fa-solid fa-check-circle text-success"></i> Integrasi IoT ESP32
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="p-5 bg-light rounded-4 border">
              <h4 class="fw-bold mb-3"><i class="fa-solid fa-bullseye text-primary me-2"></i> Visi Utama</h4>
              <p class="text-muted mb-4">Mewujudkan Kota Tegal sebagai kota bahari yang cerdas, hijau, ramah lingkungan, serta berbasis data digital terpadu.</p>
              <h4 class="fw-bold mb-3"><i class="fa-solid fa-chart-line text-primary me-2"></i> Manfaat</h4>
              <p class="text-muted mb-0">Memberikan kemudahan akses informasi lingkungan publik bagi masyarakat serta membantu pengambil kebijakan dalam mengevaluasi tata kota.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. KAWASAN / ZONA PEMANTAUAN -->
    <section class="py-5" id="kawasan" style="background-color: #0b1329; color: #fff; position: relative; overflow: hidden;">
      <div class="container py-4">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
          <h6 class="text-info fw-bold text-uppercase" style="letter-spacing: 1.5px;">Titik Pantau IoT</h6>
          <h2 class="fw-bold text-white">Kawasan & Iklim Mikro Publik</h2>
          <p class="text-light opacity-75">Pemantauan kondisi suhu dan parameter lingkungan secara langsung di berbagai titik strategis Kota Tegal.</p>
        </div>

        <!-- Barisan Kartu Lokasi -->
        <div class="row g-4">
          <!-- Alun-Alun -->
          <div class="col-md-4">
            <div class="card border-0 rounded-4 overflow-h shadow-lg text-white" style="background: linear-gradient(rgba(11, 19, 41, 0.4), rgba(11, 19, 41, 0.85)), url('image/alun-alun-tegal.jpeg'); background-size: cover; background-position: center; height: 350px; display: flex; flex-direction: column; justify-content: space-between; padding: 25px;">
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-dark bg-opacity-75 border border-secondary px-3 py-2 rounded-pill text-info" style="font-size: 0.75rem;">BEACON</span>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">32.0&deg;C &bull; Hangat Terik</span>
              </div>
              <div>
                <span class="text-info fw-bold small" style="letter-spacing: 1px;">PLAZA SENTRAL</span>
                <h3 class="fw-bold text-white mt-1 mb-2">Alun-Alun Tegal</h3>
                <p class="text-light small opacity-75 mb-0">Area pusat interaksi publik dengan eksposur langsung radiasi matahari.</p>
              </div>
            </div>
          </div>

          <!-- Balaikota -->
          <div class="col-md-4">
            <div class="card border-0 rounded-4 overflow-h shadow-lg text-white" style="background: linear-gradient(rgba(11, 19, 41, 0.4), rgba(11, 19, 41, 0.85)), url('image/balaikota.jpeg'); background-size: cover; background-position: center; height: 350px; display: flex; flex-direction: column; justify-content: space-between; padding: 25px;">
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-dark bg-opacity-75 border border-secondary px-3 py-2 rounded-pill text-info" style="font-size: 0.75rem;">BEACON</span>
                <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">33.5&deg;C &bull; Koridor Utama</span>
              </div>
              <div>
                <span class="text-info fw-bold small" style="letter-spacing: 1px;">KANTOR PEMERINTAHAN</span>
                <h3 class="fw-bold text-white mt-1 mb-2">Balaikota Tegal</h3>
                <p class="text-light small opacity-75 mb-0">Gerbang utama kawasan perkantoran pemerintahan Kota Tegal.</p>
              </div>
            </div>
          </div>

          <!-- Masjid Agung -->
          <div class="col-md-4">
            <div class="card border-0 rounded-4 overflow-h shadow-lg text-white" style="background: linear-gradient(rgba(11, 19, 41, 0.4), rgba(11, 19, 41, 0.85)), url('image/masjid agung.jpeg'); background-size: cover; background-position: center; height: 350px; display: flex; flex-direction: column; justify-content: space-between; padding: 25px;">
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-dark bg-opacity-75 border border-secondary px-3 py-2 rounded-pill text-info" style="font-size: 0.75rem;">BEACON</span>
                <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">29.8&deg;C &bull; Sejuk Terlindung</span>
              </div>
              <div>
                <span class="text-info fw-bold small" style="letter-spacing: 1px;">RELIGI & PUBLIK</span>
                <h3 class="fw-bold text-white mt-1 mb-2">Masjid Agung</h3>
                <p class="text-light small opacity-75 mb-0">Kawasan lanskap arsitektur religius dengan naungan luas dan sejuk.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. LAYANAN SMART CITY (Smart Parking, Smart Lamp, Smart Temp & Humidity) -->
    <section class="py-5 bg-light" id="layanan" style="padding-top: 100px; padding-bottom: 100px;">
      <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
          <h6 class="text-primary fw-bold text-uppercase">Fitur Unggulan</h6>
          <h2 class="fw-bold">Layanan Smart City Kawasan</h2>
          <p class="text-muted">Berbagai fasilitas dan sistem terintegrasi yang dikembangkan untuk kawasan publik Alun-Alun Kota Tegal.</p>
        </div>
        <div class="row g-4">
          <!-- Layanan 1: Smart Parking -->
          <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
              <div class="mb-3 text-primary fs-2">
                <i class="fa-solid fa-square-parking"></i>
              </div>
              <h4 class="fw-bold mb-3">Smart Parking</h4>
              <p class="text-muted mb-0">Sistem pengelolaan dan pemantauan ketersediaan ruang parkir kendaraan di sekitar kawasan secara terpadu guna mengurangi kemacetan.</p>
            </div>
          </div>
          <!-- Layanan 2: Smart Lamp -->
          <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
              <div class="mb-3 text-primary fs-2">
                <i class="fa-solid fa-lightbulb"></i>
              </div>
              <h4 class="fw-bold mb-3">Smart Lamp</h4>
              <p class="text-muted mb-0">Penerangan jalan umum cerdas berbasis sensor otomatis yang menyesuaikan tingkat intensitas cahaya lingkungan secara efisien.</p>
            </div>
          </div>
          <!-- Layanan 3: Smart Temp & Humidity -->
          <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
              <div class="mb-3 text-primary fs-2">
                <i class="fa-solid fa-temperature-half"></i>
              </div>
              <h4 class="fw-bold mb-3">Smart Temp & Humidity</h4>
              <p class="text-muted mb-0">Pemantauan iklim mikro publik meliputi suhu, kelembapan udara, dan tingkat polusi secara real-time di titik strategis alun-alun.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. KONTAK & MAPS ALUN-ALUN TEGAL -->
    <section class="py-5 bg-white" id="kontak" style="padding-top: 100px; padding-bottom: 100px;">
      <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
          <h6 class="text-primary fw-bold text-uppercase">Hubungi Kami</h6>
          <h2 class="fw-bold">Lokasi & Kontak Pengelola</h2>
          <p class="text-muted">Silakan hubungi kami untuk informasi lebih lanjut seputar sistem pemantauan atau kunjungi lokasi perangkat.</p>
        </div>
        <div class="row g-4 align-items-stretch">
          <!-- Kolom Kiri: Formulir Kontak -->
          <div class="col-lg-6">
            <div class="p-4 bg-light rounded-4 border h-100 shadow-sm">
              <h4 class="fw-bold mb-4">Kirim Pesan</h4>
              <form>
                <div class="mb-3">
                  <label class="form-label small fw-semibold">Nama Lengkap</label>
                  <input type="text" class="form-control" placeholder="Masukkan nama Anda">
                </div>
                <div class="mb-3">
                  <label class="form-label small fw-semibold">Email / Kontak</label>
                  <input type="text" class="form-control" placeholder="email@domain.com">
                </div>
                <div class="mb-3">
                  <label class="form-label small fw-semibold">Pesan / Masukan</label>
                  <textarea class="form-control" rows="4" placeholder="Tuliskan pesan Anda..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Kirim Pesan</button>
              </form>
            </div>
          </div>
          <!-- Kolom Kanan: Google Maps Alun-Alun Tegal -->
          <div class="col-lg-6">
            <div class="h-100 rounded-4 overflow-hidden shadow-sm border" style="min-height: 400px;">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.9169623192087!2d109.134267!3d-6.868175!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fb86b3e6e7371%3A0xf0523456789!2sAlun-Alun%20Kota%20Tegal!5e0!3m2!1sid!2sid!4v1650000000000!5m2!1sid!2sid" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="text-white py-4" style="background-color: #0b1329;">
      <div class="container text-center">
        <p class="mb-0 text-muted small">&copy; 2026 Tegal Ecosense &bull; Pemerintah Kota Tegal. All Rights Reserved.</p>
      </div>
    </footer>
  </div>


  <!-- SCRIPT KONTROL & AUTO-SLIDE BACKGROUND -->
  <script>
    // =========================================================
    // NAVBAR ACTIVE MENU OTOMATIS
    // Menandai menu sesuai section yang sedang dilihat/dibuka.
    // =========================================================
    document.addEventListener('DOMContentLoaded', function () {
      const navLinks = document.querySelectorAll('#navbarNav .nav-link[href^="#"]');
      const sections = document.querySelectorAll('#mainWebsiteContent section[id]');

      function setActiveMenu(id) {
        navLinks.forEach(link => {
          link.classList.toggle('active', link.getAttribute('href') === '#' + id);
        });
      }

      // Saat menu diklik, arahkan section tepat di bawah navbar.
      // Tidak memakai anchor scroll bawaan browser karena posisi navbar
      // fixed dapat membuat section berhenti terlalu jauh dari atas.
      navLinks.forEach(link => {
        link.addEventListener('click', function (event) {
          const targetId = this.getAttribute('href').substring(1);
          const target = document.getElementById(targetId);
          const navbar = document.querySelector('#mainWebsiteContent .navbar');
          const navbarCollapse = document.getElementById('navbarNav');

          if (!target || !navbar) return;

          event.preventDefault();
          setActiveMenu(targetId);

          // Pada HP, navbar sedang terbuka ketika menu dipilih.
          // Jika tinggi navbar diukur sebelum dropdown ditutup, offset menjadi
          // terlalu besar sehingga section berhenti tidak tepat di bawah navbar.
          const isMobile = window.matchMedia('(max-width: 767.98px)').matches;
          const scrollToTarget = () => {
            const navbarHeight = navbar.getBoundingClientRect().height;
            const targetTop = target.getBoundingClientRect().top + window.pageYOffset;
            const scrollTop = Math.max(0, targetTop - navbarHeight);

            window.scrollTo({
              top: scrollTop,
              behavior: 'smooth'
            });
          };

          if (isMobile && navbarCollapse && navbarCollapse.classList.contains('show') && window.bootstrap) {
            const collapse = bootstrap.Collapse.getInstance(navbarCollapse) ||
                             new bootstrap.Collapse(navbarCollapse, { toggle: false });

            // Tutup dropdown terlebih dahulu, baru hitung posisi section.
            collapse.hide();

            // Tunggu transisi Bootstrap selesai agar tinggi navbar sudah kembali
            // ke tinggi normal (hanya header), lalu scroll tepat ke section.
            navbarCollapse.addEventListener('hidden.bs.collapse', scrollToTarget, { once: true });
          } else {
            scrollToTarget();
          }

          // Tetap ubah hash tanpa memicu scroll bawaan browser.
          history.pushState(null, '', '#' + targetId);
        });
      });

      // Saat pengguna scroll, menu mengikuti section yang sedang terlihat.
      const observer = new IntersectionObserver((entries) => {
        const visibleSections = entries
          .filter(entry => entry.isIntersecting)
          .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

        if (visibleSections.length > 0) {
          setActiveMenu(visibleSections[0].target.id);
        }
      }, {
        root: null,
        rootMargin: '-90px 0px -45% 0px',
        threshold: [0.1, 0.25, 0.5, 0.75]
      });

      sections.forEach(section => observer.observe(section));

      // Jika halaman dibuka langsung dengan URL #tentang, #kawasan, dst.
      if (window.location.hash) {
        const initialId = window.location.hash.substring(1);
        if (document.getElementById(initialId)) {
          setActiveMenu(initialId);
        }
      }
    });

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

    // Slideshow Background Otomatis tiap 3 Detik
    setInterval(() => {
      currentBgIndex = (currentBgIndex + 1) % backgroundImages.length;
      let newBgUrl = `linear-gradient(rgba(11, 19, 41, 0.45), rgba(11, 19, 41, 0.88)), url('${backgroundImages[currentBgIndex]}')`;
      
      welcomeSec.style.backgroundImage = newBgUrl;
      heroDashboard.style.backgroundImage = newBgUrl;
    }, 3000);



    // Fungsi Transisi Masuk ke Situs Utama
    function enterWebsite() {
      const mainContent = document.getElementById('mainWebsiteContent');
      
      welcomeSec.style.opacity = '0';
      welcomeSec.style.transform = 'scale(1.05)';
      
      setTimeout(() => {
        welcomeSec.style.display = 'none';
        mainContent.style.display = 'block';
        setTimeout(() => {
          mainContent.style.opacity = '1';
          window.scrollTo(0, 0);
        }, 50);
      }, 500);
    }

    // Fungsi Kembali ke Halaman Depan
    function returnToWelcome() {
      const mainContent = document.getElementById('mainWebsiteContent');
      
      mainContent.style.opacity = '0';
      setTimeout(() => {
        mainContent.style.display = 'none';
        welcomeSec.style.display = 'flex';
        welcomeSec.style.opacity = '1';
        welcomeSec.style.transform = 'scale(1)';
        window.scrollTo(0, 0);
      }, 500);
    }

    // Carousel Zona Interaktif
    let currentZoneIndex = 0;
    const totalZones = 4;
    const zoneNames = [
      "01 Alun-Alun Kota Tegal",
      "02 Balaikota Tegal",
      "03 Masjid Agung Kota Tegal",
      "04 Taman Pancasila"
    ];

    function switchZone(index) {
      currentZoneIndex = index;
      for(let i=0; i<totalZones; i++) {
        document.getElementById('zoneCard' + i).classList.remove('active');
        document.querySelectorAll('.zone-tab-item')[i].classList.remove('active');
      }
      document.getElementById('zoneCard' + currentZoneIndex).classList.add('active');
      document.querySelectorAll('.zone-tab-item')[currentZoneIndex].classList.add('active');
      document.getElementById('currentZoneIndicator').textContent = zoneNames[currentZoneIndex];
    }

    function nextZone() {
      currentZoneIndex = (currentZoneIndex + 1) % totalZones;
      switchZone(currentZoneIndex);
    }

    function prevZone() {
      currentZoneIndex = (currentZoneIndex - 1 + totalZones) % totalZones;
      switchZone(currentZoneIndex);
    }

  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <!-- ========================================== -->
  <!-- TEMPELKAN KODE HTML CHATBOT DI SINI        -->
  <!-- (Sebelum tag penutup </body>)              -->
  <!-- ========================================== -->
  <div class="chat-float-btn" onclick="toggleChatWindow()" title="Chat Sity Smart City Bot">
    <i class="fa-solid fa-headset"></i>
  </div>

  <div class="chat-window" id="chatWindow">
    <!-- Header Telegram App -->
    <div class="tg-chat-header">
      <div class="d-flex align-items-center gap-2">
        <div class="tg-avatar">
          <i class="fa-solid fa-headset"></i>
        </div>
        <div>
          <div class="tg-title">Sity Smartcity Bot</div>
          <div class="tg-status">bot &bull; selalu aktif</div>
        </div>
      </div>
      <i class="fa-solid fa-xmark fs-4 text-white p-1" style="cursor: pointer;" onclick="toggleChatWindow()" title="Tutup Chat"></i>
    </div>
    
    <!-- Body Chat Bubble Telegram -->
    <div class="tg-chat-body" id="chatBody">
      <div class="tg-msg tg-msg-bot">
        <b>🤖 Sity Smartcity Bot</b><br>
        Halo Jack! Inyong Sity, bot resmi pemantauan Alun-Alun Kota Tegal.<br><br>
        Klik tombol <b>Pilih Pertanyaan</b> di bawah untuk memulai data telemetri:
        <div class="tg-time" id="initialTime">10:00</div>
      </div>
    </div>

    <!-- Container Pop-up Menu Pilihan Pertanyaan -->
    <div id="keyboardMenuPanel" class="tg-keyboard-panel" style="display: none;">
      <div class="d-flex justify-content-between align-items-center mb-2 px-1">
        <span class="text-muted fw-bold" style="font-size: 0.72rem;">MENU PERTANYAAN</span>
        <i class="fa-solid fa-xmark text-muted" style="cursor: pointer;" onclick="toggleKeyboardMenu()"></i>
      </div>
      <div class="tg-inline-keyboard">
        <div class="tg-btn-inline" onclick="sendPreset('Suhu Udara', '🌡️ Berapa suhu udara Alun-Alun Tegal?')">
          <i class="fa-solid fa-temperature-half text-warning"></i>
          <span>Suhu Udara Saat ini</span>
        </div>
        <div class="tg-btn-inline" onclick="sendPreset('Kualitas Udara', '💨 Kualitas udara (MQ135)?')">
          <i class="fa-solid fa-wind text-info"></i>
          <span>Kualitas Udara Saat ini</span>
        </div>
        <div class="tg-btn-inline" onclick="sendPreset('Deteksi Hujan', '🌧️ Status curah hujan?')">
          <i class="fa-solid fa-cloud-rain text-primary"></i>
          <span>Status Curah Hujan</span>
        </div>
        <div class="tg-btn-inline" onclick="sendPreset('Kelembaban Udara', '💧 Kelembaban udara RH?')">
          <i class="fa-solid fa-droplet text-success"></i>
          <span>Kelembaban Udara </span>
        </div>
        <div class="tg-btn-inline" onclick="sendPreset('Info Kawasan', '📍 Daftar zona pemantauan?')">
          <i class="fa-solid fa-map-location-dot text-danger"></i>
          <span>Zona Pemantauan Alun-Alun</span>
        </div>
      </div>
    </div>

    <!-- Footer Menu Telegram -->
    <div class="tg-chat-footer">
      <div class="d-flex align-items-center gap-2">
        <button class="tg-btn-cmd" onclick="resetBot()"><i class="fa-solid fa-arrows-rotate me-1"></i> mulai</button>
        <button class="tg-btn-cmd bg-primary text-white" onclick="toggleKeyboardMenu()">
          <i class="fa-solid fa-list-ul me-1"></i> Pilih Pertanyaan
        </button>
      </div>
    </div>
  </div>

  <!-- ========================================== -->
  <!-- TEMPELKAN KODE JAVASCRIPT DI SINI          -->
  <!-- ========================================== -->
  <script>

    function switchZone(index) {
      currentZoneIndex = index;
      for(let i = 0; i < totalZones; i++) {
        document.getElementById('zoneCard' + i).classList.remove('active');
        document.querySelectorAll('.zone-tab-item')[i].classList.remove('active');
      }
      document.getElementById('zoneCard' + index).classList.add('active');
      document.querySelectorAll('.zone-tab-item')[index].classList.add('active');
      document.getElementById('currentZoneIndicator').innerText = zoneNames[index];
    }

    function nextZone() {
      let nextIndex = (currentZoneIndex + 1) % totalZones;
      switchZone(nextIndex);
    }

    function prevZone() {
      let prevIndex = (currentZoneIndex - 1 + totalZones) % totalZones;
      switchZone(prevIndex);
    }

    // --- FITUR TELEGRAM CHATBOT ENGINE (SITY) ---
    function getTimeNow() {
      const d = new Date();
      return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
    }

    function toggleChatWindow() {
      const chatWin = document.getElementById('chatWindow');
      if (chatWin.style.display === 'flex') {
        chatWin.style.display = 'none';
      } else {
        chatWin.style.display = 'flex';
      }
    }

    function toggleKeyboardMenu() {
      const panel = document.getElementById('keyboardMenuPanel');
      if (panel.style.display === 'none' || panel.style.display === '') {
        panel.style.display = 'block';
      } else {
        panel.style.display = 'none';
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      const initTime = document.getElementById('initialTime');
      if (initTime) initTime.innerText = getTimeNow();
    });

    function resetBot() {
      const chatBody = document.getElementById('chatBody');
      chatBody.innerHTML = `
        <div class="tg-msg tg-msg-bot">
          <b>🤖 Sity Smartcity Bot</b><br>
          Sesi diulang! Klik tombol <b>Pilih Pertanyaan</b> di bawah untuk menampilkan menu layanan.
          <div class="tg-time">${getTimeNow()}</div>
        </div>
      `;
      document.getElementById('keyboardMenuPanel').style.display = 'none';
      chatBody.scrollTop = chatBody.scrollHeight;
    }

    function sendPreset(key, questionText) {
      const chatBody = document.getElementById('chatBody');
      document.getElementById('keyboardMenuPanel').style.display = 'none';

      const userMsgHtml = `
        <div class="tg-msg tg-msg-user">
          ${questionText}
          <div class="tg-time">${getTimeNow()}</div>
        </div>
      `;
      chatBody.insertAdjacentHTML('beforeend', userMsgHtml);
      chatBody.scrollTop = chatBody.scrollHeight;

      let botResponse = "";
      switch(key) {
        case 'Suhu Udara':
          botResponse = "🌡️ <b>TELEMETRI SUHU UDARA</b><br>Suhu ambien Alun-Alun Kota Tegal saat ini <b>31.10 °C</b>.<br><i>Status: Hangat Terik dengan paparan radiasi termal pesisir.</i>";
          break;
        case 'Kualitas Udara':
          botResponse = "💨 <b>SENSOR MQ135 KUALITAS UDARA</b><br>Pembacaan ADC: <b>3140 ADC (4330 mV)</b>.<br><i>Status: Kadar gas emisi kendaraan tergolong stabil di ruang terbuka.</i>";
          break;
        case 'Deteksi Hujan':
          botResponse = "🌧️ <b>SENSOR PRESIPITASI HUJAN</b><br>Status Pelat GPIO 8: <b>Kering (3914 ADC)</b>.<br><i>Status: Tidak terdeteksi adanya tetesan air hujan.</i>";
          break;
        case 'Kelembaban Udara':
          botResponse = "💧 <b>KELEMBABAN RELATIF (RH)</b><br>Kelembaban udara tercatat <b>69.60 % RH</b>.<br><i>Status: Dipengaruhi oleh kelembaban uap air Laut Jawa.</i>";
          break;
        case 'Info Kawasan':
          botResponse = "📍 <b>DAFTAR ZONA OBSERVASIONAL</b><br>1. <b>Rumput Sintetis</b> (Plaza Sentral)<br>2. <b>Jalan Pancasila</b> (Koridor Heritage)<br>3. <b>Masjid Agung Tegal</b> (Kawasan Religi)<br>4. <b>Saluran Sungai</b> (Titik Hidro)";
          break;
        default:
          botResponse = "Menghubungkan ke broker MQTT...";
      }

      setTimeout(() => {
        const botMsgHtml = `
          <div class="tg-msg tg-msg-bot">
            ${botResponse}
            <div class="tg-time">${getTimeNow()}</div>
          </div>
        `;
        chatBody.insertAdjacentHTML('beforeend', botMsgHtml);
        chatBody.scrollTop = chatBody.scrollHeight;
      }, 350);
    }
  </script>
</body>
</html>