<?php
/**
 * Template Name: Course Builder Page
 *
 * Maps 1-to-1 to course-builder.html and ar/course-builder.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<section class="section-padding-sm">
  <div class="container container-md">
    <?php if ( ! is_user_logged_in() ) : ?>
      <div class="card" style="text-align:center;padding:var(--space-xl);">
        <p style="color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php wpm_is_rtl() ? _e('سجّل الدخول لإنشاء دورة.', 'edtech') : _e('Sign in to create a course.', 'edtech'); ?></p>
        <a href="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>" class="btn btn-primary"><?php wpm_is_rtl() ? _e('تسجيل الدخول', 'edtech') : _e('Sign In', 'edtech'); ?></a>
      </div>
    <?php else : ?>

    <div class="card reveal" style="margin-bottom:var(--space-xl);padding:var(--space-md);">
      <div style="display:flex;justify-content:space-between;font-weight:700;margin-bottom:var(--space-sm);">
        <span><?php wpm_is_rtl() ? _e('1. المعلومات الأساسية', 'edtech') : _e('1. Basic Info', 'edtech'); ?></span>
        <span><?php wpm_is_rtl() ? _e('2. المنهج', 'edtech') : _e('2. Curriculum', 'edtech'); ?></span>
        <span><?php wpm_is_rtl() ? _e('3. الوسائط', 'edtech') : _e('3. Media', 'edtech'); ?></span>
        <span><?php wpm_is_rtl() ? _e('4. التسعير والنشر', 'edtech') : _e('4. Pricing & Publish', 'edtech'); ?></span>
      </div>
      <div class="progress-bar"><div class="progress-fill" style="width:25%;"></div></div>
    </div>

    <div class="card reveal">
      <?php edtech_render_notice(); ?>
      <h2 style="margin-bottom:var(--space-lg);"><?php wpm_is_rtl() ? _e('المعلومات الأساسية للدورة', 'edtech') : _e('Course Basic Information', 'edtech'); ?></h2>
      <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <?php wp_nonce_field( 'edtech_course_builder', 'edtech_course_builder_nonce' ); ?>
        <input type="hidden" name="action" value="edtech_course_builder">
        <div class="form-group">
          <label class="form-label" for="builder-title"><?php wpm_is_rtl() ? _e('عنوان الدورة *', 'edtech') : _e('Course Title *', 'edtech'); ?></label>
          <input id="builder-title" name="course_title" type="text" class="input-field" placeholder="<?php wpm_is_rtl() ? _e('تطوير الويب المتكامل', 'edtech') : _e('Full-Stack Web Development', 'edtech'); ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="builder-desc"><?php wpm_is_rtl() ? _e('وصف الدورة', 'edtech') : _e('Course Description', 'edtech'); ?></label>
          <textarea id="builder-desc" name="course_description" class="input-field" rows="4" placeholder="<?php wpm_is_rtl() ? _e('صف محتوى الدورة وما سيتعلمه الطالب...', 'edtech') : _e('Describe the course content and what students will learn...', 'edtech'); ?>"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label" for="builder-price"><?php wpm_is_rtl() ? _e('السعر ($)', 'edtech') : _e('Price ($)', 'edtech'); ?></label>
          <input id="builder-price" name="course_price" type="number" class="input-field" value="49" min="0" step="1">
        </div>
        <button type="submit" class="btn btn-primary"><?php wpm_is_rtl() ? _e('حفظ والمتابعة للخطوة التالية →', 'edtech') : _e('Save & Continue to Next Step →', 'edtech'); ?></button>
      </form>
    </div>

    <?php endif; ?>
  </div>
</section>
</main>

<?php
get_footer();
