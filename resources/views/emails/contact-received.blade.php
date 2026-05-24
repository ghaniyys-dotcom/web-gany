<h2>Pesan baru dari website</h2>
<p><strong>Nama:</strong> {{ $message->name }}</p>
<p><strong>Email:</strong> {{ $message->email }}</p>
<p><strong>Company:</strong> {{ $message->company ?: '-' }}</p>
<p><strong>Budget:</strong> {{ $message->budget ?: '-' }}</p>
<p><strong>Pesan:</strong></p>
<p style="white-space:pre-wrap">{{ $message->message }}</p>
