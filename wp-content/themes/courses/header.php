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
    /* Critical inline CSS for mobile menu — guarantees visibility */
    @media (max-width: 1023px) {
      .nav-hamburger {
        display: flex !important;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        width: 44px;
        height: 44px;
        padding: 0;
        border: 1px solid var(--color-border-subtle, #ccc);
        border-radius: 6px;
        background: #fff;
        cursor: pointer;
        flex-shrink: 0;
      }
      .nav-hamburger span {
        display: block;
        width: 22px;
        height: 2px;
        margin: 0 auto;
        background: #222;
        border-radius: 2px;
      }
      .nav-links { display: none !important; }
      .lang-switcher-btn, .nav-action-dashboard, .nav-action-enroll { display: none !important; }
    }
    .mobile-menu {
      position: fixed; top: 0; right: 0; height: 100vh; width: min(360px, 85vw);
      background: #fff; z-index: 2001; overflow-y: auto;
      padding: 32px 24px 48px; display: flex; flex-direction: column; gap: 32px;
      transform: translateX(100%); transition: transform 0.35s ease;
      visibility: hidden;
    }
    .mobile-menu.open { transform: translateX(0) !important; visibility: visible !important; }
    .mobile-menu-backdrop {
      position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 2000;
      opacity: 0; pointer-events: none; transition: opacity 0.25s ease;
    }
    .mobile-menu-backdrop.open { opacity: 1; pointer-events: auto; }
    [dir="rtl"] .mobile-menu { right: auto; left: 0; transform: translateX(-100%); }
    .mobile-nav-links { display: flex; flex-direction: column; gap: 2px; padding: 0; margin: 0; list-style: none; }
    .mobile-nav-links a { display: block; font-size: 1rem; padding: 12px 16px; border-radius: 6px; color: #222; }
    .mobile-nav-links a:hover { background: rgba(37,99,235,0.08); color: #2563eb; }
    .mobile-menu-actions { display: flex; flex-direction: column; gap: 8px; padding-top: 16px; border-top: 1px solid #eee; }
    .mobile-menu-actions .btn { width: 100%; height: 48px; display: inline-flex; align-items: center; justify-content: center; }
    @media (min-width: 1024px) {
      .nav-hamburger { display: none !important; }
      .mobile-menu, .mobile-menu-backdrop { display: none !important; }
    }
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
        <a href="<?php echo esc_url( edtech_get_dashboard_url() ); ?>" class="btn btn-secondary nav-action-dashboard"><?php is_rtl() ? _e( 'لوحتي', 'edtech' ) : _e( 'Dashboard', 'edtech' ); ?></a>
      <?php else : ?>
        <a href="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>" class="btn btn-secondary nav-action-dashboard"><?php is_rtl() ? _e( 'تسجيل الدخول', 'edtech' ) : _e( 'Sign In', 'edtech' ); ?></a>
      <?php endif; ?>
      <a href="<?php echo esc_url( edtech_page_url( 'checkout' ) ); ?>" class="btn btn-primary nav-action-enroll" id="hero-enroll-cta"><?php is_rtl() ? _e( 'اشترك الآن', 'edtech' ) : _e( 'Enroll Now', 'edtech' ); ?></a>
      <button class="nav-hamburger" aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'edtech' ); ?>" aria-expanded="false" aria-controls="mobile-menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<!-- ===== MOBILE MENU PANEL ===== -->
<div class="mobile-menu" id="mobile-menu" role="dialog" aria-label="<?php esc_attr_e( 'Mobile navigation', 'edtech' ); ?>">
  <nav aria-label="<?php esc_attr_e( 'Mobile navigation', 'edtech' ); ?>">
    <?php
    wp_nav_menu( array(
      'theme_location' => 'primary',
      'menu_class'     => 'mobile-nav-links',
      'container'      => false,
      'fallback_cb'    => 'edtech_nav_menu_fallback',
      'depth'          => 1,
    ) );
    ?>
  </nav>
  <div class="mobile-menu-actions">
    <?php if ( is_rtl() ) : ?>
      <a href="<?php echo esc_url( add_query_arg( 'lang', 'en' ) ); ?>" class="btn btn-ghost"><?php _e( 'English', 'edtech' ); ?></a>
    <?php else : ?>
      <a href="<?php echo esc_url( add_query_arg( 'lang', 'ar' ) ); ?>" class="btn btn-ghost">العربية</a>
    <?php endif; ?>
    <?php if ( is_user_logged_in() ) : ?>
      <a href="<?php echo esc_url( edtech_get_dashboard_url() ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e( 'لوحتي', 'edtech' ) : _e( 'Dashboard', 'edtech' ); ?></a>
    <?php else : ?>
      <a href="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e( 'تسجيل الدخول', 'edtech' ) : _e( 'Sign In', 'edtech' ); ?></a>
    <?php endif; ?>
    <a href="<?php echo esc_url( edtech_page_url( 'checkout' ) ); ?>" class="btn btn-primary"><?php is_rtl() ? _e( 'اشترك الآن', 'edtech' ) : _e( 'Enroll Now', 'edtech' ); ?></a>
  </div>
</div>
<div class="mobile-menu-backdrop" id="mobile-menu-backdrop" aria-hidden="true"></div>
