<?php
/**
 * The template for displaying a single Instructor.
 *
 * @package EdTech
 */

get_header();

while ( have_posts() ) : the_post();
	$post_id  = get_the_ID();
	$title    = get_post_meta( $post_id, '_instructor_title', true );
	$audio    = get_post_meta( $post_id, '_instructor_audio_url', true );
	$rating   = get_post_meta( $post_id, '_instructor_rating', true ) ?: '4.9';
	$students = get_post_meta( $post_id, '_instructor_students', true );
	$img      = edtech_get_post_image( $post_id, 'large' );

	$audio_src = '';
	if ( $audio ) {
		$audio_src = preg_match( '#^https?://#i', $audio ) ? $audio : get_template_directory_uri() . '/' . ltrim( $audio, '/' );
	}
	?>
	<main>
	<!-- Instructor Hero -->
	<section class="section-padding" style="background:linear-gradient(145deg,var(--color-bg-main) 0%,var(--color-bg-subtle) 100%);">
	  <div class="container">
	    <div style="display:flex;align-items:center;gap:var(--space-xl);flex-wrap:wrap;">
	      <img src="<?php echo esc_url( $img ); ?>" alt="<?php the_title_attribute(); ?>" style="width:160px;height:160px;border-radius:50%;object-fit:cover;border:4px solid var(--color-primary);">
	      <div class="reveal">
	        <p style="color:var(--color-text-muted);font-size:13px;margin-bottom:4px;">
	          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;text-decoration:none;"><?php is_rtl() ? _e('الرئيسية', 'edtech') : _e('Home', 'edtech'); ?></a> &rsaquo;
	          <a href="<?php echo esc_url( get_post_type_archive_link( 'instructor' ) ); ?>" style="color:inherit;text-decoration:none;"><?php is_rtl() ? _e('المدربون', 'edtech') : _e('Instructors', 'edtech'); ?></a>
	        </p>
	        <h1 style="margin-bottom:var(--space-xs);"><?php the_title(); ?></h1>
	        <?php if ( $title ) : ?><p style="color:var(--color-primary);font-weight:600;font-size:1.125rem;margin-bottom:var(--space-sm);"><?php echo esc_html( $title ); ?></p><?php endif; ?>
	        <p style="font-size:14px;color:var(--color-text-muted);margin-bottom:var(--space-md);">★ <?php echo esc_html( $rating ); ?><?php if ( $students ) : ?> · <?php echo esc_html( number_format_i18n( (float) $students ) ); ?> <?php is_rtl() ? _e('طالب', 'edtech') : _e('Students', 'edtech'); ?><?php endif; ?></p>
	        <?php if ( $audio_src ) : ?>
	          <button class="audio-btn" data-audio="<?php echo esc_url( $audio_src ); ?>" aria-label="<?php esc_attr_e('Play voice intro', 'edtech'); ?>">
	            <div class="audio-bars" aria-hidden="true"><div class="audio-bar"></div><div class="audio-bar"></div><div class="audio-bar"></div><div class="audio-bar"></div><div class="audio-bar"></div></div>
	            <span><?php is_rtl() ? _e('مقدمة صوتية', 'edtech') : _e('Voice Intro', 'edtech'); ?></span>
	          </button>
	        <?php endif; ?>
	      </div>
	    </div>
	  </div>
	</section>

	<!-- Instructor Bio -->
	<section class="section-padding-sm">
	  <div class="container" style="max-width:760px;">
	    <div class="card reveal" style="padding:var(--space-xl);">
	      <?php the_content(); ?>
	    </div>
	  </div>
	</section>

	<!-- Courses by this instructor -->
	<?php
	$inst_courses = get_posts( array(
		'post_type'      => 'course',
		'posts_per_page' => 3,
		'meta_key'       => '_course_instructor',
		'meta_value'     => get_the_title(),
	) );
	if ( $inst_courses ) : ?>
	<section class="section-padding-sm" style="background:var(--color-bg-subtle);">
	  <div class="container">
	    <h2 style="margin-bottom:var(--space-lg);"><?php is_rtl() ? _e('دورات المدرب', 'edtech') : _e('Courses by this Instructor', 'edtech'); ?></h2>
	    <div class="grid grid-3">
	      <?php foreach ( $inst_courses as $c ) : setup_postdata( $c ); ?>
	        <?php get_template_part( 'template-parts/content-course-card' ); ?>
	      <?php endforeach; wp_reset_postdata(); ?>
	    </div>
	  </div>
	</section>
	<?php endif; ?>
	</main>
	<?php
endwhile;

get_footer();
