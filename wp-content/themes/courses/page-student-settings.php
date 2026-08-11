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

    <?php if ( ! is_user_logged_in() ) : ?>
      <div class="card" style="text-align:center;padding:var(--space-xl);">
        <p style="color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php is_rtl() ? _e('سجّل الدخول لتعديل إعدادات حسابك.', 'edtech') : _e('Sign in to edit your account settings.', 'edtech'); ?></p>
        <a href="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>" class="btn btn-primary"><?php is_rtl() ? _e('تسجيل الدخول', 'edtech') : _e('Sign In', 'edtech'); ?></a>
      </div>
    <?php else :
      $current_user = wp_get_current_user();
      $headline  = get_user_meta( $current_user->ID, '_edtech_headline', true );
      $portfolio = get_user_meta( $current_user->ID, '_edtech_portfolio', true );
    ?>

    <div class="layout-profile">
      <nav class="card" style="padding:var(--space-md);">
        <a class="nav-link active" href="#profile"><?php is_rtl() ? _e('👤 الملف الشخصي', 'edtech') : _e('👤 Profile', 'edtech'); ?></a>
        <a class="nav-link" href="#security"><?php is_rtl() ? _e('🔒 الأمان', 'edtech') : _e('🔒 Security', 'edtech'); ?></a>
      </nav>

      <div class="card reveal">
        <?php edtech_render_notice(); ?>
        <h2 style="margin-bottom:var(--space-lg);"><?php is_rtl() ? _e('معلومات الملف الشخصي', 'edtech') : _e('Profile Information', 'edtech'); ?></h2>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
          <?php wp_nonce_field( 'edtech_profile_update', 'edtech_profile_nonce' ); ?>
          <input type="hidden" name="action" value="edtech_profile_update">
          <div class="form-group">
            <label class="form-label" for="full-name"><?php is_rtl() ? _e('الاسم الكامل', 'edtech') : _e('Full Name', 'edtech'); ?></label>
            <input id="full-name" name="display_name" type="text" class="input-field" value="<?php echo esc_attr( $current_user->display_name ); ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="email"><?php is_rtl() ? _e('البريد الإلكتروني', 'edtech') : _e('Email Address', 'edtech'); ?></label>
            <input id="email" name="user_email" type="email" class="input-field" value="<?php echo esc_attr( $current_user->user_email ); ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="headline"><?php is_rtl() ? _e('العنوان المهني', 'edtech') : _e('Professional Headline', 'edtech'); ?></label>
            <input id="headline" name="headline" type="text" class="input-field" value="<?php echo esc_attr( $headline ); ?>" placeholder="<?php is_rtl() ? _e('مطور ويب متكامل', 'edtech') : _e('Full-Stack Developer', 'edtech'); ?>">
          </div>
          <div class="form-group">
            <label class="form-label" for="portfolio"><?php is_rtl() ? _e('رابط معرض الأعمال', 'edtech') : _e('Portfolio URL', 'edtech'); ?></label>
            <input id="portfolio" name="portfolio" type="url" class="input-field" value="<?php echo esc_attr( $portfolio ); ?>" placeholder="https://github.com/username">
          </div>
          <button type="submit" class="btn btn-primary"><?php is_rtl() ? _e('حفظ التغييرات', 'edtech') : _e('Save Changes', 'edtech'); ?></button>
        </form>
      </div>
    </div>

    <?php endif; ?>
  </div>
</section>
</main>

<?php
get_footer();
