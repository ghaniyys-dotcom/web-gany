<!DOCTYPE html>
<html lang="id">
<head>
@include('partials.head-meta', [
    'pageTitle' => trim($__env->yieldContent('title')),
    'pageDescription' => $__env->yieldContent('meta_description', $site->tagline),
])
@stack('head')
@vite(['resources/js/app.js'])
</head>
<body>
<div id="pm-cursor"></div>
<div id="pm-cursor-aura"><span class="pm-cursor-label"></span></div>
<div class="page-transition-overlay" id="pageTransition"></div>
@include('partials.nav', ['homeAnchors' => false])
<main>@yield('content')</main>
@include('partials.footer')
@include('partials.command-center')
@include('partials.site-scripts')
</body>
</html>
