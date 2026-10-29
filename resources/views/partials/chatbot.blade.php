<!-- Tombol Floating Chatbot -->
<div class="chat-float-btn" onclick="toggleChatWindow()" title="Chat Sity Smart City Bot">
  <i class="fa-solid fa-headset"></i>
</div>

<!-- Jendela Chatbot -->
<div class="chat-window" id="chatWindow">
  <!-- Header Chatbot -->
  <div class="tg-chat-header">
    <div class="d-flex align-items-center gap-2">
      <div class="tg-avatar">
        <i class="fa-solid fa-headset"></i>
      </div>
      <div>
        <div class="tg-title">SiSity Chatbot Smartcity</div>
        <div class="tg-status">bot &bull; selalu aktif</div>
      </div>
    </div>
    <i class="fa-solid fa-xmark fs-4 text-white p-1" style="cursor: pointer;" onclick="toggleChatWindow()" title="Tutup Chat"></i>
  </div>
  
  <!-- Body Chat Bubble -->
  <div class="tg-chat-body" id="chatBody">
    <div class="tg-msg tg-msg-bot">
      <b>🤖 Sity Smartcity Bot</b><br>
      Halo! Aku Sity, bot resmi pemantauan Smart City Kota Tegal.<br><br>
      Silakan ketik pertanyaan kamu di bawah ini!
      <div class="tg-time" id="initialTime">10:00</div>
    </div>
  </div>

  <!-- Footer Input Teks & Tombol Kirim -->
  <div class="tg-chat-footer">
    <div class="d-flex align-items-center gap-2 w-100">
      <input type="text" id="chatInput" class="form-control form-control-sm rounded-pill px-3" placeholder="Ketik pertanyaan..." style="font-size: 0.82rem; border: 1px solid #ccc;">
      <button type="button" class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" id="sendBtn" style="width: 35px; height: 35px; flex-shrink: 0;">
        <i class="fa-solid fa-paper-plane" style="font-size: 0.8rem;"></i>
      </button>
    </div>
  </div>
</div>