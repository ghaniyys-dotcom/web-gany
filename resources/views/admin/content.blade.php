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
        $x['tag'] ?? '',
        $x['title'] ?? '',
        $x['body'] ?? '',
        $x['image_url'] ?? '',
        $x['project_url'] ?? '',
        $x['client'] ?? '',
        $x['challenge'] ?? '',
        $x['solution'] ?? '',
        $x['tech_stack'] ?? '',
        $x['results'] ?? ''
    ];
    return implode(' | ', $p);
})->implode("\n");

$works_en=collect(\App\Models\PortfolioWork::orderBy('sort_order')->get()->toArray())->map(function($x){
    $p = [
        $x['tag_en'] ?? '',
        $x['title'] ?? '',
        $x['body_en'] ?? '',
        $x['image_url'] ?? '',
        $x['project_url'] ?? '',
        $x['client'] ?? '',
        $x['challenge_en'] ?? '',
        $x['solution_en'] ?? '',
        $x['tech_stack'] ?? '',
        $x['results_en'] ?? ''
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
            🇮🇩 / 🇬🇧 Bilingual Hero Section & Tagline
        </h3>

        <!-- Tagline Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Tagline</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('tagline', 'tagline_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <input id="tagline" name="tagline" value="{{ old('tagline',$site->tagline) }}">
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <input id="tagline_en" name="tagline_en" value="{{ old('tagline_en',$site->tagline_en) }}">
            </div>
        </div>

        <!-- Hero Title Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
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

        <!-- Hero Subtitle Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Hero Subtitle</h4>
                <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 5px 12px; font-size: 11px; font-weight: 600; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s; font-family: 'Space Grotesk';" onclick="translateField('hero_subtitle', 'hero_subtitle_en', this)">⚡ Auto-Translate</button>
            </div>
            <div class="field" style="margin-bottom: 12px;">
                <label style="font-size: 12px; color: var(--muted);">Indonesian version</label>
                <textarea id="hero_subtitle" name="hero_subtitle" required>{{ old('hero_subtitle',$site->hero_subtitle) }}</textarea>
            </div>
            <div class="field">
                <label style="font-size: 12px; color: #ff5500;">English version</label>
                <textarea id="hero_subtitle_en" name="hero_subtitle_en">{{ old('hero_subtitle_en',$site->hero_subtitle_en) }}</textarea>
            </div>
        </div>

        <!-- CTA Buttons Bilingual Group -->
        <div style="background: rgba(255, 255, 255, 0.015); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; margin-bottom: 35px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                <h4 style="color: #fff; margin: 0; font-size: 14px; font-weight: 500;">Call to Action (CTA) Buttons</h4>
                <div style="display: flex; gap: 8px;">
                    <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 4px 10px; font-size: 10px; font-weight: 600; border-radius: 6px; cursor: pointer; font-family: 'Space Grotesk';" onclick="translateField('primary_cta', 'primary_cta_en', this)">Primary CTA</button>
                    <button type="button" style="background: rgba(57, 255, 20, 0.1); color: var(--green); border: 1px solid rgba(57, 255, 20, 0.3); padding: 4px 10px; font-size: 10px; font-weight: 600; border-radius: 6px; cursor: pointer; font-family: 'Space Grotesk';" onclick="translateField('secondary_cta', 'secondary_cta_en', this)">Secondary CTA</button>
                </div>
            </div>
            <div class="grid">
                <div class="field">
                    <label>Primary CTA (ID)</label>
                    <input id="primary_cta" name="primary_cta" value="{{ old('primary_cta',$site->primary_cta) }}">
                </div>
                <div class="field">
                    <label style="color: #ff5500;">Primary CTA (EN)</label>
                    <input id="primary_cta_en" name="primary_cta_en" value="{{ old('primary_cta_en',$site->primary_cta_en) }}">
                </div>
            </div>
            <div class="grid" style="margin-top: 15px;">
                <div class="field">
                    <label>Secondary CTA (ID)</label>
                    <input id="secondary_cta" name="secondary_cta" value="{{ old('secondary_cta',$site->secondary_cta) }}">
                </div>
                <div class="field">
                    <label style="color: #ff5500;">Secondary CTA (EN)</label>
                    <input id="secondary_cta_en" name="secondary_cta_en" value="{{ old('secondary_cta_en',$site->secondary_cta_en) }}">
                </div>
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
@endsection