<!-- CHATBOT TELEGRAM SMART CITY (SITY BOT) -->
<div class="chat-float-btn" onclick="toggleChatWindow()" title="Chat Sity Smart City Bot">
  <i class="fa-solid fa-headset"></i>
</div>

<div class="chat-window" id="chatWindow">
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
  
  <div class="tg-chat-body" id="chatBody">
    <div class="tg-msg tg-msg-bot">
      <b>🤖 Sity Smartcity Bot</b><br>
      Halo Jack! Inyong Sity, bot resmi pemantauan Alun-Alun Kota Tegal.<br><br>
      Klik tombol <b>Pilih Pertanyaan</b> di bawah untuk memulai data telemetri:
      <div class="tg-time" id="initialTime">10:00</div>
    </div>
  </div>

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

  <div class="tg-chat-footer">
    <div class="d-flex align-items-center gap-2">
      <button class="tg-btn-cmd" onclick="resetBot()"><i class="fa-solid fa-arrows-rotate me-1"></i> mulai</button>
      <button class="tg-btn-cmd bg-primary text-white" onclick="toggleKeyboardMenu()">
        <i class="fa-solid fa-list-ul me-1"></i> Pilih Pertanyaan
      </button>
    </div>
  </div>
</div>