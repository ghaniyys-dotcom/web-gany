@php
    $pageTitle = $pageTitle ?? ($site->brand_name.' — Digital Company Profile');
    $pageDescription = $pageDescription ?? ($site->tagline.' — '.Str::limit($site->hero_subtitle, 120));
    $canonical = $canonical ?? url()->current();
    $ogImage = asset(config('site.og_image'));
    $mark = $site->logo_initials ?: strtoupper(substr($site->brand_name, 0, 2));
@endphp
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.theme-script')
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="dark">
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ $canonical }}">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ $site->brand_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
@vite(['resources/css/app.css'])
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
<link rel="stylesheet" href="{{ asset('css/theme.css') }}?v={{ filemtime(public_path('css/theme.css')) }}">
<link rel="stylesheet" href="{{ asset('css/responsive.css') }}?v={{ filemtime(public_path('css/responsive.css')) }}">
