<?php
/**
 * Template Name: Instructor Dashboard Page
 *
 * Maps 1-to-1 to instructor-dashboard.html and ar/instructor-dashboard.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<section class="section-padding-sm">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-lg);" class="reveal">
      <div>
        <h1 style="margin-bottom:4px;"><?php is_rtl() ? _e('لوحة تحكم المدرب', 'edtech') : _e('Creator Dashboard', 'edtech'); ?></h1>
        <p style="color:var(--color-text-muted);"><?php is_rtl() ? _e('أهلاً بعودتك م. طارق! إليك نظرة عامة على أداء دوراتك.', 'edtech') : _e("Welcome back, Eng. Tariq! Here's your performance overview.", 'edtech'); ?></p>
      </div>
      <a href="<?php echo esc_url( home_url('/course-builder') ); ?>" class="btn btn-primary">+ <?php is_rtl() ? _e('إنشاء دورة جديدة', 'edtech') : _e('Create Course', 'edtech'); ?></a>
    </div>

    <!-- Performance Metric Cards -->
    <div class="grid grid-4 reveal" style="margin-bottom:var(--space-xl);">
      <div class="stat-card">
        <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e('إجمالي الإيرادات', 'edtech') : _e('Total Revenue', 'edtech'); ?></span>
        <div class="stat-value">$12,450</div>
      </div>
      <div class="stat-card">
        <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e('إجمالي الطلاب', 'edtech') : _e('Total Students', 'edtech'); ?></span>
        <div class="stat-value">12,400</div>
      </div>
      <div class="stat-card">
        <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e('متوسط التقييم', 'edtech') : _e('Average Rating', 'edtech'); ?></span>
        <div class="stat-value">★ 4.9</div>
      </div>
      <div class="stat-card">
        <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e('أسئلة قيد الانتظار', 'edtech') : _e('Active Q&A', 'edtech'); ?></span>
        <div class="stat-value">23</div>
      </div>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
