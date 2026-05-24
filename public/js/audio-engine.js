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
    this.isMuted = localStorage.getItem('pm_audio_muted') !== 'false'; // Default to muted to respect user/browser autoplay
    this.droneOsc1 = null;
    this.droneOsc2 = null;
    this.droneFilter = null;
    this.droneLfo = null;
    this.initialized = false;
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

    // Create filter for dark warm soundscape
    this.droneFilter = this.ctx.createBiquadFilter();
    this.droneFilter.type = 'lowpass';
    this.droneFilter.frequency.setValueAtTime(140, this.ctx.currentTime);
    this.droneFilter.Q.setValueAtTime(2.0, this.ctx.currentTime);

    // Create drone gain (very soft to keep it premium and subtle)
    this.droneGain = this.ctx.createGain();
    this.droneGain.gain.setValueAtTime(0.035, this.ctx.currentTime);

    // Drone Oscillator 1: Deep sub warm triangle (A0 = 55Hz)
    this.droneOsc1 = this.ctx.createOscillator();
    this.droneOsc1.type = 'triangle';
    this.droneOsc1.frequency.setValueAtTime(55, this.ctx.currentTime);

    // Drone Oscillator 2: Mild detuned sawtooth octave (A1 = 110.4Hz)
    this.droneOsc2 = this.ctx.createOscillator();
    this.droneOsc2.type = 'sawtooth';
    this.droneOsc2.frequency.setValueAtTime(110.4, this.ctx.currentTime);

    // Route drone components
    this.droneOsc1.connect(this.droneFilter);
    this.droneOsc2.connect(this.droneFilter);
    this.droneFilter.connect(this.droneGain);
    this.droneGain.connect(this.masterGain);

    // Create LFO (Low-Frequency Oscillator) to sweep filter frequency for movement
    this.droneLfo = this.ctx.createOscillator();
    this.droneLfo.type = 'sine';
    this.droneLfo.frequency.setValueAtTime(0.08, this.ctx.currentTime); // very slow swell (12 seconds cycle)

    const lfoGain = this.ctx.createGain();
    lfoGain.gain.setValueAtTime(45, this.ctx.currentTime); // Modulate by +/- 45Hz

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
    }

    return this.isMuted;
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
