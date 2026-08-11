/* ============================================================
   I18N.JS — Bilingual Language Switcher (AR/EN)
   Switches html[lang] + html[dir] and links to mirrored page
   ============================================================ */
(function() {
  'use strict';

  const LANG_MAP = {
    en: { dir: 'ltr', label: 'English', prefix: '' },
    ar: { dir: 'rtl', label: 'العربية', prefix: '/ar' }
  };

  function switchLanguage(targetLang) {
    const config = LANG_MAP[targetLang];
    if (!config) return;
    const html = document.documentElement;
    html.setAttribute('lang', targetLang);
    html.setAttribute('dir', config.dir);

    // Navigate to mirrored page
    const currentPath = window.location.pathname;
    let newPath;
    if (currentPath.includes('/ar/')) {
      // EN -> AR already done: go to EN root equivalent
      newPath = currentPath.replace('/ar/', '/');
    } else if (targetLang === 'ar') {
      // Go to AR mirror
      const base = currentPath === '/' || currentPath.endsWith('index.html') ? '' : currentPath.split('/').pop();
      newPath = '/ar/' + (base || 'index.html');
    } else {
      newPath = currentPath;
    }
    if (newPath !== currentPath) window.location.href = newPath;
  }

  document.querySelectorAll('.lang-switcher-btn, [data-lang]').forEach(btn => {
    btn.addEventListener('click', () => {
      const lang = btn.dataset.lang;
      if (lang) switchLanguage(lang);
    });
  });

})();
