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
    <div class="card reveal" style="margin-bottom:var(--space-xl);padding:var(--space-md);">
      <div style="display:flex;justify-content:space-between;font-weight:700;margin-bottom:var(--space-sm);">
        <span><?php is_rtl() ? _e('1. المعلومات الأساسية', 'edtech') : _e('1. Basic Info', 'edtech'); ?></span>
        <span><?php is_rtl() ? _e('2. المنهج', 'edtech') : _e('2. Curriculum', 'edtech'); ?></span>
        <span><?php is_rtl() ? _e('3. الوسائط', 'edtech') : _e('3. Media', 'edtech'); ?></span>
        <span><?php is_rtl() ? _e('4. التسعير والنشر', 'edtech') : _e('4. Pricing & Publish', 'edtech'); ?></span>
      </div>
      <div class="progress-bar"><div class="progress-fill" style="width:25%;"></div></div>
    </div>

    <div class="card reveal">
      <h2 style="margin-bottom:var(--space-lg);"><?php is_rtl() ? _e('المعلومات الأساسية للدورة', 'edtech') : _e('Course Basic Information', 'edtech'); ?></h2>
      <form onsubmit="event.preventDefault();showToast('<?php is_rtl() ? _e('تم حفظ الحقول!', 'edtech') : _e('Saved!', 'edtech'); ?>');">
        <div class="form-group">
          <label class="form-label" for="builder-title"><?php is_rtl() ? _e('عنوان الدورة *', 'edtech') : _e('Course Title *', 'edtech'); ?></label>
          <input id="builder-title" type="text" class="input-field" placeholder="<?php is_rtl() ? _e('تطوير الويب المتكامل', 'edtech') : _e('Full-Stack Web Development', 'edtech'); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><?php is_rtl() ? _e('الخطوة التالية →', 'edtech') : _e('Next Step →', 'edtech'); ?></button>
      </form>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
