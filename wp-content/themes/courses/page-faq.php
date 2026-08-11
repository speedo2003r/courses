<?php
/**
 * Template Name: FAQ & Support Center Page
 *
 * Maps 1-to-1 to faq.html and ar/faq.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<!-- FAQ Search Hero -->
<section class="section-padding" style="background:linear-gradient(145deg,var(--color-bg-main) 0%,var(--color-bg-subtle) 100%);">
  <div class="container container-sm" style="text-align:center;">
    <div class="reveal">
      <h1 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('كيف يمكننا مساعدتك اليوم؟', 'edtech') : _e('How Can We Help You?', 'edtech'); ?></h1>
      <div class="input-search-wrapper" style="max-width:480px;margin-inline:auto;">
        <svg class="input-search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="search" class="input-field input-lg input-pill search-input" placeholder="<?php is_rtl() ? _e('كيف أحصل على شهادة الإتمام؟', 'edtech') : _e('How do I get my certificate?', 'edtech'); ?>" id="faq-search">
      </div>
    </div>
  </div>
</section>

<!-- FAQ Accordion -->
<section class="section-padding-sm">
  <div class="container container-md">
    <div class="accordion-group">
      <div class="accordion-item active">
        <button class="accordion-trigger" aria-expanded="true">
          <?php is_rtl() ? _e('كيف أسجل في دورة جديدة؟', 'edtech') : _e('How do I enroll in a course?', 'edtech'); ?>
          <svg class="accordion-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="accordion-body">
          <p style="font-size:14px;"><?php is_rtl() ? _e('اضغط على زر اشترك الآن في صفحة تفاصيل أي دورة. ستحصل فوراً على وصول مدى الحياة.', 'edtech') : _e('Click the Enroll Now button on any course page to get immediate lifetime access.', 'edtech'); ?></p>
        </div>
      </div>
      <div class="accordion-item">
        <button class="accordion-trigger" aria-expanded="false">
          <?php is_rtl() ? _e('كيف أحصل على شهادة الإتمام؟', 'edtech') : _e('How do I get my certificate of completion?', 'edtech'); ?>
          <svg class="accordion-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="accordion-body">
          <p style="font-size:14px;"><?php is_rtl() ? _e('أكمل جميع الدروس والتقييمات، وتنشأ شهادتك تلقائياً في صفحة الشهادات.', 'edtech') : _e('Complete all lessons and assessments to automatically generate your verified certificate.', 'edtech'); ?></p>
        </div>
      </div>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
