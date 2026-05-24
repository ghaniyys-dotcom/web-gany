@extends('admin.layout')
@section('heading', 'Manage Testimonials')
@section('content')
<div class="card">
    <h3 style="margin-top:0; margin-bottom:15px; font-size:16px;">Tambah Testimonial Baru</h3>
    <form method="POST" action="{{ route('admin.testimonials.store') }}">
        @csrf
        <div class="grid">
            <div class="field">
                <label>Nama Client</label>
                <input name="name" required>
            </div>
            <div class="field">
                <label>Role</label>
                <input name="role" placeholder="CEO / Founder / Product Owner">
            </div>
            <div class="field">
                <label>Company</label>
                <input name="company" placeholder="Asteria Studio">
            </div>
        </div>

        <!-- Quote Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 16px; margin-bottom: 16px; margin-top: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-weight: 500; font-size: 13px; color: #fff;">Quote / Testimonial</label>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 3px 8px; font-size: 10px; font-weight: 600; border-radius: 4px; cursor: pointer; font-family: 'Space Grotesk';" onclick="translateField('q_new', 'qe_new', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 10px;">
                <textarea id="q_new" name="quote" placeholder="Tulis review/quote dalam Bahasa Indonesia..." required style="min-height: 80px; font-size: 13px;"></textarea>
            </div>
            <div class="field">
                <textarea id="qe_new" name="quote_en" placeholder="English translation..." style="min-height: 80px; font-size: 13px;"></textarea>
            </div>
        </div>

        <div class="field" style="display:flex; align-items:center; margin-top:15px; margin-bottom: 15px;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" checked style="width:auto;"> Aktif / Active
            </label>
        </div>

        <button class="btn" style="width:100%; font-family: 'Space Grotesk';">Tambah Testimonial</button>
    </form>
</div>

<h3 style="color:#fff; margin-top:40px; margin-bottom:20px; font-size:16px;">Daftar Testimonial Aktif</h3>

@foreach($items as $item)
<div class="card">
    <form method="POST" action="{{ route('admin.testimonials.update', $item) }}">
        @csrf @method('PUT')
        <div class="grid">
            <div class="field">
                <label>Nama Client</label>
                <input name="name" value="{{ $item->name }}" required>
            </div>
            <div class="field">
                <label>Role</label>
                <input name="role" value="{{ $item->role }}">
            </div>
            <div class="field">
                <label>Company</label>
                <input name="company" value="{{ $item->company }}">
            </div>
        </div>

        <!-- Quote Edit Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 16px; margin-bottom: 16px; margin-top: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-weight: 500; font-size: 13px; color: #fff;">Quote / Testimonial</label>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 3px 8px; font-size: 10px; font-weight: 600; border-radius: 4px; cursor: pointer; font-family: 'Space Grotesk';" onclick="translateField('q_{{ $item->id }}', 'qe_{{ $item->id }}', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 10px;">
                <textarea id="q_{{ $item->id }}" name="quote" required style="min-height: 80px; font-size: 13px;">{{ $item->quote }}</textarea>
            </div>
            <div class="field">
                <textarea id="qe_{{ $item->id }}" name="quote_en" placeholder="English translation..." style="min-height: 80px; font-size: 13px;">{{ $item->quote_en }}</textarea>
            </div>
        </div>

        <div class="field" style="display:flex; align-items:center; margin-top:15px; margin-bottom: 15px;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" @checked($item->is_active) style="width:auto;"> Aktif / Active
            </label>
        </div>

        <button class="btn" style="width:100%; font-family: 'Space Grotesk';">Simpan Perubahan</button>
    </form>
    <form method="POST" action="{{ route('admin.testimonials.destroy', $item) }}" style="margin-top:10px" onsubmit="return confirm('Hapus testimonial ini?')">
        @csrf @method('DELETE')
        <button class="btn danger" type="submit" style="width:100%;">Hapus Testimonial</button>
    </form>
</div>
@endforeach
@endsection
