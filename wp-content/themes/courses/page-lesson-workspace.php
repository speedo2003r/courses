<?php
/**
 * Template Name: Lesson Workspace Page
 *
 * Maps 1-to-1 to lesson-workspace.html and ar/lesson-workspace.html
 *
 * @package EdTech
 */

get_header();

$course_id = edtech_get_course_id_from_request();
$course     = $course_id ? get_post( $course_id ) : null;
$course_meta = $course ? edtech_get_course_meta( $course_id ) : null;
$progress   = $course_id && is_user_logged_in() ? (int) get_user_meta( get_current_user_id(), '_edtech_course_progress_' . $course_id, true ) : 0;
$dashboard_url = edtech_page_url( 'student-dashboard' );
?>

<main>
<?php if ( $course ) : ?>
<div style="background:var(--color-bg-dark);height:56px;display:flex;align-items:center;padding-inline:var(--space-lg);gap:var(--space-md);color:white;">
  <a href="<?php echo esc_url( $dashboard_url ); ?>" style="color:rgba(255,255,255,0.7);font-size:13px;text-decoration:none;">← <?php is_rtl() ? _e('لوحة الطالب', 'edtech') : _e('Dashboard', 'edtech'); ?></a>
  <span style="font-size:14px;font-weight:600;"><?php echo esc_html( $course->post_title ); ?></span>
  <?php if ( is_user_logged_in() && edtech_user_is_enrolled( $course_id ) ) : ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-inline-start:auto;">
      <?php wp_nonce_field( 'edtech_mark_complete', 'edtech_mark_complete_nonce' ); ?>
      <input type="hidden" name="action" value="edtech_mark_complete">
      <input type="hidden" name="course_id" value="<?php echo esc_attr( $course_id ); ?>">
      <button type="submit" class="btn btn-primary btn-sm"><?php is_rtl() ? _e('تحديد كمكتمل', 'edtech') : _e('Mark as Complete', 'edtech'); ?></button>
    </form>
  <?php else : ?>
    <span style="margin-inline-start:auto;font-size:13px;color:rgba(255,255,255,0.5);"><?php is_rtl() ? _e('سجّل الدخول للتتبع', 'edtech') : _e('Sign in to track', 'edtech'); ?></span>
  <?php endif; ?>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;min-height:calc(100vh - 56px);">
  <div>
    <div class="video-container" style="border-radius:0;aspect-ratio:16/9;">
      <img src="<?php echo esc_url( edtech_get_post_image( $course_id, 'large', 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=1400&auto=format&fit=crop&q=80' ) ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
      <div class="video-play-overlay">
        <div class="video-play-btn"><svg width="28" height="28" viewBox="0 0 24 24" fill="var(--color-primary)"><polygon points="5 3 19 12 5 21 5 3"/></svg></div>
      </div>
    </div>
    <div style="padding:var(--space-lg);max-width:800px;">
      <h1 style="margin-bottom:var(--space-sm);"><?php echo esc_html( $course->post_title ); ?></h1>
      <?php if ( $progress > 0 ) : ?>
        <p style="font-size:14px;color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php printf( esc_html( is_rtl() ? 'التقدم الحالي: %d%%' : 'Current progress: %d%%' ), $progress ); ?></p>
      <?php endif; ?>
      <?php
      // Render course content (syllabus/outcomes/skills from meta).
      $sections = array(
        'syllabus' => is_rtl() ? 'المنهج' : 'Curriculum',
        'outcomes' => is_rtl() ? 'مخرجات التعلم' : 'Learning Outcomes',
        'skills'   => is_rtl() ? 'المهارات' : 'Skills',
      );
      foreach ( $sections as $key => $label ) :
        if ( ! empty( $course_meta[ $key ] ) ) : ?>
          <div style="margin-bottom:var(--space-lg);">
            <h2 style="font-size:var(--font-size-h4);margin-bottom:var(--space-sm);"><?php echo esc_html( $label ); ?></h2>
            <?php echo wp_kses_post( wpautop( $course_meta[ $key ] ) ); ?>
          </div>
        <?php endif;
      endforeach;
      // Fall back to course content if no structured meta.
      if ( empty( $course_meta['syllabus'] ) && empty( $course_meta['outcomes'] ) && empty( $course_meta['skills'] ) ) {
        echo wp_kses_post( wpautop( $course->post_content ) );
      }
      ?>
    </div>
  </div>

  <aside style="background:var(--color-bg-dark);color:white;padding:var(--space-md);">
    <h4 style="color:white;margin-bottom:var(--space-md);"><?php is_rtl() ? _e('منهج الدورة', 'edtech') : _e('Course Curriculum', 'edtech'); ?></h4>
    <div style="font-size:13px;display:flex;flex-direction:column;gap:8px;">
      <?php
      if ( ! empty( $course_meta['syllabus'] ) ) :
        // Parse syllabus lines into curriculum items.
        $lines = array_filter( array_map( 'trim', explode( "\n", strip_tags( $course_meta['syllabus'] ) ) ) );
        $i = 1;
        foreach ( $lines as $line ) :
          $num = ceil( $i / 3 ) . '.' . ( ( $i - 1 ) % 3 + 1 );
          ?>
          <span style="color:rgba(255,255,255,0.8);"><?php echo esc_html( $num . ' ' . $line ); ?></span>
          <?php
          $i++;
        endforeach;
      else : ?>
        <span style="color:rgba(255,255,255,0.5);"><?php is_rtl() ? _e('لا يوجد منهج بعد.', 'edtech') : _e('No curriculum yet.', 'edtech'); ?></span>
      <?php endif; ?>
    </div>
    <?php if ( $progress < 100 ) : ?>
      <div style="margin-top:var(--space-md);">
        <div style="width:100%;height:6px;background:rgba(255,255,255,0.15);border-radius:3px;overflow:hidden;">
          <div style="width:<?php echo esc_attr( $progress ); ?>%;height:100%;background:var(--color-primary);"></div>
        </div>
        <span style="font-size:12px;color:rgba(255,255,255,0.6);"><?php echo esc_html( $progress ); ?>%</span>
      </div>
    <?php else : ?>
      <p style="margin-top:var(--space-md);color:var(--color-success);font-size:13px;">✓ <?php is_rtl() ? _e('أكملت هذه الدورة!', 'edtech') : _e('You completed this course!', 'edtech'); ?></p>
    <?php endif; ?>
  </aside>
</div>
<?php else : ?>
<div style="padding:var(--space-2xl);text-align:center;">
  <h1><?php is_rtl() ? _e('لم يتم اختيار دورة', 'edtech') : _e('No Course Selected', 'edtech'); ?></h1>
  <p style="color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php is_rtl() ? _e('تصفح الكتالوج لاختيار دورة.', 'edtech') : _e('Browse the catalog to choose a course.', 'edtech'); ?></p>
  <a href="<?php echo esc_url( get_post_type_archive_link( 'course' ) ); ?>" class="btn btn-primary"><?php is_rtl() ? _e('تصفح الدورات ←', 'edtech') : _e('Browse Courses →', 'edtech'); ?></a>
</div>
<?php endif; ?>
</main>

<?php
get_footer();
