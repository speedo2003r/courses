<?php
/**
 * The Single Course template
 *
 * Maps 1-to-1 to course-detail.html and ar/course-detail.html
 *
 * @package EdTech
 */

get_header();

$post_id = get_the_ID();
$meta = edtech_get_course_meta( $post_id );
?>

<main>
<!-- Cinema Hero -->
<section style="position:relative;overflow:hidden;background:var(--color-bg-dark);min-height:360px;display:flex;align-items:center;">
  <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1400&auto=format&fit=crop&q=80" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.25;" loading="eager">
  <div class="container" style="position:relative;z-index:1;padding-block:var(--space-3xl);">
    <p style="font-size:13px;color:rgba(255,255,255,0.6);margin-bottom:var(--space-sm);">
      <a href="<?php echo esc_url( home_url('/') ); ?>" style="color:inherit;"><?php is_rtl() ? _e('الرئيسية', 'edtech') : _e('Home', 'edtech'); ?></a> &rsaquo; 
      <a href="<?php echo esc_url( home_url('/catalog') ); ?>" style="color:inherit;"><?php is_rtl() ? _e('الدورات', 'edtech') : _e('Courses', 'edtech'); ?></a> &rsaquo; 
      <?php the_title(); ?>
    </p>
    <h1 style="color:white;font-size:var(--font-size-h1);max-width:680px;margin-bottom:var(--space-md);"><?php the_title(); ?></h1>
    <p style="color:rgba(255,255,255,0.8);font-size:var(--font-size-body-lg);max-width:620px;margin-bottom:var(--space-md);">
      <?php is_rtl() ? _e('ابنِ تطبيقات كاملة جاهزة للإنتاج باستخدام React 19 وNode.js وPostgreSQL والنشر السحابي.', 'edtech') : _e('Build production-ready applications with React 19, Node.js, PostgreSQL, and cloud deployment.', 'edtech'); ?>
    </p>
    <div style="display:flex;align-items:center;gap:var(--space-md);flex-wrap:wrap;margin-bottom:var(--space-md);">
      <span class="badge badge-bestseller">★ <?php echo esc_html( $meta['badge'] ); ?></span>
      <div style="display:flex;align-items:center;gap:4px;">
        <span class="stars">★★★★★</span>
        <span style="color:white;font-weight:700;"><?php echo esc_html( $meta['rating'] ); ?></span>
        <span style="color:rgba(255,255,255,0.6);font-size:13px;">(<?php echo esc_html( $meta['reviews_count'] ); ?> <?php is_rtl() ? _e('تقييم', 'edtech') : _e('ratings', 'edtech'); ?>)</span>
      </div>
      <span style="color:rgba(255,255,255,0.6);font-size:13px;"><?php is_rtl() ? _e('+12,400 طالب مسجل', 'edtech') : _e('12,400+ enrolled students', 'edtech'); ?></span>
    </div>
  </div>
</section>

<!-- Course Body -->
<section class="section-padding-sm">
  <div class="container">
    <div style="display:grid;grid-template-columns:65fr 35fr;gap:var(--space-xl);align-items:start;">

      <!-- Main Content -->
      <div>
        <!-- Outcomes -->
        <div class="card reveal" style="margin-bottom:var(--space-lg);">
          <h2 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('ماذا ستبني وتكتسب؟', 'edtech') : _e('What You Will Build & Gain', 'edtech'); ?></h2>
          <div class="tabs">
            <button class="tab-trigger active" data-panel="outcomes-skills"><?php is_rtl() ? _e('المهارات المكتسبة', 'edtech') : _e('Skills Gained', 'edtech'); ?></button>
            <button class="tab-trigger" data-panel="outcomes-project"><?php is_rtl() ? _e('معرض المشاريع', 'edtech') : _e('Project Showcase', 'edtech'); ?></button>
          </div>
          <div data-tabs-container>
            <div id="outcomes-skills" class="tab-panel active">
              <ul style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-sm);font-size:14px;">
                <li>✓ <?php is_rtl() ? _e('هندسة مكونات React 19', 'edtech') : _e('React 19 Component Architecture', 'edtech'); ?></li>
                <li>✓ <?php is_rtl() ? _e('بناء واجهات Node.js REST APIs', 'edtech') : _e('Node.js REST API Development', 'edtech'); ?></li>
                <li>✓ <?php is_rtl() ? _e('تصميم قواعد بيانات PostgreSQL', 'edtech') : _e('PostgreSQL Database Design', 'edtech'); ?></li>
                <li>✓ <?php is_rtl() ? _e('نشر التطبيقات عبر Docker والسحابة', 'edtech') : _e('Docker & Cloud Deployment', 'edtech'); ?></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Syllabus Accordion -->
        <div class="reveal" style="margin-bottom:var(--space-lg);">
          <h2 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('منهج الدورة', 'edtech') : _e('Course Syllabus', 'edtech'); ?></h2>
          <div class="accordion-group">
            <div class="accordion-item active">
              <button class="accordion-trigger" aria-expanded="true">
                <span><?php is_rtl() ? _e('الوحدة 1: أساسيات الواجهة الأمامية', 'edtech') : _e('Module 1: Frontend Foundations', 'edtech'); ?></span>
                <svg class="accordion-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
              </button>
              <div class="accordion-body">
                <ul style="display:flex;flex-direction:column;gap:8px;padding-top:var(--space-sm);font-size:14px;">
                  <li>▶ 1.1 <?php is_rtl() ? _e('دلالات HTML5 الحديثة (12 دقيقة)', 'edtech') : _e('Modern HTML5 Semantics (12 mins)', 'edtech'); ?></li>
                  <li>▶ 1.2 <?php is_rtl() ? _e('تخطيطات CSS Grid & Flexbox (24 دقيقة)', 'edtech') : _e('CSS Grid & Flexbox Layouts (24 mins)', 'edtech'); ?></li>
                  <li>▶ 1.3 <?php is_rtl() ? _e('تعمق في JavaScript ES2025 (38 دقيقة)', 'edtech') : _e('JavaScript ES2025 Deep Dive (38 mins)', 'edtech'); ?></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sticky Sidebar -->
      <aside style="position:sticky;top:88px;">
        <div class="card" style="padding:var(--space-lg);">
          <div style="display:flex;align-items:baseline;gap:var(--space-sm);margin-bottom:var(--space-xs);">
            <span style="font-size:2rem;font-weight:800;color:var(--color-text-title);">$<?php echo esc_html( $meta['price'] ); ?></span>
            <span style="font-size:1.125rem;color:var(--color-text-muted);text-decoration:line-through;">$<?php echo esc_html( $meta['price_orig'] ); ?></span>
          </div>
          <a href="<?php echo esc_url( home_url('/checkout') ); ?>" class="btn btn-primary btn-lg" style="width:100%;margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('اشترك الآن', 'edtech') : _e('Enroll Now', 'edtech'); ?></a>
          <a href="<?php echo esc_url( home_url('/free-masterclass') ); ?>" class="btn btn-secondary btn-lg" style="width:100%;"><?php is_rtl() ? _e('جرب درساً مجاناً', 'edtech') : _e('Try Free Lesson', 'edtech'); ?></a>
        </div>
      </aside>

    </div>
  </div>
</section>
</main>

<?php
get_footer();
