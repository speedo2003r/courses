<?php
/**
 * Template Name: Student Dashboard Page
 *
 * Maps 1-to-1 to student-dashboard.html and ar/student-dashboard.html
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
        <h1 style="margin-bottom:4px;"><?php is_rtl() ? _e('صباح الخير، أحمد! 👋', 'edtech') : _e('Good morning, Ahmed! 👋', 'edtech'); ?></h1>
        <p style="color:var(--color-text-muted);"><?php is_rtl() ? _e('أنت مستمر في الدراسة منذ 🔥 5 أيام متتالية!', 'edtech') : _e("You're on a 🔥 5 Day Study Streak!", 'edtech'); ?></p>
      </div>
      <div style="display:flex;gap:var(--space-md);">
        <a href="<?php echo esc_url( home_url('/catalog') ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e('تصفح الدورات', 'edtech') : _e('Browse Courses', 'edtech'); ?></a>
        <a href="<?php echo esc_url( home_url('/lesson-workspace') ); ?>" class="btn btn-primary"><?php is_rtl() ? _e('متابعة التعلم', 'edtech') : _e('Resume Learning', 'edtech'); ?></a>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 300px;gap:var(--space-xl);">
      <div>
        <div class="card reveal" style="background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-secondary) 100%);color:white;padding:var(--space-xl);margin-bottom:var(--space-lg);">
          <span style="font-size:13px;opacity:0.8;display:block;margin-bottom:4px;"><?php is_rtl() ? _e('تابع من حيث توقفت', 'edtech') : _e('Continue Where You Left Off', 'edtech'); ?></span>
          <h2 style="color:white;margin-bottom:6px;"><?php is_rtl() ? _e('تطوير الويب المتكامل', 'edtech') : _e('Full-Stack Web Development', 'edtech'); ?></h2>
          <p style="opacity:0.85;font-size:14px;"><?php is_rtl() ? _e('الدرس 1.3: تعمق في JavaScript ES2025', 'edtech') : _e('Lesson 1.3: JavaScript ES2025 Deep Dive', 'edtech'); ?></p>
          <a href="<?php echo esc_url( home_url('/lesson-workspace') ); ?>" class="btn btn-lg" style="background:white;color:var(--color-primary);margin-top:var(--space-md);"><?php is_rtl() ? _e('▶ متابعة الدرس', 'edtech') : _e('▶ Resume Lesson', 'edtech'); ?></a>
        </div>
      </div>

      <aside>
        <div class="card reveal">
          <h3 style="margin-bottom:var(--space-lg);"><?php is_rtl() ? _e('حلقات الإنجازات', 'edtech') : _e('Achievement Rings', 'edtech'); ?></h3>
          <p style="font-size:14px;"><?php is_rtl() ? _e('ساعات المشاهدة: 4.3س من 12.5س (35%)', 'edtech') : _e('Hours Watched: 4.3h of 12.5h (35%)', 'edtech'); ?></p>
          <a href="<?php echo esc_url( home_url('/certificates') ); ?>" class="btn btn-secondary" style="width:100%;margin-top:var(--space-md);"><?php is_rtl() ? _e('عرض الشهادات', 'edtech') : _e('View Certificates', 'edtech'); ?></a>
        </div>
      </aside>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
