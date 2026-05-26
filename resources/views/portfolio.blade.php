@extends('layouts.app')
@section('title', 'Portfolio — '.$site->brand_name)
@section('meta_description', __('Showcase project dan hasil kerja').' '.$site->brand_name)
@section('content')
<div class="page-hero">
<div class="wrap">
<span class="kicker">Portfolio</span>
<h1>{{ __('Project yang udah kita bantu wujudkan.') }}</h1>
<p>{{ __('Koleksi showcase dari company profile, product launch, sampai brand refresh — semua bisa diedit dari admin.') }}</p>
</div>
</div>
<section class="section">
<div class="wrap">
<div class="portfolio-grid">
@forelse(($site->works ?? []) as $work)
@php $slug = \Illuminate\Support\Str::slug($work['title'] ?? ''); @endphp
<article class="card portfolio-card">
  <div class="card-inner">
    <div class="browser-mockup" style="margin-top:0;margin-bottom:20px">
      <div class="browser-bar">
        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
        <div class="browser-address">{{ !empty($work['project_url']) ? $work['project_url'] : 'https://yoursite.com' }}</div>
      </div>
      <div class="browser-content">
        @if(!empty($work['image_url']))
          <img src="{{ asset($work['image_url']) }}" alt="{{ $work['title'] ?? '' }}" class="browser-img">
        @else
          <div class="browser-placeholder">
            <span>✦ {{ __('Premium Showcase') }}</span>
            <p>{{ __('Preview website interaktif') }}</p>
          </div>
        @endif
        <div class="browser-overlay">
          <a href="{{ !empty($slug) ? route('portfolio.detail', $slug) : '#' }}" class="btn btn-primary btn-sm">{{ __('View Case Study ↗') }}</a>
        </div>
      </div>
    </div>
    <span class="tag">{{ $work['tag'] ?? 'Project' }}</span>
    <h3><a href="{{ !empty($slug) ? route('portfolio.detail', $slug) : '#' }}" style="color:inherit;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--purple)'" onmouseout="this.style.color='inherit'">{{ $work['title'] ?? '' }}</a></h3>
    <p>{{ $work['body'] ?? '' }}</p>
    <div style="display:flex;gap:10px;margin-top:14px;">
      <a class="btn btn-primary" href="{{ !empty($slug) ? route('portfolio.detail', $slug) : '#' }}" style="padding:6px 12px;font-size:13px;display:inline-flex;width:fit-content;margin:0">{{ __('Read Case Study →') }}</a>
      @if(!empty($work['project_url']))
        <a class="btn btn-ghost" href="{{ $work['project_url'] }}" target="_blank" rel="noopener" style="padding:6px 12px;font-size:13px;display:inline-flex;width:fit-content;margin:0">{{ __('Live Site ↗') }}</a>
      @endif
    </div>
  </div>
</article>
@empty
<article class="card portfolio-card">
  <div class="card-inner">
    <p>{{ __('Belum ada project. Tambahkan dari admin panel → Edit Website → Works.') }}</p>
  </div>
</article>
@endforelse
</div>
<div style="text-align:center;margin-top:40px">
<a class="btn btn-primary" href="{{ route('home') }}#contact">{{ __('Mulai Project Kamu →') }}</a>
</div>
</div>
</section>
@endsection
