<!-- 🎚️ RETRO-FUTURE HUD COMMAND CENTER OVERLAY -->
<div id="pm-command-center" aria-hidden="true" style="display:none">
  <div class="cc-backdrop"></div>
  <div class="cc-modal">
    <div class="cc-glow-orb"></div>
    <div class="cc-header">
      <div class="cc-title">
        <span class="cc-badge">SYSTEM HUD v1.2</span>
        <span class="cc-status">● CONSOLE ONLINE</span>
      </div>
      <button type="button" class="cc-close" id="ccCloseBtn">✕</button>
    </div>
    <div class="cc-input-wrapper">
      <span class="cc-prompt">❯</span>
      <input type="text" id="ccInput" placeholder="Ketik command... (contoh: /glow, /embers, /contact)" autocomplete="off" spellcheck="false">
    </div>
    <div class="cc-body">
      <div class="cc-section-title">COMMANDS TERSEDIA</div>
      <div class="cc-commands-list">
        <div class="cc-cmd-item" data-cmd="/glow">
          <span class="cc-cmd-name">/glow</span>
          <span class="cc-cmd-desc">Mengaktifkan overdrive glow visual warna oranye neon</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/cyber">
          <span class="cc-cmd-name">/cyber</span>
          <span class="cc-cmd-desc">Mengubah pendaran aksen menjadi hijau neon cyber futuristik</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/embers">
          <span class="cc-cmd-name">/embers</span>
          <span class="cc-cmd-desc">Memicu ledakan badai percikan api di layar</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/contact">
          <span class="cc-cmd-name">/contact</span>
          <span class="cc-cmd-desc">Meluncur mulus ke section Hubungi Kami</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/services">
          <span class="cc-cmd-name">/services</span>
          <span class="cc-cmd-desc">Melihat daftar layanan premium kami</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/portfolio">
          <span class="cc-cmd-name">/portfolio</span>
          <span class="cc-cmd-desc">Melihat kumpulan showcase karya terbaik</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/about">
          <span class="cc-cmd-name">/about</span>
          <span class="cc-cmd-desc">Melihat cerita pendiri dan skill constellation</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/mute">
          <span class="cc-cmd-name">/mute</span>
          <span class="cc-cmd-desc">Mematikan ambient synth soundscape</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/unmute">
          <span class="cc-cmd-name">/unmute</span>
          <span class="cc-cmd-desc">Mengaktifkan ambient synth soundscape</span>
        </div>
        <div class="cc-cmd-item" data-cmd="/home">
          <span class="cc-cmd-name">/home</span>
          <span class="cc-cmd-desc">Kembali ke halaman utama teratas</span>
        </div>
      </div>
    </div>
    <div class="cc-footer">
      <span>Tekan <kbd>ENTER</kbd> untuk eksekusi, <kbd>ESC</kbd> untuk keluar</span>
    </div>
  </div>
</div>

<style>
#pm-command-center {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 999999;
  display: flex;
  align-items: center;
  justify-content: center;
}
.cc-backdrop {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.75);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  opacity: 0;
  transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.cc-modal {
  position: relative;
  width: 90%;
  max-width: 650px;
  background: rgba(10, 10, 10, 0.85);
  border: 1px solid rgba(255, 106, 26, 0.18);
  border-radius: 28px;
  padding: 35px;
  box-shadow: 0 30px 90px rgba(0, 0, 0, 0.9), 0 0 50px rgba(255, 106, 26, 0.05);
  transform: scale(0.9) translateY(20px);
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  max-height: 90vh;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}
.cc-glow-orb {
  position: absolute;
  top: -150px;
  right: -150px;
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(255, 106, 26, 0.15) 0%, transparent 70%);
  pointer-events: none;
}
.cc-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}
.cc-title {
  display: flex;
  align-items: center;
  gap: 12px;
}
.cc-badge {
  font-size: 11px;
  font-weight: 800;
  color: var(--purple);
  background: rgba(255, 106, 26, 0.08);
  border: 1px solid rgba(255, 106, 26, 0.2);
  padding: 4px 10px;
  border-radius: 8px;
  letter-spacing: 0.05em;
}
.cc-status {
  font-size: 10px;
  font-weight: 700;
  color: #15be83;
  letter-spacing: 0.05em;
  animation: pulseCCStatus 2s infinite;
}
@keyframes pulseCCStatus {
  0%, 100% { opacity: 0.6; }
  50% { opacity: 1; }
}
.cc-close {
  background: transparent;
  border: none;
  color: var(--muted);
  font-size: 18px;
  cursor: pointer;
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: all 0.2s ease;
}
.cc-close:hover {
  background: rgba(255, 255, 255, 0.05);
  color: #fff;
}
.cc-input-wrapper {
  display: flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.02);
  border: 1px solid rgba(255, 106, 26, 0.12);
  border-radius: 16px;
  padding: 14px 20px;
  margin-bottom: 24px;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
.cc-input-wrapper:focus-within {
  border-color: var(--purple);
  box-shadow: 0 0 20px rgba(255, 106, 26, 0.15);
}
.cc-prompt {
  color: var(--purple);
  font-weight: 900;
  margin-right: 14px;
  font-size: 18px;
}
#ccInput {
  background: transparent;
  border: none;
  color: #fff;
  font-size: 17px;
  font-family: 'Outfit', sans-serif;
  width: 100%;
  outline: none;
}
#ccInput::placeholder {
  color: var(--muted);
  opacity: 0.6;
}
.cc-section-title {
  font-size: 11px;
  font-weight: 700;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  margin-bottom: 12px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  padding-bottom: 8px;
}
.cc-commands-list {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  max-height: 250px;
  overflow-y: auto;
  padding-right: 6px;
  -webkit-overflow-scrolling: touch;
}
.cc-commands-list::-webkit-scrollbar {
  width: 4px;
}
.cc-commands-list::-webkit-scrollbar-thumb {
  background: rgba(255, 106, 26, 0.2);
  border-radius: 2px;
}
.cc-cmd-item {
  display: flex;
  flex-direction: column;
  background: rgba(255, 255, 255, 0.015);
  border: 1px solid rgba(255, 255, 255, 0.03);
  padding: 12px 16px;
  border-radius: 14px;
  cursor: pointer;
  transition: all 0.2s ease;
}
.cc-cmd-item:hover, .cc-cmd-item.active {
  background: rgba(255, 106, 26, 0.06);
  border-color: rgba(255, 106, 26, 0.3);
  transform: translateY(-1px);
}
.cc-cmd-name {
  font-size: 14px;
  font-weight: 700;
  color: var(--purple);
  margin-bottom: 4px;
}
.cc-cmd-desc {
  font-size: 11px;
  color: var(--muted);
  line-height: 1.4;
}
.cc-footer {
  margin-top: 25px;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
  padding-top: 16px;
  display: flex;
  justify-content: space-between;
  font-size: 11px;
  color: var(--muted);
}
.cc-footer kbd {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.15);
  padding: 2px 6px;
  border-radius: 5px;
  color: #fff;
  font-size: 9px;
  margin: 0 2px;
}

/* Open states */
#pm-command-center.open .cc-backdrop {
  opacity: 1;
}
#pm-command-center.open .cc-modal {
  transform: scale(1) translateY(0);
  opacity: 1;
}

/* Glow Overdrive Effect */
.glow-overdrive {
  --glow: rgba(255, 106, 26, 0.7);
  --line: rgba(255, 120, 50, 0.25);
}
.glow-overdrive body::before {
  opacity: 0.55 !important;
  filter: blur(80px) !important;
}

@media (max-width: 768px) {
  #ccTriggerBtn {
    display: none !important;
  }
}
</style>

