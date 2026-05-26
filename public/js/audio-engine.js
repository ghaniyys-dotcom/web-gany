/**
 * 🌋 FLUXORA CINEMATIC SOUNDSYNTH ENGINE (Web Audio API)
 * Synthesizes high-end cinematic soundscapes and UI acoustic feedback in real-time.
 * Zero external asset dependencies. Instant load, purely procedural.
 */

class FluxoraSynth {
  constructor() {
    this.ctx = null;
    this.masterGain = null;
    this.droneGain = null;
    this.isMuted = localStorage.getItem('pm_audio_muted') === 'true'; // Default to unmuted (false) so sound starts immediately on first load
    this.droneOsc1 = null;
    this.droneOsc2 = null;
    this.droneFilter = null;
    this.droneLfo = null;
    this.initialized = false;
    this.activitySwell = 0; // scroll/mouse activity level [0.0 - 1.0]
    this.physicsRunning = false;
  }

  init() {
    if (this.initialized) return;

    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
    if (!AudioContextClass) return;

    this.ctx = new AudioContextClass();
    
    // Master Volume control
    this.masterGain = this.ctx.createGain();
    this.masterGain.gain.setValueAtTime(this.isMuted ? 0 : 0.4, this.ctx.currentTime);
    this.masterGain.connect(this.ctx.destination);

    // Initialize atmospheric pad drone
    this.setupAtmosphericDrone();

    this.initialized = true;

    // Start physical dynamic lerping loop
    this.startDronePhysics();

    // Handle resume if state is suspended (standard browser safety mechanism)
    if (!this.isMuted && this.ctx.state === 'suspended') {
      const resume = () => {
        if (this.ctx && this.ctx.state === 'suspended') {
          this.ctx.resume();
        }
        window.removeEventListener('click', resume);
        window.removeEventListener('keydown', resume);
      };
      window.addEventListener('click', resume);
      window.addEventListener('keydown', resume);
    }
  }

  setupAtmosphericDrone() {
    if (!this.ctx || !this.masterGain) return;

    // Create filter for dark warm soundscape (low resonance for ultra-smoothness)
    this.droneFilter = this.ctx.createBiquadFilter();
    this.droneFilter.type = 'lowpass';
    this.droneFilter.frequency.setValueAtTime(130, this.ctx.currentTime);
    this.droneFilter.Q.setValueAtTime(0.7, this.ctx.currentTime);

    // Create drone gain (soft and subtle for warm background elegance)
    this.droneGain = this.ctx.createGain();
    this.droneGain.gain.setValueAtTime(0.025, this.ctx.currentTime);

    // Drone Oscillator 1: Deep warm sub sine (A0 = 55Hz)
    this.droneOsc1 = this.ctx.createOscillator();
    this.droneOsc1.type = 'sine';
    this.droneOsc1.frequency.setValueAtTime(55, this.ctx.currentTime);

    // Drone Oscillator 2: Smooth glassy perfect fifth triangle (E2 = 82.4Hz)
    this.droneOsc2 = this.ctx.createOscillator();
    this.droneOsc2.type = 'triangle';
    this.droneOsc2.frequency.setValueAtTime(82.4, this.ctx.currentTime);

    // Route drone components
    this.droneOsc1.connect(this.droneFilter);
    this.droneOsc2.connect(this.droneFilter);
    this.droneFilter.connect(this.droneGain);
    this.droneGain.connect(this.masterGain);

    // Create LFO to sweep filter frequency for very slow breathing movement
    this.droneLfo = this.ctx.createOscillator();
    this.droneLfo.type = 'sine';
    this.droneLfo.frequency.setValueAtTime(0.05, this.ctx.currentTime); // slow breathing (20-second cycle)

    const lfoGain = this.ctx.createGain();
    lfoGain.gain.setValueAtTime(12, this.ctx.currentTime); // Modulate by +/- 12Hz

    this.droneLfo.connect(lfoGain);
    lfoGain.connect(this.droneFilter.frequency);

    // Start oscillators
    this.droneOsc1.start();
    this.droneOsc2.start();
    this.droneLfo.start();
  }

  // Procedure: procedurally synthesize a premium high-end tactile switch click
  playTactileClick() {
    this.init();
    if (this.isMuted || !this.ctx) return;
    if (this.ctx.state === 'suspended') this.ctx.resume();

    const now = this.ctx.currentTime;
    
    // Core click oscillator
    const osc = this.ctx.createOscillator();
    osc.type = 'triangle';
    osc.frequency.setValueAtTime(950, now);
    osc.frequency.exponentialRampToValueAtTime(120, now + 0.04);

    // Quick volume envelope
    const gain = this.ctx.createGain();
    gain.gain.setValueAtTime(0.09, now);
    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.04);

    // Route click
    osc.connect(gain);
    gain.connect(this.masterGain);
    
    osc.start(now);
    osc.stop(now + 0.05);

    // Synthesize micro white-noise burst for physical friction shutter sound
    try {
      const bufferSize = this.ctx.sampleRate * 0.012; // 12ms burst
      const buffer = this.ctx.createBuffer(1, bufferSize, this.ctx.sampleRate);
      const data = buffer.getChannelData(0);
      for (let i = 0; i < bufferSize; i++) {
        data[i] = Math.random() * 2 - 1;
      }

      const noise = this.ctx.createBufferSource();
      noise.buffer = buffer;

      const noiseFilter = this.ctx.createBiquadFilter();
      noiseFilter.type = 'bandpass';
      noiseFilter.frequency.setValueAtTime(3200, now);
      noiseFilter.Q.setValueAtTime(4.0, now);

      const noiseGain = this.ctx.createGain();
      noiseGain.gain.setValueAtTime(0.02, now);
      noiseGain.gain.exponentialRampToValueAtTime(0.001, now + 0.012);

      noise.connect(noiseFilter);
      noiseFilter.connect(noiseGain);
      noiseGain.connect(this.masterGain);

      noise.start(now);
      noise.stop(now + 0.015);
    } catch(e) {}
  }

  // Procedure: procedurally synthesize a high-frequency ambient starry shimmer
  playHoverShimmer() {
    this.init();
    if (this.isMuted || !this.ctx) return;
    if (this.ctx.state === 'suspended') this.ctx.resume();

    const now = this.ctx.currentTime;

    const osc = this.ctx.createOscillator();
    osc.type = 'sine';
    osc.frequency.setValueAtTime(1800, now);
    osc.frequency.exponentialRampToValueAtTime(3400, now + 0.18);

    const filter = this.ctx.createBiquadFilter();
    filter.type = 'highpass';
    filter.frequency.setValueAtTime(2200, now);

    const gain = this.ctx.createGain();
    gain.gain.setValueAtTime(0.008, now);
    gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.18);

    osc.connect(filter);
    filter.connect(gain);
    gain.connect(this.masterGain);

    osc.start(now);
    osc.stop(now + 0.2);
  }

  // Procedure: procedurally synthesize a deep sub-bass cinematic drop and atmospheric curtain whoosh
  playCurtainBassDrop() {
    this.init();
    if (this.isMuted || !this.ctx) return;
    if (this.ctx.state === 'suspended') this.ctx.resume();

    const now = this.ctx.currentTime;

    // 1. Deep Cinematic Sub-Bass Drop (Sine oscillator falling from 90Hz to 28Hz)
    const subOsc = this.ctx.createOscillator();
    subOsc.type = 'sine';
    subOsc.frequency.setValueAtTime(90, now);
    subOsc.frequency.exponentialRampToValueAtTime(28, now + 1.2);

    const subGain = this.ctx.createGain();
    subGain.gain.setValueAtTime(0.48, now);
    subGain.gain.exponentialRampToValueAtTime(0.001, now + 1.25);

    subOsc.connect(subGain);
    subGain.connect(this.masterGain);

    // 2. Whoosh Swell (Filter-modulated noise sweep to simulate curtain split friction)
    try {
      const bufferSize = this.ctx.sampleRate * 1.5; // 1.5 seconds whoosh
      const buffer = this.ctx.createBuffer(1, bufferSize, this.ctx.sampleRate);
      const data = buffer.getChannelData(0);
      for (let i = 0; i < bufferSize; i++) {
        data[i] = Math.random() * 2 - 1;
      }

      const noise = this.ctx.createBufferSource();
      noise.buffer = buffer;

      const filter = this.ctx.createBiquadFilter();
      filter.type = 'bandpass';
      filter.frequency.setValueAtTime(120, now);
      filter.frequency.exponentialRampToValueAtTime(450, now + 0.45);
      filter.frequency.exponentialRampToValueAtTime(80, now + 1.25);
      filter.Q.setValueAtTime(2.5, now);

      const noiseGain = this.ctx.createGain();
      noiseGain.gain.setValueAtTime(0.005, now);
      noiseGain.gain.linearRampToValueAtTime(0.08, now + 0.35); // swell peak
      noiseGain.gain.exponentialRampToValueAtTime(0.001, now + 1.25);

      noise.connect(filter);
      filter.connect(noiseGain);
      noiseGain.connect(this.masterGain);

      noise.start(now);
      noise.stop(now + 1.3);
    } catch(e) {}

    subOsc.start(now);
    subOsc.stop(now + 1.3);
  }

  // Procedure: procedurally synthesize a high-end elegant cosmic big bang explosion
  playCosmicExplosion() {
    this.init();
    if (this.isMuted || !this.ctx) return;
    if (this.ctx.state === 'suspended') this.ctx.resume();

    const now = this.ctx.currentTime;

    // 1. Deep Kinetic Sub-Bass Swell (Sine falling from 160Hz to 30Hz)
    const subOsc = this.ctx.createOscillator();
    subOsc.type = 'sine';
    subOsc.frequency.setValueAtTime(160, now);
    subOsc.frequency.exponentialRampToValueAtTime(30, now + 1.8);

    const subGain = this.ctx.createGain();
    subGain.gain.setValueAtTime(0.35, now);
    subGain.gain.exponentialRampToValueAtTime(0.001, now + 1.85);

    subOsc.connect(subGain);
    subGain.connect(this.masterGain);

    // 2. High-Tech Shimmer Spark (Triangle sweep from 1500Hz to 600Hz, quick splash)
    const splashOsc = this.ctx.createOscillator();
    splashOsc.type = 'triangle';
    splashOsc.frequency.setValueAtTime(1500, now);
    splashOsc.frequency.exponentialRampToValueAtTime(600, now + 0.35);

    const splashGain = this.ctx.createGain();
    splashGain.gain.setValueAtTime(0.05, now);
    splashGain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);

    splashOsc.connect(splashGain);
    splashGain.connect(this.masterGain);

    // 3. Elegant Gas Whoosh (Bandpass noise sweep representing expander wave)
    try {
      const bufferSize = this.ctx.sampleRate * 1.5; // 1.5 seconds whoosh
      const buffer = this.ctx.createBuffer(1, bufferSize, this.ctx.sampleRate);
      const data = buffer.getChannelData(0);
      for (let i = 0; i < bufferSize; i++) {
        data[i] = Math.random() * 2 - 1;
      }

      const noise = this.ctx.createBufferSource();
      noise.buffer = buffer;

      const filter = this.ctx.createBiquadFilter();
      filter.type = 'bandpass';
      filter.frequency.setValueAtTime(450, now);
      filter.frequency.exponentialRampToValueAtTime(1200, now + 0.25);
      filter.frequency.exponentialRampToValueAtTime(150, now + 1.4);
      filter.Q.setValueAtTime(1.8, now);

      const noiseGain = this.ctx.createGain();
      noiseGain.gain.setValueAtTime(0.04, now);
      noiseGain.gain.linearRampToValueAtTime(0.12, now + 0.2); // expander peak
      noiseGain.gain.exponentialRampToValueAtTime(0.001, now + 1.5);

      noise.connect(filter);
      filter.connect(noiseGain);
      noiseGain.connect(this.masterGain);

      noise.start(now);
      noise.stop(now + 1.5);
    } catch(e) {}

    subOsc.start(now);
    subOsc.stop(now + 1.9);
    splashOsc.start(now);
    splashOsc.stop(now + 0.4);
  }

  // Procedure: procedurally synthesize a reverse cosmic implosion recall that locks nodes in place
  playCosmicRecall() {
    this.init();
    if (this.isMuted || !this.ctx) return;
    if (this.ctx.state === 'suspended') this.ctx.resume();

    const now = this.ctx.currentTime;
    const duration = 1.4; // matches 0.4s to 1.8s duration

    // 1. Swelling Implosion Whine (Sine sweep rising from 90Hz to 480Hz)
    const riseOsc = this.ctx.createOscillator();
    riseOsc.type = 'sine';
    riseOsc.frequency.setValueAtTime(90, now);
    riseOsc.frequency.exponentialRampToValueAtTime(480, now + duration);

    const riseGain = this.ctx.createGain();
    riseGain.gain.setValueAtTime(0.001, now);
    riseGain.gain.exponentialRampToValueAtTime(0.12, now + duration);

    riseOsc.connect(riseGain);
    riseGain.connect(this.masterGain);

    // 2. Whoosh Swell (Filter-modulated noise rising sweep)
    try {
      const bufferSize = this.ctx.sampleRate * duration;
      const buffer = this.ctx.createBuffer(1, bufferSize, this.ctx.sampleRate);
      const data = buffer.getChannelData(0);
      for (let i = 0; i < bufferSize; i++) {
        data[i] = Math.random() * 2 - 1;
      }

      const noise = this.ctx.createBufferSource();
      noise.buffer = buffer;

      const filter = this.ctx.createBiquadFilter();
      filter.type = 'bandpass';
      filter.frequency.setValueAtTime(100, now);
      filter.frequency.exponentialRampToValueAtTime(650, now + duration);
      filter.Q.setValueAtTime(3.0, now);

      const noiseGain = this.ctx.createGain();
      noiseGain.gain.setValueAtTime(0.001, now);
      noiseGain.gain.linearRampToValueAtTime(0.06, now + duration);

      noise.connect(filter);
      filter.connect(noiseGain);
      noiseGain.connect(this.masterGain);

      noise.start(now);
      noise.stop(now + duration + 0.05);
    } catch(e) {}

    riseOsc.start(now);
    riseOsc.stop(now + duration + 0.05);

    // 3. Locking click/ding (Procedural glassy chime precisely at recall completion)
    const chimeTime = now + duration;
    
    const chimeOsc = this.ctx.createOscillator();
    chimeOsc.type = 'triangle';
    chimeOsc.frequency.setValueAtTime(1200, chimeTime);
    chimeOsc.frequency.exponentialRampToValueAtTime(80, chimeTime + 0.15);

    const chimeGain = this.ctx.createGain();
    chimeGain.gain.setValueAtTime(0, chimeTime - 0.01);
    chimeGain.gain.setValueAtTime(0.05, chimeTime);
    chimeGain.gain.exponentialRampToValueAtTime(0.0001, chimeTime + 0.15);

    chimeOsc.connect(chimeGain);
    chimeGain.connect(this.masterGain);

    chimeOsc.start(chimeTime);
    chimeOsc.stop(chimeTime + 0.2);
  }

  toggleMute() {
    this.init();
    if (!this.ctx) return this.isMuted;

    this.isMuted = !this.isMuted;
    localStorage.setItem('pm_audio_muted', this.isMuted);

    if (this.ctx.state === 'suspended') {
      this.ctx.resume();
    }

    const now = this.ctx.currentTime;
    if (this.isMuted) {
      // Fade out
      this.masterGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.15);
      setTimeout(() => {
        if (this.isMuted && this.masterGain) {
          this.masterGain.gain.setValueAtTime(0, this.ctx.currentTime);
        }
      }, 160);
    } else {
      // Fade in
      this.masterGain.gain.setValueAtTime(0.001, now);
      this.masterGain.gain.exponentialRampToValueAtTime(0.4, now + 0.25);
      this.startDronePhysics();
    }

    return this.isMuted;
  }

  // Increment dynamic activity level
  triggerActivitySwell(amount) {
    this.init();
    this.activitySwell = Math.min(this.activitySwell + amount, 1.0);
  }

  // Smooth audio-rate physical simulation loop
  startDronePhysics() {
    if (this.physicsRunning) return;
    this.physicsRunning = true;

    const updateLoop = () => {
      if (!this.initialized || this.isMuted || !this.ctx) {
        this.physicsRunning = false;
        return;
      }

      // Smooth decay of activity velocity
      this.activitySwell *= 0.95;
      if (this.activitySwell < 0.001) this.activitySwell = 0;

      // Base: Osc1 = 55Hz (A0 sub), Osc2 = 82.4Hz (E2 pad fifth), Lowpass Filter = 130Hz
      // Peak: Sub stays warm (+2Hz), Pad has a mild float (+5.6Hz), Filter opens gently (+50Hz)
      const baseFreq1 = 55;
      const baseFreq2 = 82.4;
      const baseFilter = 130;

      const targetFreq1 = baseFreq1 + this.activitySwell * 2;
      const targetFreq2 = baseFreq2 + this.activitySwell * 5.6;
      const targetFilter = baseFilter + this.activitySwell * 50;

      const now = this.ctx.currentTime;

      // Apply lowpass filtering to eliminate audio-rate clicks
      if (this.droneOsc1) {
        this.droneOsc1.frequency.setTargetAtTime(targetFreq1, now, 0.08);
      }
      if (this.droneOsc2) {
        this.droneOsc2.frequency.setTargetAtTime(targetFreq2, now, 0.08);
      }
      if (this.droneFilter) {
        this.droneFilter.frequency.setTargetAtTime(targetFilter, now, 0.08);
      }

      requestAnimationFrame(updateLoop);
    };

    requestAnimationFrame(updateLoop);
  }
}

// Global hook
window.FluxoraAudio = new FluxoraSynth();

// Auto-init on first dynamic user gesture
const initAudioOnInteraction = () => {
  window.FluxoraAudio.init();
  window.removeEventListener('click', initAudioOnInteraction);
  window.removeEventListener('scroll', initAudioOnInteraction);
  window.removeEventListener('mousemove', initAudioOnInteraction);
};
window.addEventListener('click', initAudioOnInteraction, { passive: true });
window.addEventListener('scroll', initAudioOnInteraction, { passive: true });
window.addEventListener('mousemove', initAudioOnInteraction, { passive: true });
