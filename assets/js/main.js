/**
 * HomeFix Quetta - Main Interactive & GSAP Animation Engine
 */

window.HF = window.HF || {};

// Unified Interaction Engine
Object.assign(window.HF, {
  toast: function(type, message, duration = 4000) {
    let container = document.getElementById('hf-toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'hf-toast-container';
      container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; max-height: 100vh; overflow: hidden;';
      document.body.appendChild(container);
    }
    
    // Ensure max 4 visible
    while (container.children.length >= 4) {
      container.removeChild(container.firstChild);
    }
    
    const toast = document.createElement('div');
    toast.className = `hf-toast toast-${type}`;
    toast.style.cssText = 'background: white; padding: 12px 16px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 12px; position: relative; overflow: hidden; border-left: 4px solid; transform: translateX(120%); opacity: 0;';
    
    let iconSvg = '';
    let borderColor = '#000';
    if (type === 'success') {
      iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
      borderColor = '#10b981';
    } else if (type === 'error') {
      iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
      borderColor = '#ef4444';
    } else if (type === 'info') {
      iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';
      borderColor = '#3b82f6';
    } else if (type === 'warning') {
      iconSvg = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
      borderColor = '#f59e0b';
    }
    
    toast.style.borderColor = borderColor;
    
    toast.innerHTML = `
      <div style="flex-shrink:0;">${iconSvg}</div>
      <div style="flex-grow:1; font-size: 14px; font-weight: 500; color: #1f2937;">${message}</div>
      <button class="hf-toast-close" style="background:transparent; border:none; cursor:pointer; padding:4px; margin:-4px; opacity:0.5;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
      <div class="hf-toast-progress" style="position:absolute; bottom:0; left:0; height:3px; background:${borderColor}; width:100%;"></div>
    `;
    
    container.appendChild(toast);
    
    // Animate in
    gsap.to(toast, { x: 0, opacity: 1, duration: 0.4, ease: 'back.out(1.2)' });
    
    const progressBar = toast.querySelector('.hf-toast-progress');
    gsap.to(progressBar, { width: '0%', duration: duration / 1000, ease: 'none' });
    
    let dismissed = false;
    const dismiss = () => {
      if (dismissed) return;
      dismissed = true;
      gsap.to(toast, {
        x: 100,
        opacity: 0,
        duration: 0.3,
        ease: 'power2.in',
        onComplete: () => {
          if (toast.parentNode) toast.parentNode.removeChild(toast);
        }
      });
    };
    
    toast.querySelector('.hf-toast-close').addEventListener('click', dismiss);
    setTimeout(dismiss, duration);
  },
  // Helper: resolve any input (jQuery, selector string, or DOM element) to a DOM element
  _resolve: function(el) {
    if (!el) return null;
    if (typeof el === 'string') return document.querySelector(el);
    if (el instanceof HTMLElement) return el;
    if (el.jquery && el.length) return el[0]; // jQuery object
    return el;
  },

  btnLoading: function(el, text) {
    text = text || 'Processing...';
    el = HF._resolve(el);
    if (!el) return;
    if (!el.hasAttribute('data-hf-original-html')) {
      el.setAttribute('data-hf-original-html', el.innerHTML);
    }
    el.innerHTML = '<svg class="animate-spin" style="margin-right:8px;width:18px;height:18px;display:inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>' + text + '</span>';
    el.classList.add('btn-loading');
    el.setAttribute('disabled', 'true');
  },
  
  btnSuccess: function(el, text) {
    text = text || 'Done!';
    el = HF._resolve(el);
    if (!el) return;
    el.innerHTML = '<svg style="margin-right:8px;width:18px;height:18px;display:inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> <span>' + text + '</span>';
    el.classList.add('btn-success');
    el.classList.remove('btn-loading', 'btn-error');
  },
  
  btnError: function(el, text) {
    text = text || 'Error';
    el = HF._resolve(el);
    if (!el) return;
    el.innerHTML = '<svg style="margin-right:8px;width:18px;height:18px;display:inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> <span>' + text + '</span>';
    el.classList.add('btn-error');
    el.classList.remove('btn-loading', 'btn-success');
  },
  
  btnReset: function(el) {
    el = HF._resolve(el);
    if (!el) return;
    if (el.hasAttribute('data-hf-original-html')) {
      el.innerHTML = el.getAttribute('data-hf-original-html');
    }
    el.classList.remove('btn-loading', 'btn-success', 'btn-error');
    el.removeAttribute('disabled');
    if (typeof lucide !== 'undefined') {
      lucide.createIcons({ root: el });
    }
  },
  
  showFieldError: function(input, message) {
    input = HF._resolve(input);
    if (!input) return;
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    // Find the form-group parent or direct parent
    var container = input.closest('.form-group') || input.parentNode;
    var errorEl = container.querySelector('.field-error');
    if (!errorEl) {
      errorEl = document.createElement('div');
      errorEl.className = 'field-error';
      errorEl.style.cssText = 'color:#ef4444;font-size:12px;margin-top:4px;opacity:0;transform:translateY(-6px);transition:all 0.25s ease';
      container.appendChild(errorEl);
    }
    errorEl.textContent = message;
    requestAnimationFrame(function() {
      errorEl.style.opacity = '1';
      errorEl.style.transform = 'translateY(0)';
    });
  },
  
  clearFieldError: function(input) {
    input = HF._resolve(input);
    if (!input) return;
    input.classList.remove('is-invalid');
    var container = input.closest('.form-group') || input.parentNode;
    var errorEl = container.querySelector('.field-error');
    if (errorEl) {
      errorEl.style.opacity = '0';
      errorEl.style.transform = 'translateY(-6px)';
      setTimeout(function() {
        if (errorEl.parentNode) errorEl.parentNode.removeChild(errorEl);
      }, 250);
    }
  },
  
  clearAllFieldErrors: function(form) {
    form = HF._resolve(form);
    if (!form) return;
    var inputs = form.querySelectorAll('.is-invalid');
    inputs.forEach(function(input) { HF.clearFieldError(input); });
  },
  
  validateEmail: function(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(email).toLowerCase());
  },
  
  validatePhone: function(phone) {
    return /^(0|\+92)\d{9,12}$/.test(String(phone).replace(/[\s\-]/g, ''));
  },
  
  getPasswordStrength: function(password) {
    var score = 0;
    if (password.length >= 6) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    score = Math.min(4, score);
    
    var labels = ['Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    var classes = ['strength-weak', 'strength-weak', 'strength-fair', 'strength-good', 'strength-strong'];
    
    return {
      score: score,
      label: labels[score],
      class: classes[score]
    };
  }
});

document.addEventListener('DOMContentLoaded', () => {
  // Initialize Lucide Icons
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }

  // Improved Navbar Scroll
  const navbar = document.querySelector('.glass-nav');
  if (navbar) {
    let lastScrollY = window.scrollY;
    let ticking = false;
    
    window.addEventListener('scroll', () => {
      lastScrollY = window.scrollY;
      if (!ticking) {
        window.requestAnimationFrame(() => {
          if (lastScrollY > 20) {
            navbar.classList.add('scrolled');
          } else {
            navbar.classList.remove('scrolled');
          }
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
    
    // Trigger once on load
    if (window.scrollY > 20) navbar.classList.add('scrolled');
  }

  // Improved Mobile Menu Drawer Toggle
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const closeMobileMenu = document.getElementById('closeMobileMenu');

  if (mobileMenuBtn && mobileMenu) {
    const drawerContent = mobileMenu.querySelector('.drawer-content');
    const itemsToStagger = mobileMenu.querySelectorAll('a, button');
    
    // Ensure overlay click closes menu
    mobileMenu.addEventListener('click', (e) => {
      if (e.target === mobileMenu) {
        closeMenu();
      }
    });
    
    const openMenu = () => {
      mobileMenu.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
      
      // Fade overlay
      gsap.fromTo(mobileMenu, { backgroundColor: 'rgba(0,0,0,0)' }, { backgroundColor: 'rgba(0,0,0,0.5)', duration: 0.3 });
      
      // Slide drawer
      gsap.fromTo(drawerContent, 
        { x: '100%' }, 
        { x: '0%', duration: 0.35, ease: 'power3.out' }
      );
      
      // Stagger items
      if (itemsToStagger.length) {
        gsap.fromTo(itemsToStagger, 
          { x: 20, opacity: 0 },
          { x: 0, opacity: 1, duration: 0.3, stagger: 0.05, delay: 0.1, ease: 'power2.out' }
        );
      }
    };
    
    const closeMenu = () => {
      document.body.classList.remove('overflow-hidden');
      
      gsap.to(mobileMenu, { backgroundColor: 'rgba(0,0,0,0)', duration: 0.3 });
      gsap.to(drawerContent, {
        x: '100%',
        duration: 0.25,
        ease: 'power3.in',
        onComplete: () => mobileMenu.classList.add('hidden')
      });
    };

    mobileMenuBtn.addEventListener('click', openMenu);
    if (closeMobileMenu) {
      closeMobileMenu.addEventListener('click', closeMenu);
    }

    // Smooth navigation for all mobile menu links
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        const href = this.getAttribute('href') || '';
        
        // Check if this is an anchor link to a section on current page (e.g. #how-it-works)
        if (href.includes('#')) {
          const hash = href.substring(href.indexOf('#'));
          const target = document.querySelector(hash);
          
          if (target && (window.location.pathname.endsWith('index.php') || window.location.pathname === '/' || window.location.pathname.endsWith('/'))) {
            e.preventDefault();
            closeMenu();
            setTimeout(() => {
              target.scrollIntoView({ behavior: 'smooth', block: 'start' });
              history.pushState(null, null, hash);
            }, 250);
            return;
          }
        }
        
        // For standard navigation links, close drawer before unloading
        closeMenu();
      });
    });
  }

  // Smooth scroll for all hash anchor links on page
  document.querySelectorAll('a[href^="#"], a[href*="index.php#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href') || '';
      if (!href.includes('#')) return;
      const hash = href.substring(href.indexOf('#'));
      if (!hash || hash === '#') return;
      const target = document.querySelector(hash);
      if (target && (window.location.pathname.endsWith('index.php') || window.location.pathname === '/' || window.location.pathname.endsWith('/'))) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        history.pushState(null, null, hash);
      }
    });
  });

  // Network Detection
  let wasOffline = !navigator.onLine;
  window.addEventListener('offline', () => {
    wasOffline = true;
    HF.toast('warning', "You're offline...");
  });
  window.addEventListener('online', () => {
    if (wasOffline) {
      HF.toast('success', "You're back online");
      wasOffline = false;
    }
  });

  // Form Input Focus Enhancement
  const formInputs = document.querySelectorAll('.form-input');
  formInputs.forEach(input => {
    input.addEventListener('focus', () => {
      if (input.parentNode) input.parentNode.classList.add('is-focused');
    });
    
    input.addEventListener('blur', () => {
      if (input.parentNode) input.parentNode.classList.remove('is-focused');
      if (input.value.trim() !== '') {
        input.classList.add('is-filled');
      } else {
        input.classList.remove('is-filled');
      }
    });
    
    input.addEventListener('input', () => {
      if (input.classList.contains('is-invalid')) {
        HF.clearFieldError(input);
      }
    });
    
    // Init state for pre-filled inputs
    if (input.value && input.value.trim() !== '') {
      input.classList.add('is-filled');
    }
  });

  // Image Lazy Reveal
  const revealImages = document.querySelectorAll('img.img-reveal');
  revealImages.forEach(img => {
    if (img.complete) {
      img.classList.add('is-loaded');
    } else {
      img.addEventListener('load', () => {
        img.classList.add('is-loaded');
      });
    }
  });

  // GSAP Animations
  if (typeof gsap !== 'undefined') {
    // Register ScrollTrigger if available
    if (typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
    }

    // Hero Section Animation
    const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    if (document.querySelector('.hero-badge')) {
      heroTl.fromTo('.hero-badge', 
        { y: -20, opacity: 0 }, 
        { y: 0, opacity: 1, duration: 0.6, delay: 0.1 }
      );
    }

    if (document.querySelector('.hero-title')) {
      heroTl.fromTo('.hero-title', 
        { y: 30, opacity: 0 }, 
        { y: 0, opacity: 1, duration: 0.8 }, 
        '-=0.4'
      );
    }

    if (document.querySelector('.hero-desc')) {
      heroTl.fromTo('.hero-desc', 
        { y: 20, opacity: 0 }, 
        { y: 0, opacity: 1, duration: 0.6 }, 
        '-=0.5'
      );
    }

    if (document.querySelector('.hero-cta')) {
      heroTl.fromTo('.hero-cta', 
        { y: 20, opacity: 0 }, 
        { y: 0, opacity: 1, duration: 0.6 }, 
        '-=0.4'
      );
    }

    if (document.querySelector('.hero-visual')) {
      heroTl.fromTo('.hero-visual', 
        { scale: 0.95, opacity: 0, y: 30 }, 
        { scale: 1, opacity: 1, y: 0, duration: 1 }, 
        '-=0.6'
      );
    }

    if (document.querySelector('.hero-float-card')) {
      heroTl.fromTo('.hero-float-card', 
        { scale: 0.8, opacity: 0 }, 
        { scale: 1, opacity: 1, duration: 0.6, stagger: 0.15 }, 
        '-=0.5'
      );
    }

    // ScrollTrigger: Category & Service Cards Entrance
    if (typeof ScrollTrigger !== 'undefined') {
      const scrollCards = document.querySelectorAll('.animate-on-scroll');
      scrollCards.forEach((card, idx) => {
        gsap.fromTo(card, 
          { y: 40, opacity: 0 },
          {
            y: 0,
            opacity: 1,
            duration: 0.6,
            ease: 'power2.out',
            scrollTrigger: {
              trigger: card,
              start: 'top 88%',
              toggleActions: 'play none none none'
            }
          }
        );
      });

      // Animated Stat Counters
      const counters = document.querySelectorAll('.counter-val');
      counters.forEach(counter => {
        const target = +counter.getAttribute('data-target') || 0;
        const suffix = counter.getAttribute('data-suffix') || '';

        ScrollTrigger.create({
          trigger: counter,
          start: 'top 85%',
          onEnter: () => {
            let obj = { val: 0 };
            gsap.to(obj, {
              val: target,
              duration: 2,
              ease: 'power2.out',
              onUpdate: () => {
                counter.innerText = Math.floor(obj.val).toLocaleString() + suffix;
              }
            });
          },
          once: true
        });
      });

      // How It Works Steps
      const steps = document.querySelectorAll('.process-step');
      if (steps.length > 0) {
        gsap.fromTo(steps,
          { y: 30, opacity: 0 },
          {
            y: 0,
            opacity: 1,
            duration: 0.6,
            stagger: 0.2,
            scrollTrigger: {
              trigger: '#how-it-works',
              start: 'top 80%'
            }
          }
        );
      }
    }
  }

  // Interactive Before & After Slider
  initBeforeAfterSliders();

  // Initialize Leaflet Map for Quetta coverage
  initQuettaMap();
});

/**
 * Before & After Image Comparison Component
 */
function initBeforeAfterSliders() {
  const containers = document.querySelectorAll('.img-compare-container');
  containers.forEach(container => {
    const slider = container.querySelector('.img-compare-slider');
    const beforeImg = container.querySelector('.img-compare-before');
    if (!slider || !beforeImg) return;

    let isDown = false;

    const move = (e) => {
      if (!isDown) return;
      let rect = container.getBoundingClientRect();
      let pageX = e.pageX || (e.touches && e.touches[0].pageX);
      let posX = pageX - rect.left;
      if (posX < 0) posX = 0;
      if (posX > rect.width) posX = rect.width;
      let percent = (posX / rect.width) * 100;
      slider.style.left = percent + '%';
      beforeImg.style.width = percent + '%';
    };

    slider.addEventListener('mousedown', () => isDown = true);
    slider.addEventListener('touchstart', () => isDown = true, { passive: true });
    window.addEventListener('mouseup', () => isDown = false);
    window.addEventListener('touchend', () => isDown = false);
    window.addEventListener('mousemove', move);
    window.addEventListener('touchmove', move, { passive: true });
  });
}

/**
 * Leaflet.js Quetta Service Coverage Map
 */
function initQuettaMap() {
  const mapElement = document.getElementById('quetta-coverage-map');
  if (!mapElement || typeof L === 'undefined') return;

  // Quetta Coordinates
  const quettaCoords = [30.1798, 66.9750];
  const map = L.map('quetta-coverage-map', {
    scrollWheelZoom: false
  }).setView(quettaCoords, 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap contributors | HomeFix Quetta'
  }).addTo(map);

  // Quetta Key Hubs
  const serviceHubs = [
    { name: 'HomeFix HQ (Satellite Town)', coords: [30.1850, 66.9950], desc: 'Central Dispatch & Support Hub', badge: 'Headquarters' },
    { name: 'Jinnah Town & Samungli', coords: [30.1980, 66.9600], desc: 'Fast Response: < 30 mins', badge: 'Active Hub' },
    { name: 'Quetta Cantonment (Cantt)', coords: [30.2100, 67.0100], desc: 'Plumbing & Pipe Specialist Unit', badge: 'Active Hub' },
    { name: 'Satellite Town & Double Rd', coords: [30.1600, 66.9800], desc: 'Electrical & Solar Teams', badge: 'Active Hub' },
    { name: 'Model Town & Airport Rd', coords: [30.2200, 66.9500], desc: 'Handyman & Mounting Unit', badge: 'Active Hub' },
    { name: 'Brewery Road & Spiny Rd', coords: [30.1700, 66.9500], desc: 'Painting & Maintenance Hub', badge: 'Active Hub' }
  ];

  // Custom Icon
  const customIcon = L.divIcon({
    className: 'custom-map-pin',
    html: `<div style="background-color: #0D9488; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(13,148,136,0.4); border: 2px solid white;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg></div>`,
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
  });

  serviceHubs.forEach(hub => {
    const marker = L.marker(hub.coords, { icon: customIcon }).addTo(map);
    marker.bindPopup(`
      <div style="min-width: 180px; padding: 4px;">
        <span style="font-size: 10px; font-weight: 700; background: #CCFBF1; color: #0F766E; padding: 2px 8px; border-radius: 9999px; text-transform: uppercase;">${hub.badge}</span>
        <h4 style="font-weight: 700; color: #0F172A; margin: 6px 0 2px 0; font-size: 14px;">${hub.name}</h4>
        <p style="font-size: 12px; color: #64748B; margin: 0;">${hub.desc}</p>
        <a href="booking.php?area=${encodeURIComponent(hub.name.split('(')[0].trim())}" style="display: inline-block; margin-top: 8px; font-size: 11px; font-weight: 600; color: #0D9488; text-decoration: none;">Book Service Here →</a>
      </div>
    `);
  });
}
