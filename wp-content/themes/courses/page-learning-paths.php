<?php
/**
 * Template Name: Learning Paths Page
 *
 * Maps 1-to-1 to learning-paths.html and ar/learning-paths.html
 *
 * @package EdTech
 */

get_header();

// Gather total weeks across all paths for the estimate calculation.
$all_paths  = get_posts( array( 'post_type' => 'learning_path', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
$total_weeks = 0;
foreach ( $all_paths as $p ) {
	$w = (int) get_post_meta( $p->ID, '_path_weeks', true );
	if ( $w ) {
		$total_weeks += $w;
	}
}
// Fallback if no path meta is set yet.
if ( ! $total_weeks ) {
	$total_weeks = 16;
}
$is_rtl = is_rtl();
?>

<main>
<!-- Path Hero -->
<section class="section-padding" style="background:linear-gradient(145deg,var(--color-bg-dark) 0%,hsl(222,47%,14%) 100%);position:relative;overflow:hidden;">
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <div class="reveal">
      <span class="badge badge-new" style="margin-bottom:var(--space-md);"><?php $is_rtl ? _e('مسارات مهنية منظمة', 'edtech') : _e('Structured Career Tracks', 'edtech'); ?></span>
      <h1 style="color:white;font-size:var(--font-size-display);margin-bottom:var(--space-md);"><?php $is_rtl ? _e('مسارات التعلم المهنية', 'edtech') : _e('Professional Learning Paths', 'edtech'); ?></h1>
      <p style="color:rgba(255,255,255,0.75);font-size:var(--font-size-body-lg);max-width:600px;margin-inline:auto;margin-bottom:var(--space-xl);">
        <?php $is_rtl ? _e('اتبع مساراتنا المهنية المنسقة والمصممة لتأهيلك لسوق العمل بسرعة وفاعلية.', 'edtech') : _e('Follow curated multi-course career tracks and arrive at your destination faster.', 'edtech'); ?>
      </p>
      <div style="max-width:400px;margin-inline:auto;background:rgba(255,255,255,0.1);padding:var(--space-md);border-radius:var(--radius-lg);border:1px solid rgba(255,255,255,0.15);">
        <label for="commitment-slider" style="display:block;color:rgba(255,255,255,0.8);font-size:14px;margin-bottom:var(--space-xs);">
          <?php $is_rtl ? _e('ساعات الدراسة الأسبوعية:', 'edtech') : _e('Weekly Study Hours:', 'edtech'); ?> <strong id="commitment-val" style="color:white;"><?php echo $is_rtl ? '10 ساعات/أسبوع' : '10 hrs/wk'; ?></strong>
        </label>
        <input type="range" id="commitment-slider" min="5" max="20" value="10" style="width:100%;accent-color:var(--color-accent);">
        <p id="completion-estimate" style="color:var(--color-accent);font-size:14px;margin-top:var(--space-xs);font-weight:600;"></p>
      </div>
    </div>
  </div>
</section>

<!-- Skill Tree -->
<section class="section-padding">
  <div class="container container-md">
    <?php
    $paths = new WP_Query( array( 'post_type' => 'learning_path', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
    if ( $paths->have_posts() ) :
      $phase = 1;
      while ( $paths->have_posts() ) : $paths->the_post();
        $weeks   = get_post_meta( get_the_ID(), '_path_weeks', true );
        $pcourses= get_post_meta( get_the_ID(), '_path_courses', true );
        $badge   = get_post_meta( get_the_ID(), '_path_badge', true );
        $badge_class = $badge ? 'badge-' . strtolower( $badge ) : 'badge-free';
        ?>
        <div class="reveal" style="margin-bottom:var(--space-lg);">
          <h2><?php the_title(); ?></h2>
          <p style="color:var(--color-text-muted);margin-top:var(--space-xs);"><?php echo esc_html( sprintf( '%s %s · %s', $pcourses, ( is_rtl() ? 'دورات' : 'Courses' ), ( $weeks ? $weeks . ' ' . ( is_rtl() ? 'أسابيع' : 'Weeks' ) : '' ) ) ); ?></p>
        </div>
        <div class="skill-tree reveal">
          <div class="skill-node">
            <div class="skill-node-card" style="display:flex;align-items:center;justify-content:space-between;">
              <div>
                <span class="badge <?php echo esc_attr( $badge_class ); ?>" style="margin-bottom:4px;"><?php printf( esc_html( is_rtl() ? 'المرحلة %d' : 'Phase %d' ), $phase ); ?></span>
                <h3 style="margin:0;font-size:var(--font-size-h4);"><?php the_title(); ?></h3>
                <p style="font-size:13px;color:var(--color-text-muted);margin-top:4px;"><?php echo esc_html( sprintf( '%s %s · %s %s', $pcourses, ( is_rtl() ? 'دورات' : 'Courses' ), $weeks, ( is_rtl() ? 'أسابيع' : 'Weeks' ) ) ); ?></p>
              </div>
              <a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-secondary" style="flex-shrink:0;"><?php is_rtl() ? _e('ابدأ المرحلة ←', 'edtech') : _e('Start Phase →', 'edtech'); ?></a>
            </div>
          </div>
        </div>
        <?php
        $phase++;
      endwhile;
      wp_reset_postdata();
    else : ?>
      <p style="color:var(--color-text-muted);text-align:center;"><?php is_rtl() ? _e('لا توجد مسارات تعلم بعد. أضفها من لوحة التحكم.', 'edtech') : _e('No learning paths yet. Add them from the admin.', 'edtech'); ?></p>
    <?php endif; ?>
  </div>
</section>
</main>

<script>
(function() {
  'use strict';

  var slider   = document.getElementById('commitment-slider');
  var valLabel = document.getElementById('commitment-val');
  var estimate = document.getElementById('completion-estimate');
  if (!slider || !estimate) return;

  var totalWeeks = <?php echo (int) $total_weeks; ?>;
  var isRtl      = <?php echo $is_rtl ? 'true' : 'false'; ?>;

  function formatMonths(months) {
    var rounded = Math.max(1, Math.round(months));
    if (isRtl) {
      return 'المدة المقدّرة: ' + rounded + ' ' + (rounded <= 10 ? 'أشهر' : 'شهر');
    }
    return 'Estimated completion: ' + rounded + (rounded === 1 ? ' month' : ' months');
  }

  function update() {
    var hrs   = parseInt(slider.value, 10);
    // Baseline: 10 hrs/wk completes the path in totalWeeks.
    // More hours → proportionally fewer weeks → convert to months.
    var weeks = Math.ceil(totalWeeks * 10 / hrs);
    var months = weeks / 4.33;

    if (valLabel) {
      valLabel.textContent = isRtl ? hrs + ' ساعات/أسبوع' : hrs + ' hrs/wk';
    }
    estimate.textContent = formatMonths(months);
  }

  slider.addEventListener('input', update);
  update();
})();
</script>

<?php
get_footer();
