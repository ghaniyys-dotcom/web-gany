@php
  $skillsJson = [];
  if (isset($skills)) {
      $skillsJson = $skills->map(function($s) {
          return [
              'name'     => $s->name,
              'level'    => $s->level,
              'years'    => $s->years,
              'category' => $s->category,
              'color'    => $s->color,
          ];
      })->toArray();
  }
@endphp
<script>
  window._isHome = @json(request()->routeIs('home'));
  window._sessionSuccess = @json(session('success'));
  window._expertiseTickers = @json(isset($intro) ? ($intro->expertise_tickers ?? []) : []);
  window._introRoles = @json(isset($intro) ? ($intro->roles ?? []) : []);
  window._skillData = @json($skillsJson);

  // Live premium timezone clock tick
  document.addEventListener('DOMContentLoaded', function() {
    function updateClock() {
      const clockEl = document.getElementById('footer-local-clock');
      if (!clockEl) return;
      
      const options = {
        timeZone: 'Asia/Jakarta',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
      };
      
      try {
        const formatter = new Intl.DateTimeFormat('en-US', options);
        clockEl.textContent = formatter.format(new Date());
      } catch (e) {
        const now = new Date();
        const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        const jktTime = new Date(utc + (3600000 * 7));
        clockEl.textContent = jktTime.toLocaleTimeString('en-US', {
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          hour12: true
        });
      }
    }
    setInterval(updateClock, 1000);
    updateClock();
  });
</script>
<script src="{{ asset('js/site-scripts.js') }}?v={{ filemtime(public_path('js/site-scripts.js')) }}" defer></script>
