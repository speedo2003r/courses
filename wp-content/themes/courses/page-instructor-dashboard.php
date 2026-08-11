<?php
/**
 * Template Name: Instructor Dashboard Page
 *
 * Maps 1-to-1 to instructor-dashboard.html and ar/instructor-dashboard.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<section class="section-padding-sm">
  <div class="container">
    <?php if ( ! is_user_logged_in() ) : ?>
      <div class="card" style="text-align:center;padding:var(--space-xl);">
        <p style="color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php is_rtl() ? _e('سجّل الدخول كمدرب للوصول إلى لوحة التحكم.', 'edtech') : _e('Sign in as an instructor to access the dashboard.', 'edtech'); ?></p>
        <a href="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>" class="btn btn-primary"><?php is_rtl() ? _e('تسجيل الدخول', 'edtech') : _e('Sign In', 'edtech'); ?></a>
      </div>
    <?php else :
      $current_user = wp_get_current_user();
      $my_courses = get_posts( array(
        'post_type'   => 'course',
        'post_status' => array( 'publish', 'draft' ),
        'posts_per_page' => -1,
        'author'      => $current_user->ID,
      ) );

      $total_students = 0;
      $total_revenue  = 0;
      $rating_sum     = 0;
      $rating_count   = 0;
      foreach ( $my_courses as $c ) {
        $meta = edtech_get_course_meta( $c->ID );
        // Count students enrolled in this course across all users.
        $student_query = new WP_User_Query( array(
          'meta_key'   => '_edtech_enrolled_courses',
          'meta_value' => $c->ID,
          'compare'    => 'LIKE',
          'fields'     => 'ID',
          'count_total' => true,
        ) );
        $count = $student_query->get_total();
        $total_students += $count;
        $total_revenue  += ( (float) $meta['price'] * $count );
        if ( $meta['rating'] ) {
          $rating_sum   += (float) $meta['rating'];
          $rating_count += 1;
        }
      }
      $avg_rating = $rating_count ? round( $rating_sum / $rating_count, 1 ) : '—';
    ?>

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-lg);" class="reveal">
      <div>
        <h1 style="margin-bottom:4px;"><?php is_rtl() ? _e('لوحة تحكم المدرب', 'edtech') : _e('Creator Dashboard', 'edtech'); ?></h1>
        <p style="color:var(--color-text-muted);"><?php printf( esc_html( is_rtl() ? 'أهلاً بعودتك %s! إليك نظرة عامة على أداء دوراتك.' : 'Welcome back, %s! Here\'s your performance overview.' ), esc_html( $current_user->display_name ) ); ?></p>
      </div>
      <a href="<?php echo esc_url( edtech_page_url( 'course-builder' ) ); ?>" class="btn btn-primary">+ <?php is_rtl() ? _e('إنشاء دورة جديدة', 'edtech') : _e('Create Course', 'edtech'); ?></a>
    </div>

    <!-- Performance Metric Cards -->
    <div class="grid grid-4 reveal" style="margin-bottom:var(--space-xl);">
      <div class="stat-card">
        <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e('إجمالي الإيرادات', 'edtech') : _e('Total Revenue', 'edtech'); ?></span>
        <div class="stat-value">$<?php echo esc_html( number_format( $total_revenue, 0 ) ); ?></div>
      </div>
      <div class="stat-card">
        <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e('إجمالي الطلاب', 'edtech') : _e('Total Students', 'edtech'); ?></span>
        <div class="stat-value"><?php echo esc_html( number_format( $total_students ) ); ?></div>
      </div>
      <div class="stat-card">
        <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e('متوسط التقييم', 'edtech') : _e('Average Rating', 'edtech'); ?></span>
        <div class="stat-value">★ <?php echo esc_html( $avg_rating ); ?></div>
      </div>
      <div class="stat-card">
        <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e('عدد الدورات', 'edtech') : _e('Total Courses', 'edtech'); ?></span>
        <div class="stat-value"><?php echo esc_html( count( $my_courses ) ); ?></div>
      </div>
    </div>

    <!-- Course List -->
    <div class="card reveal">
      <h2 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('دوراتي', 'edtech') : _e('My Courses', 'edtech'); ?></h2>
      <?php if ( ! empty( $my_courses ) ) : ?>
        <div style="display:grid;gap:var(--space-sm);">
          <?php foreach ( $my_courses as $c ) :
            $meta = edtech_get_course_meta( $c->ID );
            $status_label = 'draft' === $c->post_status ? ( is_rtl() ? 'مسودة' : 'Draft' ) : ( is_rtl() ? 'منشورة' : 'Published' );
          ?>
            <div style="display:flex;flex-wrap:wrap;gap:var(--space-sm);align-items:center;padding:var(--space-sm);background:var(--color-bg-subtle);border-radius:var(--radius-md);">
              <div style="flex-grow:1;">
                <h4 style="font-size:14px;margin-bottom:2px;"><?php echo esc_html( $c->post_title ); ?></h4>
                <span style="font-size:12px;color:var(--color-text-muted);"><?php echo esc_html( $status_label ); ?> · $<?php echo esc_html( $meta['price'] ); ?></span>
              </div>
              <a href="<?php echo esc_url( get_edit_post_link( $c->ID, 'raw' ) ); ?>" class="btn btn-secondary" style="font-size:12px;padding:6px 12px;"><?php is_rtl() ? _e('تعديل', 'edtech') : _e('Edit', 'edtech'); ?></a>
              <a href="<?php echo esc_url( get_permalink( $c->ID ) ); ?>" class="btn btn-ghost" style="font-size:12px;padding:6px 12px;"><?php is_rtl() ? _e('عرض', 'edtech') : _e('View', 'edtech'); ?></a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p style="color:var(--color-text-muted);text-align:center;padding:var(--space-md);"><?php is_rtl() ? _e('لم تنشئ أي دورة بعد.', 'edtech') : _e('You haven\'t created any courses yet.', 'edtech'); ?></p>
      <?php endif; ?>
    </div>

    <?php endif; ?>
  </div>
</section>
</main>

<?php
get_footer();
