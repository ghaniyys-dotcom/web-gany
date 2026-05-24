@extends('admin.layout')
@section('heading', 'Newsletter Subscribers')
@section('content')
<div class="card">
<table>
<tr><th>Email</th><th>Tanggal</th></tr>
@forelse($items as $item)
<tr><td>{{ $item->email }}</td><td>{{ $item->created_at->format('d M Y H:i') }}</td></tr>
@empty
<tr><td colspan="2" class="muted">Belum ada subscriber.</td></tr>
@endforelse
</table>
<div style="margin-top:18px">{{ $items->links() }}</div>
</div>
@endsection
