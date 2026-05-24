@extends('layouts.app')

@section('title', ($work['title'] ?? 'Case Study') . ' — ' . $site->brand_name)
@section('meta_description', __('Case study mendalam untuk proyek') . ' ' . ($work['title'] ?? '') . ' ' . __('oleh') . ' ' . $site->brand_name)

@section('content')
<div class="page-hero">
    <div class="wrap" style="position: relative; z-index: 2;">
        <span class="kicker">{{ $work['tag'] ?? __('Case Study') }}</span>
        <h1 style="max-width: 800px; margin: 0 auto 24px auto;">{{ $work['title'] ?? '' }}</h1>
        <p class="lead" style="max-width: 650px; margin: 0 auto;">{{ $work['body'] ?? '' }}</p>
    </div>
</div>

<section class="section" style="padding-top: 0;">
    <div class="wrap">
        {{-- Showcase Browser Mockup --}}
        <div class="browser-mockup" style="margin-bottom: 50px; box-shadow: 0 30px 60px rgba(0,0,0,0.4); border-color: rgba(255, 106, 26, 0.15);">
            <div class="browser-bar">
                <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                <div class="browser-address">{{ !empty($work['project_url']) ? $work['project_url'] : 'https://yoursite.com' }}</div>
            </div>
            <div class="browser-content" style="height: 480px;">
                @if(!empty($work['image_url']))
                    <img src="{{ asset($work['image_url']) }}" alt="{{ $work['title'] ?? '' }}" class="browser-img">
                @else
                    <div class="browser-placeholder">
                        <span>✦ {{ $work['title'] ?? '' }}</span>
                        <p>{{ __('Interactive Premium Showcase') }}</p>
                    </div>
                @endif
                @if(!empty($work['project_url']))
                    <div class="browser-overlay">
                        <a href="{{ $work['project_url'] }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">{{ __('Visit Live Site ↗') }}</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Asymmetric Case Study Content --}}
        <div class="founder-grid" style="grid-template-columns: 1fr 2fr; gap: 60px; align-items: start;">
            {{-- Left column: Meta Info Card --}}
            <div class="card" style="padding: 30px; position: sticky; top: 100px; border: 1px solid rgba(255, 255, 255, 0.05); background: rgba(255, 255, 255, 0.01); backdrop-filter: blur(10px);">
                <h3 style="margin-top: 0; font-size: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 15px; color: var(--purple);">{{ __('Project Details') }}</h3>
                
                <div style="margin-bottom: 20px;">
                    <span style="display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); margin-bottom: 4px;">{{ __('Client') }}</span>
                    <strong style="font-size: 16px; color: var(--navy);">{{ !empty($work['client']) ? $work['client'] : __('Confidential Client') }}</strong>
                </div>

                <div style="margin-bottom: 20px;">
                    <span style="display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); margin-bottom: 4px;">{{ __('Category') }}</span>
                    <strong style="font-size: 16px; color: var(--navy);">{{ $work['tag'] ?? __('Web Engineering') }}</strong>
                </div>

                @if(!empty($work['tech_stack']))
                <div style="margin-bottom: 20px;">
                    <span style="display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); margin-bottom: 4px;">{{ __('Tech Stack') }}</span>
                    <div style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px;">
                        @foreach(explode(',', $work['tech_stack']) as $tech)
                            <span class="tag" style="margin: 0; font-size: 11px; background: rgba(255, 106, 26, 0.08); border-color: rgba(255, 106, 26, 0.20); color: var(--purple);">{{ trim($tech) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($work['project_url']))
                <a href="{{ $work['project_url'] }}" target="_blank" rel="noopener" class="btn btn-primary" style="display: block; text-align: center; margin-top: 30px; width: 100%;">{{ __('Visit Live Site ↗') }}</a>
                @endif
            </div>

            {{-- Right column: Case Study Text --}}
            <div style="display: grid; gap: 40px;">
                @if(!empty($work['challenge']))
                <div>
                    <span class="founder-eyebrow" style="margin-bottom: 8px;">{{ __('THE CHALLENGE') }}</span>
                    <h2 style="font-size: 32px; margin-top: 0; margin-bottom: 16px;">{{ __('Tantangan Proyek') }}</h2>
                    <p style="font-size: 17px; line-height: 1.8; color: var(--muted); white-space: pre-line;">{{ $work['challenge'] }}</p>
                </div>
                @endif

                @if(!empty($work['solution']))
                <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 40px;">
                    <span class="founder-eyebrow" style="margin-bottom: 8px; color: var(--purple);">{{ __('THE SOLUTION') }}</span>
                    <h2 style="font-size: 32px; margin-top: 0; margin-bottom: 16px;">{{ __('Pendekatan & Solusi') }}</h2>
                    <p style="font-size: 17px; line-height: 1.8; color: var(--muted); white-space: pre-line;">{{ $work['solution'] }}</p>
                </div>
                @endif

                @if(!empty($work['results']))
                <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 40px;">
                    <span class="founder-eyebrow" style="margin-bottom: 8px; color: #ff6a1a;">{{ __('THE RESULTS & IMPACT') }}</span>
                    <h2 style="font-size: 32px; margin-top: 0; margin-bottom: 16px;">{{ __('Hasil & Dampak Proyek') }}</h2>
                    <p style="font-size: 17px; line-height: 1.8; color: var(--muted); white-space: pre-line;">{{ $work['results'] }}</p>
                </div>
                @endif

                {{-- Default fallback if no story entered --}}
                @if(empty($work['challenge']) && empty($work['solution']))
                <div>
                    <span class="founder-eyebrow" style="margin-bottom: 8px;">{{ __('OVERVIEW') }}</span>
                    <h2 style="font-size: 32px; margin-top: 0; margin-bottom: 16px;">{{ __('Deskripsi Proyek') }}</h2>
                    <p style="font-size: 17px; line-height: 1.8; color: var(--muted);">{{ __('Proyek ini dirancang dan dikembangkan dengan standar estetika tertinggi. Kami merancang arsitektur visual asimetris yang memaksimalkan konversi, kecepatan loading halaman yang instan, serta integrasi manajemen data kustom menggunakan Laravel.') }}</p>
                    <p style="font-size: 17px; line-height: 1.8; color: var(--muted);">{{ __('Hubungi kami jika Anda ingin membangun kehadiran digital premium seperti ini untuk brand atau perusahaan Anda.') }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Navigation Footer --}}
        <div style="margin-top: 80px; padding-top: 30px; border-top: 1px solid rgba(255, 255, 255, 0.08); display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('portfolio') }}" class="btn btn-ghost">← {{ __('Kembali ke Portfolio') }}</a>
            <a href="{{ route('home') }}#contact" class="btn btn-primary">{{ __('Mulai Project Baru →') }}</a>
        </div>
    </div>
</section>
@endsection
