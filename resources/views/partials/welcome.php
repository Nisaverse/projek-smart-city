<!-- 1. HALAMAN DEPAN / LANDING SCREEN -->
<section id="welcomeSection">
  <div class="bg-overlay"></div>
  
  <div class="welcome-top-center animate-top-info">
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
    </div>
    <div class="welcome-gov-title">Pemerintah Kota Tegal</div>
    <div class="welcome-jawa-script">ꦱ꧀ꦩꦂꦠ꧀ꦱꦶꦠ꧀ꦶꦏꦺꦴꦠꦠꦼꦒꦭ꧀</div>
  </div>

  <div class="welcome-center">
    <h1 class="welcome-title animate-title">SMARTCITY KOTA TEGAL</h1>
    <p class="welcome-desc animate-desc">Pusat informasi dan layanan publik Smart City Kota Tegal yang menghadirkan pemantauan kondisi lingkungan ruang publik secara transparan, akurat, dan dapat diakses langsung oleh warga.</p>
    <div class="d-flex align-items-center animate-btn">
      <button class="btn-masuk-situs" onclick="enterWebsite()">MASUK SITUS &rarr;</button>
    </div>
  </div>

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
        <span class="text-warning">33.5&deg;C</span>
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