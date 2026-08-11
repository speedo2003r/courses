/* ============================================================
   SEARCH.JS — Natural Language Search & Auto-Suggest
   Uses EDTECH_SEARCH localized data (titles + permalinks).
   ============================================================ */
(function() {
  'use strict';

  if (typeof EDTECH_SEARCH === 'undefined' || !EDTECH_SEARCH.courses) return;

  const COURSES = EDTECH_SEARCH.courses;
  const isAr    = EDTECH_SEARCH.is_rtl || document.documentElement.lang === 'ar';

  document.querySelectorAll('.search-input').forEach(input => {
    const wrapper = input.closest('.input-search-wrapper');
    if (!wrapper) return;
    let dropdown = wrapper.querySelector('.auto-suggest');
    if (!dropdown) {
      dropdown = document.createElement('div');
      dropdown.className = 'auto-suggest';
      dropdown.setAttribute('role', 'listbox');
      wrapper.appendChild(dropdown);
    }

    input.addEventListener('input', () => {
      const query = input.value.trim().toLowerCase();
      dropdown.innerHTML = '';
      if (query.length < 2) { dropdown.classList.remove('open'); return; }

      const matches = COURSES.filter(c => {
        const haystack = (c.title || '').toLowerCase();
        return haystack.includes(query) || (c.category || '').toLowerCase().includes(query);
      });

      if (matches.length === 0) {
        dropdown.innerHTML = `<div class="auto-suggest-item" style="color:var(--color-text-muted)">${isAr ? 'لا توجد نتائج' : 'No courses found'}</div>`;
      } else {
        matches.slice(0, 6).forEach(course => {
          const item = document.createElement('a');
          item.href = course.url;
          item.className = 'auto-suggest-item';
          item.setAttribute('role', 'option');
          item.innerHTML = `
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;color:var(--color-text-muted)"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <span>${course.title}</span>
            <span style="margin-inline-start:auto;font-size:11px;color:var(--color-text-muted)">${course.category}</span>`;
          dropdown.appendChild(item);
        });
      }
      dropdown.classList.add('open');
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') { dropdown.classList.remove('open'); input.blur(); }
    });

    document.addEventListener('click', (e) => {
      if (!wrapper.contains(e.target)) dropdown.classList.remove('open');
    });
  });

})();
