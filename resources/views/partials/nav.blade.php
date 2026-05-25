@php
    $mark = $site->logo_initials ?: strtoupper(substr($site->brand_name, 0, 2));
    $homeAnchors = $homeAnchors ?? false;
@endphp
<div class="nav-sensor-strip" id="navSensorStrip"></div>
<nav class="nav hud-nav" id="hudNavBar">
<div class="wrap nav-inner">
<a class="brand" href="{{ $homeAnchors ? route('home') : route('home') }}">
<span class="mark">{{ $mark }}</span>
<span>{{ $site->brand_name }}</span>
</a>
<div class="links">
@if($homeAnchors)
<a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
<a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
<a href="#services">Services</a>
<a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio') ? 'active' : '' }}">Portfolio</a>
<a href="#faq">FAQ</a>
<a href="#contact">Contact</a>
@else
<a href="{{ route('home') }}">Home</a>
<a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
<a href="{{ route('home') }}#services">Services</a>
<a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio') ? 'active' : '' }}">Portfolio</a>
<a href="{{ route('home') }}#faq">FAQ</a>
<a href="{{ route('home') }}#contact">Contact</a>
@endif
</div>
<div class="nav-actions">
<div class="lang-switcher">
  <div class="lang-active-pill" style="transform: translateX({{ app()->getLocale() === 'en' ? '100%' : '0' }});"></div>
  <a href="{{ route('lang.switch', 'id') }}" class="lang-btn {{ app()->getLocale() === 'id' ? 'active' : '' }}" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">ID</a>
  <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}" onclick="if(window.FluxoraAudio) window.FluxoraAudio.playTactileClick();">EN</a>
</div>
<button type="button" class="btn btn-ghost btn-sm cc-trigger-btn" id="ccTriggerBtn" style="display:inline-flex; align-items:center; gap:6px; margin-right:4px;">
  <span>Console</span> <kbd style="font-family:inherit; opacity:0.8; font-size:11px; background:rgba(255,255,255,0.15); padding:1px 5px; border-radius:4px; border:1px solid rgba(255,255,255,0.12)">⌘K</kbd>
</button>
<button type="button" class="soundwave-toggle" aria-label="Toggle SFX Audio" title="Toggle SFX">
  <span class="bar bar-1"></span>
  <span class="bar bar-2"></span>
  <span class="bar bar-3"></span>
  <span class="bar bar-4"></span>
</button>
<button type="button" class="nav-toggle" id="navToggle" aria-label="Buka menu" aria-expanded="false">☰</button>
<a class="btn btn-primary" href="{{ $homeAnchors ? '#contact' : route('home').'#contact' }}">Start Project →</a>
</div>
</div>
</nav>
<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
@if($homeAnchors)
<a href="{{ route('home') }}">Home</a>
<a href="{{ route('about') }}">About</a>
<a href="#services">Services</a>
<a href="{{ route('portfolio') }}">Portfolio</a>
<a href="#faq">FAQ</a>
<a href="#contact">Contact</a>
@else
<a href="{{ route('home') }}">Home</a>
<a href="{{ route('about') }}">About</a>
<a href="{{ route('home') }}#services">Services</a>
<a href="{{ route('portfolio') }}">Portfolio</a>
<a href="{{ route('home') }}#faq">FAQ</a>
<a href="{{ route('home') }}#contact">Contact</a>
@endif
<div class="mobile-menu-divider"></div>
<button type="button" class="mobile-cmd-btn" id="mobileCmdBtn" onclick="openCommandCenter()">
  <span class="cmd-icon">⌘</span>
  <span class="cmd-label">Command Center</span>
  <span class="cmd-hint">Tap to open</span>
</button>
</div>
