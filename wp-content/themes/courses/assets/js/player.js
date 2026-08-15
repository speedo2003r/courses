/* ============================================================
   PLAYER.JS — Custom HD Video Player + PIP Float
   ============================================================ */
(function() {
  'use strict';

  // ---- Custom Video Play Overlays ----
  document.querySelectorAll('.video-container').forEach(container => {
    const overlay = container.querySelector('.video-play-overlay');
    const video   = container.querySelector('video');
    const controls = container.querySelector('.video-controls');

    if (!overlay || !video) return;

    overlay.addEventListener('click', () => {
      overlay.style.display = 'none';
      video.play();
      if (controls) controls.style.display = 'flex';
    });

    video.addEventListener('pause', () => { overlay.style.display = 'flex'; });
    video.addEventListener('play',  () => { overlay.style.display = 'none'; });
  });

  // ---- PIP Float on Scroll ----
  const pipTrigger = document.querySelector('[data-pip-trigger]');
  const pipWindow  = document.getElementById('video-pip');

  if (pipTrigger && pipWindow) {
    const sentinel = pipTrigger;
    const pipObserver = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        pipWindow.classList.toggle('active', !e.isIntersecting);
      });
    }, { threshold: 0.1 });
    pipObserver.observe(sentinel);
  }

  // ---- Mark as Complete button confetti burst ----
  const markCompleteBtn = document.getElementById('mark-complete-btn');
  if (markCompleteBtn) {
    markCompleteBtn.addEventListener('click', () => {
      markCompleteBtn.textContent = '✓ Completed!';
      markCompleteBtn.classList.add('btn-secondary');
      markCompleteBtn.disabled = true;
      // Simple confetti burst (CSS-only dots)
      for (let i = 0; i < 20; i++) {
        const dot = document.createElement('div');
        dot.style.cssText = `position:fixed;width:8px;height:8px;border-radius:50%;background:hsl(${Math.random()*360},80%,60%);top:40%;left:${20+Math.random()*60}%;pointer-events:none;z-index:9999;animation:confettiFall 1s ease-out forwards;`;
        document.body.appendChild(dot);
        setTimeout(() => dot.remove(), 1000);
      }
    });
  }

  // Add confetti keyframe once
  if (!document.getElementById('confetti-style')) {
    const s = document.createElement('style');
    s.id = 'confetti-style';
    s.textContent = '@keyframes confettiFall{0%{opacity:1;transform:translateY(0) rotate(0)}100%{opacity:0;transform:translateY(120px) rotate(720deg)}}';
    document.head.appendChild(s);
  }

  // ---- Speed control ----
  document.querySelectorAll('.speed-control').forEach(btn => {
    btn.addEventListener('click', () => {
      const video = document.querySelector('.lesson-video');
      const speeds = [0.75, 1.0, 1.25, 1.5, 2.0];
      const current = video ? video.playbackRate : 1.0;
      const nextIdx = (speeds.indexOf(current) + 1) % speeds.length;
      if (video) video.playbackRate = speeds[nextIdx];
      btn.textContent = speeds[nextIdx] + 'x';
    });
  });

})();
