<?php
/**
 * Template part for displaying a Course Card
 *
 * @package EdTech
 */

$post_id = get_the_ID();
$meta = edtech_get_course_meta( $post_id );
$thumb_url = get_the_post_thumbnail_url( $post_id, 'medium_large' ) ?: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80';
$categories = wp_get_post_terms( $post_id, 'course_category', array( 'fields' => 'names' ) );
$category_name = ! empty( $categories ) ? $categories[0] : ( is_rtl() ? 'تطوير الويب' : 'Development' );
?>
<article class="card course-card reveal" data-category="<?php echo esc_attr( $category_name ); ?>">
  <div class="card-thumbnail">
    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
    <?php if ( ! empty( $meta['badge'] ) ) : ?>
      <span class="badge badge-bestseller" style="position:absolute;top:10px;inset-inline-start:10px;"><?php echo esc_html( $meta['badge'] ); ?></span>
    <?php endif; ?>
  </div>
  <div class="card-body">
    <p style="font-size:12px;color:var(--color-text-muted);margin-bottom:4px;font-weight:600;text-transform:uppercase;"><?php echo esc_html( $category_name ); ?></p>
    <h3 style="font-size:16px;line-height:1.35;margin-bottom:6px;"><a href="<?php the_permalink(); ?>" style="color:inherit;"><?php the_title(); ?></a></h3>
    <div class="course-meta">
      <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&auto=format&fit=crop&q=80" alt="" style="width:20px;height:20px;border-radius:50%;object-fit:cover;">
      <span><?php echo esc_html( $meta['instructor'] ); ?></span> · <span><?php echo esc_html( $meta['lessons_count'] ); ?> <?php is_rtl() ? _e('درس', 'edtech') : _e('lessons', 'edtech'); ?></span> · <span><?php echo esc_html( $meta['duration'] ); ?></span>
    </div>
    <div style="display:flex;align-items:center;gap:4px;margin-bottom:8px;">
      <?php echo edtech_render_stars( $meta['rating'] ); ?>
      <span style="font-size:13px;font-weight:600;color:var(--color-accent);"><?php echo esc_html( $meta['rating'] ); ?></span>
      <span style="font-size:12px;color:var(--color-text-muted);">(<?php echo esc_html( $meta['reviews_count'] ); ?>)</span>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--color-border-subtle);padding-top:10px;">
      <div>
        <span class="course-price">$<?php echo esc_html( $meta['price'] ); ?></span>
        <span class="course-price-orig">$<?php echo esc_html( $meta['price_orig'] ); ?></span>
      </div>
      <a href="<?php echo esc_url( home_url( '/checkout' ) ); ?>" class="btn btn-primary btn-sm"><?php is_rtl() ? _e( 'اشترك', 'edtech' ) : _e( 'Enroll', 'edtech' ); ?></a>
    </div>
  </div>
</article>
