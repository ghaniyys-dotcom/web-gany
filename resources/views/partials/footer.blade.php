@php
  $mark = $site->logo_initials ?: strtoupper(substr($site->brand_name, 0, 2));
@endphp

@if(!request()->routeIs('home'))
  {{-- Promo Magnet for other pages, rendered beautifully above the dark footer --}}
  <div class="wrap" style="margin-bottom: 40px; margin-top: 20px;">
    <div class="newsletter-bar">
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
@endif

<footer class="footer-premium">
  <div class="wrap footer-content">
    
    {{-- Top Section: Logo and Capsule Pills --}}
    <div class="footer-top">
      <a class="footer-brand" href="{{ route('home') }}">
        <span class="footer-brand-mark">{{ $mark }}</span>
        <span class="footer-brand-name">{{ $site->brand_name }}</span>
      </a>
      
      <div class="footer-contact-pills">
        @if($site->email)
          <a href="mailto:{{ $site->email }}" class="contact-pill-link" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">
            <span class="pill-dot"></span>
            {{ $site->email }}
          </a>
        @endif
        @if($site->whatsapp)
          @php
            $formattedPhone = $site->whatsapp;
            if (str_starts_with($formattedPhone, '62')) {
                // If it's Indonesian number, format nicely
                $formattedPhone = '+' . substr($formattedPhone, 0, 2) . ' ' . substr($formattedPhone, 2, 3) . '-' . substr($formattedPhone, 5, 4) . '-' . substr($formattedPhone, 9);
            }
          @endphp
          <a href="https://wa.me/{{ $site->whatsapp }}?text={{ urlencode('Halo, saya tertarik untuk konsultasi project website.') }}" target="_blank" rel="noopener" class="contact-pill-link contact-pill-whatsapp" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">
            <span class="pill-dot glow-green"></span>
            {{ $formattedPhone }}
          </a>
        @endif
      </div>
    </div>

    <hr class="footer-divider">

    {{-- Middle Section: Grid (Links, Socials, Local Time, Version) --}}
    <div class="footer-grid">
      
      {{-- Column 1: Links --}}
      <div class="footer-col">
        <h4 class="footer-col-title">{{ __('Links') }}</h4>
        <ul class="footer-links-list">
          <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
          <li><a href="{{ route('about') }}">{{ __('About') }}</a></li>
          <li><a href="{{ route('home') }}#services">{{ __('Services') }}</a></li>
          <li><a href="{{ route('portfolio') }}">{{ __('Portfolio') }}</a></li>
          <li><a href="{{ route('home') }}#faq">{{ __('FAQ') }}</a></li>
          <li><a href="{{ route('home') }}#contact">{{ __('Contact') }}</a></li>
          @if($showAdminLink)
            <li><a href="{{ route('admin.login') }}" style="opacity: 0.5;">Admin</a></li>
          @endif
        </ul>
      </div>

      {{-- Column 2: Socials --}}
      <div class="footer-col">
        <h4 class="footer-col-title">{{ __('Socials') }}</h4>
        <ul class="footer-links-list">
          @if($site->email)
            <li><a href="mailto:{{ $site->email }}">Email</a></li>
          @endif
          @if($site->whatsapp)
            <li><a href="https://wa.me/{{ $site->whatsapp }}?text={{ urlencode('Halo, saya tertarik untuk konsultasi project website.') }}" target="_blank" rel="noopener">WhatsApp</a></li>
          @endif
          <li><a href="https://linkedin.com" target="_blank" rel="noopener">LinkedIn</a></li>
          <li><a href="https://github.com" target="_blank" rel="noopener">GitHub</a></li>
        </ul>
      </div>

      {{-- Column 3: Local Time --}}
      <div class="footer-col">
        <h4 class="footer-col-title">{{ __('Local Time') }}</h4>
        <div class="footer-time-container">
          <span id="footer-local-clock" class="footer-clock-digit">--:--:-- PM</span>
          <span class="footer-timezone">WIB UTC+7</span>
        </div>
      </div>

      {{-- Column 4: Version --}}
      <div class="footer-col">
        <h4 class="footer-col-title">{{ __('Version') }}</h4>
        <div class="footer-version-text">
          <span>2026 © Edition</span>
          <span class="footer-version-sub">Crafted by Gany</span>
        </div>
      </div>

    </div>

  </div>

  {{-- Giant typographic brand name background --}}
  <div class="footer-giant-brand-container">
    <div class="footer-giant-brand-text">{{ strtoupper($site->brand_name) }}</div>
  </div>
</footer>
