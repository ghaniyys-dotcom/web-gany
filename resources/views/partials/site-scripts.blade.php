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

  // Edge Triggered Navigation Reveal
  document.addEventListener('DOMContentLoaded', function() {
    const nav = document.getElementById('hudNavBar');
    const sensor = document.getElementById('navSensorStrip');
    
    if (nav) {
      if (window.scrollY > 80) {
        nav.classList.add('nav-visible');
      }
      
      window.addEventListener('scroll', function() {
        if (window.scrollY > 80) {
          nav.classList.add('nav-visible');
        } else {
          nav.classList.remove('nav-visible');
        }
      }, { passive: true });
      
      if (sensor) {
        sensor.addEventListener('mouseenter', function() {
          nav.classList.add('nav-visible');
        });
      }
      
      nav.addEventListener('mouseleave', function() {
        if (window.scrollY <= 80) {
          nav.classList.remove('nav-visible');
        }
      });
    }
  });

  // Scramble & Typing Text effect with custom markup parsing
  document.addEventListener('DOMContentLoaded', function() {
    const scrambleTitle = document.getElementById('hero-scramble-title');
    const mountText = document.getElementById('scramble-mount-text');
    if (!scrambleTitle || !mountText) return;
    
    const words = scrambleTitle.getAttribute('data-words') || '';
    let charsArray = [];
    let isHighlighted = false;
    
    for (let i = 0; i < words.length; i++) {
      if (words[i] === '*') {
        isHighlighted = !isHighlighted;
        continue;
      }
      charsArray.push({
        char: words[i],
        highlight: isHighlighted
      });
    }
    
    let currentIdx = 0;
    const scrambleChars = 'ABCDEFGHJKMNPQRSTUVWXY0123456789@#$%&✦◈↗';
    
    function scrambleType() {
      if (currentIdx < charsArray.length) {
        let html = '';
        for (let i = 0; i < currentIdx; i++) {
          const item = charsArray[i];
          if (item.highlight) {
            html += `<span class="text-neon">${item.char}</span>`;
          } else {
            html += `<span>${item.char}</span>`;
          }
        }
        
        if (currentIdx < charsArray.length && charsArray[currentIdx].char !== ' ') {
          const randChar = scrambleChars[Math.floor(Math.random() * scrambleChars.length)];
          if (charsArray[currentIdx].highlight) {
            html += `<span class="text-neon" style="opacity: 0.8;">${randChar}</span>`;
          } else {
            html += `<span style="opacity: 0.8;">${randChar}</span>`;
          }
        }
        
        mountText.innerHTML = html;
        currentIdx++;
        setTimeout(scrambleType, 30 + Math.random() * 20);
      } else {
        let html = '';
        for (let i = 0; i < charsArray.length; i++) {
          const item = charsArray[i];
          if (item.highlight) {
            html += `<span class="text-neon">${item.char}</span>`;
          } else {
            html += `<span>${item.char}</span>`;
          }
        }
        mountText.innerHTML = html;
        const cursorEl = scrambleTitle.querySelector('.animate-pulse');
        if (cursorEl) cursorEl.style.display = 'none';
      }
    }
    
    setTimeout(scrambleType, 800);
  });

  // Services Carousel horizontal snap & arrow navigation
  document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('servicesSnapTrack');
    const dots = document.querySelectorAll('#servicesCarouselDots .carousel-dot-indicator');
    const arrowLeft = document.getElementById('servicesArrowLeft');
    const arrowRight = document.getElementById('servicesArrowRight');
    
    if (track) {
      const slides = track.querySelectorAll('.snap-slide');
      const totalSlides = slides.length;
      
      function updateNavState(activeIndex) {
        dots.forEach((dot, idx) => {
          dot.classList.toggle('active', idx === activeIndex);
        });
        if (arrowLeft) {
          arrowLeft.style.opacity = activeIndex === 0 ? '0.15' : '1';
          arrowLeft.style.pointerEvents = activeIndex === 0 ? 'none' : 'auto';
        }
        if (arrowRight) {
          arrowRight.style.opacity = activeIndex === totalSlides - 1 ? '0.15' : '1';
          arrowRight.style.pointerEvents = activeIndex === totalSlides - 1 ? 'none' : 'auto';
        }
      }
      
      track.addEventListener('scroll', function() {
        const slideWidth = track.offsetWidth;
        const activeIndex = Math.round(track.scrollLeft / slideWidth);
        updateNavState(activeIndex);
      }, { passive: true });
      
      dots.forEach(dot => {
        dot.addEventListener('click', function() {
          const index = parseInt(this.getAttribute('data-dot-index'));
          track.scrollTo({ left: index * track.offsetWidth, behavior: 'smooth' });
        });
      });
      
      if (arrowLeft) {
        arrowLeft.addEventListener('click', function() {
          const activeIndex = Math.round(track.scrollLeft / track.offsetWidth);
          if (activeIndex > 0) track.scrollTo({ left: (activeIndex - 1) * track.offsetWidth, behavior: 'smooth' });
        });
      }
      if (arrowRight) {
        arrowRight.addEventListener('click', function() {
          const activeIndex = Math.round(track.scrollLeft / track.offsetWidth);
          if (activeIndex < totalSlides - 1) track.scrollTo({ left: (activeIndex + 1) * track.offsetWidth, behavior: 'smooth' });
        });
      }
      
      let isTransitioning = false;
      track.addEventListener('wheel', function(e) {
        if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
          const activeIndex = Math.round(track.scrollLeft / track.offsetWidth);
          if (e.deltaY > 0 && activeIndex < totalSlides - 1) {
            e.preventDefault();
            if (!isTransitioning) { isTransitioning = true; track.scrollTo({ left: (activeIndex + 1) * track.offsetWidth, behavior: 'smooth' }); setTimeout(() => isTransitioning = false, 600); }
          } else if (e.deltaY < 0 && activeIndex > 0) {
            e.preventDefault();
            if (!isTransitioning) { isTransitioning = true; track.scrollTo({ left: (activeIndex - 1) * track.offsetWidth, behavior: 'smooth' }); setTimeout(() => isTransitioning = false, 600); }
          }
        }
      }, { passive: false });
    }
  });

  // Bento Grid filtering
  document.addEventListener('DOMContentLoaded', function() {
    const filterPills = document.querySelectorAll('#bentoFilterTrack .bento-filter-pill');
    const bentoCards = document.querySelectorAll('#bentoGridContainer .bento-card-custom');
    
    if (filterPills.length > 0 && bentoCards.length > 0) {
      filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
          filterPills.forEach(p => p.classList.remove('active'));
          this.classList.add('active');
          const filterVal = this.getAttribute('data-filter');
          bentoCards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (filterVal === 'all' || cardCat === filterVal) {
              card.style.display = 'block';
              setTimeout(() => { card.style.opacity = '1'; card.style.transform = 'scale(1)'; }, 50);
            } else {
              card.style.opacity = '0';
              card.style.transform = 'scale(0.9)';
              setTimeout(() => { card.style.display = 'none'; }, 300);
            }
          });
        });
      });
    }
  });

  // Magnetic Card Puller & 3D Card Tilter
  document.addEventListener('DOMContentLoaded', function() {
    const magneticCards = document.querySelectorAll('.magnetic-card');
    magneticCards.forEach(card => {
      const tiltEnabled = card.getAttribute('data-tilt-enabled') === 'true';
      card.addEventListener('mousemove', function(e) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        const pullX = x * 0.12;
        const pullY = y * 0.12;
        let tiltX = 0, tiltY = 0;
        if (tiltEnabled) { tiltX = -(y / (rect.height / 2)) * 12; tiltY = (x / (rect.width / 2)) * 12; }
        card.style.transform = `translate3d(${pullX}px, ${pullY}px, 0) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
        card.style.transition = 'none';
        const pctX = ((e.clientX - rect.left) / rect.width) * 100;
        const pctY = ((e.clientY - rect.top) / rect.height) * 100;
        card.style.setProperty('--sheen-x', `${pctX}%`);
        card.style.setProperty('--sheen-y', `${pctY}%`);
        card.style.setProperty('--sheen-opacity', '1');
        const img = card.querySelector('.bento-media-img');
        if (img) { img.style.transform = `scale(1.12) translate3d(${-pullX * 0.5}px, ${-pullY * 0.5}px, 0)`; img.style.transition = 'none'; }
      });
      card.addEventListener('mouseleave', function() {
        card.style.transform = 'translate3d(0, 0, 0) rotateX(0deg) rotateY(0deg)';
        card.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
        card.style.setProperty('--sheen-opacity', '0');
        const img = card.querySelector('.bento-media-img');
        if (img) { img.style.transform = 'scale(1.12) translate3d(0, 0, 0)'; img.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)'; }
      });
    });
  });

  // Centered Timeline Scroll Line Drawer & Accordion
  document.addEventListener('DOMContentLoaded', function() {
    const wireDraw = document.getElementById('timelineWireDraw');
    const processSection = document.getElementById('process');
    const steps = document.querySelectorAll('.timeline-step-row');
    
    if (wireDraw && processSection) {
      const container = document.querySelector('.timeline-wire-container');
      const track = document.querySelector('.timeline-wire-track');
      
      function alignTimelineWire() {
        if (!container || !track || !wireDraw) return;
        const dots = document.querySelectorAll('.timeline-node-dot');
        if (dots.length === 0) return;
        const containerRect = container.getBoundingClientRect();
        const totalHeight = containerRect.height;
        const svgY = Array.from(dots).map(dot => {
          const dotRect = dot.getBoundingClientRect();
          return ((dotRect.top + dotRect.height / 2) - containerRect.top) / totalHeight * 1000;
        });
        const d = `M 40,0 Q 15,${svgY[0] / 2} 40,${svgY[0]} T 40,${svgY[1]} T 40,${svgY[2]} T 40,${svgY[3]} L 40,1000`;
        track.setAttribute('d', d);
        wireDraw.setAttribute('d', d);
        const pathLength = wireDraw.getTotalLength();
        wireDraw.style.strokeDasharray = pathLength;
      }
      
      alignTimelineWire();
      window.addEventListener('resize', alignTimelineWire);
      
      window.addEventListener('scroll', function() {
        const rect = processSection.getBoundingClientRect();
        const scrollDistance = -rect.top + (window.innerHeight / 2);
        let progress = scrollDistance / rect.height;
        progress = Math.max(0, Math.min(1, progress));
        const pathLength = wireDraw.getTotalLength();
        wireDraw.style.strokeDashoffset = pathLength - (progress * pathLength);
        steps.forEach(step => {
          step.classList.toggle('active-step', step.getBoundingClientRect().top < window.innerHeight * 0.65);
        });
      }, { passive: true });
    }
  });

  window.toggleTimelineAccordion = function(box) {
    const row = box.closest('.timeline-step-row');
    const isOpen = box.classList.contains('open');
    document.querySelectorAll('.timeline-accordion-box.open').forEach(b => {
      if (b !== box) { b.classList.remove('open'); b.closest('.timeline-step-row').classList.remove('open'); }
    });
    box.classList.toggle('open', !isOpen);
    row.classList.toggle('open', !isOpen);
  };

  // Mobile Command Center trigger (same as Cmd+K)
  window.openCommandCenter = function() {
    if (window.openCC) {
      window.openCC();
    }
    // Also close mobile menu if open
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu) {
      mobileMenu.classList.remove('open');
      mobileMenu.setAttribute('aria-hidden', 'true');
    }
  };
</script>
<script src="{{ asset('js/audio-engine.js') }}"></script>
<script src="{{ asset('js/site-scripts.js') }}" defer></script>

