<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login — Asteria Labs</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --bg-base: #060608;
  --bg-card: rgba(16, 16, 24, 0.6);
  --line: rgba(255, 85, 0, 0.12);
  --line-hover: rgba(255, 85, 0, 0.35);
  --text: #f3f4f6;
  --muted: #9ca3af;
  --pri: #ff5500;
  --pri-glow: rgba(255, 85, 0, 0.25);
  --red: #ff3355;
  --glass-glow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
}

body {
  margin: 0;
  min-height: 100vh;
  display: grid;
  place-items: center;
  background-color: var(--bg-base);
  background-image: 
    radial-gradient(circle at 10% 20%, rgba(255, 85, 0, 0.08) 0%, transparent 40%),
    radial-gradient(circle at 90% 80%, rgba(57, 255, 20, 0.03) 0%, transparent 40%);
  background-attachment: fixed;
  font-family: 'Outfit', sans-serif;
  color: var(--text);
}

.box {
  width: min(420px, calc(100% - 32px));
  background: var(--bg-card);
  border: 1px solid var(--line);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  border-radius: 24px;
  padding: 36px;
  box-shadow: var(--glass-glow);
  transition: all 0.3s ease;
}

.box:hover {
  border-color: var(--line-hover);
  box-shadow: 0 12px 40px 0 rgba(255, 85, 0, 0.04);
}

h1 {
  font-family: 'Space Grotesk', sans-serif;
  margin: 0 0 8px;
  font-size: 28px;
  font-weight: 800;
  background: linear-gradient(135deg, #fff 30%, var(--pri) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-align: center;
}

p.muted {
  color: var(--muted);
  font-size: 14px;
  text-align: center;
  margin: 0 0 28px;
  font-weight: 500;
}

input {
  width: 100%;
  background: rgba(10, 10, 15, 0.8);
  border: 1px solid var(--line);
  color: var(--text);
  border-radius: 12px;
  padding: 14px 16px;
  font-family: 'Outfit', sans-serif;
  font-size: 14px;
  transition: all 0.3s ease;
  outline: none;
  box-sizing: border-box;
}

input:focus {
  border-color: var(--pri);
  box-shadow: 0 0 15px var(--pri-glow), inset 0 0 8px rgba(255, 85, 0, 0.08);
  background: rgba(6, 6, 8, 0.95);
}

label {
  display: block;
  margin-bottom: 8px;
  color: #cbd5ee;
  font-weight: 600;
  font-size: 12px;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

.btn {
  width: 100%;
  margin-top: 24px;
  padding: 14px;
  border: 1px solid rgba(255, 85, 0, 0.3);
  border-radius: 12px;
  background: linear-gradient(135deg, var(--pri) 0%, #d43d00 100%);
  color: #fff;
  font-family: 'Outfit', sans-serif;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(255, 85, 0, 0.2);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 85, 0, 0.35);
  border-color: var(--pri);
}

.btn:active {
  transform: translateY(0);
}

.alert {
  padding: 14px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 20px;
  backdrop-filter: blur(8px);
}

.alert.error {
  background: rgba(255, 51, 85, 0.08);
  border: 1px solid rgba(255, 51, 85, 0.25);
  color: #fecaca;
  box-shadow: 0 0 20px rgba(255, 51, 85, 0.04);
}

.alert.success {
  background: rgba(57, 255, 20, 0.08);
  border: 1px solid rgba(57, 255, 20, 0.25);
  color: #a7f3d0;
  box-shadow: 0 0 20px rgba(57, 255, 20, 0.04);
}
</style>
</head>
<body>
<form class="box" method="POST" action="{{ route('admin.login.post') }}">
@csrf
<h1>Admin Login</h1>
<p class="muted">Masuk untuk mengelola web Asteria Labs.</p>
@if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
@if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
<label for="password">Password</label>
<input id="password" type="password" name="password" required autofocus autocomplete="current-password">
<button class="btn" type="submit">Masuk</button>
</form>
</body>
</html>
