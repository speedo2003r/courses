<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
  <style>
    <?php if ( is_rtl() ) : ?>
      body { font-family: var(--font-body-ar), 'Cairo', sans-serif; }
      h1,h2,h3,h4,h5 { font-family: var(--font-display-ar), 'Cairo', sans-serif; }
    <?php endif; ?>
  </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ===== NAVBAR ===== -->
<header class="navbar" role="banner">
  <div class="navbar-container">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="<?php bloginfo( 'name' ); ?>">
      <?php if ( has_custom_logo() ) : ?>
        <?php the_custom_logo(); ?>
      <?php else : ?>
        <?php is_rtl() ? _e( 'منصة التعلم', 'edtech' ) : _e( 'EdTech Platform', 'edtech' ); ?>
      <?php endif; ?>
    </a>
    <nav aria-label="<?php esc_attr_e( 'Main navigation', 'edtech' ); ?>">
      <?php
      wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_class'     => 'nav-links',
        'container'      => false,
        'fallback_cb'    => 'edtech_nav_menu_fallback',
        'depth'          => 1,
      ) );
      ?>
    </nav>
    <div class="nav-actions">
      <?php if ( is_rtl() ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'lang', 'en' ) ); ?>" class="btn btn-ghost lang-switcher-btn" data-lang="en">English</a>
      <?php else : ?>
        <a href="<?php echo esc_url( add_query_arg( 'lang', 'ar' ) ); ?>" class="btn btn-ghost lang-switcher-btn" data-lang="ar">العربية</a>
      <?php endif; ?>
      <?php if ( is_user_logged_in() ) : ?>
        <a href="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e( 'لوحتي', 'edtech' ) : _e( 'Dashboard', 'edtech' ); ?></a>
      <?php else : ?>
        <a href="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e( 'تسجيل الدخول', 'edtech' ) : _e( 'Sign In', 'edtech' ); ?></a>
      <?php endif; ?>
      <a href="<?php echo esc_url( edtech_page_url( 'checkout' ) ); ?>" class="btn btn-primary" id="hero-enroll-cta"><?php is_rtl() ? _e( 'اشترك الآن', 'edtech' ) : _e( 'Enroll Now', 'edtech' ); ?></a>
    </div>
  </div>
</header>
