<?php
/**
 * Template Name: Certificates Page
 *
 * Maps 1-to-1 to certificates.html and ar/certificates.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<section class="section-padding">
  <div class="container">
    <h1 style="margin-bottom:var(--space-xl);"><?php is_rtl() ? _e('شهاداتي', 'edtech') : _e('My Certificates', 'edtech'); ?></h1>

    <?php
    if ( ! is_user_logged_in() ) : ?>
      <div class="card" style="text-align:center;padding:var(--space-xl);">
        <p style="color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php is_rtl() ? _e('سجّل الدخول لعرض شهاداتك.', 'edtech') : _e('Sign in to view your certificates.', 'edtech'); ?></p>
        <a href="<?php echo esc_url( edtech_page_url( 'checkout' ) ); ?>" class="btn btn-primary"><?php is_rtl() ? _e('تسجيل الدخول', 'edtech') : _e('Sign In', 'edtech'); ?></a>
      </div>
    <?php else :
      $user_id    = get_current_user_id();
      $enrolled   = edtech_get_enrolled_course_ids( $user_id );
      $completed  = array();
      foreach ( $enrolled as $cid ) {
        $progress = (int) get_user_meta( $user_id, '_edtech_course_progress_' . $cid, true );
        if ( 100 === $progress ) {
          $completed[] = $cid;
        }
      }

      if ( empty( $completed ) ) : ?>
        <div class="card" style="text-align:center;padding:var(--space-xl);">
          <div style="font-size:3rem;margin-bottom:var(--space-md);">🎓</div>
          <h3 style="margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('لا توجد شهادات بعد', 'edtech') : _e('No certificates yet', 'edtech'); ?></h3>
          <p style="color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php is_rtl() ? _e('أكمل دورة بنسبة 100% للحصول على شهادة.', 'edtech') : _e('Complete a course at 100% to earn a certificate.', 'edtech'); ?></p>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'course' ) ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e('تصفح الدورات ←', 'edtech') : _e('Browse Courses →', 'edtech'); ?></a>
        </div>
      <?php else :
        $current_user = wp_get_current_user();
        foreach ( $completed as $cid ) :
          $course = get_post( $cid );
          if ( ! $course || 'course' !== $course->post_type ) continue;
          $meta = edtech_get_course_meta( $cid );
          $cert_id = 'CERT-' . date( 'Y' ) . '-' . strtoupper( substr( $course->post_name, 0, 3 ) ) . '-' . sprintf( '%04d', $cid );
          ?>
          <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-xl);" class="reveal">
            <div>
              <h2 style="margin-bottom:4px;"><?php echo esc_html( $course->post_title ); ?></h2>
              <p style="color:var(--color-text-muted);"><?php printf( esc_html( is_rtl() ? 'معرف الشهادة: %s' : 'Certificate ID: %s' ), esc_html( $cert_id ) ); ?></p>
            </div>
            <div style="display:flex;gap:var(--space-sm);flex-wrap:wrap;">
              <button class="btn btn-secondary" onclick="window.print()"><?php is_rtl() ? _e('تنزيل PDF', 'edtech') : _e('Download PDF', 'edtech'); ?></button>
              <button class="btn btn-primary"><?php is_rtl() ? _e('المشاركة على LinkedIn', 'edtech') : _e('Share to LinkedIn', 'edtech'); ?></button>
            </div>
          </div>

          <!-- Certificate Canvas -->
          <div class="certificate-canvas reveal" style="max-width:860px;margin-inline:auto;margin-bottom:var(--space-2xl);">
            <div class="certificate-seal">🎓</div>
            <p style="font-size:var(--font-size-body-sm);font-weight:600;text-transform:uppercase;color:var(--color-primary);margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('شهادة إتمام وتأهيل مهني', 'edtech') : _e('Certificate of Completion', 'edtech'); ?></p>
            <h2 style="font-size:2.5rem;font-weight:800;margin-bottom:var(--space-sm);"><?php echo esc_html( $current_user->display_name ); ?></h2>
            <h3 style="font-size:1.5rem;color:var(--color-primary);margin-bottom:var(--space-sm);"><?php echo esc_html( $course->post_title ); ?></h3>
            <p style="font-size:14px;color:var(--color-text-muted);margin-bottom:var(--space-xl);"><?php echo esc_html( sprintf( '%s %s · %s', $meta['lessons_count'], ( is_rtl() ? 'دروس' : 'Lessons' ), $meta['duration'] ) ); ?></p>
          </div>
        <?php endforeach;
      endif;
    endif;
    ?>
  </div>
</section>
</main>

<?php
get_footer();
