@extends('layouts.app')
@section('title', 'About — '.$site->brand_name)
@section('content')
<div class="page-hero">
<div class="wrap">
@if($intro->availability_enabled)
<span class="availability-badge {{ $intro->is_available ? 'available' : 'busy' }}" style="margin-bottom:20px">
  <span class="availability-dot"></span>
  {{ $intro->availability_text }}
</span>
@endif
<span class="kicker">About Us</span>
<h1>We build digital presence that actually works.</h1>
<p>{{ $site->brand_name }} adalah partner digital yang fokus bikin company profile, web experience, dan growth system yang rapi dari strategi sampai launch.</p>
</div>
</div>
<section class="section">
<div class="wrap">
<div class="grid-2">
<div>
<span class="kicker">Our Story</span>
<h2>Dari konsep sampai launch, kita handle semuanya.</h2>
<p>Kita percaya website bukan cuma brosur digital. Website harus jadi aset bisnis yang bisa bikin orang percaya, tertarik, dan akhirnya hubungi kamu.</p>
<br>
<a class="btn btn-primary" href="{{ route('home') }}#contact">Konsultasi Gratis →</a>
</div>
<div class="mini-card" style="border-radius:32px;padding:40px">
<span class="kicker" style="color:var(--cyan)">Why Us</span>
<h3 style="color:#fff;font-size:28px;margin:14px 0 10px">Premium look. Real results.</h3>
<ul style="list-style:none;padding:0;margin:0;display:grid;gap:14px">
<li style="display:flex;gap:10px"><span style="color:var(--green)">✓</span><span style="color:#cbd5e1">Desain premium yang bikin brand keliatan mahal</span></li>
<li style="display:flex;gap:10px"><span style="color:var(--green)">✓</span><span style="color:#cbd5e1">Struktur SEO-friendly dan cepat</span></li>
<li style="display:flex;gap:10px"><span style="color:var(--green)">✓</span><span style="color:#cbd5e1">Admin panel untuk edit konten</span></li>
</ul>
</div>
</div>
</div>
</section>

@if($skills->isNotEmpty())
<section class="section section-alt">
<div class="wrap">
<div style="text-align:center;margin-bottom:40px">
<span class="kicker">Skills & Expertise</span>
<h2>Skill Constellation.</h2>
<p style="max-width:520px;margin:12px auto 0">Hover ke titik bintang untuk melihat detail. Setiap node adalah skill yang dipelajari dan diasah dalam project nyata.</p>
</div>
<div class="constellation-wrapper" style="max-width:900px;margin:0 auto">
<canvas id="skill-canvas" height="500"></canvas>
<div class="constellation-tooltip" id="skillTooltip">
  <div class="tt-name" id="ttName">—</div>
  <div class="tt-cat" id="ttCat">—</div>
  <div class="tt-bar"><div class="tt-fill" id="ttFill" style="width:0%;background:#ff6a1a"></div></div>
  <div class="tt-stats">
    <span id="ttLevel">0%</span>
    <span id="ttYears">0 years</span>
  </div>
</div>
</div>
<script>
@php
$skillsJson = $skills->map(function($s) {
  return [
    'name'     => $s->name,
    'level'    => $s->level,
    'years'    => $s->years,
    'category' => $s->category,
    'color'    => $s->color,
  ];
});
@endphp
window._skillData = @json($skillsJson);
</script>
</div>
</section>
@endif

<section class="section section-alt" style="{{ $skills->isNotEmpty() ? '' : '' }}">
<div class="wrap">
<div style="text-align:center;margin-bottom:30px">
<span class="kicker">By The Numbers</span>
<h2>Angka yang bikin bangga.</h2>
</div>
<div class="stat-grid">
@foreach(($site->stats ?? []) as $stat)
<div class="stat">
<b>{{ $stat['value'] ?? '' }}</b>
<small>{{ $stat['label'] ?? '' }}</small>
</div>
@endforeach
</div>
</div>
</section>
<section class="section">
<div class="wrap" style="text-align:center">
<span class="kicker">Ready?</span>
<h2>Yuk ngobrolin project kamu.</h2>
<p style="max-width:560px;margin:0 auto 24px">Isi form contact atau chat WhatsApp. Kita bantu dari konsep sampai launch.</p>
<div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
<a class="btn btn-primary" href="{{ route('home') }}#contact">Konsultasi Gratis →</a>
@if($site->whatsapp)
<a class="btn btn-ghost" href="https://wa.me/{{ $site->whatsapp }}">WhatsApp</a>
@endif
</div>
</div>
</section>
@endsection

