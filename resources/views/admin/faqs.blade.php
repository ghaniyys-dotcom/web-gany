@extends('admin.layout')
@section('heading', 'Manage FAQ')
@section('content')
<div class="card">
    <h3 style="margin-top:0; margin-bottom:15px; font-size:16px;">Tambah FAQ Baru</h3>
    <form method="POST" action="{{ route('admin.faqs.store') }}">
        @csrf
        
        <!-- Question Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-weight: 500; font-size: 13px; color: #fff;">Pertanyaan / Question</label>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 3px 8px; font-size: 10px; font-weight: 600; border-radius: 4px; cursor: pointer; font-family: 'Space Grotesk';" onclick="translateField('q_new', 'qe_new', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 10px;">
                <input id="q_new" name="question" placeholder="Tulis pertanyaan dalam Bahasa Indonesia..." required style="font-size: 13px;">
            </div>
            <div class="field">
                <input id="qe_new" name="question_en" placeholder="English translation..." style="font-size: 13px;">
            </div>
        </div>

        <!-- Answer Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-weight: 500; font-size: 13px; color: #fff;">Jawaban / Answer</label>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 3px 8px; font-size: 10px; font-weight: 600; border-radius: 4px; cursor: pointer; font-family: 'Space Grotesk';" onclick="translateField('a_new', 'ae_new', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 10px;">
                <textarea id="a_new" name="answer" placeholder="Tulis jawaban dalam Bahasa Indonesia..." required style="min-height: 80px; font-size: 13px;"></textarea>
            </div>
            <div class="field">
                <textarea id="ae_new" name="answer_en" placeholder="English translation..." style="min-height: 80px; font-size: 13px;"></textarea>
            </div>
        </div>

        <div class="grid" style="margin-bottom: 15px;">
            <div class="field">
                <label>Urutan / Sort Order</label>
                <input name="sort_order" type="number" value="0">
            </div>
            <div class="field" style="display:flex; align-items:center; margin-top:25px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="width:auto;"> Aktif / Active
                </label>
            </div>
        </div>

        <button class="btn" style="width:100%; font-family: 'Space Grotesk';">Tambah FAQ</button>
    </form>
</div>

<h3 style="color:#fff; margin-top:40px; margin-bottom:20px; font-size:16px;">Daftar FAQ Aktif</h3>

@foreach($items as $item)
<div class="card">
    <form method="POST" action="{{ route('admin.faqs.update', $item) }}">
        @csrf @method('PUT')
        
        <!-- Question Edit Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-weight: 500; font-size: 13px; color: #fff;">Pertanyaan / Question</label>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 3px 8px; font-size: 10px; font-weight: 600; border-radius: 4px; cursor: pointer; font-family: 'Space Grotesk';" onclick="translateField('q_{{ $item->id }}', 'qe_{{ $item->id }}', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 10px;">
                <input id="q_{{ $item->id }}" name="question" value="{{ $item->question }}" required style="font-size: 13px;">
            </div>
            <div class="field">
                <input id="qe_{{ $item->id }}" name="question_en" value="{{ $item->question_en }}" placeholder="English translation..." style="font-size: 13px;">
            </div>
        </div>

        <!-- Answer Edit Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 16px; margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label style="font-weight: 500; font-size: 13px; color: #fff;">Jawaban / Answer</label>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 3px 8px; font-size: 10px; font-weight: 600; border-radius: 4px; cursor: pointer; font-family: 'Space Grotesk';" onclick="translateField('a_{{ $item->id }}', 'ae_{{ $item->id }}', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 10px;">
                <textarea id="a_{{ $item->id }}" name="answer" required style="min-height: 80px; font-size: 13px;">{{ $item->answer }}</textarea>
            </div>
            <div class="field">
                <textarea id="ae_{{ $item->id }}" name="answer_en" placeholder="English translation..." style="min-height: 80px; font-size: 13px;">{{ $item->answer_en }}</textarea>
            </div>
        </div>

        <div class="grid" style="margin-bottom: 15px;">
            <div class="field">
                <label>Urutan / Sort Order</label>
                <input name="sort_order" type="number" value="{{ $item->sort_order }}">
            </div>
            <div class="field" style="display:flex; align-items:center; margin-top:25px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" @checked($item->is_active) style="width:auto;"> Aktif / Active
                </label>
            </div>
        </div>

        <button class="btn" style="width:100%; font-family: 'Space Grotesk';">Simpan Perubahan</button>
    </form>
    <form method="POST" action="{{ route('admin.faqs.destroy', $item) }}" style="margin-top:10px" onsubmit="return confirm('Hapus FAQ ini?')">
        @csrf @method('DELETE')
        <button class="btn danger" type="submit" style="width:100%;">Hapus FAQ</button>
    </form>
</div>
@endforeach
@endsection
