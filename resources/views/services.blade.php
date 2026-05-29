@extends('layouts.app')
@section('title', 'Services — '.$site->brand_name)
@section('content')
<div class="page-hero">
<div class="wrap">
<span class="kicker">Services</span>
<h1>{{ __('Everything your company profile needs.') }}</h1>
<p>{{ __('Website yang bukan cuma cantik, tapi juga menjual dan gampang dikelola.') }}</p>
</div>
</div>
<section class="section">
<div class="wrap">
<div class="cards">
@foreach(($site->services ?? []) as $service)
<div class="card">
<div class="icon">{{ $service['icon'] ?? '✦' }}</div>
<h3>{{ $service['title'] ?? '' }}</h3>
<p>{{ $service['body'] ?? '' }}</p>
</div>
@endforeach
</div>
</div>
</section>
<section class="section section-alt">
<div class="wrap">
<div style="text-align:center;margin-bottom:30px">
<span class="kicker">{{ __('Process') }}</span>
<h2>{{ __('Cara kerja kita simpel.') }}</h2>
</div>
<div class="process-inline">
@foreach([['Discover','Mapping tujuan, audience, dan konten penting.'],['Design','Visual premium, hierarchy jelas, mobile-first.'],['Build','Laravel, database, admin panel, contact flow.'],['Launch','Testing, polish, deploy-ready.']] as $i => $s)
<div class="process-card">
<span style="color:var(--purple);font-family:'Source Code Pro';font-weight:900">0{{ $i + 1 }}</span>
<h3 style="margin:14px 0 8px">{{ __($s[0]) }}</h3>
<p style="margin:0">{{ __($s[1]) }}</p>
</div>
@endforeach
</div>
</div>
</section>
<section class="section">
<div class="wrap" style="text-align:center">
<span class="kicker">Pricing</span>
<h2 style="margin-bottom:30px">{{ __('Transparan, tanpa hidden cost.') }}</h2>
<div class="pricing-grid">
<div class="card pricing-card">
<span style="color:var(--purple);font-weight:800;font-size:14px">STARTER</span><br>
<b style="font-size:36px;color:var(--navy)">Rp 2.5jt</b>
<p>{{ __('Company profile 3-5 pages, responsive, contact form, 1x revision.') }}</p>
<a class="btn btn-ghost" href="{{ route('home') }}#contact">{{ __('Pilih →') }}</a>
</div>
<div class="card pricing-card highlight">
<span style="color:var(--purple);font-weight:800;font-size:14px">PRO</span><br>
<b style="font-size:36px;color:var(--navy)">Rp 5jt</b>
<p>{{ __('5-8 pages, admin panel, SEO, newsletter, 3x revision.') }}</p>
<a class="btn btn-primary" href="{{ route('home') }}#contact">{{ __('Pilih →') }}</a>
</div>
<div class="card pricing-card">
<span style="color:var(--purple);font-weight:800;font-size:14px">CUSTOM</span><br>
<b style="font-size:36px;color:var(--navy)">Quote</b>
<p>{{ __('Complex project, e-commerce, custom features.') }}</p>
<a class="btn btn-ghost" href="{{ route('home') }}#contact">{{ __('Diskusi →') }}</a>
</div>
</div>
</div>
</section>
<section class="section section-alt">
<div class="wrap" style="text-align:center">
<h2 style="margin-bottom:18px">{{ __('Siap bikin website kamu?') }}</h2>
<a class="btn btn-primary" href="{{ route('home') }}#contact">{{ __('Konsultasi Gratis →') }}</a>
</div>
</section>
@endsection
