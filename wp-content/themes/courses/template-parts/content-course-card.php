<?php
/**
 * Template part for displaying a Course Card
 *
 * @package EdTech
 */

$post_id = get_the_ID();
$meta = edtech_get_course_meta( $post_id );
$thumb_url = edtech_get_post_image( $post_id, 'medium_large' );
$categories = wp_get_post_terms( $post_id, 'course_category', array( 'fields' => 'all' ) );
$category_name = ! empty( $categories ) ? $categories[0]->name : ( is_rtl() ? 'تطوير الويب' : 'Development' );
$category_slug = ! empty( $categories ) ? $categories[0]->slug : '';

// Instructor avatar/name: prefer the course meta, fall back to the first instructor CPT.
$instructor_name = $meta['instructor'];
$instructor_avatar = '';
if ( $instructor_name ) {
	$inst = get_page_by_title( $instructor_name, OBJECT, 'instructor' );
	if ( $inst ) {
		$instructor_avatar = edtech_get_post_image( $inst->ID, 'thumbnail' );
	}
}
if ( ! $instructor_avatar ) {
	$first_inst = get_posts( array( 'post_type' => 'instructor', 'posts_per_page' => 1 ) );
	if ( $first_inst ) {
		$instructor_avatar = edtech_get_post_image( $first_inst[0]->ID, 'thumbnail' );
		if ( ! $instructor_name ) {
			$instructor_name = get_the_title( $first_inst[0]->ID );
		}
	}
}
if ( ! $instructor_name ) {
	$instructor_name = is_rtl() ? 'مدرب خبير' : 'Expert Instructor';
}
?>
<article class="card course-card reveal" data-category="<?php echo esc_attr( $category_slug ); ?>" data-category-name="<?php echo esc_attr( $category_name ); ?>">
  <div class="card-thumbnail">
    <a href="<?php the_permalink(); ?>">
      <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
    </a>
    <?php if ( ! empty( $meta['badge'] ) ) : ?>
      <span class="badge badge-bestseller" style="position:absolute;top:10px;inset-inline-start:10px;"><?php echo esc_html( $meta['badge'] ); ?></span>
    <?php endif; ?>
  </div>
  <div class="card-body">
    <p style="font-size:12px;color:var(--color-text-muted);margin-bottom:4px;font-weight:600;text-transform:uppercase;"><?php echo esc_html( $category_name ); ?></p>
    <h3 style="font-size:16px;line-height:1.35;margin-bottom:6px;"><a href="<?php the_permalink(); ?>" style="color:inherit;"><?php the_title(); ?></a></h3>
    <div class="course-meta">
      <?php if ( $instructor_avatar ) : ?>
        <img src="<?php echo esc_url( $instructor_avatar ); ?>" alt="" style="width:20px;height:20px;border-radius:50%;object-fit:cover;">
      <?php endif; ?>
      <span><?php echo esc_html( $instructor_name ); ?></span> · <span><?php echo esc_html( $meta['lessons_count'] ); ?> <?php is_rtl() ? _e('درس', 'edtech') : _e('lessons', 'edtech'); ?></span> · <span><?php echo esc_html( $meta['duration'] ); ?></span>
    </div>
    <div style="display:flex;align-items:center;gap:4px;margin-bottom:8px;">
      <?php echo edtech_render_stars( $meta['rating'] ); // phpcs:ignore ?>
      <span style="font-size:13px;font-weight:600;color:var(--color-accent);"><?php echo esc_html( $meta['rating'] ); ?></span>
      <span style="font-size:12px;color:var(--color-text-muted);">(<?php echo esc_html( $meta['reviews_count'] ); ?>)</span>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--color-border-subtle);padding-top:10px;">
      <div>
        <span class="course-price">$<?php echo esc_html( $meta['price'] ); ?></span>
        <span class="course-price-orig">$<?php echo esc_html( $meta['price_orig'] ); ?></span>
      </div>
      <a href="<?php echo esc_url( edtech_get_checkout_url( $post_id ) ); ?>" class="btn btn-primary btn-sm"><?php is_rtl() ? _e( 'اشترك', 'edtech' ) : _e( 'Enroll', 'edtech' ); ?></a>
    </div>
  </div>
</article>
