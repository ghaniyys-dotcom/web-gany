@extends('admin.layout')
@section('heading','Edit Website Content')
@section('content')
@php 
$stats=collect($site->stats??[])->map(fn($x)=>($x['value']??'').' | '.($x['label']??''))->implode("\n");
$stats_en=collect($site->stats_en??[])->map(fn($x)=>($x['value']??'').' | '.($x['label']??''))->implode("\n");

$services=collect($site->services??[])->map(fn($x)=>($x['icon']??'').' | '.($x['title']??'').' | '.($x['body']??''))->implode("\n");
$services_en=collect($site->services_en??[])->map(fn($x)=>($x['icon']??'').' | '.($x['title']??'').' | '.($x['body']??''))->implode("\n");

$works=collect(\App\Models\PortfolioWork::orderBy('sort_order')->get()->toArray())->map(function($x){
    $p = [
        str_replace(["\r", "\n", "|"], " ", $x['tag'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['title'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['body'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['image_url'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['project_url'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['client'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['challenge'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['solution'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['tech_stack'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['results'] ?? '')
    ];
    return implode(' | ', $p);
})->implode("\n");

$works_en=collect(\App\Models\PortfolioWork::orderBy('sort_order')->get()->toArray())->map(function($x){
    $p = [
        str_replace(["\r", "\n", "|"], " ", $x['tag_en'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['title'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['body_en'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['image_url'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['project_url'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['client'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['challenge_en'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['solution_en'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['tech_stack'] ?? ''),
        str_replace(["\r", "\n", "|"], " ", $x['results_en'] ?? '')
    ];
    return implode(' | ', $p);
})->implode("\n");

$pricing = $site->estimator_pricing ?? [
    'base_prices' => ['landing' => 3000000, 'compro' => 6000000, 'custom' => 12000000],
    'feature_prices' => ['animation' => 1500000, 'admin' => 2500000, 'seo' => 1000000, 'multilang' => 2000000]
];
$process_steps = collect($site->process_steps ?? [])->map(fn($x) => ($x['icon'] ?? '') . ' | ' . ($x['title'] ?? '') . ' | ' . ($x['metric'] ?? '') . ' | ' . ($x['body'] ?? $x['desc'] ?? ''))->implode("\n");
$process_steps_en = collect($site->process_steps_en ?? [])->map(fn($x) => ($x['icon'] ?? '') . ' | ' . ($x['title'] ?? '') . ' | ' . ($x['metric'] ?? '') . ' | ' . ($x['body'] ?? $x['desc'] ?? ''))->implode("\n");
@endphp

<div class="card">
    <div style="background: rgba(255, 106, 26, 0.04); border: 1px solid rgba(255, 106, 26, 0.15); border-radius: 16px; padding: 20px; margin-bottom: 30px; box-shadow: 0 0 15px rgba(255, 106, 26, 0.02)">
        <h4 style="color: #fff; margin: 0 0 8px; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <span style="color: var(--pri)">💡</span> Owner Comfort Hub — Tips Pengelolaan
        </h4>
        <p style="color: var(--muted); font-size: 13px; line-height: 1.6; margin: 0;">
            Selamat datang di halaman edit konten website! Di sini Anda dapat memperbarui seluruh teks layanan, langkah pengerjaan (*Process steps*), statistik performa, dan portofolio karya Anda. 
            Beberapa kolom yang sudah tidak digunakan di front-end (Tagline, Subtitle, dan CTA) telah disederhanakan agar Anda bisa fokus mengelola data utama dengan lebih nyaman dan bersih.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.content.update') }}">
        @csrf 
        @method('PUT')
        
        <h3 style="color: #fff; margin-top: 0; margin-bottom: 20px; font-weight: 500; font-size: 16px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
            🪐 Core Brand & Identity
        </h3>

        <div class="grid">
            <div class="field">
                <label>Brand Name</label>
                <input name="brand_name" value="{{ old('brand_name',$site->brand_name) }}" required>
            </div>
            <div class="field">
                <label>Logo Initials (2-4 huruf)</label>
                <input name="logo_initials" value="{{ old('logo_initials',$site->logo_initials) }}" maxlength="8" placeholder="AL">
            </div>
            <div class="field">
                <label>Email</label>
                <input name="email" value="{{ old('email',$site->email) }}" required>
            </div>
        </div>
        
        <div class="field" style="margin-bottom: 25px;">
            <label>WhatsApp (tanpa +)</label>
            <input name="whatsapp" value="{{ old('whatsapp',$site->whatsapp) }}">
        </div>

        <h3 style="color: #fff; margin-top: 40px; margin-bottom: 20px; font-weight: 500; font-size: 16px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
            🇮🇩 / 🇬🇧 Bilingual Hero Section
        </h3>

        <!-- Hero Title Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 35px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Hero Title — gunakan *kata* untuk font miring/oranye menyala (contoh: *trusted*)</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('hero_title', 'hero_title_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <textarea id="hero_title" name="hero_title" required>{{ old('hero_title',$site->hero_title) }}</textarea>
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <textarea id="hero_title_en" name="hero_title_en">{{ old('hero_title_en',$site->hero_title_en) }}</textarea>
            </div>
        </div>

        <h3 style="color: #fff; margin-top: 40px; margin-bottom: 20px; font-weight: 500; font-size: 16px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
            🇮🇩 / 🇬🇧 Bilingual Dynamic Elements & Structure
        </h3>

        <!-- Stats Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Stats — format per baris: value | label</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('stats_lines', 'stats_lines_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <textarea id="stats_lines" name="stats_lines">{{ old('stats_lines',$stats) }}</textarea>
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <textarea id="stats_lines_en" name="stats_lines_en">{{ old('stats_lines_en',$stats_en) }}</textarea>
            </div>
        </div>

        <!-- Services Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Services — format per baris: icon | title | body</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('services_lines', 'services_lines_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <textarea id="services_lines" name="services_lines" style="min-height:140px">{{ old('services_lines',$services) }}</textarea>
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <textarea id="services_lines_en" name="services_lines_en" style="min-height:140px">{{ old('services_lines_en',$services_en) }}</textarea>
            </div>
        </div>

        <!-- Process Steps Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Process Steps (Timeline) — format per baris: icon/key | title | metric/tagline | description</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('process_steps_lines', 'process_steps_lines_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <textarea id="process_steps_lines" name="process_steps_lines" style="min-height:140px">{{ old('process_steps_lines',$process_steps) }}</textarea>
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <textarea id="process_steps_lines_en" name="process_steps_lines_en" style="min-height:140px">{{ old('process_steps_lines_en',$process_steps_en) }}</textarea>
            </div>
        </div>

        <!-- Works Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 35px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Works Showcase — tag | title | body | image_url (opsional) | project_url (opsional) | client (opsional) | challenge (opsional) | solution (opsional) | tech_stack (opsional) | results (opsional)</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('works_lines', 'works_lines_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <textarea id="works_lines" name="works_lines" style="min-height:160px">{{ old('works_lines',$works) }}</textarea>
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <textarea id="works_lines_en" name="works_lines_en" style="min-height:160px">{{ old('works_lines_en',$works_en) }}</textarea>
            </div>
        </div>


        <!-- Promo Magnet / Newsletter Settings Section -->
        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 40px 0;">
        <h3 style="color: #fff; margin-bottom: 20px; font-weight: 500; font-size: 16px; display: flex; align-items: center; gap: 8px;">
            <span style="color: var(--purple)">✦</span> Pengaturan Promo Magnet / Newsletter
        </h3>

        <!-- Newsletter Title Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Judul Promo Magnet</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('newsletter_title', 'newsletter_title_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <input id="newsletter_title" name="newsletter_title" value="{{ old('newsletter_title',$site->newsletter_title) }}">
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <input id="newsletter_title_en" name="newsletter_title_en" value="{{ old('newsletter_title_en',$site->newsletter_title_en) }}">
            </div>
        </div>

        <!-- Newsletter Desc Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Deskripsi Promo Magnet</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('newsletter_desc', 'newsletter_desc_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <textarea id="newsletter_desc" name="newsletter_desc" style="min-height: 80px;">{{ old('newsletter_desc',$site->newsletter_desc) }}</textarea>
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <textarea id="newsletter_desc_en" name="newsletter_desc_en" style="min-height: 80px;">{{ old('newsletter_desc_en',$site->newsletter_desc_en) }}</textarea>
            </div>
        </div>


        <!-- Estimator Budget Settings Section -->
        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 40px 0;">
        <h3 style="color: #fff; margin-bottom: 20px; font-weight: 500; font-size: 16px; display: flex; align-items: center; gap: 8px;">
            <span style="color: var(--pri)">✦</span> Pengaturan Estimator Budget & Range
        </h3>
        
        <div class="field" style="margin-bottom: 25px;">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                <input type="checkbox" name="estimator_enabled" value="1" @checked(old('estimator_enabled', $site->estimator_enabled)) style="width: auto; margin: 0; transform: scale(1.2);">
                <strong style="color: #fff; font-size: 14.5px;">Aktifkan Kalkulator Estimasi Budget di Landing Page</strong>
            </label>
            <p style="color: var(--muted); font-size: 12.5px; margin: 6px 0 0 26px;">
                Jika dicentang, kalkulator budget interaktif akan muncul. Jika dimatikan, halaman depan akan menampilkan banner pengantar konsultasi yang bersahabat & semi-formal untuk negosiasi budget di balik layar.
            </p>
        </div>

        <div style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255, 255, 255, 0.04); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <h4 style="color: #fff; margin: 0 0 15px; font-size: 14px; font-weight: 500; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">1. Harga Dasar Tipe Website (Rupiah)</h4>
            <div class="grid">
                <div class="field">
                    <label>Landing Page</label>
                    <input type="number" name="price_landing" value="{{ old('price_landing', $pricing['base_prices']['landing'] ?? 3000000) }}" placeholder="3000000" min="0">
                </div>
                <div class="field">
                    <label>Company Profile</label>
                    <input type="number" name="price_compro" value="{{ old('price_compro', $pricing['base_prices']['compro'] ?? 6000000) }}" placeholder="6000000" min="0">
                </div>
                <div class="field">
                    <label>Custom SaaS</label>
                    <input type="number" name="price_custom" value="{{ old('price_custom', $pricing['base_prices']['custom'] ?? 12000000) }}" placeholder="12000000" min="0">
                </div>
            </div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255, 255, 255, 0.04); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <h4 style="color: #fff; margin: 0 0 15px; font-size: 14px; font-weight: 500; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">2. Harga Fitur Tambahan (Rupiah)</h4>
            <div class="grid">
                <div class="field">
                    <label>Premium Animations</label>
                    <input type="number" name="price_animation" value="{{ old('price_animation', $pricing['feature_prices']['animation'] ?? 1500000) }}" placeholder="1500000" min="0">
                </div>
                <div class="field">
                    <label>Custom CMS / Admin</label>
                    <input type="number" name="price_admin" value="{{ old('price_admin', $pricing['feature_prices']['admin'] ?? 2500000) }}" placeholder="2500000" min="0">
                </div>
            </div>
            <div class="grid" style="margin-top: 12px;">
                <div class="field">
                    <label>SEO Pack</label>
                    <input type="number" name="price_seo" value="{{ old('price_seo', $pricing['feature_prices']['seo'] ?? 1000000) }}" placeholder="1000000" min="0">
                </div>
                <div class="field">
                    <label>Multi-Language</label>
                    <input type="number" name="price_multilang" value="{{ old('price_multilang', $pricing['feature_prices']['multilang'] ?? 2000000) }}" placeholder="2000000" min="0">
                </div>
            </div>
        </div>

        <div class="field" style="margin-top: 20px; margin-bottom: 30px;">
            <label>Pilihan Dropdown Budget Range (Satu pilihan per baris)</label>
            <textarea name="budget_ranges_lines" style="min-height: 110px;" placeholder="Di bawah Rp 1 juta&#10;Rp 1 - 3 juta&#10;Rp 3 - 5 juta&#10;Rp 5 juta+">{{ old('budget_ranges_lines', implode("\n", $site->budget_ranges ?? ['Di bawah Rp 1 juta', 'Rp 1 - 3 juta', 'Rp 3 - 5 juta', 'Rp 5 juta+'])) }}</textarea>
            <p style="color: var(--muted); font-size: 12.5px; margin-top: 6px;">
                Tuliskan pilihan budget range yang akan muncul di dropdown form kontak. Gunakan akhiran "juta", "jt", "ribu", atau "rb" agar kalkulator budget interaktif dapat mencocokkan pilihan secara otomatis.
            </p>
        </div>

        <button class="btn" type="submit" style="width: 100%; padding: 15px; font-size: 15px; font-weight: 600; font-family: 'Space Grotesk';">Save All Content Changes</button>
    </form>
</div>

<style>
/* CSS styles for the Interactive List Builders */
.list-builder-container {
    background: rgba(255, 255, 255, 0.015);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: 16px;
    padding: 20px;
    margin-top: 10px;
    box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.3);
}

.list-builder-items {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 18px;
}

.list-builder-card {
    background: rgba(16, 16, 24, 0.5);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 18px;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    animation: cardFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.list-builder-card:hover {
    border-color: var(--line-hover);
    box-shadow: 0 8px 30px rgba(255, 85, 0, 0.03);
    transform: translateY(-2px);
}

@keyframes cardFadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

.list-builder-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    padding-bottom: 8px;
}

.list-builder-card-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: #fff;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.list-builder-card-index {
    color: var(--pri);
    background: rgba(255, 85, 0, 0.1);
    border: 1px solid rgba(255, 85, 0, 0.25);
    border-radius: 6px;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: bold;
}

.list-builder-card-controls {
    display: flex;
    gap: 6px;
}

.list-builder-control-btn {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    color: var(--muted);
    border-radius: 6px;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.list-builder-control-btn:hover {
    background: rgba(255, 85, 0, 0.1);
    border-color: rgba(255, 85, 0, 0.3);
    color: #fff;
}

.list-builder-control-btn.delete-btn:hover {
    background: rgba(255, 51, 85, 0.1);
    border-color: rgba(255, 51, 85, 0.3);
    color: var(--red);
}

.list-builder-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 14px;
}

/* 6-Column Grid Width Spans */
.grid-col-1 { grid-column: span 1; }
.grid-col-2 { grid-column: span 2; }
.grid-col-3 { grid-column: span 3; }
.grid-col-4 { grid-column: span 4; }
.grid-col-5 { grid-column: span 5; }
.grid-col-6 { grid-column: span 6; }

@media (max-width: 991px) {
    .list-builder-grid > div {
        grid-column: span 6 !important;
    }
}

.list-builder-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.list-builder-field label {
    font-size: 11px;
    color: var(--muted);
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 0;
}

.list-builder-field input, .list-builder-field textarea {
    width: 100%;
    background: rgba(10, 10, 15, 0.6);
    border: 1px solid var(--line);
    color: var(--text);
    border-radius: 10px;
    padding: 10px 14px;
    font-family: 'Outfit', sans-serif;
    font-size: 13.5px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
}

.list-builder-field input:focus, .list-builder-field textarea:focus {
    border-color: var(--pri);
    box-shadow: 0 0 12px var(--pri-glow), inset 0 0 6px rgba(255, 85, 0, 0.08);
    background: rgba(6, 6, 8, 0.85);
}

.list-builder-field textarea {
    min-height: 80px;
    resize: vertical;
}

.list-builder-add-btn {
    width: 100%;
    background: rgba(255, 85, 0, 0.03);
    border: 1px dashed rgba(255, 85, 0, 0.2);
    color: var(--pri);
    border-radius: 12px;
    padding: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 600;
    font-size: 13.5px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
}

.list-builder-add-btn:hover {
    background: rgba(255, 85, 0, 0.08);
    border-color: var(--pri);
    box-shadow: 0 4px 15px rgba(255, 85, 0, 0.15);
}
</style>

<script>
class InteractiveListBuilder {
    constructor(textareaId, fields, emptyDefault) {
        this.textarea = document.getElementById(textareaId);
        if (!this.textarea) return;

        this.fields = fields;
        this.emptyDefault = emptyDefault;

        // Parse initial value
        this.items = this.parseValue(this.textarea.value);

        // Hide raw textarea
        this.textarea.style.display = 'none';

        // Create builder container in DOM
        this.container = document.createElement('div');
        this.container.className = 'list-builder-container';
        this.textarea.parentNode.insertBefore(this.container, this.textarea.nextSibling);

        // Render UI
        this.render();

        // Listen for external updates (e.g. translate button)
        this.textarea.addEventListener('change', () => {
            this.items = this.parseValue(this.textarea.value);
            this.render();
        });
    }

    parseValue(text) {
        if (!text || !text.trim()) return [];
        return text.split('\n').map(line => {
            const cols = line.split('|').map(c => c.trim());
            const obj = {};
            this.fields.forEach((f, idx) => {
                obj[f.name] = cols[idx] || '';
            });
            return obj;
        });
    }

    compileValue() {
        const text = this.items.map(item => {
            return this.fields.map(f => {
                const val = (item[f.name] || '').toString();
                // strip pipe and newlines to preserve structural integrity
                return val.replace(/[\r\n|]/g, ' ').trim();
            }).join(' | ');
        }).join('\n');

        this.textarea.value = text;
    }

    render() {
        this.container.innerHTML = '';

        // Create list wrapper
        const itemsWrapper = document.createElement('div');
        itemsWrapper.className = 'list-builder-items';

        if (this.items.length === 0) {
            const emptyState = document.createElement('div');
            emptyState.style.cssText = 'text-align: center; padding: 30px; color: var(--muted); font-size: 13.5px;';
            emptyState.textContent = 'Belum ada data. Klik "+ Tambah Item Baru" di bawah untuk memulai.';
            itemsWrapper.appendChild(emptyState);
        } else {
            this.items.forEach((item, idx) => {
                const card = this.createCard(item, idx);
                itemsWrapper.appendChild(card);
            });
        }

        this.container.appendChild(itemsWrapper);

        // Create add button
        const addBtn = document.createElement('button');
        addBtn.type = 'button';
        addBtn.className = 'list-builder-add-btn';
        addBtn.innerHTML = `<span>➕</span> Tambah Item Baru`;
        addBtn.addEventListener('click', () => this.addItem());
        this.container.appendChild(addBtn);
    }

    createCard(item, idx) {
        const card = document.createElement('div');
        card.className = 'list-builder-card';

        // Dynamic title based on title or label if available, otherwise just number
        let displayTitle = '';
        if (item.title) displayTitle = item.title;
        else if (item.label) displayTitle = item.label;
        else if (item.value) displayTitle = item.value;
        else if (item.icon) displayTitle = item.icon;
        else if (item.tag) displayTitle = item.tag;

        if (displayTitle) {
            displayTitle = displayTitle.length > 30 ? displayTitle.substring(0, 30) + '...' : displayTitle;
        } else {
            displayTitle = `Item Baru`;
        }

        // Header
        const header = document.createElement('div');
        header.className = 'list-builder-card-header';
        header.innerHTML = `
            <div class="list-builder-card-title">
                <span class="list-builder-card-index">#${idx + 1}</span>
                <span>${displayTitle}</span>
            </div>
            <div class="list-builder-card-controls">
                <button type="button" class="list-builder-control-btn move-up-btn" title="Pindahkan Ke Atas">▲</button>
                <button type="button" class="list-builder-control-btn move-down-btn" title="Pindahkan Ke Bawah">▼</button>
                <button type="button" class="list-builder-control-btn delete-btn" title="Hapus Item">✕</button>
            </div>
        `;

        // Controls events
        header.querySelector('.move-up-btn').addEventListener('click', () => this.moveItem(idx, -1));
        header.querySelector('.move-down-btn').addEventListener('click', () => this.moveItem(idx, 1));
        header.querySelector('.delete-btn').addEventListener('click', () => this.deleteItem(idx));

        // Disable up/down if out of bounds
        if (idx === 0) {
            header.querySelector('.move-up-btn').style.opacity = '0.3';
            header.querySelector('.move-up-btn').style.pointerEvents = 'none';
        }
        if (idx === this.items.length - 1) {
            header.querySelector('.move-down-btn').style.opacity = '0.3';
            header.querySelector('.move-down-btn').style.pointerEvents = 'none';
        }

        // Grid
        const grid = document.createElement('div');
        grid.className = 'list-builder-grid';

        this.fields.forEach(f => {
            const fieldWrapper = document.createElement('div');
            fieldWrapper.className = `list-builder-field ${this.getGridClass(f.width)}`;

            const label = document.createElement('label');
            label.textContent = f.label;
            fieldWrapper.appendChild(label);

            let input;
            if (f.type === 'textarea') {
                input = document.createElement('textarea');
                input.value = item[f.name] || '';
                input.rows = 2;
            } else {
                input = document.createElement('input');
                input.type = 'text';
                input.value = item[f.name] || '';
            }

            input.placeholder = f.placeholder || '';

            // Handle live update
            const updateHandler = (e) => {
                const originalVal = e.target.value;
                const cleanVal = originalVal.replace(/[\r\n|]/g, ' ');
                
                if (originalVal !== cleanVal) {
                    const start = e.target.selectionStart;
                    const end = e.target.selectionEnd;
                    e.target.value = cleanVal;
                    e.target.setSelectionRange(start, end);
                }
                
                item[f.name] = cleanVal;
                
                // Update live header title if it is the title/label/value/icon/tag
                if (['title', 'label', 'value', 'icon', 'tag'].includes(f.name)) {
                    let newTitle = item.title || item.label || item.value || item.icon || item.tag || `Item Baru`;
                    newTitle = newTitle.length > 30 ? newTitle.substring(0, 30) + '...' : newTitle;
                    header.querySelector('.list-builder-card-title span:last-child').textContent = newTitle;
                }

                this.compileValue();
            };

            input.addEventListener('input', updateHandler);
            input.addEventListener('change', updateHandler);

            fieldWrapper.appendChild(input);
            grid.appendChild(fieldWrapper);
        });

        card.appendChild(header);
        card.appendChild(grid);
        return card;
    }

    getGridClass(width) {
        if (width === 'span 1') return 'grid-col-2';
        if (width === 'span 1.5') return 'grid-col-3';
        if (width === 'span 2') return 'grid-col-4';
        if (width === 'span 3') return 'grid-col-6';
        return 'grid-col-6';
    }

    addItem() {
        const newItem = { ...this.emptyDefault };
        this.items.push(newItem);
        this.compileValue();
        this.render();

        const cards = this.container.querySelectorAll('.list-builder-card');
        if (cards.length > 0) {
            const lastCard = cards[cards.length - 1];
            lastCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const firstInput = lastCard.querySelector('input, textarea');
            if (firstInput) firstInput.focus();
        }
    }

    deleteItem(idx) {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            this.items.splice(idx, 1);
            this.compileValue();
            this.render();
        }
    }

    moveItem(idx, direction) {
        const targetIdx = idx + direction;
        if (targetIdx < 0 || targetIdx >= this.items.length) return;

        const temp = this.items[idx];
        this.items[idx] = this.items[targetIdx];
        this.items[targetIdx] = temp;

        this.compileValue();
        this.render();

        setTimeout(() => {
            const cards = this.container.querySelectorAll('.list-builder-card');
            if (cards[targetIdx]) {
                cards[targetIdx].style.borderColor = 'var(--pri)';
                cards[targetIdx].style.boxShadow = '0 0 20px var(--pri-glow)';
                cards[targetIdx].scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => {
                    cards[targetIdx].style.borderColor = '';
                    cards[targetIdx].style.boxShadow = '';
                }, 1000);
            }
        }, 50);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // 1. Stats Builders
    const statsFields = [
        { name: 'value', label: 'Value', type: 'text', placeholder: 'e.g. 42+', width: 'span 1' },
        { name: 'label', label: 'Label', type: 'text', placeholder: 'e.g. projects shipped', width: 'span 2' }
    ];
    const statsDefault = { value: '', label: '' };
    new InteractiveListBuilder('stats_lines', statsFields, statsDefault);
    new InteractiveListBuilder('stats_lines_en', statsFields, statsDefault);

    // 2. Services Builders
    const servicesFields = [
        { name: 'icon', label: 'Icon', type: 'text', placeholder: 'e.g. ✦', width: 'span 1' },
        { name: 'title', label: 'Title', type: 'text', placeholder: 'e.g. Immersive Interface Design', width: 'span 2' },
        { name: 'body', label: 'Body / Description', type: 'textarea', placeholder: 'Presents an exclusive...', width: 'span 3' }
    ];
    const servicesDefault = { icon: '', title: '', body: '' };
    new InteractiveListBuilder('services_lines', servicesFields, servicesDefault);
    new InteractiveListBuilder('services_lines_en', servicesFields, servicesDefault);

    // 3. Process Steps Builders
    const processStepsFields = [
        { name: 'icon', label: 'Icon / Key', type: 'text', placeholder: 'e.g. Discover', width: 'span 1' },
        { name: 'title', label: 'Title', type: 'text', placeholder: 'e.g. Discover', width: 'span 1' },
        { name: 'metric', label: 'Metric / Tagline', type: 'text', placeholder: 'e.g. Tactics, Research, & Plan', width: 'span 1' },
        { name: 'description', label: 'Description', type: 'textarea', placeholder: 'Mapping goals...', width: 'span 3' }
    ];
    const processStepsDefault = { icon: '', title: '', metric: '', description: '' };
    new InteractiveListBuilder('process_steps_lines', processStepsFields, processStepsDefault);
    new InteractiveListBuilder('process_steps_lines_en', processStepsFields, processStepsDefault);

    // 4. Works Showcase Builders
    const worksFields = [
        { name: 'tag', label: 'Tag', type: 'text', placeholder: 'e.g. Product Launch', width: 'span 1' },
        { name: 'title', label: 'Project Title', type: 'text', placeholder: 'e.g. OrbitOS', width: 'span 1' },
        { name: 'client', label: 'Client (opsional)', type: 'text', placeholder: 'e.g. Nebula Capital', width: 'span 1' },
        { name: 'image_url', label: 'Image URL (opsional)', type: 'text', placeholder: 'e.g. images/mockup_orbit.png', width: 'span 1.5' },
        { name: 'project_url', label: 'Project URL (opsional)', type: 'text', placeholder: 'e.g. https://orbitos.demo', width: 'span 1.5' },
        { name: 'body', label: 'Brief Description', type: 'textarea', placeholder: 'Launch page SaaS dengan...', width: 'span 3' },
        { name: 'challenge', label: 'Challenge (opsional)', type: 'textarea', placeholder: 'Tantangan kami adalah...', width: 'span 1.5' },
        { name: 'solution', label: 'Solution (opsional)', type: 'textarea', placeholder: 'Solusi kami adalah...', width: 'span 1.5' },
        { name: 'tech_stack', label: 'Tech Stack (opsional)', type: 'text', placeholder: 'e.g. Laravel, React, Three.js', width: 'span 1.5' },
        { name: 'results', label: 'Results (opsional)', type: 'text', placeholder: 'e.g. Target konversi terlampaui...', width: 'span 1.5' }
    ];
    const worksDefault = { tag: '', title: '', client: '', image_url: '', project_url: '', body: '', challenge: '', solution: '', tech_stack: '', results: '' };
    new InteractiveListBuilder('works_lines', worksFields, worksDefault);
    new InteractiveListBuilder('works_lines_en', worksFields, worksDefault);
});
</script>
@endsection