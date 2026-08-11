/* ============================================================
   SEARCH.JS — Natural Language Search & Auto-Suggest
   ============================================================ */
(function() {
  'use strict';

  const COURSES = [
    { title: 'Full-Stack Web Development',        title_ar: 'تطوير الويب المتكامل',           url: 'course-detail.html', category: 'Development' },
    { title: 'Figma UI/UX Design Systems',        title_ar: 'أنظمة تصميم Figma',              url: 'course-detail.html', category: 'Design' },
    { title: 'Python Data Analytics Dashboard',   title_ar: 'لوحة تحليلات Python',            url: 'course-detail.html', category: 'Data Science' },
    { title: 'Digital Marketing & Growth',        title_ar: 'التسويق الرقمي والنمو',          url: 'course-detail.html', category: 'Marketing' },
    { title: 'React 19 Advanced Patterns',        title_ar: 'أنماط React 19 المتقدمة',        url: 'course-detail.html', category: 'Development' },
    { title: 'Node.js API Architecture',          title_ar: 'هندسة Node.js',                   url: 'course-detail.html', category: 'Development' },
  ];

  const isAr = document.documentElement.lang === 'ar';

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
        const haystack = (isAr ? c.title_ar : c.title).toLowerCase();
        return haystack.includes(query) || c.category.toLowerCase().includes(query);
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
            <span>${isAr ? course.title_ar : course.title}</span>
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
