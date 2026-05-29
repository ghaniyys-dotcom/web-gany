<!DOCTYPE html>
<html lang="id">

<head>
  @include('partials.head-meta')
  @vite(['resources/js/app.js'])
  <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "Organization",
      "name": "{{ $site->brand_name }}",
      "url": "{{ url('/') }}",
      "description": "{{ $site->tagline }}",
      "email": "{{ $site->email }}",
      "sameAs": []
    }
  </script>
</head>

<body class="page-glow">
  {{-- Premium Masterpiece: Film Grain Texturing Overlay --}}
  <div class="film-grain"></div>

  {{-- Premium Masterpiece: Custom Cursor --}}
  <div id="pm-cursor"></div>
  <div id="pm-cursor-aura"></div>
  {{-- Premium Masterpiece: Page Transition Overlay --}}
  <div class="page-transition-overlay" id="pageTransition"></div>

  {{-- Premium Masterpiece: Cinematic Intro Overlay --}}
  @if($intro->is_enabled)
  <div id="intro-overlay" onclick="dismissIntro()" style="cursor: pointer;">
    <!-- 🎭 Dual-Panel Split Curtain Background Layers -->
    <div class="intro-curtain curtain-left"></div>
    <div class="intro-curtain curtain-right"></div>

    <div class="intro-content-wrapper" style="position: relative; z-index: 10; width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 0;">
      <div class="intro-glow-orb"></div>
      <canvas id="ember-particles"></canvas>

      <!-- 🎬 Giant Defocused Warm-White Serif 'halo' Centerpiece -->
      <div class="intro-line intro-name" id="iLine1">halo</div>
    </div>
  </div>
  @endif

  @include('partials.nav', ['homeAnchors' => true])

  <header class="hero-fullscreen">
    {{-- Hologram Centerpiece (Immersive centered background globe behind typography) --}}
    <div class="hero-hologram-centerpiece">
      <div id="hero-3d-hologram" class="hero-3d-container" style="width: 100%; height: 100%;" data-logos="{{ json_encode($orbitSkills) }}"></div>
    </div>

    {{-- Foreground Content (Centered vertically & horizontally over the centerpiece - Pristine, ultra-minimalist aesthetic) --}}
    <div class="hero-fullscreen-wrap" style="align-items: center; text-align: center;">
      <div class="hero-fullscreen-content" data-parallax data-parallax-speed="-0.03" style="max-width: 820px; text-align: center; margin: 0 auto; display: flex; flex-direction: column; align-items: center; justify-content: center;">
        {{-- Typographic scrambled typing title --}}
        <h1 class="text-white font-extrabold tracking-tight leading-none select-none text-center" id="hero-scramble-title" data-words="{{ $site->hero_title }}" style="font-family: 'Space Grotesk', sans-serif; font-size: clamp(34px, 5.5vw, 68px); line-height: 1.1; letter-spacing: -0.02em; margin: 0 auto; text-align: center; width: 100%;">
          <span id="scramble-mount-text"></span><span class="animate-pulse select-none" style="color:var(--purple); font-weight: 300;">_</span>
        </h1>
      </div>
    </div>
  </header>

  @if(isset($skills) && count($skills) > 0)
  <div class="marquee-section">
    <div class="marquee-wrapper">
      <span class="marquee-title">Capabilities</span>
      <div class="marquee-track">
        <div class="marquee-content">
          @foreach($skills as $s)
          <span class="marquee-item">
            <span class="marquee-dot" style="color: {{ $s->color ?? 'var(--purple)' }}; background-color: {{ $s->color ?? 'var(--purple)' }}"></span>
            {{ $s->name }}
          </span>
          @endforeach
        </div>
        <div class="marquee-content" aria-hidden="true">
          @foreach($skills as $s)
          <span class="marquee-item">
            <span class="marquee-dot" style="color: {{ $s->color ?? 'var(--purple)' }}; background-color: {{ $s->color ?? 'var(--purple)' }}"></span>
            {{ $s->name }}
          </span>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- Premium Sleek Stats Ribbon Section --}}
  @php $stats = $site->stats ?? []; @endphp
  @if(count($stats) > 0)
  <section class="stats-ribbon-section">
    <div class="wrap stats-ribbon-grid">
      @foreach($stats as $index => $stat)
      <div class="stats-ribbon-item" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">
        <strong>{{ $stat['value'] }}</strong>
        <small>{{ $stat['label'] }}</small>
      </div>
      @if($index < count($stats) - 1)
        <div class="stats-ribbon-divider">
    </div>
    @endif
    @endforeach
    </div>
  </section>
  @endif

  @php
  $photoPath = $founder->photo_path && file_exists(public_path($founder->photo_path))
  ? asset($founder->photo_path)
  : asset('images/founder.jpeg');

  $signaturePath = $founder->signature_path && file_exists(public_path($founder->signature_path))
  ? asset($founder->signature_path)
  : asset('images/signature.png');
  @endphp
  <section class="founder-section">
    <div class="wrap founder-grid">
      <div class="founder-visual" id="founder-tilt" data-parallax data-parallax-speed="0.08">
        <img src="{{ $photoPath }}" alt="Founder Portrait" class="founder-img">
      </div>
      <div id="about-content-mount" data-eyebrow="{{ $founder->eyebrow }}" data-heading="{{ $founder->heading }}" data-description="{{ $founder->description }}" data-signature="{{ $signaturePath }}"></div>
    </div>
  </section>

  <section id="services" class="snap-carousel-container">
    <div class="wrap" style="margin-bottom: 40px;">
      <div class="section-head" style="margin-bottom: 0;">
        <div>
          <span class="eyebrow">{{ __('Services') }}</span>
          <h2 style="font-size: clamp(24px, 4vw, 42px); font-weight: 800; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em; color: #fff;">
            {{ __('Handcrafted digital interfaces designed to be remembered.') }}
          </h2>
        </div>
        <p style="color: rgba(255,255,255,0.5); font-size: 15px; max-width: 520px; margin-top: 10px;">
          {{ __('Zero templates. Zero compromises. Every pixel is written by hand to build an organic, premium digital presence.') }}
        </p>
      </div>
    </div>

    {{-- Snap Carousel Track --}}
    <div class="snap-carousel-track" id="servicesSnapTrack">
      @foreach(($site->services ?? []) as $index => $service)
      @php
      $directionClass = 'reveal-slide-alt';
      if ($index % 2 === 0) {
      $directionClass = 'reveal-stagger-cascade';
      }
      @endphp
      <div class="snap-slide" data-slide-index="{{ $index }}">
        <div class="tech-grid-bg"></div>
        <span class="slide-giant-num">0{{ $index + 1 }}</span>

        <div class="slide-content-box {{ $directionClass }}">
          <h3 class="slide-title-glitch" data-text="{{ $service['title'] ?? '' }}" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">
            {{ $service['title'] ?? '' }}
          </h3>
          <p class="slide-desc">
            {{ $service['body'] ?? '' }}
          </p>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Premium Floating Control Dock --}}
    <div class="carousel-control-dock">
      <button type="button" class="dock-arrow-btn" id="servicesArrowLeft" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();" style="opacity: 0.15; pointer-events: none;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <div class="carousel-indicator-bar" id="servicesCarouselDots" style="margin: 0 8px;">
        @foreach(($site->services ?? []) as $index => $service)
        <div class="carousel-dot-indicator {{ $index === 0 ? 'active' : '' }}" data-dot-index="{{ $index }}"></div>
        @endforeach
      </div>

      <button type="button" class="dock-arrow-btn" id="servicesArrowRight" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </section>

  <section id="work" class="section">
    <div class="wrap">
      <div class="section-head" style="margin-bottom: 30px;">
        <div><span class="eyebrow">{{ __('Showcase') }}</span>
          <h2 style="font-size: clamp(24px, 4vw, 42px); font-weight: 800; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em; color: #fff;">
            {{ __('Production-grade engineering. Focused on performance.') }}
          </h2>
        </div>
        <p style="margin: 0;"><a href="{{ route('portfolio') }}" class="bento-archives-link" style="color:var(--purple);font-weight:800;font-size: 15px;">{{ __('Browse the archives →') }}</a></p>
      </div>

      @if(count($site->works ?? []) > 0)
      {{-- Custom Pill Filter Track --}}
      <div class="bento-filter-pill-track" id="bentoFilterTrack">
        <div class="bento-filter-pill active" data-filter="all" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">{{ __('All Projects') }}</div>
        @php
        $tags = collect($site->works)->pluck('tag')->unique()->filter();
        @endphp
        @foreach($tags as $tag)
        <div class="bento-filter-pill" data-filter="{{ Str::slug($tag) }}" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">{{ $tag }}</div>
        @endforeach
      </div>

      {{-- Asymmetric Bento Grid --}}
      <div class="bento-grid-custom reveal-scale-blur" id="bentoGridContainer">
        @foreach(collect($site->works)->values() as $index => $work)
        @php
        $slug = \Illuminate\Support\Str::slug($work['title'] ?? '');
        $bentoClass = 'bento-box-standard';
        if ($index === 0) {
        $bentoClass = 'bento-box-hero';
        } elseif ($index === 1) {
        $bentoClass = 'bento-box-wide';
        } elseif ($index === 2) {
        $bentoClass = 'bento-box-tall';
        }
        @endphp
        <article class="bento-card-custom {{ $bentoClass }} magnetic-card" data-category="{{ Str::slug($work['tag'] ?? 'all') }}" data-tilt-enabled="{{ $index === 1 ? 'true' : 'false' }}">
          {{-- Glossy Sheen Reflection Overlay --}}
          <div class="bento-card-sheen"></div>

          {{-- Background Media --}}
          <div class="bento-card-media">
            @if(!empty($work['image_url']))
            <img src="{{ asset($work['image_url']) }}" alt="{{ $work['title'] ?? '' }}" class="bento-media-img">
            @else
            <div class="w-full h-full flex flex-col justify-center items-center bg-[#07070a] text-zinc-800" style="min-height:380px;">
              <span class="text-4xl font-extrabold select-none opacity-10">GANY LABS</span>
            </div>
            @endif
          </div>

          {{-- Content Details --}}
          <div class="bento-overlay-details">
            <span class="bento-card-tag">{{ $work['tag'] ?? '' }}</span>
            <h3 class="bento-card-title">
              <a href="{{ !empty($slug) ? route('portfolio.detail', $slug) : '#' }}" style="color:inherit;text-decoration:none;" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">
                {{ $work['title'] ?? '' }}
              </a>
            </h3>
            <p class="bento-card-desc">{{ $work['body'] ?? '' }}</p>
          </div>
        </article>
        @endforeach
      </div>
      @else
      <p style="text-align:center; color: var(--muted); padding: 40px 0;">{{ __('Tambahkan showcase project dari admin → Edit Website → Works.') }}</p>
      @endif
    </div>
  </section>

  @if(isset($skills) && $skills->isNotEmpty())
  <section class="section">
    <div class="wrap">
      <div style="text-align:center;margin-bottom:40px">
        <span class="kicker">{{ __('Skills & Expertise') }}</span>
        <h2><span class="reveal-mask"><span class="reveal-mask-content">{{ __('Skill Constellation.') }}</span></span></h2>
        <p style="max-width:520px;margin:12px auto 0">{{ __('Hover ke titik bintang untuk melihat detail. Setiap node adalah skill yang dipelajari dan diasah dalam project nyata.') }}</p>
      </div>
      <div class="constellation-wrapper" style="max-width:900px;margin:0 auto">
        <canvas id="skill-canvas" height="500"></canvas>
        <div class="constellation-tooltip" id="skillTooltip">
          <div class="tt-name" id="ttName">—</div>
          <div class="tt-cat" id="ttCat">—</div>
          <div class="tt-bar">
            <div class="tt-fill" id="ttFill" style="width:0%;background:#ff6a1a"></div>
          </div>
          <div class="tt-stats">
            <span class="tt-level-val" id="ttLevel">0%</span>
            <span class="tt-years-val" id="ttYears">0 years</span>
          </div>
        </div>
      </div>

    </div>
  </section>
  @endif

  <section id="process" class="timeline-centered-section">
    {{-- Vertical Wire Track (Winding Neon Path) --}}
    <div class="timeline-wire-container">
      <svg class="timeline-svg-wire" viewBox="0 0 80 1000" fill="none" preserveAspectRatio="none">
        <path d="M 40,0 Q 15,125 40,250 T 40,500 T 40,750 T 40,1000" class="timeline-wire-track" stroke-width="4" stroke-linecap="round" />
        <path d="M 40,0 Q 15,125 40,250 T 40,500 T 40,750 T 40,1000" class="timeline-wire-draw" id="timelineWireDraw" stroke-width="4" stroke-linecap="round" />
      </svg>
    </div>

    <div class="wrap" style="position: relative; z-index: 2;">
      <div class="section-head" style="margin-bottom: 60px; text-align: center; justify-content: center;">
        <div>
          <span class="eyebrow">{{ __('Process') }}</span>
          <h2 style="font-size: clamp(24px, 4vw, 42px); font-weight: 800; font-family: 'Space Grotesk', sans-serif; letter-spacing: -0.02em; color: #fff;">
            {{ __('Highly disciplined execution. Zero compromise.') }}
          </h2>
        </div>
        <p style="color: rgba(255,255,255,0.5); font-size: 15px; max-width: 520px; margin: 10px auto 0;">
          {{ __('From raw conceptual drafting to production-grade clean deployment.') }}
        </p>
      </div>

      {{-- Alternating Steps Timeline --}}
      <div class="flex flex-col relative">
        @php
        $steps = $site->process_steps ?? [];
        $svgMap = [
        'discover' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
          <circle cx="12" cy="12" r="10" />
          <path stroke-linecap="round" d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10zM2 12h20" />
        </svg>',
        'design' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122l.18.18a3 3 0 004.3 0l6.19-6.19a3 3 0 00-4.3-4.3l-6.19 6.19a3 3 0 000 4.3z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 12.5L9.75 14h1.5l.375-1.5 M3 21v-3h3" />
        </svg>',
        'build' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
        </svg>',
        'launch' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.59 8.42m5.84 5.95a14.98 14.98 0 01-5.84-5.95m0 0a14.98 14.98 0 00-6.16 12.12A14.98 14.98 0 009.59 8.42m0 0H3.75v5.625h5.625" />
        </svg>'
        ];
        @endphp

        @foreach($steps as $index => $step)
        @php
        $isEven = $index % 2 === 0;
        $stepClass = $isEven ? 'timeline-step-left' : 'timeline-step-right';
        $stepIcon = strtolower($step['icon'] ?? $step['title'] ?? '');
        @endphp
        <div class="timeline-step-row {{ $stepClass }}" data-step-index="{{ $index }}">
          <div class="timeline-node-dot"></div>

          <div class="timeline-step-col">
            <div class="timeline-accordion-box" onclick="toggleTimelineAccordion(this)">
              <div class="timeline-accordion-header">
                <div class="flex items-center gap-3">
                  <span class="text-zinc-600 font-mono text-xs select-none">0{{ $index + 1 }} //</span>
                  <h3>{{ $step['title'] ?? '' }}</h3>
                </div>
                <div class="timeline-accordion-icon">
                  {!! $svgMap[$stepIcon] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
                    <circle cx="12" cy="12" r="10" />
                    <path stroke-linecap="round" d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10zM2 12h20" />
                  </svg>' !!}
                </div>
              </div>

              <div class="timeline-accordion-content">
                <span class="block text-[11px] font-mono uppercase tracking-wider mb-2" style="color:var(--purple)">{{ $step['metric'] ?? '' }}</span>
                <p class="timeline-accordion-desc">
                  {{ $step['body'] ?? $step['desc'] ?? '' }}
                </p>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  @if($testimonials->isNotEmpty())
  <section class="section">
    <div class="wrap">
      <div class="section-head" style="justify-content:center;text-align:center">
        <div><span class="eyebrow">{{ __('Testimonials') }}</span>
          <h2>{{ __('Apa kata klien.') }}</h2>
        </div>
      </div>
      <div class="testimonials-grid reveal-slide-alt">
        @foreach($testimonials as $t)
        <div class="testimonial">
          <div class="rating-stars" style="display:flex;gap:4px;margin-bottom:12px;color:#f59e0b">
            @for($i = 0; $i < ($t->rating ?? 5); $i++)
              <svg class="star-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
              </svg>
              @endfor
          </div>
          <div class="quote">"{{ $t->quote }}"</div>
          <p class="testimonial-meta">— {{ $t->name }}@if($t->role), {{ $t->role }}@endif @if($t->company) · {{ $t->company }}@endif</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @else
  <section class="section">
    <div class="wrap">
      <div class="testimonial" style="text-align:center">
        <span class="eyebrow" style="color:var(--purple)">{{ __('Proof') }}</span>
        <div class="quote">"{{ __('Looks premium, loads fast, and finally makes the company feel legit online.') }}"</div>
        <p class="testimonial-meta">{{ __('Tambahkan testimonial dari admin panel.') }}</p>
      </div>
    </div>
  </section>
  @endif

  @if($faqs->isNotEmpty())
  <section id="faq" class="section section-alt">
    <div class="wrap">
      <div class="section-head" style="justify-content:center;text-align:center;margin-bottom:32px">
        <div><span class="eyebrow">{{ __('FAQ') }}</span>
          <h2>{{ __('Pertanyaan yang sering ditanya.') }}</h2>
        </div>
      </div>
      <div class="faq-list">
        @foreach($faqs as $faq)
        <details class="faq-item">
          <summary>{{ $faq->question }}</summary>
          <p>{{ $faq->answer }}</p>
        </details>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <section id="contact" class="section">
    <div class="wrap">
      <div class="contact-box">
        <div class="contact-copy">
          <span class="kicker">{{ __('Ready when you are') }}</span>
          <h2><span class="reveal-mask"><span class="reveal-mask-content">{{ __("Let's turn your company into a premium digital presence.") }}</span></span></h2>
          <p>{{ __('Isi form ini — pesan langsung masuk database dan email notifikasi secara aman.') }}</p>
          @if(session('success'))<div class="success">{{ session('success') }}</div>@endif
          @if($errors->any())<div class="success error-box">{{ __('Ada input yang belum benar:') }} {{ $errors->first() }}</div>@endif

          {{-- WhatsApp VIP Consultation --}}
          <div class="cal-booking-box" style="margin-top: 30px; padding: 20px; border-radius: 16px; background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.15); box-shadow: 0 0 25px rgba(16, 185, 129, 0.03);">
            <h4 style="margin:0 0 8px; font-size:16px; display:flex; align-items:center; gap:8px;">
              <span style="display:inline-block; width:8px; height:8px; background:#10b981; border-radius:50%; box-shadow: 0 0 8px #10b981;"></span>
              {{ __('Lebih Suka Ngobrol Langsung?') }}
            </h4>
            <p style="font-size:13.5px; color: var(--muted); margin:0 0 16px; line-height: 1.5;">{{ __('Ingin respons yang lebih cepat? Jangan ragu untuk menyapa kami via WhatsApp. Mari diskusikan ide proyek, kebutuhan fitur, serta penyesuaian anggaran Anda secara langsung dengan lebih praktis.') }}</p>
            <a href="https://wa.me/{{ $site->whatsapp }}?text=Halo%20Gany%20Labs%2C%20saya%20mau%20konsultasi%20soal%20pembuatan%20website..." target="_blank" class="btn btn-primary btn-sm" style="margin:0; width:100%; display:flex; align-items:center; justify-content:center; gap:8px; background:#10b981; border-color:#10b981; text-decoration:none; color:#fff; font-weight: 500;">
              💬 {{ __('Konsultasi via WhatsApp') }} →
            </a>
          </div>
        </div>
        <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
          @csrf
          <div style="position:absolute;left:-9999px" aria-hidden="true">
            <label>Website <input name="website" tabindex="-1" autocomplete="off"></label>
          </div>

          @if(!empty($site->estimator_enabled))
          <div id="interactive-estimator-mount" data-pricing="{{ json_encode($site->estimator_pricing) }}" style="min-height: 380px; margin-bottom: 24px;"></div>
          @else
          <div class="estimator-disabled-banner" style="margin-bottom: 24px; padding: 24px; border-radius: 12px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
            <span class="founder-eyebrow" style="color: var(--purple); font-size: 11px; margin-bottom: 8px; display: block; font-weight: 400; letter-spacing: 0.12em;">{{ __('✦ NEGOSIASI TERBUKA & TRUSTED') }}</span>
            <h4 style="margin: 0 0 10px; font-size: 18px; color: #fff; font-weight: 300; letter-spacing: -0.02em;">{{ __('Ceritakan Kebutuhan Website Anda') }}</h4>
            <p style="font-size: 13.5px; color: var(--muted); margin: 0; line-height: 1.6; font-weight: 300;">
              {{ __('Kami percaya setiap proyek memiliki keunikan tersendiri. Silakan diskusikan rencana, ide, atau fitur website yang Anda inginkan. Kami sangat terbuka untuk menyesuaikan ruang lingkup pekerjaan agar selaras dengan rencana anggaran (budget) investasi Anda.') }}
            </p>
          </div>
          @endif

          <div class="form-grid">
            <input name="name" value="{{ old('name') }}" placeholder="{{ __('Nama') }}" required>
            <input name="email" type="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required>
          </div>
          <div class="form-grid" style="margin-top:12px">
            <input name="company" value="{{ old('company') }}" placeholder="{{ __('Company / brand') }}">
            <select name="budget" id="form-budget-select">
              <option value="">{{ __('Budget range') }}</option>
              @foreach(($site->budget_ranges ?? ['Di bawah Rp 1 juta', 'Rp 1 - 3 juta', 'Rp 3 - 5 juta', 'Rp 5 juta+']) as $range)
              <option @selected(old('budget')===$range)>{{ $range }}</option>
              @endforeach
            </select>
          </div>
          <textarea name="message" id="form-message-area" placeholder="{{ !empty($site->estimator_enabled) ? __('Ceritain kebutuhan website...') : __('Ceritakan rencana, fitur, atau ide website yang Anda butuhkan di sini. Kami sangat terbuka untuk berdiskusi tentang bagaimana menyesuaikannya dengan anggaran Anda...') }}" required style="margin-top:12px">{{ old('message') }}</textarea>
          <button class="btn btn-primary" type="submit" style="width:100%;margin-top:12px">{{ __('Kirim Message →') }}</button>
        </form>
      </div>
      <div class="newsletter-bar" style="margin-top:24px">
        <div>
          <strong style="color:var(--navy)">{{ $site->newsletter_title }}</strong>
          <p style="margin:6px 0 0;font-size:14px;color:var(--muted)">{{ $site->newsletter_desc }}</p>
        </div>
        <form method="POST" action="{{ route('newsletter.store') }}">
          @csrf
          <input type="email" name="newsletter_email" placeholder="{{ __('Email kamu') }}" required>
          <button class="btn btn-ghost" type="submit">{{ __('Subscribe') }}</button>
        </form>
      </div>
    </div>
  </section>



  @include('partials.footer')
  @include('partials.command-center')
  @include('partials.site-scripts')
</body>

</html>