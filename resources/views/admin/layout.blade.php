<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Panel — {{ $title ?? 'Control Center' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --bg-base: #060608;
  --bg-sidebar: rgba(10, 10, 14, 0.75);
  --bg-card: rgba(16, 16, 24, 0.6);
  --line: rgba(255, 85, 0, 0.12);
  --line-hover: rgba(255, 85, 0, 0.35);
  --text: #f3f4f6;
  --muted: #9ca3af;
  --pri: #ff5500;
  --pri-glow: rgba(255, 85, 0, 0.25);
  --green: #39ff14;
  --green-glow: rgba(57, 255, 20, 0.2);
  --red: #ff3355;
  --red-glow: rgba(255, 51, 85, 0.2);
  --glass-glow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
}

* {
  box-sizing: border-box;
  scrollbar-width: thin;
  scrollbar-color: var(--pri) transparent;
}

body {
  margin: 0;
  font-family: 'Outfit', system-ui, -apple-system, sans-serif;
  background-color: var(--bg-base);
  background-image: 
    radial-gradient(circle at 10% 20%, rgba(255, 85, 0, 0.05) 0%, transparent 40%),
    radial-gradient(circle at 90% 80%, rgba(57, 255, 20, 0.03) 0%, transparent 40%);
  background-attachment: fixed;
  color: var(--text);
  min-height: 100vh;
}

a {
  color: inherit;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.shell {
  display: grid;
  grid-template-columns: 280px 1fr;
  min-height: 100vh;
}

/* 🪐 Side Navigation */
.side {
  border-right: 1px solid var(--line);
  padding: 30px 20px;
  background: var(--bg-sidebar);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);
  position: sticky;
  top: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
  overflow-y: auto;
  z-index: 100;
}

.brand {
  font-family: 'Space Grotesk', sans-serif;
  font-weight: 800;
  font-size: 22px;
  letter-spacing: -0.5px;
  background: linear-gradient(135deg, #fff 30%, var(--pri) 100%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 35px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.brand::before {
  content: '';
  display: inline-block;
  width: 10px;
  height: 10px;
  background: var(--pri);
  border-radius: 50%;
  box-shadow: 0 0 10px var(--pri);
}

.nav {
  display: flex;
  flex-direction: column;
  gap: 6px;
  flex: 1;
}

.nav a, .logout {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  padding: 12px 16px;
  border-radius: 14px;
  color: var(--muted);
  border: 1px solid transparent;
  background: transparent;
  text-align: left;
  font-family: 'Outfit', sans-serif;
  font-weight: 500;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav a.active {
  background: rgba(255, 85, 0, 0.08);
  border-color: rgba(255, 85, 0, 0.25);
  color: #fff;
  font-weight: 600;
  box-shadow: inset 0 0 12px rgba(255, 85, 0, 0.05);
}

.nav a:hover, .logout:hover {
  background: rgba(255, 255, 255, 0.03);
  border-color: rgba(255, 255, 255, 0.05);
  color: #fff;
  transform: translateX(4px);
}

.logout:hover {
  background: rgba(255, 51, 85, 0.08);
  border-color: rgba(255, 51, 85, 0.2);
  color: #ff3355;
}

/* 🔮 Content Dashboard */
.main {
  padding: 40px;
  max-width: 1400px;
  margin: 0 auto;
  width: 100%;
}

.card {
  background: var(--bg-card);
  border: 1px solid var(--line);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border-radius: 20px;
  padding: 26px;
  margin-bottom: 24px;
  box-shadow: var(--glass-glow);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card:hover {
  border-color: var(--line-hover);
  box-shadow: 0 12px 40px 0 rgba(255, 85, 0, 0.04);
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.muted {
  color: var(--muted);
}

h1, h2, h3, th {
  font-family: 'Space Grotesk', sans-serif;
  letter-spacing: -0.3px;
}

/* ⚡ Forms & Inputs */
input, textarea, select {
  width: 100%;
  background: rgba(10, 10, 15, 0.8);
  border: 1px solid var(--line);
  color: var(--text);
  border-radius: 12px;
  padding: 12px 16px;
  font-family: 'Outfit', sans-serif;
  font-size: 14px;
  transition: all 0.3s ease;
  outline: none;
}

input:focus, textarea:focus, select:focus {
  border-color: var(--pri);
  box-shadow: 0 0 15px var(--pri-glow), inset 0 0 8px rgba(255, 85, 0, 0.08);
  background: rgba(6, 6, 8, 0.95);
}

textarea {
  min-height: 130px;
  resize: vertical;
}

.field {
  margin-bottom: 18px;
}

label {
  display: block;
  margin-bottom: 8px;
  color: #e5e7eb;
  font-weight: 600;
  font-size: 13px;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

/* 💎 Premium Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: 1px solid rgba(255, 85, 0, 0.3);
  border-radius: 14px;
  padding: 12px 24px;
  background: linear-gradient(135deg, var(--pri) 0%, #d43d00 100%);
  color: #fff;
  font-family: 'Outfit', sans-serif;
  font-weight: 700;
  font-size: 14px;
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

.btn.danger {
  background: linear-gradient(135deg, var(--red) 0%, #d41c3b 100%);
  border-color: rgba(255, 51, 85, 0.3);
  box-shadow: 0 4px 15px rgba(255, 51, 85, 0.15);
}

.btn.danger:hover {
  box-shadow: 0 6px 20px rgba(255, 51, 85, 0.3);
  border-color: var(--red);
}

/* 🟢 Status Alerts */
.alert {
  padding: 16px 20px;
  border-radius: 14px;
  margin-bottom: 24px;
  font-weight: 500;
  font-size: 14px;
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  gap: 10px;
}

.success {
  background: rgba(57, 255, 20, 0.08);
  border: 1px solid rgba(57, 255, 20, 0.25);
  color: #a7f3d0;
  box-shadow: 0 0 20px rgba(57, 255, 20, 0.04);
}

.error {
  background: rgba(255, 51, 85, 0.08);
  border: 1px solid rgba(255, 51, 85, 0.25);
  color: #fecaca;
  box-shadow: 0 0 20px rgba(255, 51, 85, 0.04);
}

/* 📊 Cyberpunk Table design */
table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 8px;
  margin-top: -8px;
}

th {
  color: var(--muted);
  font-weight: 600;
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 16px 20px;
  border-bottom: 1px solid var(--line);
}

td {
  padding: 16px 20px;
  background: rgba(255, 255, 255, 0.015);
  border-top: 1px solid var(--line);
  border-bottom: 1px solid var(--line);
  transition: all 0.3s ease;
}

tr td:first-child {
  border-left: 1px solid var(--line);
  border-top-left-radius: 14px;
  border-bottom-left-radius: 14px;
}

tr td:last-child {
  border-right: 1px solid var(--line);
  border-top-right-radius: 14px;
  border-bottom-right-radius: 14px;
}

tr:hover td {
  background: rgba(255, 85, 0, 0.03);
  border-color: rgba(255, 85, 0, 0.25);
}

/* 🎨 Details component styles */
details summary {
  outline: none;
  transition: color 0.3s;
}

details[open] summary {
  color: #fff !important;
}

/* ==========================================================================
   ADMIN DASHBOARD ANALYTICS
   ========================================================================== */
.admin-analytics-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 32px;
}
@media (max-width: 1024px) {
  .admin-analytics-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .admin-analytics-grid { grid-template-columns: 1fr; }
}

.analytic-card {
  background: var(--bg-card);
  border-radius: 20px;
  padding: 24px;
  border: 1px solid var(--line);
  box-shadow: var(--glass-glow);
  position: relative;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.analytic-card:hover { 
  transform: translateY(-4px); 
  border-color: var(--line-hover);
  box-shadow: 0 12px 40px 0 rgba(255, 85, 0, 0.04);
}
.analytic-card .lbl {
  font-size: 12.5px;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--muted);
  font-weight: 700;
  display: block;
  margin-bottom: 8px;
}
.analytic-card .val {
  font-size: 32px;
  font-weight: 800;
  color: #fff;
  line-height: 1;
}
.analytic-card .glow-indicator {
  position: absolute;
  top: 16px;
  right: 16px;
  width: 32px;
  height: 32px;
  border-radius: 10px;
  display: grid;
  place-items: center;
  font-size: 16px;
  font-weight: bold;
}
.val-visits { color: #ff5500 !important; }
.val-uniques { color: #ffaa00 !important; }
.val-cals { color: #3b82f6 !important; }
.val-budgets { color: #39ff14 !important; }

/* SVG Chart */
.svg-chart-container {
  background: var(--bg-card);
  border-radius: 24px;
  padding: 32px;
  border: 1px solid var(--line);
  box-shadow: var(--glass-glow);
  margin-bottom: 40px;
  position: relative;
}
.chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}
.chart-header h3 { font-size: 20px; font-weight: 800; color: #fff; margin: 0; }
.chart-legend { display: flex; gap: 16px; font-size: 13px; font-weight: 600; }
.legend-item { display: flex; align-items: center; gap: 6px; }
.legend-dot { width: 8px; height: 8px; border-radius: 50%; }
.svg-canvas-wrapper { position: relative; width: 100%; }

.chart-path-line {
  stroke-dasharray: 1200;
  stroke-dashoffset: 1200;
  animation: drawChartPath 2.5s cubic-bezier(0.25, 1, 0.5, 1) forwards;
}
@keyframes drawChartPath { to { stroke-dashoffset: 0; } }
.chart-glow-filter {
  filter: drop-shadow(0px 8px 12px rgba(255, 85, 0, 0.25)) drop-shadow(0px 4px 6px rgba(255, 85, 0, 0.15));
}
.chart-point {
  transition: r 0.2s ease, fill-opacity 0.2s ease;
  cursor: pointer;
}
.chart-point:hover { r: 7; fill-opacity: 1; }
.chart-tooltip {
  position: absolute;
  background: rgba(6, 6, 8, 0.95);
  backdrop-filter: blur(8px);
  border: 1px solid var(--line);
  padding: 10px 14px;
  border-radius: 12px;
  color: #fff;
  font-size: 12px;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.15s ease, transform 0.15s ease;
  transform: translateY(8px);
  z-index: 50;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
}
.chart-tooltip.visible { opacity: 1; transform: translateY(0); }

@media(max-width: 1024px) {
  .shell {
    grid-template-columns: 1fr;
  }
  .side {
    position: relative;
    height: auto;
    border-right: 0;
    border-bottom: 1px solid var(--line);
    padding: 24px;
  }
  .grid {
    grid-template-columns: 1fr;
  }
  .main {
    padding: 24px;
  }
}
</style>
</head>
<body>
<div class="shell">
<aside class="side">
<div class="brand">Control Center</div>
<nav class="nav">
<a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
<a class="{{ request()->routeIs('admin.content') ? 'active' : '' }}" href="{{ route('admin.content') }}">Edit Website</a>
<a class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}" href="{{ route('admin.messages') }}">Contact Messages</a>
<a class="{{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}" href="{{ route('admin.testimonials') }}">Testimonials</a>
<a class="{{ request()->routeIs('admin.faqs*') ? 'active' : '' }}" href="{{ route('admin.faqs') }}">FAQ</a>
<a class="{{ request()->routeIs('admin.intro') ? 'active' : '' }}" href="{{ route('admin.intro') }}">✨ Intro Sequence</a>
<a class="{{ request()->routeIs('admin.founder') ? 'active' : '' }}" href="{{ route('admin.founder') }}">👤 Founder Profile</a>
<a class="{{ request()->routeIs('admin.skills*') ? 'active' : '' }}" href="{{ route('admin.skills') }}">⭐ Skills</a>
<a class="{{ request()->routeIs('admin.newsletters') ? 'active' : '' }}" href="{{ route('admin.newsletters') }}">Newsletter</a>
<a class="{{ request()->routeIs('admin.password') ? 'active' : '' }}" href="{{ route('admin.password') }}">Ganti Password</a>
<a href="{{ route('home') }}" target="_blank" rel="noopener" style="margin-top: 15px; text-decoration: underline; color: var(--pri); font-weight: 600;">Lihat Website ↗</a>
<form method="POST" action="{{ route('admin.logout') }}" style="margin-top:20px">@csrf<button class="logout" type="submit">Logout</button></form>
</nav>
</aside>
<main class="main">
<h1 style="margin:0 0 6px; font-size:32px; font-weight:800; background:linear-gradient(to right, #fff, var(--muted)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">@yield('heading', 'Dashboard')</h1>
<p class="muted" style="margin:0 0 30px; font-size:14px; font-weight:500;">Kelola company profile langsung dari browser.</p>
@if(session('success'))<div class="alert success"><span>✓</span> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert error"><span>✕</span> {{ session('error') }}</div>@endif
@yield('content')
</main>
</div>
<script>
window.translateField = function(sourceId, targetId, btn) {
    const text = document.getElementById(sourceId).value;
    if (!text) {
        alert('Tulis teks di kolom bahasa Indonesia terlebih dahulu!');
        return;
    }
    
    const originalText = btn.innerHTML;
    btn.innerHTML = '⚡ Translating...';
    btn.disabled = true;
    btn.style.opacity = 0.5;

    // Check if the text is multi-line with pipeline separators
    if (text.includes('|')) {
        const lines = text.split('\n');
        const translatedLinesPromises = lines.map(line => {
            if (!line.trim()) return Promise.resolve('');
            const chunks = line.split('|');
            // For each chunk, translate if it contains words and is not a URL/icon/hex color
            const chunkPromises = chunks.map(chunk => {
                const trimmed = chunk.trim();
                // Heuristic to skip icons, numbers, or URLs
                if (!trimmed || trimmed.includes('://') || /^[✦◈↗\d+.\-%#]+$/.test(trimmed)) {
                    return Promise.resolve(chunk);
                }
                return fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=id&tl=en&dt=t&q=${encodeURIComponent(trimmed)}`)
                    .then(res => res.json())
                    .then(data => (data && data[0]) ? data[0].map(x => x[0]).join('') : chunk)
                    .catch(() => chunk);
            });
            return Promise.all(chunkPromises).then(translatedChunks => translatedChunks.join(' | '));
        });

        Promise.all(translatedLinesPromises)
            .then(translatedLines => {
                const targetEl = document.getElementById(targetId);
                targetEl.value = translatedLines.join('\n');
                flashTarget(targetId);
                targetEl.dispatchEvent(new Event('change'));
            })
            .catch(err => {
                console.error(err);
                alert('Gagal menerjemahkan pipeline text.');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.style.opacity = '';
            });
    } else {
        // Plain single line or multi-line translation
        fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=id&tl=en&dt=t&q=${encodeURIComponent(text)}`)
            .then(res => res.json())
            .then(data => {
                if (data && data[0]) {
                    const translation = data[0].map(x => x[0]).join('');
                    const targetEl = document.getElementById(targetId);
                    targetEl.value = translation;
                    flashTarget(targetId);
                    targetEl.dispatchEvent(new Event('change'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('Gagal menerjemahkan.');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.style.opacity = '';
            });
    }
};

function flashTarget(targetId) {
    const targetEl = document.getElementById(targetId);
    targetEl.style.boxShadow = '0 0 15px rgba(57, 255, 20, 0.4)';
    targetEl.style.borderColor = 'var(--green)';
    setTimeout(() => {
        targetEl.style.boxShadow = '';
        targetEl.style.borderColor = '';
    }, 1500);
}
</script>
</body>
</html>
