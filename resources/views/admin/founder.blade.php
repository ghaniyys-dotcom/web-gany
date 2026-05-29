@extends('admin.layout')
@section('heading', 'Founder Profile')
@section('content')
<div class="card">
  <form method="POST" action="{{ route('admin.founder.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div style="margin-bottom:20px">
      <h3 style="margin:0 0 4px;font-size:18px">Founder Profile Settings</h3>
      <p class="muted" style="margin:0;font-size:13px">Konten personal asimetris yang dipasang persis di bawah Hero section.</p>
    </div>

    <hr style="border-color:var(--line);margin:0 0 20px">

    <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Eyebrow / Sub-heading Kecil</h4>
        <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('eyebrow', 'eyebrow_en', this)">⚡ Auto-Translate</button>
      </div>
      <div class="field" style="margin-bottom: 12px;">
        <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
        <input id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $founder->eyebrow) }}" placeholder="MEET THE FOUNDER" required>
      </div>
      <div class="field">
        <label style="font-size: 12px; color: #ff5500;">English version</label>
        <input id="eyebrow_en" name="eyebrow_en" value="{{ old('eyebrow_en', $founder->eyebrow_en) }}" placeholder="MEET THE FOUNDER">
      </div>
    </div>

    <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Heading Utama (Welcoming)</h4>
        <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('heading', 'heading_en', this)">⚡ Auto-Translate</button>
      </div>
      <div class="field" style="margin-bottom: 12px;">
        <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
        <input id="heading" name="heading" value="{{ old('heading', $founder->heading) }}" placeholder="Hi, I'm Gany." required>
      </div>
      <div class="field">
        <label style="font-size: 12px; color: #ff5500;">English version</label>
        <input id="heading_en" name="heading_en" value="{{ old('heading_en', $founder->heading_en) }}" placeholder="Hi, I'm Gany.">
      </div>
    </div>

    <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Deskripsi Profil</h4>
        <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('description', 'description_en', this)">⚡ Auto-Translate</button>
      </div>
      <div class="field" style="margin-bottom: 12px;">
        <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
        <textarea id="description" name="description" style="min-height:140px" required>{{ old('description', $founder->description) }}</textarea>
      </div>
      <div class="field">
        <label style="font-size: 12px; color: #ff5500;">English version</label>
        <textarea id="description_en" name="description_en" style="min-height:140px">{{ old('description_en', $founder->description_en) }}</textarea>
      </div>
    </div>

    <hr style="border-color:var(--line);margin:24px 0">

    <div class="grid">
      <div class="field">
        <label>Foto Portrait Founder</label>
        <input type="file" name="photo" accept="image/*" style="margin-bottom:10px">
        <small class="muted" style="display:block;margin-bottom:12px">Rekomendasi rasio portrait (misal 3:4 atau 4:5), maks 2MB.</small>
        
        @if($founder->photo_path)
          <div style="margin-top:10px">
            <span class="muted" style="display:block;font-size:12px;margin-bottom:6px">Foto Saat Ini:</span>
            <img src="{{ asset($founder->photo_path) }}" alt="Portrait" style="max-height:150px;border-radius:8px;border:1px solid var(--line);filter:grayscale(0.5)">
          </div>
        @else
          <div style="padding:15px;background:#091020;border:1px dashed var(--line);border-radius:12px;font-size:12px" class="muted">
            Belum ada foto. Default siluet avatar akan digunakan.
          </div>
        @endif
      </div>

      <div class="field" style="grid-column: span 2">
        <label>Tanda Tangan Digital (Signature)</label>
        <input type="file" name="signature" accept="image/*" style="margin-bottom:10px">
        <small class="muted" style="display:block;margin-bottom:12px">Gunakan file PNG transparan dengan tinta putih/terang agar menyatu dengan dark mode, maks 1MB.</small>

        @if($founder->signature_path)
          <div style="margin-top:10px">
            <span class="muted" style="display:block;font-size:12px;margin-bottom:6px">Tanda Tangan Saat Ini:</span>
            <div style="background:#091020;padding:12px;border-radius:8px;border:1px solid var(--line);display:inline-block">
              <img src="{{ asset($founder->signature_path) }}" alt="Signature" style="max-height:60px">
            </div>
          </div>
        @else
          <div style="padding:15px;background:#091020;border:1px dashed var(--line);border-radius:12px;font-size:12px" class="muted">
            Belum ada tanda tangan digital.
          </div>
        @endif
      </div>
    </div>

    <hr style="border-color:var(--line);margin:24px 0 20px">

    <button class="btn" type="submit" style="width: 100%; padding: 15px; font-size: 15px; font-weight: 600; font-family: 'Space Grotesk';">💾 Simpan Profil Founder</button>
  </form>
</div>
@endsection
