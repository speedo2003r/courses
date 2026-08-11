<?php
/**
 * Template Name: Learning Paths Page
 *
 * Maps 1-to-1 to learning-paths.html and ar/learning-paths.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<!-- Path Hero -->
<section class="section-padding" style="background:linear-gradient(145deg,var(--color-bg-dark) 0%,hsl(222,47%,14%) 100%);position:relative;overflow:hidden;">
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <div class="reveal">
      <span class="badge badge-new" style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('مسارات مهنية منظمة', 'edtech') : _e('Structured Career Tracks', 'edtech'); ?></span>
      <h1 style="color:white;font-size:var(--font-size-display);margin-bottom:var(--space-md);"><?php is_rtl() ? _e('مسارات التعلم المهنية', 'edtech') : _e('Professional Learning Paths', 'edtech'); ?></h1>
      <p style="color:rgba(255,255,255,0.75);font-size:var(--font-size-body-lg);max-width:600px;margin-inline:auto;margin-bottom:var(--space-xl);">
        <?php is_rtl() ? _e('اتبع مساراتنا المهنية المنسقة والمصممة لتأهيلك لسوق العمل بسرعة وفاعلية.', 'edtech') : _e('Follow curated multi-course career tracks and arrive at your destination faster.', 'edtech'); ?>
      </p>
      <div style="max-width:400px;margin-inline:auto;background:rgba(255,255,255,0.1);padding:var(--space-md);border-radius:var(--radius-lg);border:1px solid rgba(255,255,255,0.15);">
        <label for="commitment-slider" style="display:block;color:rgba(255,255,255,0.8);font-size:14px;margin-bottom:var(--space-xs);">
          <?php is_rtl() ? _e('ساعات الدراسة الأسبوعية:', 'edtech') : _e('Weekly Study Hours:', 'edtech'); ?> <strong id="commitment-val" style="color:white;"><?php is_rtl() ? _e('10 ساعات/أسبوع', 'edtech') : _e('10 hrs/wk', 'edtech'); ?></strong>
        </label>
        <input type="range" id="commitment-slider" min="5" max="20" value="10" style="width:100%;accent-color:var(--color-accent);">
      </div>
    </div>
  </div>
</section>

<!-- Skill Tree -->
<section class="section-padding">
  <div class="container container-md">
    <div class="reveal" style="margin-bottom:var(--space-xl);">
      <h2><?php is_rtl() ? _e('مسار مطور الويب المتكامل (Full-Stack)', 'edtech') : _e('Full-Stack Web Developer Path', 'edtech'); ?></h2>
      <p style="color:var(--color-text-muted);margin-top:var(--space-xs);"><?php is_rtl() ? _e('10 دورات · ~6 أشهر بمعدل 10 ساعات/أسبوع', 'edtech') : _e('10 Courses · ~6 Months at 10 hrs/wk', 'edtech'); ?></p>
    </div>
    <div class="skill-tree reveal">
      <div class="skill-node">
        <div class="skill-node-card" style="display:flex;align-items:center;justify-content:space-between;">
          <div>
            <span class="badge badge-free" style="margin-bottom:4px;"><?php is_rtl() ? _e('المرحلة 1 — الأساسيات', 'edtech') : _e('Phase 1 — Foundations', 'edtech'); ?></span>
            <h3 style="margin:0;font-size:var(--font-size-h4);"><?php is_rtl() ? _e('أساسيات HTML, CSS وJavaScript', 'edtech') : _e('HTML, CSS & JavaScript Foundations', 'edtech'); ?></h3>
            <p style="font-size:13px;color:var(--color-text-muted);margin-top:4px;"><?php is_rtl() ? _e('3 دورات · 4 أسابيع · مبتدئ', 'edtech') : _e('3 Courses · 4 Weeks · Beginner', 'edtech'); ?></p>
          </div>
          <a href="<?php echo esc_url( home_url('/catalog') ); ?>" class="btn btn-secondary" style="flex-shrink:0;"><?php is_rtl() ? _e('ابدأ المرحلة ←', 'edtech') : _e('Start Phase →', 'edtech'); ?></a>
        </div>
      </div>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
