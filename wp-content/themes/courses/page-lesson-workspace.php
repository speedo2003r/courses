<?php
/**
 * Template Name: Lesson Workspace Page
 *
 * Maps 1-to-1 to lesson-workspace.html and ar/lesson-workspace.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<div style="background:var(--color-bg-dark);height:56px;display:flex;align-items:center;padding-inline:var(--space-lg);gap:var(--space-md);color:white;">
  <a href="<?php echo esc_url( home_url('/student-dashboard') ); ?>" style="color:rgba(255,255,255,0.7);font-size:13px;text-decoration:none;">← <?php is_rtl() ? _e('لوحة الطالب', 'edtech') : _e('Dashboard', 'edtech'); ?></a>
  <span style="font-size:14px;font-weight:600;"><?php is_rtl() ? _e('تطوير الويب المتكامل — الدرس 1.3', 'edtech') : _e('Full-Stack Web Development — Lesson 1.3', 'edtech'); ?></span>
  <button class="btn btn-primary btn-sm" style="margin-inline-start:auto;" onclick="showToast('<?php is_rtl() ? _e('تم تحديده كمكتمل!', 'edtech') : _e('Marked as Complete!', 'edtech'); ?>')"><?php is_rtl() ? _e('تحديد كمكتمل', 'edtech') : _e('Mark as Complete', 'edtech'); ?></button>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;min-height:calc(100vh - 56px);">
  <div>
    <div class="video-container" style="border-radius:0;aspect-ratio:16/9;">
      <img src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=1400&auto=format&fit=crop&q=80" alt="" style="width:100%;height:100%;object-fit:cover;">
      <div class="video-play-overlay">
        <div class="video-play-btn"><svg width="28" height="28" viewBox="0 0 24 24" fill="var(--color-primary)"><polygon points="5 3 19 12 5 21 5 3"/></svg></div>
      </div>
    </div>
  </div>

  <aside style="background:var(--color-bg-dark);color:white;padding:var(--space-md);">
    <h4 style="color:white;margin-bottom:var(--space-md);"><?php is_rtl() ? _e('منهج الدورة', 'edtech') : _e('Course Curriculum', 'edtech'); ?></h4>
    <div style="font-size:13px;display:flex;flex-direction:column;gap:8px;">
      <span style="color:var(--color-success);">✓ 1.1 HTML5</span>
      <span style="color:var(--color-success);">✓ 1.2 CSS Grid</span>
      <span style="color:white;font-weight:700;">▶ 1.3 JavaScript ES2025</span>
    </div>
  </aside>
</div>
</main>

<?php
get_footer();
