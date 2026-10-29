<!-- 1.1 LIVE TELEMETRI / KARTU METRIK 5 KOLOM (TEMA NAVY & PUTIH MODERN) -->
<section id="telemetri" style="background: linear-gradient(135deg, #0b1329 0%, #1e3a8a 100%); color: #ffffff; padding-top: 5rem; padding-bottom: 7rem;">
  <div class="container-fluid px-4 px-lg-5 py-3">
    <div class="text-center mx-auto mb-5" style="max-width: 700px;">
      <h6 class="text-info fw-bold text-uppercase" style="letter-spacing: 1.5px; font-size: 0.85rem;">Live Telemetri</h6>
      <h2 class="fw-bold text-white mb-2">Parameter Lingkungan Saat Ini</h2>
      <p class="text-light opacity-75">Pantauan langsung kondisi cuaca dan kualitas lingkungan kawasan Alun-Alun Kota Tegal untuk kenyamanan Anda.</p>
    </div>

    <!-- Barisan 5 Kartu Metrik -->
    <div class="row g-4 justify-content-center">
      
      <!-- 1. Suhu Saat Ini -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-is-5 telemetri-card-item">
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
            <i class="fa-solid fa-circle-dot text-warning me-2" style="font-size: 0.55rem;"></i> Suhu lingkungan sekitar
          </div>
        </div>
      </div>

      <!-- 2. Kelembaban -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-is-5 telemetri-card-item">
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
            <i class="fa-solid fa-circle-dot text-info me-2" style="font-size: 0.55rem;"></i> Kelembaban relatif udara
          </div>
        </div>
      </div>

      <!-- 3. Kualitas Udara -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-is-5 telemetri-card-item">
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
            <i class="fa-solid fa-circle-dot text-success me-2" style="font-size: 0.55rem;"></i> Kondisi Baik & Sehat
          </div>
        </div>
      </div>

      <!-- 4. Status Hujan -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-is-5 telemetri-card-item">
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
            <i class="fa-solid fa-circle-dot text-light me-2" style="font-size: 0.55rem;"></i> Presipitasi Terdeteksi
          </div>
        </div>
      </div>

      <!-- 5. Air Sungai -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-is-5 telemetri-card-item">
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
            <i class="fa-solid fa-circle-dot text-success me-2" style="font-size: 0.55rem;"></i> Status Aman Terkendali
          </div>
        </div>
      </div>

    </div>
  </div>
</section>