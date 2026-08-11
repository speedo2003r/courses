/* ============================================================
   APP.JS — Main application entry point
   Handles: Accordions, Drawer, Tabs, Reveal, Toast, Language
   ============================================================ */

(function() {
  'use strict';

  // ---- Scroll Reveal ----
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); } });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
  document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

  // ---- Accordion ----
  document.querySelectorAll('.accordion-trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.accordion-item');
      const isActive = item.classList.contains('active');
      // Close siblings in same group
      const group = item.closest('.accordion-group');
      if (group) {
        group.querySelectorAll('.accordion-item.active').forEach(sibling => {
          if (sibling !== item) sibling.classList.remove('active');
        });
      }
      item.classList.toggle('active', !isActive);
      trigger.setAttribute('aria-expanded', String(!isActive));
    });
    // Init ARIA
    const item = trigger.closest('.accordion-item');
    trigger.setAttribute('aria-expanded', item.classList.contains('active') ? 'true' : 'false');
  });

  // ---- Drawer ----
  function openDrawer(id) {
    const backdrop = document.getElementById(id || 'checkout-drawer');
    if (backdrop) { backdrop.classList.add('open'); document.body.style.overflow = 'hidden'; }
  }
  function closeAllDrawers() {
    document.querySelectorAll('.drawer-backdrop.open').forEach(b => b.classList.remove('open'));
    document.body.style.overflow = '';
  }
  document.querySelectorAll('[data-drawer-open]').forEach(btn => {
    btn.addEventListener('click', () => openDrawer(btn.dataset.drawerOpen));
  });
  document.querySelectorAll('.drawer-close, .drawer-backdrop').forEach(el => {
    el.addEventListener('click', (e) => {
      if (e.target === el) closeAllDrawers();
    });
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAllDrawers(); });
  // Legacy trigger attr
  document.querySelectorAll('[data-trigger]').forEach(btn => {
    btn.addEventListener('click', (e) => { e.preventDefault(); openDrawer(btn.dataset.trigger); });
  });

  // ---- Tabs ----
  document.querySelectorAll('.tab-trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
      const group = trigger.closest('.tabs');
      const panel  = trigger.dataset.panel;
      if (!group || !panel) return;
      group.querySelectorAll('.tab-trigger').forEach(t => t.classList.remove('active'));
      trigger.classList.add('active');
      const container = group.closest('[data-tabs-container]') || document;
      container.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      const target = container.querySelector(`#${panel}`);
      if (target) target.classList.add('active');
    });
  });

  // ---- Toast ----
  window.showToast = function(msg, duration = 3000) {
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), duration);
  };

  // ---- Counter Odometer Animation ----
  function animateCounter(el, target, duration = 1600) {
    const start = performance.now();
    const update = (now) => {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.floor(eased * target).toLocaleString();
      if (progress < 1) requestAnimationFrame(update);
    };
    requestAnimationFrame(update);
  }
  const statObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const target = parseInt(e.target.dataset.target || '0', 10);
        if (!isNaN(target)) animateCounter(e.target, target);
        statObserver.unobserve(e.target);
      }
    });
  }, { threshold: 0.5 });
  document.querySelectorAll('[data-target]').forEach(el => statObserver.observe(el));

  // ---- Active Nav Link ----
  const currentPath = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-link').forEach(link => {
    if (link.getAttribute('href') === currentPath) link.classList.add('active');
  });

  // ---- Mobile Hamburger ----
  const hamburger = document.querySelector('.nav-hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('open');
      hamburger.setAttribute('aria-expanded', String(open));
    });
  }

})();
