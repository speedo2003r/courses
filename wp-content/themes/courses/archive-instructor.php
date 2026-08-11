<?php
/**
 * The Instructors Directory template
 *
 * Maps 1-to-1 to instructors.html and ar/instructors.html
 *
 * @package EdTech
 */

get_header();

$stat_students    = edtech_get_site_setting( 'stats_students', '45000' );
$course_count     = wp_count_posts( 'course' );
$instructor_count = wp_count_posts( 'instructor' );
$stat_courses     = isset( $course_count->publish ) ? (int) $course_count->publish : 0;
$stat_instructors = isset( $instructor_count->publish ) ? (int) $instructor_count->publish : 0;
?>

<main>
<!-- Hero -->
<section class="section-padding" style="background:linear-gradient(145deg,var(--color-bg-main) 0%,var(--color-bg-subtle) 100%);">
  <div class="container" style="text-align:center;">
    <div class="reveal">
      <h1><?php is_rtl() ? _e('تعلّم من خبراء الصناعة المعتمدين', 'edtech') : _e('Learn From Verified Industry Experts', 'edtech'); ?></h1>
      <p style="color:var(--color-text-muted);max-width:580px;margin-inline:auto;margin-top:var(--space-sm);"><?php is_rtl() ? _e('جميع المدربين لديهم خبرة عملية حقيقية. اضغط على أزرار الموجات الصوتية للاستماع لمقدمة صوتية.', 'edtech') : _e('All instructors have real-world experience. Tap the microphone to hear a 30-second voice greeting.', 'edtech'); ?></p>
    </div>
  </div>
</section>

<!-- Impact Stats Bar -->
<section style="background:var(--color-primary);padding-block:var(--space-lg);">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:var(--space-md);text-align:center;">
      <div><div style="font-size:1.75rem;font-weight:800;color:white;" data-target="<?php echo esc_attr( (int) $stat_students ); ?>">0</div><div style="font-size:13px;color:rgba(255,255,255,0.8);"><?php is_rtl() ? _e('طالب نشط', 'edtech') : _e('Active Students', 'edtech'); ?></div></div>
      <div><div style="font-size:1.75rem;font-weight:800;color:white;">★ 4.9</div><div style="font-size:13px;color:rgba(255,255,255,0.8);"><?php is_rtl() ? _e('متوسط التقييم', 'edtech') : _e('Average Rating', 'edtech'); ?></div></div>
      <div><div style="font-size:1.75rem;font-weight:800;color:white;" data-target="<?php echo esc_attr( $stat_instructors ); ?>">0</div><div style="font-size:13px;color:rgba(255,255,255,0.8);"><?php is_rtl() ? _e('مدربين خبراء', 'edtech') : _e('Expert Instructors', 'edtech'); ?></div></div>
      <div><div style="font-size:1.75rem;font-weight:800;color:white;" data-target="<?php echo esc_attr( $stat_courses ); ?>">0</div><div style="font-size:13px;color:rgba(255,255,255,0.8);"><?php is_rtl() ? _e('دورة متميزة', 'edtech') : _e('Premium Courses', 'edtech'); ?></div></div>
    </div>
  </div>
</section>

<!-- Instructor Grid -->
<section class="section-padding">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:var(--space-xl);">
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) : the_post();
          get_template_part( 'template-parts/content-instructor-card' );
        endwhile;
      else :
        ?>
        <p style="grid-column:1/-1;text-align:center;color:var(--color-text-muted);"><?php is_rtl() ? _e('لا يوجد مدربون بعد. أضف مدربين من لوحة التحكم.', 'edtech') : _e('No instructors yet. Add instructors from the admin.', 'edtech'); ?></p>
      <?php endif;
      ?>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
