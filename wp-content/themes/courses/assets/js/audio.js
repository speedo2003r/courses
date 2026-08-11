/* ============================================================
   AUDIO.JS — 30-sec Instructor Voice Intro Player
   Singleton: one audio plays at a time
   ============================================================ */
(function() {
  'use strict';

  let currentAudio = null;
  let currentBtn   = null;

  document.querySelectorAll('.audio-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const src = btn.dataset.audio;

      // Stop any currently playing
      if (currentAudio) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
        if (currentBtn) currentBtn.classList.remove('playing');
      }

      if (currentBtn === btn) {
        // Toggle off
        currentAudio = null;
        currentBtn   = null;
        return;
      }

      if (!src) return;

      const audio = new Audio(src);
      currentAudio = audio;
      currentBtn   = btn;
      btn.classList.add('playing');

      audio.play().catch(() => {
        // Browser blocked autoplay — show fallback label
        btn.title = 'Click to play';
      });

      audio.addEventListener('ended', () => {
        btn.classList.remove('playing');
        currentAudio = null;
        currentBtn   = null;
      });
    });
  });

})();
