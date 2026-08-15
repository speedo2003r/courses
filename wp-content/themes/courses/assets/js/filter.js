/* ============================================================
   FILTER.JS — Catalog & Blog Real-time Filter Engine
   Supports toggle state for "All" vs Specific Category Chips
   ============================================================ */
(function() {
  'use strict';

  function initFilters() {
    const filterChips = document.querySelectorAll('.filter-chip[data-category]');
    const filterCount = document.getElementById('filter-count');
    const noResults   = document.getElementById('no-results');

    if (!filterChips.length) return;

    filterChips.forEach(chip => {
      chip.onclick = function(e) {
        e.preventDefault();
        const category = chip.getAttribute('data-category');
        const container = chip.closest('.container') || document;
        const allChips  = container.querySelectorAll('.filter-chip[data-category]');
        const allCards  = document.querySelectorAll('.course-card[data-category]');

        if (category === 'all') {
          // Reset: activate "all", deactivate rest
          allChips.forEach(c => c.classList.remove('active'));
          chip.classList.add('active');

          let count = 0;
          allCards.forEach(card => {
            card.style.display = '';
            count++;
          });
          if (filterCount) filterCount.textContent = count;
          if (noResults) noResults.style.display = 'none';
        } else {
          // Specific category chip clicked
          const allChip = container.querySelector('.filter-chip[data-category="all"]');
          if (allChip) allChip.classList.remove('active');

          chip.classList.toggle('active');

          // Get active category values
          const activeChips = [...allChips].filter(c => c.classList.contains('active') && c.getAttribute('data-category') !== 'all');
          const activeCategories = activeChips.map(c => c.getAttribute('data-category').toLowerCase());

          if (activeCategories.length === 0) {
            // Fallback to "all" if none active
            if (allChip) allChip.classList.add('active');
            let count = 0;
            allCards.forEach(card => {
              card.style.display = '';
              count++;
            });
            if (filterCount) filterCount.textContent = count;
            if (noResults) noResults.style.display = 'none';
          } else {
            let visibleCount = 0;
            allCards.forEach(card => {
              const cardCat = (card.getAttribute('data-category') || '').toLowerCase();
              const isMatch = activeCategories.some(cat => cardCat.includes(cat));
              if (isMatch) {
                card.style.display = '';
                visibleCount++;
              } else {
                card.style.display = 'none';
              }
            });
            if (filterCount) filterCount.textContent = visibleCount;
            if (noResults) noResults.style.display = visibleCount === 0 ? 'block' : 'none';
          }
        }
      };
    });

    // View Mode Switcher
    const viewBtns = document.querySelectorAll('[data-view-mode]');
    const catalog  = document.getElementById('catalog-grid');

    viewBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        viewBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if (!catalog) return;
        const mode = btn.dataset.viewMode;
        catalog.className = catalog.className.replace(/view-\w+/g, '');
        catalog.classList.add(`view-${mode}`);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFilters);
  } else {
    initFilters();
  }

})();
