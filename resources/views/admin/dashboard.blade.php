@extends('admin.layout')

@section('heading', 'Web Analytics & Overview')

@section('content')
@php
    // Calculate SVG chart coordinates
    $maxVal = 10;
    foreach ($chart_data as $data) {
        if ($data['visits'] > $maxVal) { $maxVal = $data['visits']; }
        if ($data['uniques'] > $maxVal) { $maxVal = $data['uniques']; }
    }
    
    $maxVal = ceil($maxVal * 1.2); // Add 20% headroom for a professional, uncompressed line layout
    
    $width = 1000;
    $height = 280;
    $paddingX = 50;
    $paddingY = 40;
    
    $pointsVisits = [];
    $pointsUniques = [];
    
    foreach ($chart_data as $i => $data) {
        // X ranges from paddingX to width - paddingX
        $x = $paddingX + ($i * ($width - 2 * $paddingX) / 14);
        
        // Y ranges from height - paddingY (bottom) to paddingY (top)
        $yVisits = ($height - $paddingY) - (($data['visits'] / $maxVal) * ($height - 2 * $paddingY));
        $yUniques = ($height - $paddingY) - (($data['uniques'] / $maxVal) * ($height - 2 * $paddingY));
        
        $pointsVisits[] = ['x' => $x, 'y' => $yVisits, 'val' => $data['visits'], 'date' => $data['date']];
        $pointsUniques[] = ['x' => $x, 'y' => $yUniques, 'val' => $data['uniques'], 'date' => $data['date']];
    }
    
    // Construct line path definitions
    $lineVisitsPath = '';
    $lineUniquesPath = '';
    
    foreach ($pointsVisits as $i => $p) {
        $cmd = ($i === 0) ? 'M' : 'L';
        $lineVisitsPath .= "{$cmd} {$p['x']} {$p['y']} ";
    }
    
    foreach ($pointsUniques as $i => $p) {
        $cmd = ($i === 0) ? 'M' : 'L';
        $lineUniquesPath .= "{$cmd} {$p['x']} {$p['y']} ";
    }
    
    // Construct area path definitions for gradient fills
    $areaVisitsPath = '';
    $areaUniquesPath = '';
    $bottomY = $height - $paddingY;
    
    if (count($pointsVisits) > 0) {
        $first = $pointsVisits[0];
        $last = end($pointsVisits);
        $areaVisitsPath = $lineVisitsPath . " L {$last['x']} {$bottomY} L {$first['x']} {$bottomY} Z";
    }
    
    if (count($pointsUniques) > 0) {
        $first = $pointsUniques[0];
        $last = end($pointsUniques);
        $areaUniquesPath = $lineUniquesPath . " L {$last['x']} {$bottomY} L {$first['x']} {$bottomY} Z";
    }
@endphp

<!-- Analytics Stats Grid -->
<div class="admin-analytics-grid">
    <!-- Total Visits -->
    <div class="analytic-card">
        <span class="lbl">Total Visits</span>
        <div class="val val-visits">{{ number_format($visits_count) }}</div>
        <div class="glow-indicator" style="background: rgba(249, 115, 22, 0.1); color: #f97316;">📈</div>
    </div>
    
    <!-- Unique Visitors -->
    <div class="analytic-card">
        <span class="lbl">Uniques</span>
        <div class="val val-uniques">{{ number_format($uniques_count) }}</div>
        <div class="glow-indicator" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">👤</div>
    </div>

    <!-- Booking Clicks -->
    <div class="analytic-card">
        <span class="lbl">Cal.com Clicks</span>
        <div class="val val-cals">{{ number_format($cal_clicks_count) }}</div>
        <div class="glow-indicator" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">📅</div>
    </div>

    <!-- Budget Calculations -->
    <div class="analytic-card">
        <span class="lbl">Calculator Runs</span>
        <div class="val val-budgets">{{ number_format($budget_calcs_count) }}</div>
        <div class="glow-indicator" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">💰</div>
    </div>
</div>

<!-- Dynamic Glowing SVG Analytics Line Chart -->
<div class="svg-chart-container">
    <div class="chart-header">
        <div>
            <h3>Visitor Activity Trend</h3>
            <span class="muted" style="font-size: 13px;">15-day chronological logging overview</span>
        </div>
        <div class="chart-legend">
            <div class="legend-item">
                <span class="legend-dot" style="background: #f97316; box-shadow: 0 0 8px rgba(249,115,22,0.6);"></span>
                <span style="color: var(--muted);">Total Visits</span>
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background: #a855f7; box-shadow: 0 0 8px rgba(168,85,247,0.6);"></span>
                <span style="color: var(--muted);">Unique Visitors</span>
            </div>
        </div>
    </div>
    
    <div class="svg-canvas-wrapper">
        <svg viewBox="0 0 1000 280" width="100%" height="100%" style="overflow: visible;">
            <defs>
                <!-- Area Gradients -->
                <linearGradient id="gradientVisits" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#f97316" stop-opacity="0.15" />
                    <stop offset="100%" stop-color="#f97316" stop-opacity="0.00" />
                </linearGradient>
                <linearGradient id="gradientUniques" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#a855f7" stop-opacity="0.15" />
                    <stop offset="100%" stop-color="#a855f7" stop-opacity="0.00" />
                </linearGradient>
            </defs>
            
            <!-- Horizontal Grid Lines -->
            @for($g = 0; $g <= 4; $g++)
                @php
                    $gridY = $paddingY + ($g * ($height - 2 * $paddingY) / 4);
                    $gridVal = round($maxVal - ($g * $maxVal / 4));
                @endphp
                <line x1="{{ $paddingX }}" y1="{{ $gridY }}" x2="{{ $width - $paddingX }}" y2="{{ $gridY }}" stroke="rgba(255,255,255,0.04)" stroke-width="1" />
                <text x="{{ $paddingX - 12 }}" y="{{ $gridY + 4 }}" fill="var(--muted)" font-size="10" font-weight="700" text-anchor="end" opacity="0.6">{{ $gridVal }}</text>
            @endfor

            <!-- SVG Paths (Gradients / Areas) -->
            @if($areaVisitsPath)
                <path d="{{ $areaVisitsPath }}" fill="url(#gradientVisits)" />
            @endif
            @if($areaUniquesPath)
                <path d="{{ $areaUniquesPath }}" fill="url(#gradientUniques)" />
            @endif

            <!-- SVG Paths (Stroke Lines) -->
            @if($lineVisitsPath)
                <path d="{{ $lineVisitsPath }}" fill="none" stroke="#f97316" stroke-width="3" stroke-linecap="round" class="chart-path-line chart-glow-filter" />
            @endif
            @if($lineUniquesPath)
                <path d="{{ $lineUniquesPath }}" fill="none" stroke="#a855f7" stroke-width="3" stroke-linecap="round" class="chart-path-line chart-glow-filter" />
            @endif

            <!-- Chart Hover Interactivity Circles & Date Labels -->
            @foreach($pointsVisits as $i => $pv)
                @php $pu = $pointsUniques[$i]; @endphp
                <!-- Date Label X Axis -->
                @if($i % 2 === 0 || $i === 14)
                    <text x="{{ $pv['x'] }}" y="{{ $height - 10 }}" fill="var(--muted)" font-size="10" font-weight="700" text-anchor="middle" opacity="0.6">{{ $pv['date'] }}</text>
                    <line x1="{{ $pv['x'] }}" y1="{{ $height - $paddingY }}" x2="{{ $pv['x'] }}" y2="{{ $height - $paddingY + 6 }}" stroke="rgba(255,255,255,0.1)" stroke-width="1" />
                @endif

                <!-- Invisible vertical hover guidelines -->
                <line x1="{{ $pv['x'] }}" y1="{{ $paddingY }}" x2="{{ $pv['x'] }}" y2="{{ $height - $paddingY }}" stroke="var(--purple)" stroke-width="1" stroke-dasharray="3,3" opacity="0" class="hover-guide-{{ $i }}" style="pointer-events: none;" />

                <!-- Total Visits interactive dots -->
                <circle cx="{{ $pv['x'] }}" cy="{{ $pv['y'] }}" r="4" fill="#fff" stroke="#f97316" stroke-width="2.5" class="chart-point" data-date="{{ $pv['date'] }}" data-visits="{{ $pv['val'] }}" data-uniques="{{ $pu['val'] }}" data-index="{{ $i }}" />

                <!-- Unique Visitors interactive dots -->
                <circle cx="{{ $pu['x'] }}" cy="{{ $pu['y'] }}" r="4" fill="#fff" stroke="#a855f7" stroke-width="2.5" class="chart-point" data-date="{{ $pu['date'] }}" data-visits="{{ $pv['val'] }}" data-uniques="{{ $pu['val'] }}" data-index="{{ $i }}" />
            @endforeach
        </svg>
        
        <!-- Interactive CSS Tooltip -->
        <div id="chart-tooltip" class="chart-tooltip"></div>
    </div>
</div>

<!-- Recent Messages -->
<div class="card" style="margin-top: 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: var(--navy);">Pesan Terbaru</h2>
        <a class="btn btn-ghost btn-sm" href="{{ route('admin.messages') }}">Semua Pesan →</a>
    </div>
    
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th style="width: 25%;">Nama</th>
                    <th style="width: 25%;">Email</th>
                    <th>Pesan</th>
                    <th style="width: 12%; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $m)
                    <tr>
                        <td>
                            <strong>{{ $m->name }}</strong>
                            @unless($m->is_read)
                                <span class="badge" style="background: var(--purple); color: #fff; font-size: 9px; padding: 2px 6px; border-radius: 99px; margin-left: 6px;">new</span>
                            @endunless
                        </td>
                        <td class="muted">{{ $m->email }}</td>
                        <td>{{ Str::limit($m->message, 80) }}</td>
                        <td style="text-align: right;">
                            <a class="btn btn-ghost btn-sm" href="{{ route('admin.messages.show', $m) }}">Buka</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted" style="text-align: center; padding: 40px 0;">Belum ada pesan masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- System Recovery & Emergency Actions -->
<div class="card" style="margin-top: 30px; border: 1px solid rgba(255, 85, 0, 0.15); background: rgba(255, 85, 0, 0.015);">
    <div style="margin-bottom: 15px;">
        <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: #ff5500; display: flex; align-items: center; gap: 8px;">
            <span>🛡️</span> System Recovery & Emergency Tools
        </h2>
        <p class="muted" style="margin: 6px 0 0; font-size: 13px;">Gunakan perkakas darurat ini jika data website Anda di VPS berantakan atau terjadi duplikasi data akibat perpindahan sistem.</p>
    </div>
    <hr style="border-color: rgba(255, 85, 0, 0.1); margin: 0 0 20px 0;">
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        <form method="POST" action="{{ route('admin.portfolio.reset') }}" onsubmit="return confirm('Apakah Anda yakin ingin mereset portofolio? Semua data kustom portfolio saat ini akan dihapus dan dikembalikan ke 3 project premium bawaan.');" style="margin: 0;">
            @csrf
            <button type="submit" class="btn" style="background: linear-gradient(135deg, #ff5500, #ff2200); color: #fff; border: none; padding: 12px 24px; font-weight: 600; font-family: 'Space Grotesk'; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 15px rgba(255, 85, 0, 0.2); transition: all 0.2s;">
                🔄 Reset Portfolio Showcase
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tooltip = document.getElementById('chart-tooltip');
    const wrapper = document.querySelector('.svg-canvas-wrapper');
    
    if (tooltip && wrapper) {
        document.querySelectorAll('.chart-point').forEach(circle => {
            circle.addEventListener('mouseenter', e => {
                const idx = circle.dataset.index;
                const date = circle.dataset.date;
                const visits = circle.dataset.visits;
                const uniques = circle.dataset.uniques;
                
                // Show guide line
                const guide = document.querySelector(`.hover-guide-${idx}`);
                if (guide) guide.style.opacity = '0.35';
                
                // Update tooltip content
                tooltip.innerHTML = `
                    <div style="font-weight:700;margin-bottom:4px;color:#f97316;">${date}</div>
                    <div style="display:flex;justify-content:space-between;gap:12px;">
                        <span>Visits:</span>
                        <strong>${visits}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;gap:12px;color:#a855f7;">
                        <span>Uniques:</span>
                        <strong>${uniques}</strong>
                    </div>
                `;
                
                tooltip.classList.add('visible');
                
                // Position calculations relative to the wrapper
                const rect = circle.getBoundingClientRect();
                const wrapRect = wrapper.getBoundingClientRect();
                
                const left = rect.left - wrapRect.left + (rect.width / 2) - (tooltip.offsetWidth / 2);
                const top = rect.top - wrapRect.top - tooltip.offsetHeight - 12;
                
                tooltip.style.left = left + 'px';
                tooltip.style.top = top + 'px';
            });
            
            circle.addEventListener('mouseleave', e => {
                const idx = circle.dataset.index;
                const guide = document.querySelector(`.hover-guide-${idx}`);
                if (guide) guide.style.opacity = '0';
                
                tooltip.classList.remove('visible');
            });
        });
    }
});
</script>
@endsection