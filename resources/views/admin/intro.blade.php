@extends('admin.layout')
@section('heading', 'Intro Sequence')
@section('content')
@php
    $rolesText = implode("\n", $intro->roles ?? []);
    $tickersText = implode("\n", $intro->expertise_tickers ?? []);
@endphp
<div class="card">
  <form method="POST" action="{{ route('admin.intro.update') }}">
    @csrf @method('PUT')

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
      <div>
        <h3 style="margin:0 0 4px;font-size:18px">Intro Sequence Settings</h3>
        <p class="muted" style="margin:0;font-size:13px">Konten animasi sinematik saat website pertama kali dibuka.</p>
      </div>
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:700">
        <input type="hidden" name="is_enabled" value="0">
        <input type="checkbox" name="is_enabled" value="1" {{ $intro->is_enabled ? 'checked' : '' }} style="width:auto">
        Enable Intro
      </label>
    </div>

    <hr style="border-color:var(--line);margin:0 0 20px">

    <h4 style="color: #fff; margin-bottom: 20px; font-weight: 500; font-size: 15px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 6px;">
      🇮🇩 / 🇬🇧 Bilingual Intro Fields
    </h4>

    <!-- Greeting Bilingual Group -->
    <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Greeting (baris pertama)</h4>
        <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('greeting', 'greeting_en', this)">⚡ Auto-Translate</button>
      </div>
      <div class="field" style="margin-bottom: 12px;">
        <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
        <input id="greeting" name="greeting" value="{{ old('greeting', $intro->greeting) }}" placeholder="Halo 👋">
      </div>
      <div class="field">
        <label style="font-size: 12px; color: #ff5500;">English version</label>
        <input id="greeting_en" name="greeting_en" value="{{ old('greeting_en', $intro->greeting_en) }}" placeholder="Hello 👋">
      </div>
    </div>

    <!-- Tagline Bilingual Group -->
    <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Tagline (kalimat pamungkas)</h4>
        <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('tagline', 'tagline_en', this)">⚡ Auto-Translate</button>
      </div>
      <div class="field" style="margin-bottom: 12px;">
        <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
        <input id="tagline" name="tagline" value="{{ old('tagline', $intro->tagline) }}" placeholder="Gua bikin software yang beautiful dan functional.">
      </div>
      <div class="field">
        <label style="font-size: 12px; color: #ff5500;">English version</label>
        <input id="tagline_en" name="tagline_en" value="{{ old('tagline_en', $intro->tagline_en) }}" placeholder="I build beautiful and functional software.">
      </div>
    </div>

    <!-- CTA Button Text Bilingual Group -->
    <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">CTA Button Text</h4>
        <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('cta_text', 'cta_text_en', this)">⚡ Auto-Translate</button>
      </div>
      <div class="field" style="margin-bottom: 12px;">
        <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
        <input id="cta_text" name="cta_text" value="{{ old('cta_text', $intro->cta_text) }}" placeholder="Lihat Karya Gua →">
      </div>
      <div class="field">
        <label style="font-size: 12px; color: #ff5500;">English version</label>
        <input id="cta_text_en" name="cta_text_en" value="{{ old('cta_text_en', $intro->cta_text_en) }}" placeholder="View My Work →">
      </div>
    </div>

    <!-- Availability Text Bilingual Group -->
    <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Availability Badge Text</h4>
        <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('availability_text', 'availability_text_en', this)">⚡ Auto-Translate</button>
      </div>
      <div class="field" style="margin-bottom: 12px;">
        <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
        <input id="availability_text" name="availability_text" value="{{ old('availability_text', $intro->availability_text) }}" placeholder="Available for new projects">
      </div>
      <div class="field">
        <label style="font-size: 12px; color: #ff5500;">English version</label>
        <input id="availability_text_en" name="availability_text_en" value="{{ old('availability_text_en', $intro->availability_text_en) }}" placeholder="Available for new projects">
      </div>
    </div>

    <hr style="border-color:var(--line);margin:30px 0 20px">

    <h4 style="color: #fff; margin-bottom: 20px; font-weight: 500; font-size: 15px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 6px;">
      🪐 Core Intro Configuration
    </h4>

    <div class="grid" style="margin-bottom: 20px;">
      <div class="field">
        <label>Nama / Identitas</label>
        <input name="name" value="{{ old('name', $intro->name) }}" placeholder="Nama Lengkap / Brand">
        <small class="muted">Muncul setelah greeting dengan efek glow.</small>
      </div>
      <div class="field" style="display:flex;align-items:center;gap:12px;margin-top:28px">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
          <input type="hidden" name="availability_enabled" value="0">
          <input type="checkbox" name="availability_enabled" value="1" {{ $intro->availability_enabled ? 'checked' : '' }} style="width:auto">
          Show Badge
        </label>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
          <input type="hidden" name="is_available" value="0">
          <input type="checkbox" name="is_available" value="1" {{ $intro->is_available ? 'checked' : '' }} style="width:auto">
          Status: Available (hijau)
        </label>
      </div>
    </div>

    <div class="grid" style="margin-bottom: 30px;">
      <div class="field">
        <label>Roles (satu per baris)</label>
        <textarea name="roles_text" style="min-height:120px">{{ old('roles_text', $rolesText) }}</textarea>
        <small class="muted">Contoh:<br>Full-Stack Developer<br>Laravel Engineer<br>UI/UX Enthusiast</small>
      </div>
      <div class="field">
        <label>Expertise Tickers (satu per baris)</label>
        <textarea name="tickers_text" style="min-height:120px">{{ old('tickers_text', $tickersText) }}</textarea>
        <small class="muted">Teks yang berganti-ganti di hero section:<br>Building scalable APIs<br>Crafting elegant interfaces</small>
      </div>
    </div>

    <button class="btn" type="submit" style="width: 100%; padding: 15px; font-size: 15px; font-weight: 600; font-family: 'Space Grotesk';">💾 Save Intro Settings</button>
  </form>
</div>

<div class="card" style="margin-top:0">
  <h3 style="margin:0 0 8px;font-size:16px">Preview Urutan Animasi</h3>
  <p class="muted" style="margin:0 0 16px;font-size:13px">Intro akan tampil dalam urutan berikut (hanya saat pertama kali buka website dalam session baru):</p>
  <div style="background:#000;border-radius:12px;padding:32px;font-family:monospace;font-size:13px;color:#fff;line-height:2">
    <div style="color:#a5b4fc">1. [Gelap total selama 400ms]</div>
    <div style="color:#fff">2. "{{ $intro->greeting }}" — fade in</div>
    <div style="color:#c4b5fd;font-size:16px;font-weight:700">3. "{{ $intro->name }}" — fade in + glow</div>
    <div style="color:#94a3b8">4. Roles berganti satu per satu ({{ count($intro->roles ?? []) }} items)</div>
    <div style="color:#e2e8f0">5. "{{ $intro->tagline }}" — fade in</div>
    <div style="color:#6246ea">6. Button "{{ $intro->cta_text }}" muncul</div>
    <div style="color:#64748b">7. [Transisi wipe ke hero section]</div>
  </div>
</div>
@endsection
