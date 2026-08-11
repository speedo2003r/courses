<?php
/**
 * Template part for displaying a Blog Post card (archives / related posts).
 *
 * @package EdTech
 */

$post_id      = get_the_ID();
$thumb        = edtech_get_post_image( $post_id, 'medium_large', 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=600&auto=format&fit=crop&q=80' );
$categories   = get_the_category();
$category_name= ! empty( $categories ) ? $categories[0]->name : ( is_rtl() ? 'تعليم' : 'Tutorial' );
?>
<article class="card course-card reveal">
  <div class="card-thumbnail" style="aspect-ratio:16/9;overflow:hidden;">
    <a href="<?php the_permalink(); ?>">
      <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
    </a>
    <span class="badge badge-free" style="position:absolute;top:10px;inset-inline-start:10px;"><?php echo esc_html( $category_name ); ?></span>
  </div>
  <div class="card-body">
    <h3 style="font-size:16px;line-height:1.35;margin-bottom:var(--space-xs);">
      <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
    </h3>
    <p style="font-size:13px;color:var(--color-text-muted);"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
    <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--color-border-subtle);padding-top:10px;font-size:12px;color:var(--color-text-muted);">
      <span><?php echo esc_html( get_the_date() ); ?></span>
      <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm"><?php is_rtl() ? _e('اقرأ المقال', 'edtech') : _e('Read Article', 'edtech'); ?></a>
    </div>
  </div>
</article>
