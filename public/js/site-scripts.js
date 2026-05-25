(function(){
/* =================================================================
   🌊 LAZY LOAD HEAVY LIBRARIES DYNAMICALLY
   ================================================================= */
function loadAudioEngine() {
  if (window.FluxoraAudio) return;
  const script = document.createElement('script');
  script.src = "/js/audio-engine.js";
  script.async = true;
  document.body.appendChild(script);
}

function loadLenis() {
  if (typeof window.Lenis === 'undefined') {
    setTimeout(loadLenis, 50);
    return;
  }
  
  if (window.lenis) return;

  const lenis = new window.Lenis({
    duration: 1.25,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    direction: 'vertical',
    gestureDirection: 'vertical',
    smooth: true,
    mouseMultiplier: 1.0,
    smoothTouch: false,
    touchMultiplier: 1.5,
    infinite: false,
  });

  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }
  requestAnimationFrame(raf);
  
  window.lenis = lenis;

  // Connect Lenis with anchor link smooth jumps
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      const targetEl = document.querySelector(targetId);
      if (targetEl) {
        e.preventDefault();
        lenis.scrollTo(targetEl, { offset: -80 });
      }
    });
  });
}

function initParallax() {
  const parallaxEls = document.querySelectorAll('[data-parallax]');
  if (parallaxEls.length === 0) return;

  function updateParallax() {
    parallaxEls.forEach(el => {
      const speed = parseFloat(el.dataset.parallaxSpeed) || 0.1;
      const parent = el.closest('header, section') || el.parentElement;
      const parentRect = parent.getBoundingClientRect();
      
      if (parentRect.top < window.innerHeight && parentRect.bottom > 0) {
        const offset = parentRect.top * speed * 0.45;
        el.style.transform = `translate3d(0, ${offset}px, 0)`;
      }
    });
  }

  window.addEventListener('scroll', updateParallax, { passive: true });
  updateParallax();
}

window.addEventListener('load', () => {
  loadLenis();
  initParallax();
  const audioTimeout = setTimeout(loadAudioEngine, 1500);
  const interactionTrigger = () => {
    clearTimeout(audioTimeout);
    loadAudioEngine();
    window.removeEventListener('click', interactionTrigger);
    window.removeEventListener('mousemove', interactionTrigger);
    window.removeEventListener('scroll', interactionTrigger);
    window.removeEventListener('keydown', interactionTrigger);
  };
  window.addEventListener('click', interactionTrigger, { passive: true });
  window.addEventListener('mousemove', interactionTrigger, { passive: true });
  window.addEventListener('scroll', interactionTrigger, { passive: true });
  window.addEventListener('keydown', interactionTrigger, { passive: true });
});

/* =================================================================
   1. NAV TOGGLE
   ================================================================= */
const toggle=document.getElementById('navToggle');
const menu=document.getElementById('mobileMenu');
if(toggle&&menu){
  toggle.addEventListener('click',()=>{
    const open=menu.classList.toggle('open');
    toggle.setAttribute('aria-expanded',open?'true':'false');
    menu.setAttribute('aria-hidden',open?'false':'true');
  });
  menu.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{
    menu.classList.remove('open');
    toggle.setAttribute('aria-expanded','false');
    menu.setAttribute('aria-hidden','true');
  }));
}

/* =================================================================
   2. SCROLL REVEAL & STAGGERED CHOREOGRAPHY
   ================================================================= */
const observer=new IntersectionObserver((entries)=>{
  entries.forEach(entry=>{
    if(entry.isIntersecting){
      entry.target.classList.add('visible');
    }else if(entry.boundingClientRect.top>0){
      entry.target.classList.remove('visible');
    }
  });
},{threshold:0.1});

// Observe individual fade-in elements, skipping those inside staggered containers
document.querySelectorAll('.section,.card,.case,.step,.testimonial,.contact-box,.faq-item,.portfolio-card').forEach(el=>{
  if (el.closest('.reveal-stagger-cascade, .reveal-slide-alt, .reveal-counter')) {
    return;
  }
  el.classList.add('fade-in');
  observer.observe(el);
});

// Observe parent staggered reveal containers
document.querySelectorAll('.reveal-stagger-cascade, .reveal-scale-blur, .reveal-slide-alt, .reveal-counter').forEach(el=>{
  observer.observe(el);
});

// Observe mask transitions
document.querySelectorAll('.reveal-mask').forEach(el=>{
  observer.observe(el);
});

/* =================================================================
   3. ACTIVE NAV HIGHLIGHT (home page anchor scroll)
   ================================================================= */
if(window._isHome || window.location.pathname === '/') {
  const sections=document.querySelectorAll('section[id]');
  window.addEventListener('scroll',()=>{
    let cur='';
    sections.forEach(s=>{if(window.scrollY>=s.offsetTop-120)cur=s.id;});
    document.querySelectorAll('.links a,.mobile-menu a').forEach(a=>{
      const href=a.getAttribute('href')||'';
      const linkColor=getComputedStyle(document.documentElement).getPropertyValue('--link-color').trim()||'#475467';
      a.style.color=href==='#'+cur?'var(--purple)':linkColor;
    });
  },{passive:true});
}

/* =================================================================
   4. TOAST NOTIFICATIONS
   ================================================================= */
const msg=window._sessionSuccess || null;
if(msg){
  const t=document.createElement('div');
  t.className='toast';
  t.textContent=msg;
  document.body.appendChild(t);
  setTimeout(()=>{t.style.opacity='0';t.style.transform='translateY(20px)';setTimeout(()=>t.remove(),300);},3500);
}
document.querySelector('.contact-form')?.addEventListener('submit',function(){
  const b=this.querySelector('button[type=submit],button:not([type])');
  if(b){b.classList.add('loading');b.textContent='Sending...';}
});

/* =================================================================
   5. MAGNETIC CUSTOM CURSOR & PHYSICS GLOW
   ================================================================= */
const cursor = document.getElementById('pm-cursor');
const aura = document.getElementById('pm-cursor-aura');
const cursorLabel = aura ? aura.querySelector('.pm-cursor-label') : null;

if (cursor && aura && window.matchMedia('(hover:hover)').matches) {
  document.documentElement.classList.add('pm-cursor-enabled');

  let mouseX = 0, mouseY = 0;
  let ringX = 0, ringY = 0;
  let ringWidth = 40, ringHeight = 40;
  let activeSnappedEl = null;
  let snapTx = 0, snapTy = 0;

  const trailCanvas = document.createElement('canvas');
  trailCanvas.id = 'cursor-trail-canvas';
  Object.assign(trailCanvas.style, {
    position: 'fixed',
    top: '0',
    left: '0',
    width: '100vw',
    height: '100vh',
    pointerEvents: 'none',
    zIndex: '99998',
  });
  document.body.appendChild(trailCanvas);

  const ctx = trailCanvas.getContext('2d');
  let canvasW = trailCanvas.width = window.innerWidth;
  let canvasH = trailCanvas.height = window.innerHeight;

  window.addEventListener('resize', () => {
    canvasW = trailCanvas.width = window.innerWidth;
    canvasH = trailCanvas.height = window.innerHeight;
  }, { passive: true });

  const particles = [];
  window._pmCursorParticles = particles;
  let lastMouseX = 0;
  let lastMouseY = 0;

  class TrailParticle {
    constructor(x, y, vx, vy) {
      this.x = x;
      this.y = y;
      this.vx = vx * 0.12 + (Math.random() - 0.5) * 1.5;
      this.vy = vy * 0.12 - Math.random() * 1.8 - 0.4;
      this.size = Math.random() * 2.8 + 1.2;
      this.alpha = 1.0;
      this.decay = Math.random() * 0.025 + 0.02;
      this.hue = Math.floor(Math.random() * 25) + 15;
    }
    update() {
      this.x += this.vx;
      this.y += this.vy;
      this.vy += 0.015;
      this.size *= 0.965;
      this.alpha -= this.decay;
    }
    draw() {
      ctx.save();
      ctx.globalAlpha = this.alpha;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
      ctx.fillStyle = `hsl(${this.hue}, 100%, 55%)`;
      ctx.shadowBlur = this.size * 3;
      ctx.shadowColor = `hsl(${this.hue}, 100%, 50%)`;
      ctx.fill();
      ctx.restore();
    }
  }

  document.addEventListener('mousemove', e => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    cursor.style.left = mouseX + 'px';
    cursor.style.top = mouseY + 'px';

    const dx = mouseX - lastMouseX;
    const dy = mouseY - lastMouseY;
    const speed = Math.hypot(dx, dy);
    if (speed > 1.8) {
      const numToSpawn = Math.min(Math.floor(speed / 4), 3) + 1;
      for (let i = 0; i < numToSpawn; i++) {
        const offX = (Math.random() - 0.5) * 6;
        const offY = (Math.random() - 0.5) * 6;
        particles.push(new TrailParticle(mouseX + offX, mouseY + offY, dx, dy));
      }
    }
    lastMouseX = mouseX;
    lastMouseY = mouseY;
  }, { passive: true });

  function renderCursor() {
    let targetX = mouseX;
    let targetY = mouseY;

    ctx.clearRect(0, 0, canvasW, canvasH);
    for (let i = particles.length - 1; i >= 0; i--) {
      const p = particles[i];
      p.update();
      if (p.alpha <= 0 || p.size <= 0.1) {
        particles.splice(i, 1);
      } else {
        p.draw();
      }
    }

    if (activeSnappedEl) {
      const rect = activeSnappedEl.getBoundingClientRect();
      targetX = rect.left + rect.width / 2;
      targetY = rect.top + rect.height / 2;
      
      const deltaX = mouseX - targetX;
      const deltaY = mouseY - targetY;
      const maxDisp = 10;
      const strength = 0.35;
      let targetTx = deltaX * strength;
      let targetTy = deltaY * strength;
      const dist = Math.hypot(targetTx, targetTy);
      if (dist > maxDisp) {
        targetTx = (targetTx / dist) * maxDisp;
        targetTy = (targetTy / dist) * maxDisp;
      }
      snapTx += (targetTx - snapTx) * 0.22;
      snapTy += (targetTy - snapTy) * 0.22;
      activeSnappedEl.style.transform = `translate3d(${snapTx}px, ${snapTy}px, 0)`;

      // Dynamically size the outer aura to snap & wrap around the button
      const padding = 12;
      aura.style.width = (rect.width + padding) + 'px';
      aura.style.height = (rect.height + padding) + 'px';
      aura.style.borderRadius = window.getComputedStyle(activeSnappedEl).borderRadius || '8px';
    } else {
      // Normal states sizing
      if (document.body.classList.contains('cursor-hover')) {
        aura.style.width = '48px';
        aura.style.height = '48px';
        aura.style.borderRadius = '50%';
      } else if (document.body.classList.contains('cursor-view-project')) {
        aura.style.width = '80px';
        aura.style.height = '80px';
        aura.style.borderRadius = '50%';
      } else if (document.body.classList.contains('cursor-input')) {
        aura.style.width = '16px';
        aura.style.height = '16px';
        aura.style.borderRadius = '50%';
      } else {
        aura.style.width = '32px';
        aura.style.height = '32px';
        aura.style.borderRadius = '50%';
      }
    }

    ringX += (targetX - ringX) * 0.15;
    ringY += (targetY - ringY) * 0.15;

    // Directly position the aura element
    aura.style.left = ringX + 'px';
    aura.style.top = ringY + 'px';

    requestAnimationFrame(renderCursor);
  }
  
  requestAnimationFrame(renderCursor);

  const updateInteractiveListeners = () => {
    const snaps = 'a.btn, button.btn, .nav-link, .intro-skip, .theme-toggle, .budget-card, .social-icon, .btn-primary, .btn-ghost, .bento-filter-pill, .bento-archives-link';
    document.querySelectorAll(snaps).forEach(el => {
      if (el.dataset.cursorSnappedBound) return;
      el.dataset.cursorSnappedBound = 'true';

      el.addEventListener('mouseenter', () => {
        activeSnappedEl = el;
        el.style.transition = 'none';
        document.body.classList.add('cursor-snapped');
      });
      el.addEventListener('mouseleave', () => {
        const prevEl = el;
        prevEl.style.transition = '';
        prevEl.classList.add('magnetic-releasing');
        prevEl.style.transform = '';
        setTimeout(() => {
          prevEl.classList.remove('magnetic-releasing');
        }, 600);

        activeSnappedEl = null;
        snapTx = 0;
        snapTy = 0;
        document.body.classList.remove('cursor-snapped');
      });
    });

    const projectCards = '.browser-mockup, .portfolio-card, .case.large, .case';
    document.querySelectorAll(projectCards).forEach(el => {
      if (el.dataset.cursorProjectBound) return;
      el.dataset.cursorProjectBound = 'true';

      el.addEventListener('mouseenter', () => {
        document.body.classList.add('cursor-view-project');
      });
      el.addEventListener('mouseleave', () => {
        document.body.classList.remove('cursor-view-project');
      });
    });

    const smallHover = 'a:not(.btn):not(.nav-link), button:not(.btn), .faq-item, .testimonial-card, .step, .accordion-header';
    document.querySelectorAll(smallHover).forEach(el => {
      if (el.dataset.cursorSmallBound) return;
      el.dataset.cursorSmallBound = 'true';

      if (!el.matches(snaps) && !el.matches(projectCards)) {
        el.addEventListener('mouseenter', () => document.body.classList.add('cursor-hover'));
        el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-hover'));
      }
    });

    document.querySelectorAll('input[type="text"], input[type="email"], textarea').forEach(el => {
      if (el.dataset.cursorInputBound) return;
      el.dataset.cursorInputBound = 'true';

      el.addEventListener('mouseenter', () => document.body.classList.add('cursor-input'));
      el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-input'));
    });
  };

  updateInteractiveListeners();

  const observer = new MutationObserver(updateInteractiveListeners);
  const container = document.querySelector('main') || document.body;
  observer.observe(container, { childList: true, subtree: true });
}

/* =================================================================
   6. 3D HERO TILT CARD
   ================================================================= */
const tiltWrap=document.getElementById('tilt-wrapper');
const tiltEl=document.querySelector('.tilt-target');
if(tiltWrap&&tiltEl){
  tiltWrap.addEventListener('mousemove',e=>{
    const r=tiltWrap.getBoundingClientRect();
    const x=((e.clientX-r.left)/r.width-0.5)*2;
    const y=((e.clientY-r.top)/r.height-0.5)*2;
    tiltEl.style.transform=`perspective(1000px) rotateY(${x*8}deg) rotateX(${-y*6}deg) scale3d(1.02,1.02,1.02)`;
    tiltEl.style.transition='transform 0.1s ease';
  });
  tiltWrap.addEventListener('mouseleave',()=>{
    tiltEl.style.transform='perspective(1000px) rotateY(0deg) rotateX(0deg) scale3d(1,1,1)';
    tiltEl.style.transition='transform 0.6s cubic-bezier(0.16,1,0.3,1)';
  });
}

/* =================================================================
   7. TYPEWRITER TICKER
   ================================================================= */
const tickerEl=document.getElementById('ticker-text');
const tickerData=window._expertiseTickers || [];
if(tickerEl&&tickerData.length){
  let ti=0,ci=0,deleting=false,pauseTimer=null;
  function typeTick(){
    const word=tickerData[ti]||'';
    if(!deleting){
      tickerEl.textContent=word.slice(0,ci+1);
      ci++;
      if(ci===word.length){
        deleting=true;
        pauseTimer=setTimeout(typeLoop,1800);
        return;
      }
    }else{
      tickerEl.textContent=word.slice(0,ci-1);
      ci--;
      if(ci===0){
        deleting=false;
        ti=(ti+1)%tickerData.length;
      }
    }
    typeLoop();
  }
  function typeLoop(){
    clearTimeout(pauseTimer);
    const speed=deleting?40:72;
    pauseTimer=setTimeout(typeTick,speed);
  }
  setTimeout(typeLoop,1200);
}

/* =================================================================
   8. PAGE TRANSITIONS (PREMIUM ENGINE)
   ================================================================= */
const ptOverlay=document.getElementById('pageTransition');
if(ptOverlay){
  function revealPage() {
    if(!ptOverlay.classList.contains('leaving')) {
      ptOverlay.classList.add('leaving');
      document.body.classList.add('pm-page-ready');
    }
  }
  
  if (document.readyState === 'interactive' || document.readyState === 'complete') {
    revealPage();
  } else {
    document.addEventListener('DOMContentLoaded', revealPage);
    window.addEventListener('load', revealPage);
  }
  
  setTimeout(revealPage, 1200);

  document.querySelectorAll('a[href]').forEach(a=>{
    const href=a.getAttribute('href')||'';
    if(href.startsWith('#')||href.startsWith('javascript:')||a.getAttribute('target')==='_blank'||a.getAttribute('data-no-transition')) return;
    
    a.addEventListener('click',e=>{
      try {
        const destUrl=new URL(a.href, window.location.href);
        const curUrl=new URL(window.location.href);
        
        if(destUrl.origin===curUrl.origin && destUrl.pathname===curUrl.pathname && destUrl.search===curUrl.search) {
          if (destUrl.hash) {
            return;
          }
        }
        
        e.preventDefault();
        ptOverlay.classList.remove('leaving');
        ptOverlay.classList.add('entering');
        
        const mainEl = document.querySelector('main');
        if(mainEl) {
          mainEl.style.transition = 'all 0.5s cubic-bezier(0.76, 0, 0.24, 1)';
          mainEl.style.transform = 'translateY(-12px) scale(0.98)';
          mainEl.style.opacity = '0';
        }
        
        setTimeout(()=>{
          window.location.href=a.href;
        }, 500);
      } catch(err) {}
    });
  });
}

/* =================================================================
   9. SKILL CONSTELLATION CANVAS
   ================================================================= */
const canvas=document.getElementById('skill-canvas');
const skillData=window._skillData||null;
if(canvas&&skillData&&skillData.length){
  const tooltip=document.getElementById('skillTooltip');
  const ttName=document.getElementById('ttName');
  const ttCat=document.getElementById('ttCat');
  const ttFill=document.getElementById('ttFill');
  const ttLevel=document.getElementById('ttLevel');
  const ttYears=document.getElementById('ttYears');
  const ctx=canvas.getContext('2d');

  function hexToRgba(hex, alpha) {
    let c = hex.substring(1);
    if (c.length === 3) c = c[0]+c[0]+c[1]+c[1]+c[2]+c[2];
    const r = parseInt(c.substring(0, 2), 16);
    const g = parseInt(c.substring(2, 4), 16);
    const b = parseInt(c.substring(4, 6), 16);
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
  }

  let W,H,nodes=[],cosmicDust=[];

  function resize(){
    const rect=canvas.getBoundingClientRect();
    W=canvas.width=rect.width*window.devicePixelRatio;
    H=canvas.height=500*window.devicePixelRatio;
    canvas.style.height='500px';
    ctx.scale(window.devicePixelRatio,window.devicePixelRatio);
    W=W/window.devicePixelRatio;
    H=H/window.devicePixelRatio;
    placeNodes();
  }

  function placeNodes(){
    // Cosmic Dust Background particles
    cosmicDust = [];
    for (let i = 0; i < 75; i++) {
      cosmicDust.push({
        x: Math.random() * W,
        y: Math.random() * H,
        size: Math.random() * 1.5 + 0.3,
        alpha: Math.random() * 0.45 + 0.08,
        angle: Math.random() * Math.PI * 2,
        speed: Math.random() * 0.08 + 0.03
      });
    }

    nodes=skillData.map((s,i)=>{
      const cols=Math.ceil(Math.sqrt(skillData.length*1.4));
      const col=i%cols;
      const row=Math.floor(i/cols);
      const totalCols=cols;
      const totalRows=Math.ceil(skillData.length/cols);
      const cellW=W/totalCols;
      const cellH=(H-60)/totalRows;
      const jx=(Math.random()-0.5)*cellW*0.5;
      const jy=(Math.random()-0.5)*cellH*0.5;
      return {
        x:cellW*(col+0.5)+jx,
        y:60+cellH*(row+0.5)+jy,
        vx:(Math.random()-0.5)*0.15,
        vy:(Math.random()-0.5)*0.15,
        r:6+s.level/100*10,
        ...s,
        hovered:false,
        alpha:0.7+Math.random()*0.3,
        alphaPhase:Math.random()*Math.PI*2,
      };
    });
  }

  let hoveredNode=null,mouseX=-999,mouseY=-999,animFrame;
  let tooltipX=-999,tooltipY=-999,targetTooltipX=-999,targetTooltipY=-999;

  function draw(){
    ctx.clearRect(0,0,W,H);
    const dark=true;
    const t=Date.now()/1000;

    if (tooltip && hoveredNode && targetTooltipX !== -999 && targetTooltipY !== -999) {
      if (tooltipX === -999) {
        tooltipX = targetTooltipX;
        tooltipY = targetTooltipY;
      }
      tooltipX += (targetTooltipX - tooltipX) * 0.16;
      tooltipY += (targetTooltipY - tooltipY) * 0.16;
      tooltip.style.left = tooltipX + 'px';
      tooltip.style.top = tooltipY + 'px';
    } else {
      tooltipX = -999;
      tooltipY = -999;
    }

    // 1. Draw Cosmic Dust Particles in Canvas Background
    cosmicDust.forEach(p => {
      p.angle += 0.003 * p.speed;
      p.x += Math.cos(p.angle) * p.speed;
      p.y += Math.sin(p.angle) * p.speed;
      if (p.x < 0) p.x = W;
      if (p.x > W) p.x = 0;
      if (p.y < 0) p.y = H;
      if (p.y > H) p.y = 0;
      
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(255, 255, 255, ${p.alpha})`;
      ctx.fill();
    });

    // 2. Draw connections (Category clusters get highways + energy sparks)
    for(let i=0;i<nodes.length;i++){
      for(let j=i+1;j<nodes.length;j++){
        const ni=nodes[i],nj=nodes[j];
        const dist=Math.hypot(ni.x-nj.x,ni.y-nj.y);
        
        if (ni.category === nj.category) {
          // Category cluster highways - longer reach, color-coded, brighter
          if (dist < 230) {
            const opacity = (1 - dist / 230) * (ni.hovered || nj.hovered ? 0.65 : 0.22);
            ctx.beginPath();
            ctx.strokeStyle = hexToRgba(ni.color, opacity);
            ctx.lineWidth = ni.hovered || nj.hovered ? 1.6 : 0.95;
            ctx.moveTo(ni.x, ni.y);
            ctx.lineTo(nj.x, nj.y);
            ctx.stroke();

            // Pulsating energy spark flowing on the cluster wire
            const sparkPos = (t * 0.35 + (i * 7.1 + j * 3.9)) % 1.0;
            const sparkX = ni.x + (nj.x - ni.x) * sparkPos;
            const sparkY = ni.y + (nj.y - ni.y) * sparkPos;
            
            ctx.beginPath();
            ctx.arc(sparkX, sparkY, 1.8 + Math.sin(t * 8) * 0.5, 0, Math.PI * 2);
            ctx.fillStyle = ni.color;
            ctx.shadowBlur = 6;
            ctx.shadowColor = ni.color;
            ctx.fill();
            ctx.shadowBlur = 0;
          }
        } else {
          // Cross-category background faint web lines - short reach
          if (dist < 130) {
            const opacity = (1 - dist / 130) * (ni.hovered || nj.hovered ? 0.3 : 0.05);
            ctx.beginPath();
            ctx.strokeStyle = `rgba(255, 106, 26, ${opacity})`;
            ctx.lineWidth = 0.55;
            ctx.moveTo(ni.x, ni.y);
            ctx.lineTo(nj.x, nj.y);
            ctx.stroke();
          }
        }
      }
    }

    // 3. Move nodes
    nodes.forEach(n=>{
      if (mouseX !== -999 && mouseY !== -999) {
        const dx = mouseX - n.x;
        const dy = mouseY - n.y;
        const dist = Math.hypot(dx, dy);
        
        if (dist < 150) {
          const force = (150 - dist) / 150;
          n.x += (dx / dist) * force * 0.72;
          n.y += (dy / dist) * force * 0.72;
        }
      }

      n.x+=n.vx;n.y+=n.vy;
      if(n.x<n.r||n.x>W-n.r)n.vx*=-1;
      if(n.y<n.r||n.y>H-n.r)n.vy*=-1;
    });

    // 4. Render nodes
    nodes.forEach(n=>{
      const pulse=n.hovered?1.3:1+Math.sin(t*1.5+n.alphaPhase)*0.06;
      const r=n.r*pulse;

      const rippleTime = t + n.alphaPhase * 2;
      const rippleRadius = r + (rippleTime * 12) % 24;
      const rippleAlpha = Math.max(0, 1 - ((rippleTime * 12) % 24) / 24) * (n.hovered ? 0.65 : 0.2);
      
      ctx.beginPath();
      ctx.arc(n.x, n.y, rippleRadius, 0, Math.PI * 2);
      ctx.strokeStyle = hexToRgba(n.color, rippleAlpha);
      ctx.lineWidth = 1.2;
      ctx.stroke();

      if(n.hovered){
        const g=ctx.createRadialGradient(n.x,n.y,0,n.x,n.y,r*3);
        g.addColorStop(0,n.color+'55');
        g.addColorStop(1,'transparent');
        ctx.beginPath();
        ctx.arc(n.x,n.y,r*3,0,Math.PI*2);
        ctx.fillStyle=g;
        ctx.fill();
      }
      
      ctx.beginPath();
      ctx.arc(n.x,n.y,r,0,Math.PI*2);
      ctx.fillStyle=n.hovered?n.color:n.color+(Math.round((n.hovered?1:0.75)*255).toString(16).padStart(2,'0'));
      ctx.fill();
      ctx.strokeStyle='rgba(255,255,255,0.2)';
      ctx.lineWidth=1;
      ctx.stroke();

      const labelAlpha=n.hovered?1:0.55;
      ctx.fillStyle=`rgba(255,255,255,${labelAlpha})`;
      ctx.font=`${n.hovered?'700':'500'} ${n.hovered?13:11}px Inter,system-ui`;
      ctx.textAlign='center';
      ctx.fillText(n.name,n.x,n.y+r+16);
    });

    animFrame=requestAnimationFrame(draw);
  }

  canvas.addEventListener('mousemove',e=>{
    const rect=canvas.getBoundingClientRect();
    mouseX=e.clientX-rect.left;
    mouseY=e.clientY-rect.top;
    hoveredNode=null;
    nodes.forEach(n=>{
      const dist=Math.hypot(n.x-mouseX,n.y-mouseY);
      n.hovered=dist<n.r*2+10;
      if(n.hovered)hoveredNode=n;
    });
    if(hoveredNode&&tooltip){
      tooltip.classList.add('visible');
      ttName.textContent=hoveredNode.name;
      ttCat.textContent=hoveredNode.category;
      ttFill.style.width=hoveredNode.level+'%';
      ttFill.style.background=hoveredNode.color;
      
      // Cyberpunk HUD format strings
      ttLevel.textContent=hoveredNode.level+'%';
      ttYears.textContent=hoveredNode.years+' Yr'+(hoveredNode.years!==1?'s':'');

      // Update node custom color variables
      tooltip.style.setProperty('--node-color', hoveredNode.color);
      
      targetTooltipX=Math.min(mouseX+20,W-240);
      targetTooltipY=Math.min(mouseY-80,H-140);
    }else if(tooltip){
      tooltip.classList.remove('visible');
      targetTooltipX=-999;
      targetTooltipY=-999;
    }
  });
  canvas.addEventListener('mouseleave',()=>{
    nodes.forEach(n=>n.hovered=false);
    hoveredNode=null;
    if(tooltip)tooltip.classList.remove('visible');
    targetTooltipX=-999;
    targetTooltipY=-999;
    mouseX=-999;
    mouseY=-999;
  });

  const ro=new ResizeObserver(resize);
  ro.observe(canvas.parentElement);
  resize();
  draw();

  const wrapper = canvas.parentElement;
  if(wrapper) {
    wrapper.style.perspective = '1000px';
    canvas.style.transformStyle = 'preserve-3d';
    canvas.style.transition = 'transform 0.1s ease-out';
    wrapper.addEventListener('mousemove', e => {
      const rect = wrapper.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
      const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
      canvas.style.transform = `rotateY(${x * 10}deg) rotateX(${-y * 8}deg) scale3d(1.02, 1.02, 1.02)`;
    });
    wrapper.addEventListener('mouseleave', () => {
      canvas.style.transform = 'rotateY(0deg) rotateX(0deg) scale3d(1, 1, 1)';
      canvas.style.transition = 'transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
    });
    wrapper.addEventListener('mouseenter', () => {
      canvas.style.transition = 'transform 0.1s ease-out';
    });
  }
}

/* =================================================================
   10. CINEMATIC INTRO SEQUENCE + EMBER PARTICLES
   ================================================================= */
const introOverlay=document.getElementById('intro-overlay');
const introProgress=document.getElementById('introProgress');
let emberAnimFrame = null;

function dismissIntro(){
  if(!introOverlay)return;
  sessionStorage.setItem('pm_intro_seen','1');
  
  // Dispatch custom event to trigger 3D Big Bang explosion!
  window.dispatchEvent(new CustomEvent('intro-dismissed'));
  
  if (window.FluxoraAudio && typeof window.FluxoraAudio.playCurtainBassDrop === 'function') {
    window.FluxoraAudio.playCurtainBassDrop();
  }

  introOverlay.classList.add('intro-exit');
  document.body.style.overflow='';
  if(emberAnimFrame) cancelAnimationFrame(emberAnimFrame);
  setTimeout(()=>{
    introOverlay.style.display='none';
  },1250);
}
window.dismissIntro=dismissIntro;

function initEmberParticles(){
  const canvas=document.getElementById('ember-particles');
  if(!canvas)return;
  const ctx=canvas.getContext('2d');
  let W,H;
  function resize(){
    W=canvas.width=window.innerWidth;
    H=canvas.height=window.innerHeight;
  }
  resize();
  window.addEventListener('resize',resize);

  const particles=[];
  const COUNT=12; // Extremely sparse and elegant
  for(let i=0;i<COUNT;i++){
    particles.push({
      x:Math.random()*W,
      y:Math.random()*H,
      vx:(Math.random()-0.5)*0.15, // Extremely slow drift
      vy:-(0.05+Math.random()*0.1),
      r:30+Math.random()*45, // Defocused giant bokeh circle size
      alpha:0.02+Math.random()*0.03, // Barely visible elegant luminosity
      hue:24+Math.random()*12, // Pristine warm amber/gold hues
      life:0.6+Math.random()*0.4,
      decay:0.0001+Math.random()*0.0002
    });
  }

  function drawEmbers(){
    ctx.clearRect(0,0,W,H);
    const activeParticles = [];

    particles.forEach(p=>{
      p.x+=p.vx;
      p.y+=p.vy;
      p.life-=p.decay;

      // Keep drifting bokeh circular paths smooth
      p.vx+=(Math.random()-0.5)*0.008;

      if (p.life > 0) {
        activeParticles.push(p);

        const a=p.alpha*p.life;
        // Render giant defocused bokeh gradient orb
        const grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r);
        grad.addColorStop(0, `hsla(${p.hue}, 90%, 55%, ${a})`);
        grad.addColorStop(0.4, `hsla(${p.hue}, 90%, 50%, ${a * 0.3})`);
        grad.addColorStop(1, 'rgba(0,0,0,0)');

        ctx.beginPath();
        ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
        ctx.fillStyle=grad;
        ctx.fill();
      } else {
        // Recycle slowly from the bottom with randomized radius
        p.x=Math.random()*W;
        p.y=H+p.r;
        p.life=0.7+Math.random()*0.3;
        p.vx=(Math.random()-0.5)*0.15;
        p.vy=-(0.05+Math.random()*0.1);
        p.r=30+Math.random()*45;
        activeParticles.push(p);
      }
    });

    particles.length = 0;
    particles.push(...activeParticles);

    emberAnimFrame=requestAnimationFrame(drawEmbers);
  }
  drawEmbers();
}

if(introOverlay){
  const seen=sessionStorage.getItem('pm_intro_seen');
  if(seen){
    introOverlay.style.display='none';
  }else{
    document.body.style.overflow='hidden';
    initEmberParticles();
    
    // The entire screen functions as a passive click-through interaction
    introOverlay.addEventListener('click', dismissIntro);

    const nameEl = document.getElementById('iLine1');

    async function showLine(el, delay) {
      return new Promise(res => {
        if (!el) return res();
        setTimeout(() => {
          el.classList.add('visible');
          res();
        }, delay);
      });
    }

    async function runIntro(){
      await new Promise(r=>setTimeout(r,300));
      // Fade in the 'halo' centerpiece
      await showLine(nameEl, 150);
    }
    runIntro();
  }
}

/* =================================================================
   11. INTERACTIVE BUDGET CALCULATOR
   ================================================================= */
const calcTypes = document.querySelectorAll('input[name="calc_type"]');
const calcFeatures = document.querySelectorAll('input[name="calc_features"]');
const calcTotal = document.getElementById('calc-total');
const calcApplyBtn = document.getElementById('calc-apply-btn');
const formBudgetSelect = document.getElementById('form-budget-select');
const formMessageArea = document.getElementById('form-message-area');

if (calcTypes.length && calcTotal) {
  const basePrices = {
    landing: 3000000,
    compro: 6000000,
    custom: 12000000
  };
  
  const featurePrices = {
    animation: 1500000,
    admin: 2500000,
    seo: 1000000,
    multilang: 2000000
  };

  function formatIDR(num) {
    return 'Rp ' + num.toLocaleString('id-ID');
  }

  function calculate() {
    let total = 0;
    let selectedType = 'landing';
    
    calcTypes.forEach(radio => {
      if (radio.checked) {
        selectedType = radio.value;
        total += basePrices[radio.value] || 0;
      }
      
      const card = radio.closest('.calc-option')?.querySelector('.calc-card');
      if (card) {
        if (radio.checked) {
          card.style.background = 'rgba(255, 106, 26, 0.08)';
          card.style.borderColor = 'var(--purple)';
          card.style.boxShadow = '0 0 15px rgba(255, 106, 26, 0.15)';
        } else {
          card.style.background = '';
          card.style.borderColor = '';
          card.style.boxShadow = '';
        }
      }
    });

    calcFeatures.forEach(cb => {
      if (cb.checked) {
        total += featurePrices[cb.value] || 0;
      }
    });

    calcTotal.textContent = formatIDR(total);
    return { total, selectedType };
  }

  calcTypes.forEach(r => r.addEventListener('change', calculate));
  calcFeatures.forEach(c => c.addEventListener('change', calculate));
  
  calculate();

  if (calcApplyBtn && formBudgetSelect && formMessageArea) {
    calcApplyBtn.addEventListener('click', () => {
      if (typeof window._trackGanyEvent === 'function') {
        window._trackGanyEvent('budget_calc');
      }
      
      const { total, selectedType } = calculate();
      
      if (total <= 5000000) {
        formBudgetSelect.value = 'Rp 2-5 juta';
      } else if (total <= 10000000) {
        formBudgetSelect.value = 'Rp 5-10 juta';
      } else {
        formBudgetSelect.value = 'Rp 10 juta+';
      }

      let typeLabel = selectedType === 'landing' ? 'Landing Page' : selectedType === 'compro' ? 'Company Profile' : 'Custom SaaS';
      let featuresList = [];
      calcFeatures.forEach(cb => {
        if (cb.checked) {
          const label = cb.closest('.calc-feature').querySelector('span').textContent;
          featuresList.push(label);
        }
      });

      let text = `Halo Gany, saya butuh website ${typeLabel}.\n`;
      if (featuresList.length > 0) {
        text += `Fitur tambahan: ${featuresList.join(', ')}.\n`;
      }
      text += `Estimasi kalkulator: ${formatIDR(total)}.\n\nBerikut detail tambahan project saya...`;
      
      formMessageArea.value = text;
      formMessageArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
      formMessageArea.focus();

      calcApplyBtn.textContent = 'Berhasil Diterapkan ✓';
      calcApplyBtn.style.color = '#10b981';
      setTimeout(() => {
        calcApplyBtn.textContent = 'Gunakan Estimasi ✓';
        calcApplyBtn.style.color = '';
      }, 2000);
    });
  }
}

/* =================================================================
   12. CAL.COM POPUP INTEGRATION
   ================================================================= */
const calBtn = document.getElementById('cal-booking-btn');
if (calBtn) {
  (function (C, A, L) { _c = C._c || {}; L = d => { d.forEach(c => C[A](c)) }; C[A] = C[A] || function () { (C[A].q = C[A].q || []).push(arguments) }; L(["init", "modal"]); })(window, "Cal", []);
  
  const s = document.createElement('script');
  s.src = "https://assets.cal.com/embed/embed.js";
  s.async = true;
  document.body.appendChild(s);
  
  s.onload = () => {
    Cal("init", { origin: "https://cal.com" });
    calBtn.addEventListener('click', () => {
      if (typeof window._trackGanyEvent === 'function') {
        window._trackGanyEvent('cal_click');
      }
      
      Cal("modal", {
        link: "ganylabs/15min",
        config: {
          theme: "dark",
          styles: {
            branding: {
              brandColor: "#ff6a1a"
            }
          }
        }
      });
    });
  };
}

/* =================================================================
   13. SELF-HOSTED WEB ANALYTICS TRACKER
   ================================================================= */
function trackGanyEvent(eventType) {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    fetch('/api/analytics/track', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken || '',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        event_type: eventType,
        page_url: window.location.pathname
      })
    });
  } catch (e) {
    console.warn("Analytics event tracking failed silently", e);
  }
}
window._trackGanyEvent = trackGanyEvent;

document.addEventListener('click', e => {
  const liveSiteLink = e.target.closest('[data-analytics="live_site"]');
  if (liveSiteLink) {
    trackGanyEvent('live_site_click');
  }
});

/* =================================================================
   14. CINEMATIC AUDIO MICRO-INTERACTIONS (SFX)
   ================================================================= */
document.addEventListener('DOMContentLoaded', () => {
  const hoverSelector = 'a, button, .calc-card, .faq-item, .social-icon, .nav-link, .intro-skip, .theme-toggle, .budget-card, .soundwave-toggle, .portfolio-card, .browser-mockup';

  const updateSoundwaveUI = () => {
    const waves = document.querySelectorAll('.soundwave-toggle');
    const isMuted = window.FluxoraAudio ? window.FluxoraAudio.isMuted : true;
    waves.forEach(w => {
      if (isMuted) {
        w.classList.add('muted');
        w.setAttribute('title', 'Unmute SFX');
      } else {
        w.classList.remove('muted');
        w.setAttribute('title', 'Mute SFX');
      }
    });
  };

  setTimeout(updateSoundwaveUI, 200);

  document.body.addEventListener('click', e => {
    const toggle = e.target.closest('.soundwave-toggle');
    if (toggle && window.FluxoraAudio) {
      e.preventDefault();
      window.FluxoraAudio.toggleMute();
      updateSoundwaveUI();
    }
  });

  document.body.addEventListener('mouseenter', e => {
    const el = e.target;
    if (el && el.matches && el.matches(hoverSelector) && window.FluxoraAudio) {
      window.FluxoraAudio.playHoverShimmer();
    }
  }, true);

  document.body.addEventListener('click', e => {
    const el = e.target.closest(hoverSelector);
    if (el && !el.closest('.soundwave-toggle') && window.FluxoraAudio) {
      window.FluxoraAudio.playTactileClick();
    }
  }, true);
});

/* =================================================================
   15. FOUNDER 3D TILT ENGINE
   ================================================================= */
const founderTilt = document.getElementById('founder-tilt');
if(founderTilt) {
  founderTilt.addEventListener('mousemove', e => {
    const rect = founderTilt.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
    const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
    
    founderTilt.style.transform = `perspective(1200px) rotateY(${x * 6}deg) rotateX(${-y * 5}deg)`;
    founderTilt.style.transition = 'transform 0.1s ease';
    
    const lightX = 50 + x * 30;
    const lightY = 50 + y * 30;
    founderTilt.style.background = `radial-gradient(circle at ${lightX}% ${lightY}%, rgba(255,120,50,0.04), #050505 70%)`;
  });
  
  founderTilt.addEventListener('mouseleave', () => {
    founderTilt.style.transform = 'perspective(1200px) rotateY(0deg) rotateX(0deg)';
    founderTilt.style.transition = 'transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
    founderTilt.style.background = '#050505';
  });
}

/* =================================================================
   16. PARALLAX SCROLL ENGINE
   ================================================================= */
const parallaxEls = document.querySelectorAll('[data-parallax]');
if(parallaxEls.length > 0) {
  let scrollY = window.scrollY;
  let ticking = false;
  
  function updateParallax() {
    parallaxEls.forEach(el => {
      const rect = el.getBoundingClientRect();
      const speed = parseFloat(el.dataset.parallaxSpeed) || 0.1;
      const center = rect.top + rect.height / 2;
      const viewCenter = window.innerHeight / 2;
      const offset = (center - viewCenter) * speed;
      
      if(rect.top < window.innerHeight && rect.bottom > 0) {
        el.style.transform = `translateY(${offset}px)`;
      }
    });
    ticking = false;
  }
  
  window.addEventListener('scroll', () => {
    scrollY = window.scrollY;
    if(!ticking) {
      requestAnimationFrame(updateParallax);
      ticking = true;
    }
  }, { passive: true });
  
  updateParallax();
}

/* =================================================================
   17. ANIMATED STAT COUNTER
   ================================================================= */
const statEls = document.querySelectorAll('.trust div strong');
if(statEls.length) {
  const countObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if(entry.isIntersecting && !entry.target.dataset.counted) {
        entry.target.dataset.counted = 'true';
        const text = entry.target.textContent;
        const match = text.match(/([\d.]+)/);
        if(match) {
          const target = parseFloat(match[1]);
          const prefix = text.slice(0, text.indexOf(match[1]));
          const suffix = text.slice(text.indexOf(match[1]) + match[1].length);
          const isFloat = match[1].includes('.');
          let current = 0;
          const duration = 1800;
          const start = performance.now();
          
          function animateCount(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            current = target * eased;
            entry.target.textContent = prefix + (isFloat ? current.toFixed(1) : Math.round(current)) + suffix;
            if(progress < 1) requestAnimationFrame(animateCount);
          }
          requestAnimationFrame(animateCount);
        }
      }
    });
  }, { threshold: 0.5 });
  
  statEls.forEach(el => countObserver.observe(el));
}

/* =================================================================
   18. HUD COMMAND CENTER INTERACTIVE LOGIC
   ================================================================= */
const ccContainer = document.getElementById('pm-command-center');
if (ccContainer) {
  const ccBackdrop = ccContainer.querySelector('.cc-backdrop');
  const ccCloseBtn = ccContainer.querySelector('#ccCloseBtn');
  const ccInput = ccContainer.querySelector('#ccInput');
  const ccCmdItems = ccContainer.querySelectorAll('.cc-cmd-item');
  let selectedIndex = -1;

  const openCC = () => {
    ccContainer.style.display = 'flex';
    requestAnimationFrame(() => {
      ccContainer.classList.add('open');
      ccInput.value = '';
      ccInput.focus();
      filterCommands('');
    });
    if (window.FluxoraAudio) window.FluxoraAudio.playTactileClick();
  };

  const closeCC = () => {
    ccContainer.classList.remove('open');
    if (window.FluxoraAudio) window.FluxoraAudio.playTactileClick();
    setTimeout(() => {
      ccContainer.style.display = 'none';
    }, 400);
  };

  window.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      if (ccContainer.classList.contains('open')) {
        closeCC();
      } else {
        openCC();
      }
    }
  });

  const ccTriggerBtn = document.getElementById('ccTriggerBtn');
  if (ccTriggerBtn) {
    ccTriggerBtn.addEventListener('click', e => {
      e.preventDefault();
      openCC();
    });
  }

  ccBackdrop.addEventListener('click', closeCC);
  ccCloseBtn.addEventListener('click', closeCC);

  ccContainer.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      closeCC();
    }
  });

  const filterCommands = (query) => {
    let visibleItems = [];
    ccCmdItems.forEach(item => {
      const name = item.dataset.cmd;
      const desc = item.querySelector('.cc-cmd-desc').textContent.toLowerCase();
      if (name.includes(query.toLowerCase()) || desc.includes(query.toLowerCase())) {
        item.style.display = 'flex';
        visibleItems.push(item);
      } else {
        item.style.display = 'none';
        item.classList.remove('active');
      }
    });
    selectedIndex = -1;
    return visibleItems;
  };

  ccInput.addEventListener('input', e => {
    filterCommands(e.target.value);
  });

  ccInput.addEventListener('keydown', e => {
    const visibleItems = Array.from(ccCmdItems).filter(item => item.style.display !== 'none');
    
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      if (visibleItems.length > 0) {
        if (selectedIndex >= 0) visibleItems[selectedIndex].classList.remove('active');
        selectedIndex = (selectedIndex + 1) % visibleItems.length;
        visibleItems[selectedIndex].classList.add('active');
        ccInput.value = visibleItems[selectedIndex].dataset.cmd;
        if (window.FluxoraAudio) window.FluxoraAudio.playHoverShimmer();
      }
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      if (visibleItems.length > 0) {
        if (selectedIndex >= 0) visibleItems[selectedIndex].classList.remove('active');
        selectedIndex = (selectedIndex - 1 + visibleItems.length) % visibleItems.length;
        visibleItems[selectedIndex].classList.add('active');
        ccInput.value = visibleItems[selectedIndex].dataset.cmd;
        if (window.FluxoraAudio) window.FluxoraAudio.playHoverShimmer();
      }
    } else if (e.key === 'Enter') {
      e.preventDefault();
      const val = ccInput.value.trim().toLowerCase();
      executeCommand(val);
    }
  });

  ccCmdItems.forEach((item) => {
    item.addEventListener('mouseenter', () => {
      ccCmdItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      if (window.FluxoraAudio) window.FluxoraAudio.playHoverShimmer();
    });
    item.addEventListener('click', () => {
      executeCommand(item.dataset.cmd);
    });
  });

  const executeCommand = (cmd) => {
    closeCC();
    
    setTimeout(() => {
      switch (cmd) {
        case '/home':
          window.scrollTo({ top: 0, behavior: 'smooth' });
          break;
        case '/about':
          window.location.href = '/about';
          break;
        case '/services':
          const serv = document.getElementById('services');
          if (serv) {
            serv.scrollIntoView({ behavior: 'smooth' });
          } else {
            window.location.href = '/#services';
          }
          break;
        case '/portfolio':
          window.location.href = '/portfolio';
          break;
        case '/contact':
          const cont = document.getElementById('contact');
          if (cont) {
            cont.scrollIntoView({ behavior: 'smooth' });
          } else {
            window.location.href = '/#contact';
          }
          break;
        case '/mute':
          if (window.FluxoraAudio && !window.FluxoraAudio.isMuted) {
            window.FluxoraAudio.toggleMute();
            const waves = document.querySelectorAll('.soundwave-toggle');
            waves.forEach(w => w.classList.add('muted'));
          }
          break;
        case '/unmute':
          if (window.FluxoraAudio && window.FluxoraAudio.isMuted) {
            window.FluxoraAudio.toggleMute();
            const waves = document.querySelectorAll('.soundwave-toggle');
            waves.forEach(w => w.classList.remove('muted'));
          }
          break;
        case '/glow':
          document.documentElement.classList.toggle('glow-overdrive');
          if (window.FluxoraAudio) window.FluxoraAudio.playTactileClick();
          break;
        case '/cyber':
          document.documentElement.classList.toggle('theme-cyber');
          if (document.documentElement.classList.contains('theme-cyber')) {
            localStorage.setItem('theme-cyber', 'enabled');
          } else {
            localStorage.removeItem('theme-cyber');
          }
          if (window.FluxoraAudio) window.FluxoraAudio.playTactileClick();
          break;
        case '/embers':
          triggerEmberStorm();
          break;
        default:
          break;
      }
    }, 420);
  };

  const triggerEmberStorm = () => {
    const canvas = document.getElementById('cursor-trail-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const w = canvas.width = window.innerWidth;
    const h = canvas.height = window.innerHeight;
    
    for(let i=0; i<120; i++) {
      const pX = Math.random() * w;
      const pY = h + Math.random() * 50;
      const vx = (Math.random() - 0.5) * 10;
      const vy = -Math.random() * 18 - 6;
      
      if (typeof window._pmCursorParticles !== 'undefined') {
        window._pmCursorParticles.push({
          x: pX,
          y: pY,
          vx: vx,
          vy: vy,
          size: Math.random() * 3.8 + 1.8,
          alpha: 1.0,
          decay: Math.random() * 0.02 + 0.01,
          hue: Math.floor(Math.random() * 25) + 15,
          update() {
            this.x += this.vx;
            this.y += this.vy;
            this.vy += 0.04;
            this.size *= 0.97;
            this.alpha -= this.decay;
          },
          draw() {
            ctx.save();
            ctx.globalAlpha = this.alpha;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = `hsl(${this.hue}, 100%, 55%)`;
            ctx.shadowBlur = this.size * 3;
            ctx.shadowColor = `hsl(${this.hue}, 100%, 50%)`;
            ctx.fill();
            ctx.restore();
          }
        });
      }
    }
  };
}

/* =================================================================
   19. KINETIC WARP TYPOGRAPHY JS & INERTIAL FLUID 3D CARDS
   ================================================================= */
if (window.matchMedia('(hover:hover)').matches) {
  const kineticTitle = document.querySelector('.kinetic-title');
  const laserBeam = document.getElementById('kinetic-laser');

  if (kineticTitle && laserBeam) {
    const words = kineticTitle.querySelectorAll('.kinetic-word');
    const chars = kineticTitle.querySelectorAll('.kinetic-char');

    words.forEach(word => {
      word.addEventListener('mouseenter', () => {
        const wordLeft = word.offsetLeft;
        const wordWidth = word.offsetWidth;
        laserBeam.style.left = wordLeft + 'px';
        laserBeam.style.width = wordWidth + 'px';
      });
    });

    kineticTitle.addEventListener('mouseleave', () => {
      laserBeam.style.width = '0px';
    });

    kineticTitle.addEventListener('mousemove', e => {
      const mouseX = e.clientX;
      const mouseY = e.clientY;

      chars.forEach(char => {
        const charRect = char.getBoundingClientRect();
        const charCenterX = charRect.left + charRect.width / 2;
        const charCenterY = charRect.top + charRect.height / 2;

        const deltaX = mouseX - charCenterX;
        const deltaY = mouseY - charCenterY;
        const distance = Math.hypot(deltaX, deltaY);

        const radius = 120;

        if (distance < radius) {
          const influence = (radius - distance) / radius;
          const skewX = (deltaX / radius) * -22 * influence;
          const skewY = (deltaY / radius) * 12 * influence;
          const translateY = -10 * influence;
          const scale = 1 + (0.15 * influence);

          char.style.transition = 'none';
          char.style.transform = `translate3d(0, ${translateY}px, 0) skewX(${skewX}deg) skewY(${skewY}deg) scale(${scale})`;
        } else {
          char.style.transition = '';
          char.style.transform = '';
        }
      });
    });

    kineticTitle.addEventListener('mouseleave', () => {
      chars.forEach(char => {
        char.style.transition = '';
        char.style.transform = '';
      });
    });
  }

  const interactableCards = document.querySelectorAll('.card, .case');

  interactableCards.forEach(card => {
    if (!card.querySelector('.card-specular')) {
      const glare = document.createElement('div');
      glare.className = 'card-specular';
      card.appendChild(glare);
    }

    card._tiltPhysics = {
      active: false,
      rx: 0,
      ry: 0,
      vx: 0,
      vy: 0,
      targetRx: 0,
      targetRy: 0,
      frameId: null,
      stiffness: 0.08,
      damping: 0.83
    };

    const physics = card._tiltPhysics;

    const updateCardPhysics = () => {
      if (physics.active) {
        physics.rx += (physics.targetRx - physics.rx) * 0.12;
        physics.ry += (physics.targetRy - physics.ry) * 0.12;
        
        card.style.transform = `perspective(1000px) rotateX(${physics.rx}deg) rotateY(${physics.ry}deg) scale3d(1.02, 1.02, 1.02)`;
        
        physics.frameId = requestAnimationFrame(updateCardPhysics);
      } else {
        const ax = -physics.stiffness * physics.rx;
        const ay = -physics.stiffness * physics.ry;

        physics.vx = (physics.vx + ax) * physics.damping;
        physics.vy = (physics.vy + ay) * physics.damping;

        physics.rx += physics.vx;
        physics.ry += physics.vy;

        card.style.transform = `perspective(1000px) rotateX(${physics.rx}deg) rotateY(${physics.ry}deg)`;

        if (Math.abs(physics.rx) < 0.005 && Math.abs(physics.vx) < 0.005 &&
            Math.abs(physics.ry) < 0.005 && Math.abs(physics.vy) < 0.005) {
          card.style.transform = '';
          card.classList.remove('is-tilting');
          physics.frameId = null;
        } else {
          physics.frameId = requestAnimationFrame(updateCardPhysics);
        }
      }
    };

    card.addEventListener('mouseenter', () => {
      physics.active = true;
      card.classList.add('is-tilting');
      if (!physics.frameId) {
        physics.frameId = requestAnimationFrame(updateCardPhysics);
      }
    });

    card.addEventListener('mousemove', e => {
      const rect = card.getBoundingClientRect();
      const width = rect.width;
      const height = rect.height;

      const x = ((e.clientX - rect.left) / width - 0.5) * 2;
      const y = ((e.clientY - rect.top) / height - 0.5) * 2;

      const pctX = ((e.clientX - rect.left) / width) * 100;
      const pctY = ((e.clientY - rect.top) / height) * 100;
      card.style.setProperty('--light-x', `${pctX}%`);
      card.style.setProperty('--light-y', `${pctY}%`);

      physics.targetRx = -y * 8;
      physics.targetRy = x * 8;
    });

    card.addEventListener('mouseleave', () => {
      physics.active = false;
      physics.targetRx = 0;
      physics.targetRy = 0;
      if (!physics.frameId) {
        physics.frameId = requestAnimationFrame(updateCardPhysics);
      }
    });
  });

  const mockups = document.querySelectorAll('.browser-mockup');
  mockups.forEach(mockup => {
    const img = mockup.querySelector('.browser-img');
    const content = mockup.querySelector('.browser-content');
    
    if (img && content) {
      const calculateScroll = () => {
        const scrollDistance = img.clientHeight - content.clientHeight;
        if (scrollDistance > 0) {
          mockup.style.setProperty('--scroll-y', `-${scrollDistance}px`);
          const duration = Math.min(Math.max(scrollDistance / 450, 2.5), 8);
          mockup.style.setProperty('--scroll-duration', `${duration}s`);
        } else {
          mockup.style.setProperty('--scroll-y', '0px');
          mockup.style.setProperty('--scroll-duration', '1s');
        }
      };

      mockup.addEventListener('mouseenter', calculateScroll);
      
      window.addEventListener('resize', () => {
        if (mockup.matches(':hover')) {
          calculateScroll();
        }
      }, { passive: true });
    }
  });
}

/* =================================================================
   20. THEME TOGGLE CONTROLLER
   ================================================================= */
const themeBtn = document.getElementById('themeToggle');
if(themeBtn){
  themeBtn.addEventListener('click',function(){
    const r=document.documentElement, d=r.getAttribute('data-theme')==='dark';
    if(d){
      r.removeAttribute('data-theme');
      localStorage.setItem('theme','light');
    } else {
      r.setAttribute('data-theme','dark');
      localStorage.setItem('theme','dark');
    }
  });
}

/* =================================================================
   21. DYNAMIC SYNTH SCROLL & MOUSE ACTIVITY SWELL SENSOR
   ================================================================= */
let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
let lastScrollTime = Date.now();
window.addEventListener('scroll', () => {
  const st = window.pageYOffset || document.documentElement.scrollTop;
  const now = Date.now();
  const dt = Math.max(1, now - lastScrollTime);
  const dy = Math.abs(st - lastScrollTop);
  const velocity = dy / dt; // pixels per millisecond
  
  if (velocity > 0.05 && window.FluxoraAudio) {
    window.FluxoraAudio.triggerActivitySwell(velocity * 0.16);
  }
  
  lastScrollTop = st;
  lastScrollTime = now;
}, { passive: true });

let lastMouseX = 0, lastMouseY = 0;
let lastMouseTime = Date.now();
window.addEventListener('mousemove', (e) => {
  const now = Date.now();
  const dt = Math.max(1, now - lastMouseTime);
  const dx = Math.abs(e.clientX - lastMouseX);
  const dy = Math.abs(e.clientY - lastMouseY);
  const velocity = Math.hypot(dx, dy) / dt; // pixels per millisecond
  
  if (velocity > 0.1 && window.FluxoraAudio) {
    window.FluxoraAudio.triggerActivitySwell(velocity * 0.045);
  }
  
  lastMouseX = e.clientX;
  lastMouseY = e.clientY;
  lastMouseTime = now;
}, { passive: true });

/* =================================================================
   23. CINEMATIC FLIP CARD EXPANSION TRANSITION
   ================================================================= */
document.addEventListener('DOMContentLoaded', () => {
  const bentoGrid = document.querySelector('.bento-grid-custom');
  if (!bentoGrid) return;

  bentoGrid.addEventListener('click', (e) => {
    const card = e.target.closest('.bento-card-custom');
    if (!card) return;

    const link = card.querySelector('.bento-overlay-details h3 a');
    if (!link) return;

    // Prevent default navigation
    e.preventDefault();
    const targetUrl = link.href;

    // Play curtain bass drop sound if available
    if (window.FluxoraAudio && typeof window.FluxoraAudio.playCurtainBassDrop === 'function') {
      window.FluxoraAudio.playCurtainBassDrop();
    } else if (window.FluxoraAudio) {
      window.FluxoraAudio.playTactileClick();
    }

    // Get original card rect
    const rect = card.getBoundingClientRect();

    // Create custom clone for FLIP transition
    const clone = card.cloneNode(true);
    clone.classList.add('expanding-clone');
    
    // Style clone to match exactly the original card's position and look
    clone.style.top = `${rect.top}px`;
    clone.style.left = `${rect.left}px`;
    clone.style.width = `${rect.width}px`;
    clone.style.height = `${rect.height}px`;
    clone.style.margin = '0';
    
    // Inject clone into body
    document.body.appendChild(clone);

    // Fade out / blur the rest of the page content
    const mainEl = document.querySelector('main');
    const navEl = document.querySelector('nav');
    if (mainEl) {
      mainEl.style.transition = 'opacity 0.6s ease, filter 0.6s ease';
      mainEl.style.opacity = '0.15';
      mainEl.style.filter = 'blur(12px)';
    }
    if (navEl) {
      navEl.style.transition = 'opacity 0.6s ease, filter 0.6s ease';
      navEl.style.opacity = '0.15';
      navEl.style.filter = 'blur(12px)';
    }

    // Trigger expansion in the next frame
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        clone.style.top = '0';
        clone.style.left = '0';
        clone.style.width = '100vw';
        clone.style.height = '100vh';
        clone.style.borderRadius = '0px';
      });
    });

    // Navigate to page
    setTimeout(() => {
      window.location.href = targetUrl;
    }, 650);
  });
});

/* =================================================================
   24. BROWSER BACK BUTTON (BFCACHE) RESTORE STATE REACTOR
   ================================================================= */
window.addEventListener('pageshow', (event) => {
  // Always clean up expanding transition clones when page is shown
  const clones = document.querySelectorAll('.expanding-clone');
  clones.forEach(c => c.remove());

  // Reset main content and navigation styles (blurs & opacities)
  const mainEl = document.querySelector('main');
  const navEl = document.querySelector('nav');
  if (mainEl) {
    mainEl.style.opacity = '';
    mainEl.style.filter = '';
    mainEl.style.transition = '';
    mainEl.style.transform = '';
  }
  if (navEl) {
    navEl.style.opacity = '';
    navEl.style.filter = '';
    navEl.style.transition = '';
    navEl.style.transform = '';
  }

  // Restore page transition overlay back to ready leaving state
  const ptOverlay = document.getElementById('pageTransition');
  if (ptOverlay) {
    ptOverlay.classList.remove('entering');
    ptOverlay.classList.add('leaving');
  }
  document.body.classList.add('pm-page-ready');
});

})();
