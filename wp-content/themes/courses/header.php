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
      <?php is_rtl() ? _e( 'منصة التعلم', 'edtech' ) : _e( 'EdTech Platform', 'edtech' ); ?>
    </a>
    <nav aria-label="<?php esc_attr_e( 'Main navigation', 'edtech' ); ?>">
      <ul class="nav-links" role="list">
        <li><a href="<?php echo esc_url( home_url( '/catalog' ) ); ?>" class="nav-link"><?php is_rtl() ? _e( 'الدورات', 'edtech' ) : _e( 'Courses', 'edtech' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/learning-paths' ) ); ?>" class="nav-link"><?php is_rtl() ? _e( 'مسارات التعلم', 'edtech' ) : _e( 'Learning Paths', 'edtech' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/instructors' ) ); ?>" class="nav-link"><?php is_rtl() ? _e( 'المدربون', 'edtech' ) : _e( 'Instructors', 'edtech' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/free-masterclass' ) ); ?>" class="nav-link"><?php is_rtl() ? _e( 'ماستر كلاس مجاني', 'edtech' ) : _e( 'Free Masterclass', 'edtech' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>" class="nav-link"><?php is_rtl() ? _e( 'المدونة', 'edtech' ) : _e( 'Blog', 'edtech' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="nav-link"><?php is_rtl() ? _e( 'من نحن', 'edtech' ) : _e( 'About', 'edtech' ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="nav-link"><?php is_rtl() ? _e( 'الدعم', 'edtech' ) : _e( 'FAQ', 'edtech' ); ?></a></li>
      </ul>
    </nav>
    <div class="nav-actions">
      <?php if ( is_rtl() ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'lang', 'en' ) ); ?>" class="btn btn-ghost lang-switcher-btn" data-lang="en">English</a>
      <?php else : ?>
        <a href="<?php echo esc_url( add_query_arg( 'lang', 'ar' ) ); ?>" class="btn btn-ghost lang-switcher-btn" data-lang="ar">العربية</a>
      <?php endif; ?>
      <a href="<?php echo esc_url( home_url( '/student-dashboard' ) ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e( 'لوحتي', 'edtech' ) : _e( 'Dashboard', 'edtech' ); ?></a>
      <a href="<?php echo esc_url( home_url( '/checkout' ) ); ?>" class="btn btn-primary" id="hero-enroll-cta"><?php is_rtl() ? _e( 'اشترك الآن', 'edtech' ) : _e( 'Enroll Now', 'edtech' ); ?></a>
    </div>
  </div>
</header>
