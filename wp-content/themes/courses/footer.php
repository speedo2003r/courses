<!-- ===== FOOTER ===== -->
<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <h3><?php is_rtl() ? _e( 'منصة التعلم الرقمي', 'edtech' ) : _e( 'EdTech Platform', 'edtech' ); ?></h3>
        <p>
          <?php $footer_text = edtech_get_site_setting( 'footer_text' );
          echo wp_kses_post( $footer_text ? $footer_text : ( is_rtl()
            ? 'نمكّن المتعلمين العرب والعالميين من المهارات الرقمية العالية التأثير عبر تعليم قائم على المشاريع.'
            : 'Empowering Arabic & Global learners with high-impact digital skills through expert-led project-based education.' ) ); ?>
        </p>
        <?php
        $socials = array(
          'social_linkedin' => array( 'in', edtech_get_site_setting( 'social_linkedin' ) ),
          'social_youtube'  => array( 'yt', edtech_get_site_setting( 'social_youtube' ) ),
          'social_twitter'  => array( 'x', edtech_get_site_setting( 'social_twitter' ) ),
        );
        $has_social = false;
        foreach ( $socials as $s ) {
          if ( ! empty( $s[1] ) ) { $has_social = true; break; }
        }
        if ( $has_social ) :
          ?>
          <div class="footer-social" style="display:flex;gap:var(--space-sm);margin-top:var(--space-md);">
            <?php foreach ( $socials as $key => $s ) :
              if ( empty( $s[1] ) ) { continue; } ?>
              <a href="<?php echo esc_url( $s[1] ); ?>" class="btn btn-ghost" style="height:36px;width:36px;padding:0;display:inline-flex;align-items:center;justify-content:center;" aria-label="<?php echo esc_attr( $key ); ?>" target="_blank" rel="noopener"><?php echo esc_html( strtoupper( $s[0] ) ); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="footer-col">
        <h4><?php is_rtl() ? _e( 'روابط سريعة', 'edtech' ) : _e( 'Quick Links', 'edtech' ); ?></h4>
        <?php
        wp_nav_menu( array(
          'theme_location' => 'footer-quick',
          'container'      => false,
          'menu_class'     => '',
          'fallback_cb'    => 'edtech_footer_menu_fallback',
          'depth'          => 1,
        ) );
        ?>
      </div>
      <div class="footer-col">
        <h4><?php is_rtl() ? _e( 'دعم الطلاب', 'edtech' ) : _e( 'Student Support', 'edtech' ); ?></h4>
        <?php
        wp_nav_menu( array(
          'theme_location' => 'footer-support',
          'container'      => false,
          'menu_class'     => '',
          'fallback_cb'    => 'edtech_footer_menu_fallback',
          'depth'          => 1,
        ) );
        ?>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?php echo esc_html( date( 'Y' ) ); ?> <?php is_rtl() ? _e( 'منصة التعلم الرقمي. جميع الحقوق محفوظة.', 'edtech' ) : _e( 'EdTech Platform. All rights reserved.', 'edtech' ); ?></span>
      <?php
      $contact_email = edtech_get_site_setting( 'contact_email' );
      $contact_phone = edtech_get_site_setting( 'contact_phone' );
      if ( $contact_email || $contact_phone ) : ?>
        <span style="font-size:13px;color:var(--color-text-muted);">
          <?php if ( $contact_email ) : ?><a href="mailto:<?php echo esc_attr( $contact_email ); ?>" style="color:inherit;"><?php echo esc_html( $contact_email ); ?></a><?php endif; ?>
          <?php if ( $contact_phone ) : ?> · <?php echo esc_html( $contact_phone ); ?><?php endif; ?>
        </span>
      <?php endif; ?>
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
