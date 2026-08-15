<?php
/**
 * The template for displaying a single Learning Path.
 *
 * @package EdTech
 */

get_header();

while ( have_posts() ) : the_post();
	$post_id  = get_the_ID();
	$weeks    = get_post_meta( $post_id, '_path_weeks', true );
	$pcourses = get_post_meta( $post_id, '_path_courses', true );
	$badge    = edtech_get_bilingual_meta( $post_id, '_path_badge' );
	$img      = edtech_get_post_image( $post_id, 'large' );
	?>
	<main>
	<section style="position:relative;overflow:hidden;background:var(--color-bg-dark);min-height:300px;display:flex;align-items:center;">
	  <img src="<?php echo esc_url( $img ); ?>" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.25;">
	  <div class="container" style="position:relative;z-index:1;padding-block:var(--space-3xl);">
	    <p style="font-size:13px;color:rgba(255,255,255,0.6);margin-bottom:var(--space-sm);">
	      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;text-decoration:none;"><?php wpm_is_rtl() ? _e('الرئيسية', 'edtech') : _e('Home', 'edtech'); ?></a> &rsaquo;
	      <a href="<?php echo esc_url( edtech_page_url( 'learning-paths' ) ); ?>" style="color:inherit;text-decoration:none;"><?php wpm_is_rtl() ? _e('مسارات التعلم', 'edtech') : _e('Learning Paths', 'edtech'); ?></a>
	    </p>
	    <?php if ( $badge ) : ?><span class="badge badge-bestseller" style="margin-bottom:var(--space-sm);"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
	    <h1 style="color:white;max-width:680px;margin-bottom:var(--space-sm);"><?php the_title(); ?></h1>
	    <?php if ( $weeks || $pcourses ) : ?>
	      <p style="color:rgba(255,255,255,0.8);"><?php echo esc_html( sprintf( '%s %s · %s %s', $weeks, ( wpm_is_rtl() ? 'أسابيع' : 'Weeks' ), $pcourses, ( wpm_is_rtl() ? 'دورات' : 'Courses' ) ) ); ?></p>
	    <?php endif; ?>
	  </div>
	</section>

	<section class="section-padding">
	  <div class="container" style="max-width:820px;">
	    <div class="card reveal" style="padding:var(--space-xl);">
	      <?php the_content(); ?>
	    </div>
	  </div>
	</section>
	</main>
	<?php
endwhile;

get_footer();
