<?php
/**
 * Template Name: Student Settings Page
 *
 * Maps 1-to-1 to student-settings.html and ar/student-settings.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<section class="section-padding-sm">
  <div class="container">
    <h1 style="margin-bottom:var(--space-xl);"><?php is_rtl() ? _e('إعدادات الحساب', 'edtech') : _e('Account Settings', 'edtech'); ?></h1>

    <div style="display:grid;grid-template-columns:240px 1fr;gap:var(--space-xl);">
      <nav class="card" style="padding:var(--space-md);">
        <a class="nav-link active" href="#profile"><?php is_rtl() ? _e('👤 الملف الشخصي', 'edtech') : _e('👤 Profile', 'edtech'); ?></a>
        <a class="nav-link" href="#security"><?php is_rtl() ? _e('🔒 الأمان', 'edtech') : _e('🔒 Security', 'edtech'); ?></a>
      </nav>

      <div class="card reveal">
        <h2 style="margin-bottom:var(--space-lg);"><?php is_rtl() ? _e('معلومات الملف الشخصي', 'edtech') : _e('Profile Information', 'edtech'); ?></h2>
        <form onsubmit="event.preventDefault();showToast('<?php is_rtl() ? _e('✓ تم حفظ التغييرات بنجاح!', 'edtech') : _e('✓ Profile saved successfully!', 'edtech'); ?>');">
          <div class="form-group">
            <label class="form-label" for="full-name"><?php is_rtl() ? _e('الاسم الكامل', 'edtech') : _e('Full Name', 'edtech'); ?></label>
            <input id="full-name" type="text" class="input-field" value="<?php is_rtl() ? _e('أحمد السيد', 'edtech') : _e('Ahmed Al-Sayed', 'edtech'); ?>">
          </div>
          <button type="submit" class="btn btn-primary"><?php is_rtl() ? _e('حفظ التغييرات', 'edtech') : _e('Save Changes', 'edtech'); ?></button>
        </form>
      </div>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
