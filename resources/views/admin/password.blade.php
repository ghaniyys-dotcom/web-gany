@extends('admin.layout')
@section('heading', 'Ganti Password Admin')
@section('content')
<div class="card" style="max-width:480px">
<form method="POST" action="{{ route('admin.password.update') }}">
@csrf @method('PUT')
<div class="field"><label>Password lama</label><input type="password" name="current_password" required autocomplete="current-password"></div>
<div class="field"><label>Password baru</label><input type="password" name="password" required minlength="8" autocomplete="new-password"></div>
<div class="field"><label>Konfirmasi password baru</label><input type="password" name="password_confirmation" required autocomplete="new-password"></div>
<button class="btn">Update Password</button>
</form>
<p class="muted" style="margin-top:14px;font-size:13px">Password disimpan ter-hash di database. Min. 8 karakter.</p>
</div>
@endsection
