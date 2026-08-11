<!-- ===== FOOTER ===== -->
<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <h3><?php is_rtl() ? _e( 'منصة التعلم الرقمي', 'edtech' ) : _e( 'EdTech Platform', 'edtech' ); ?></h3>
        <p>
          <?php is_rtl() 
            ? _e( 'نمكّن المتعلمين العرب والعالميين من المهارات الرقمية العالية التأثير عبر تعليم قائم على المشاريع.', 'edtech' )
            : _e( 'Empowering Arabic & Global learners with high-impact digital skills through expert-led project-based education.', 'edtech' ); ?>
        </p>
      </div>
      <div class="footer-col">
        <h4><?php is_rtl() ? _e( 'روابط سريعة', 'edtech' ) : _e( 'Quick Links', 'edtech' ); ?></h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/catalog' ) ); ?>"><?php is_rtl() ? _e( 'كتالوج الدورات', 'edtech' ) : _e( 'Courses Catalog', 'edtech' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/learning-paths' ) ); ?>"><?php is_rtl() ? _e( 'مسارات التعلم', 'edtech' ) : _e( 'Learning Paths', 'edtech' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/instructors' ) ); ?>"><?php is_rtl() ? _e( 'المدربون', 'edtech' ) : _e( 'Instructors', 'edtech' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php is_rtl() ? _e( 'المدونة والموارد', 'edtech' ) : _e( 'Blog & Resources', 'edtech' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php is_rtl() ? _e( 'من نحن', 'edtech' ) : _e( 'About Us', 'edtech' ); ?></a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4><?php is_rtl() ? _e( 'دعم الطلاب', 'edtech' ) : _e( 'Student Support', 'edtech' ); ?></h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/faq' ) ); ?>"><?php is_rtl() ? _e( 'الأسئلة الشائعة', 'edtech' ) : _e( 'FAQ & Help Center', 'edtech' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/student-dashboard' ) ); ?>"><?php is_rtl() ? _e( 'لوحة الطالب', 'edtech' ) : _e( 'Student Dashboard', 'edtech' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/certificates' ) ); ?>"><?php is_rtl() ? _e( 'شهاداتي', 'edtech' ) : _e( 'My Certificates', 'edtech' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/student-settings' ) ); ?>"><?php is_rtl() ? _e( 'إعدادات الحساب', 'edtech' ) : _e( 'Account Settings', 'edtech' ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/checkout' ) ); ?>"><?php is_rtl() ? _e( 'الدفع والتسجيل', 'edtech' ) : _e( 'Checkout', 'edtech' ); ?></a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?php echo date('Y'); ?> <?php is_rtl() ? _e( 'منصة التعلم الرقمي. جميع الحقوق محفوظة.', 'edtech' ) : _e( 'EdTech Platform. All rights reserved.', 'edtech' ); ?></span>
      <?php if ( is_rtl() ) : ?>
        <a href="<?php echo esc_url( add_query_arg( 'lang', 'en' ) ); ?>" class="btn btn-ghost" style="height:32px;font-size:13px;">English</a>
      <?php else : ?>
        <a href="<?php echo esc_url( add_query_arg( 'lang', 'ar' ) ); ?>" class="btn btn-ghost" style="height:32px;font-size:13px;">العربية</a>
      <?php endif; ?>
    </div>
  </div>
</footer>

<!-- Toast Container -->
<div id="toast" class="toast" role="status" aria-live="polite"></div>

<?php wp_footer(); ?>
</body>
</html>
