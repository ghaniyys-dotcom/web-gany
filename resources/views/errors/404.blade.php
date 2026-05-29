@php $site = \App\Models\SiteSetting::current(); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
@include('partials.head-meta', ['pageTitle' => '404 — '.$site->brand_name, 'pageDescription' => 'Halaman tidak ditemukan'])
</head>
<body>
@include('partials.nav', ['homeAnchors' => false])
<main class="error-page">
<div class="wrap">
<span class="kicker">404</span>
<h1>Halaman tidak ketemu.</h1>
<p>Mungkin link-nya salah atau halaman udah dipindah.</p>
<br>
<a class="btn btn-primary" href="{{ route('home') }}">Kembali ke Home</a>
</div>
</main>
@include('partials.footer')
@include('partials.site-scripts')
</body>
</html>
