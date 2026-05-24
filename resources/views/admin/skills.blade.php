@extends('admin.layout')
@section('heading', 'Skills — Constellation Map')
@section('content')
<div class="card">
  <h3 style="margin:0 0 6px">Tambah Skill Baru</h3>
  <p class="muted" style="margin:0 0 16px;font-size:13px">Skills yang aktif akan ditampilkan dalam Constellation Map interaktif di halaman About. Tentukan juga apakah skill ini masuk ke dalam Orbit 3D Hero.</p>
  <form method="POST" action="{{ route('admin.skills.store') }}">
    @csrf
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr;gap:12px;align-items:end">
      <div class="field" style="margin:0">
        <label>Nama Skill</label>
        <input name="name" required placeholder="e.g. Laravel">
      </div>
      <div class="field" style="margin:0">
        <label>Level (0-100)</label>
        <input name="level" type="number" min="0" max="100" value="80">
      </div>
      <div class="field" style="margin:0">
        <label>Tahun Exp</label>
        <input name="years" type="number" min="0" max="50" value="1">
      </div>
      <div class="field" style="margin:0">
        <label>Kategori</label>
        <input name="category" placeholder="Backend / Frontend / ...">
      </div>
      <div class="field" style="margin:0">
        <label>Warna (hex)</label>
        <input name="color" type="color" value="#6246ea" style="height:46px;padding:4px">
      </div>
    </div>
    <div style="display:flex;gap:16px;margin-top:12px;align-items:center;flex-wrap:wrap">
      <div class="field" style="margin:0;width:120px">
        <label>Sort Order</label>
        <input name="sort_order" type="number" value="{{ $items->count() + 1 }}">
      </div>
      <label style="display:flex;align-items:center;gap:8px;padding-top:20px;cursor:pointer">
        <input type="checkbox" name="is_active" value="1" checked style="width:auto">
        Aktif
      </label>
      <label style="display:flex;align-items:center;gap:8px;padding-top:20px;cursor:pointer">
        <input type="checkbox" name="in_orbit" value="1" style="width:auto">
        Tampilkan di Orbit 3D 🪐
      </label>
      <button class="btn" type="submit" style="padding:14px 22px;align-self:flex-end;margin-left:auto">+ Tambah Skill</button>
    </div>
  </form>
</div>

<div class="card">
  <h3 style="margin:0 0 4px">Daftar Skills ({{ $items->count() }} total)</h3>
  <p class="muted" style="margin:0 0 16px;font-size:13px">Klik skill untuk edit inline. Delete untuk hapus permanen.</p>
  <table>
    <thead>
      <tr>
        <th>Skill</th><th>Kategori</th><th>Level</th><th>Tahun</th><th>Warna</th><th>Status</th><th>Orbit 3D</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $skill)
      <tr>
        <td><strong>{{ $skill->name }}</strong></td>
        <td><span style="font-size:12px;padding:3px 8px;border-radius:6px;background:rgba(124,92,255,.15);color:#c4b5fd">{{ $skill->category }}</span></td>
        <td>
          <div style="display:flex;align-items:center;gap:8px">
            <div style="width:80px;height:6px;background:#1a2438;border-radius:3px;overflow:hidden">
              <div style="width:{{ $skill->level }}%;height:100%;background:{{ $skill->color }};border-radius:3px"></div>
            </div>
            <span style="font-size:13px;color:#94a3b8">{{ $skill->level }}%</span>
          </div>
        </td>
        <td>{{ $skill->years }}y</td>
        <td><span style="display:inline-block;width:20px;height:20px;border-radius:50%;background:{{ $skill->color }};border:2px solid rgba(255,255,255,.2)"></span></td>
        <td>
          @if($skill->is_active)
            <span style="color:#19c37d;font-size:12px;font-weight:700">● Aktif</span>
          @else
            <span style="color:#ff5470;font-size:12px;font-weight:700">○ Nonaktif</span>
          @endif
        </td>
        <td>
          @if($skill->in_orbit)
            <span style="color:#ff5500;font-size:12px;font-weight:700">🪐 Orbit</span>
          @else
            <span style="color:#94a3b8;font-size:12px">Tidak</span>
          @endif
        </td>
        <td>
          <details style="display:inline">
            <summary style="cursor:pointer;color:#7c5cff;font-size:13px;font-weight:700;list-style:none">Edit ▾</summary>
            <div style="position:absolute;z-index:10;background:#111936;border:1px solid var(--line);border-radius:14px;padding:16px;min-width:320px;margin-top:4px">
              <form method="POST" action="{{ route('admin.skills.update', $skill) }}">
                @csrf @method('PUT')
                <div style="display:grid;gap:8px">
                  <input name="name" value="{{ $skill->name }}" placeholder="Nama" required>
                  <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    <input name="level" type="number" min="0" max="100" value="{{ $skill->level }}" placeholder="Level">
                    <input name="years" type="number" min="0" max="50" value="{{ $skill->years }}" placeholder="Tahun">
                  </div>
                  <input name="category" value="{{ $skill->category }}" placeholder="Kategori">
                  <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    <input name="color" type="color" value="{{ $skill->color }}" style="height:40px;padding:4px">
                    <input name="sort_order" type="number" value="{{ $skill->sort_order }}" placeholder="Order">
                  </div>
                  <div style="display:flex;justify-content:space-between;gap:8px">
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
                      <input type="hidden" name="is_active" value="0">
                      <input type="checkbox" name="is_active" value="1" {{ $skill->is_active ? 'checked' : '' }} style="width:auto">
                      Aktif
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
                      <input type="hidden" name="in_orbit" value="0">
                      <input type="checkbox" name="in_orbit" value="1" {{ $skill->in_orbit ? 'checked' : '' }} style="width:auto">
                      Orbit 3D 🪐
                    </label>
                  </div>
                  <button class="btn" type="submit" style="font-size:13px;padding:8px 14px">Simpan</button>
                </div>
              </form>
            </div>
          </details>
          <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" style="display:inline;margin-left:8px" onsubmit="return confirm('Hapus skill {{ $skill->name }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn danger" style="font-size:12px;padding:6px 10px">✕</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:32px">Belum ada skill. Tambahkan di atas.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
