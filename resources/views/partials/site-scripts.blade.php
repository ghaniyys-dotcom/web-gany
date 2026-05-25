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
        
        // Add one scrambled character at the end
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
        // Final state
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

        // Hide trailing cursor once typing completes
        const cursorEl = scrambleTitle.querySelector('.animate-pulse');
        if (cursorEl) {
          cursorEl.style.display = 'none';
        }
      }
    }
    
    setTimeout(scrambleType, 800);
  });

  // Services Carousel horizontal snap & arrow navigation & wheel scrolling sync & mouse dragging
  document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('servicesSnapTrack');
    const dots = document.querySelectorAll('#servicesCarouselDots .carousel-dot-indicator');
    const arrowLeft = document.getElementById('servicesArrowLeft');
    const arrowRight = document.getElementById('servicesArrowRight');
    
    if (track) {
      const slides = track.querySelectorAll('.snap-slide');
      const totalSlides = slides.length;
      
      function updateNavState(activeIndex) {
        // Sync dots
        dots.forEach((dot, idx) => {
          if (idx === activeIndex) {
            dot.classList.add('active');
          } else {
            dot.classList.remove('active');
          }
        });
        
        // Sync arrows (Pill Dock style: change opacity instead of display)
        if (arrowLeft) {
          if (activeIndex === 0) {
            arrowLeft.style.opacity = '0.15';
            arrowLeft.style.pointerEvents = 'none';
          } else {
            arrowLeft.style.opacity = '1';
            arrowLeft.style.pointerEvents = 'auto';
          }
        }
        if (arrowRight) {
          if (activeIndex === totalSlides - 1) {
            arrowRight.style.opacity = '0.15';
            arrowRight.style.pointerEvents = 'none';
          } else {
            arrowRight.style.opacity = '1';
            arrowRight.style.pointerEvents = 'auto';
          }
        }
      }
      
      track.addEventListener('scroll', function() {
        const slideWidth = track.offsetWidth;
        const scrollPosition = track.scrollLeft;
        const activeIndex = Math.round(scrollPosition / slideWidth);
        updateNavState(activeIndex);
      }, { passive: true });
      
      dots.forEach(dot => {
        dot.addEventListener('click', function() {
          const index = parseInt(this.getAttribute('data-dot-index'));
          const slideWidth = track.offsetWidth;
          track.scrollTo({
            left: index * slideWidth,
            behavior: 'smooth'
          });
        });
      });
      
      if (arrowLeft) {
        arrowLeft.addEventListener('click', function() {
          const slideWidth = track.offsetWidth;
          const scrollPosition = track.scrollLeft;
          const activeIndex = Math.round(scrollPosition / slideWidth);
          if (activeIndex > 0) {
            track.scrollTo({
              left: (activeIndex - 1) * slideWidth,
              behavior: 'smooth'
            });
          }
        });
      }
      
      if (arrowRight) {
        arrowRight.addEventListener('click', function() {
          const slideWidth = track.offsetWidth;
          const scrollPosition = track.scrollLeft;
          const activeIndex = Math.round(scrollPosition / slideWidth);
          if (activeIndex < totalSlides - 1) {
            track.scrollTo({
              left: (activeIndex + 1) * slideWidth,
              behavior: 'smooth'
            });
          }
        });
      }
      
      // Translate vertical wheel scroll to horizontal slide snap over track
      let isTransitioning = false;
      track.addEventListener('wheel', function(e) {
        if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
          const slideWidth = track.offsetWidth;
          const scrollPosition = track.scrollLeft;
          const activeIndex = Math.round(scrollPosition / slideWidth);
          
          if (e.deltaY > 0) {
            if (activeIndex < totalSlides - 1) {
              e.preventDefault();
              if (!isTransitioning) {
                isTransitioning = true;
                track.scrollTo({
                  left: (activeIndex + 1) * slideWidth,
                  behavior: 'smooth'
                });
                setTimeout(() => { isTransitioning = false; }, 600);
              }
            }
          } else if (e.deltaY < 0) {
            if (activeIndex > 0) {
              e.preventDefault();
              if (!isTransitioning) {
                isTransitioning = true;
                track.scrollTo({
                  left: (activeIndex - 1) * slideWidth,
                  behavior: 'smooth'
                });
                setTimeout(() => { isTransitioning = false; }, 600);
              }
            }
          }
        }
      }, { passive: false });

      // Click & Drag horizontal mouse scroll dragging support
      let isDown = false;
      let startX;
      let scrollLeft;
      let dragMoved = false;

      track.addEventListener('mousedown', (e) => {
        isDown = true;
        dragMoved = false;
        track.classList.add('active-dragging');
        startX = e.pageX - track.offsetLeft;
        scrollLeft = track.scrollLeft;
      });

      track.addEventListener('mouseleave', () => {
        if (!isDown) return;
        isDown = false;
        track.classList.remove('active-dragging');
        snapToSlide();
      });

      track.addEventListener('mouseup', () => {
        if (!isDown) return;
        isDown = false;
        track.classList.remove('active-dragging');
        snapToSlide();
      });

      track.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - track.offsetLeft;
        const walk = (x - startX) * 1.5; // Drag speed multiplier
        if (Math.abs(walk) > 5) {
          dragMoved = true;
        }
        track.scrollLeft = scrollLeft - walk;
      });

      // Prevent link or hover trigger during active drag
      track.addEventListener('click', (e) => {
        if (dragMoved) {
          e.preventDefault();
          e.stopPropagation();
        }
      }, true);

      function snapToSlide() {
        const slideWidth = track.offsetWidth;
        const scrollPosition = track.scrollLeft;
        const activeIndex = Math.round(scrollPosition / slideWidth);
        track.scrollTo({
          left: activeIndex * slideWidth,
          behavior: 'smooth'
        });
      }
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
        
        let tiltX = 0;
        let tiltY = 0;
        if (tiltEnabled) {
          tiltX = -(y / (rect.height / 2)) * 12;
          tiltY = (x / (rect.width / 2)) * 12;
        }
        
        card.style.transform = `translate3d(${pullX}px, ${pullY}px, 0) rotateX(${tiltX}deg) rotateY(${tiltY}deg)`;
        card.style.transition = 'none';

        // Dynamic Sheen Calculation
        const pctX = ((e.clientX - rect.left) / rect.width) * 100;
        const pctY = ((e.clientY - rect.top) / rect.height) * 100;
        card.style.setProperty('--sheen-x', `${pctX}%`);
        card.style.setProperty('--sheen-y', `${pctY}%`);
        card.style.setProperty('--sheen-opacity', '1');

        // Inverse Image Parallax
        const img = card.querySelector('.bento-media-img');
        if (img) {
          img.style.transform = `scale(1.12) translate3d(${-pullX * 0.5}px, ${-pullY * 0.5}px, 0)`;
          img.style.transition = 'none';
        }
      });
      
      card.addEventListener('mouseleave', function() {
        card.style.transform = 'translate3d(0, 0, 0) rotateX(0deg) rotateY(0deg)';
        card.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';

        // Reset Sheen
        card.style.setProperty('--sheen-opacity', '0');

        // Reset Inverse Parallax
        const img = card.querySelector('.bento-media-img');
        if (img) {
          img.style.transform = 'scale(1.12) translate3d(0, 0, 0)';
          img.style.transition = 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
        }
      });
    });
  });

  // Centered Timeline Scroll Line Drawer & Accordion Expansion
  document.addEventListener('DOMContentLoaded', function() {
    const wireDraw = document.getElementById('timelineWireDraw');
    const processSection = document.getElementById('process');
    const steps = document.querySelectorAll('.timeline-step-row');
    
    if (wireDraw && processSection) {
      const container = document.querySelector('.timeline-wire-container');
      const track = document.querySelector('.timeline-wire-track');
      
      // 📐 Dynamically align winding Bezier path to the physical vertical center of each node
      function alignTimelineWire() {
        if (!container || !track || !wireDraw) return;
        const dots = document.querySelectorAll('.timeline-node-dot');
        if (dots.length === 0) return;
        
        const containerRect = container.getBoundingClientRect();
        const totalHeight = containerRect.height;
        
        // Map Y coordinates of each dot to the 0-1000 coordinate space of SVG viewBox
        const svgY = Array.from(dots).map(dot => {
          const dotRect = dot.getBoundingClientRect();
          const relativeY = (dotRect.top + dotRect.height / 2) - containerRect.top;
          return (relativeY / totalHeight) * 1000;
        });
        
        // Build smooth winding path running exactly through x = 40 (center) at each dot's y position
        const d = `M 40,0 ` +
                  `Q 15,${svgY[0] / 2} 40,${svgY[0]} ` +
                  `T 40,${svgY[1]} ` +
                  `T 40,${svgY[2]} ` +
                  `T 40,${svgY[3]} ` +
                  `L 40,1000`;
                  
        track.setAttribute('d', d);
        wireDraw.setAttribute('d', d);
        
        const pathLength = wireDraw.getTotalLength();
        wireDraw.style.strokeDasharray = pathLength;
      }
      
      alignTimelineWire();
      window.addEventListener('resize', alignTimelineWire);
      
      window.addEventListener('scroll', function() {
        const rect = processSection.getBoundingClientRect();
        const sectionHeight = rect.height;
        const viewportHeight = window.innerHeight;
        
        const scrollDistance = -rect.top + (viewportHeight / 2);
        let progress = scrollDistance / sectionHeight;
        progress = Math.max(0, Math.min(1, progress));
        
        const pathLength = wireDraw.getTotalLength();
        const offset = pathLength - (progress * pathLength);
        wireDraw.style.strokeDashoffset = offset;
        
        steps.forEach(step => {
          const stepRect = step.getBoundingClientRect();
          if (stepRect.top < viewportHeight * 0.65) {
            step.classList.add('active-step');
          } else {
            step.classList.remove('active-step');
          }
        });
      }, { passive: true });
    }
  });

  window.toggleTimelineAccordion = function(box) {
    const row = box.closest('.timeline-step-row');
    const isOpen = box.classList.contains('open');
    const content = box.querySelector('.timeline-accordion-content');
    
    document.querySelectorAll('.timeline-accordion-box').forEach(otherBox => {
      otherBox.classList.remove('open');
      const otherContent = otherBox.querySelector('.timeline-accordion-content');
      if (otherContent) otherContent.style.maxHeight = '0px';
    });
    
    document.querySelectorAll('.timeline-step-row').forEach(otherRow => {
      otherRow.classList.remove('open-step');
    });
    
    if (!isOpen) {
      box.classList.add('open');
      if (row) row.classList.add('open-step');
      content.style.maxHeight = '250px';
      if (window.FluxoraAudio) window.FluxoraAudio.playTactileClick();
    } else {
      box.classList.remove('open');
      content.style.maxHeight = '0px';
    }
  };
</script>
<script src="{{ asset('js/site-scripts.js') }}?v={{ filemtime(public_path('js/site-scripts.js')) }}" defer></script>
