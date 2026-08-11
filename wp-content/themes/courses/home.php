<?php
/**
 * Template Name: Blog Page
 * The Blog / Resources Hub Archive template
 *
 * Maps 1-to-1 to blog.html and ar/blog.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<!-- Editorial Hero -->
<section class="section-padding" style="background:linear-gradient(145deg,var(--color-bg-main) 0%,var(--color-bg-subtle) 100%);">
  <div class="container">
    <div class="reveal">
      <h1 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('مركز المعرفة والموارد التعليمية', 'edtech') : _e('Knowledge Hub & Educational Resources', 'edtech'); ?></h1>
      
      <!-- Category Filter Chips -->
      <div style="display:flex;gap:var(--space-xs);flex-wrap:wrap;margin-bottom:var(--space-lg);" id="blog-category-filters">
        <button class="chip filter-chip active" data-category="all"><?php is_rtl() ? _e('جميع المواضيع', 'edtech') : _e('All Topics', 'edtech'); ?></button>
        <button class="chip filter-chip" data-category="web-development"><?php is_rtl() ? _e('تطوير الويب', 'edtech') : _e('Web Development', 'edtech'); ?></button>
        <button class="chip filter-chip" data-category="design"><?php is_rtl() ? _e('التصميم', 'edtech') : _e('Design', 'edtech'); ?></button>
        <button class="chip filter-chip" data-category="data-science"><?php is_rtl() ? _e('علم البيانات', 'edtech') : _e('Data Science', 'edtech'); ?></button>
        <button class="chip filter-chip" data-category="digital-marketing"><?php is_rtl() ? _e('التسويق الرقمي', 'edtech') : _e('Digital Marketing', 'edtech'); ?></button>
      </div>
    </div>

    <!-- Featured Article -->
    <?php
    $featured_posts = get_posts( array( 'posts_per_page' => 1, 'post_type' => 'post' ) );
    if ( ! empty( $featured_posts ) ) :
      $featured   = $featured_posts[0];
      $feat_id     = $featured->ID;
      $feat_link   = get_permalink( $feat_id );
      $feat_thumb  = get_the_post_thumbnail_url( $feat_id, 'large' ) ?: 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=900&auto=format&fit=crop&q=80';
      $feat_title  = get_the_title( $feat_id );
      $feat_excerpt= get_the_excerpt( $feat_id ) ?: get_post_field( 'post_content', $feat_id );
    ?>
    <div class="card course-card reveal" data-category="web-development development" style="display:grid;grid-template-columns:55fr 45fr;gap:0;padding:0;overflow:hidden;margin-bottom:var(--space-xl);">
      <div style="aspect-ratio:16/9;overflow:hidden;">
        <a href="<?php echo esc_url( $feat_link ); ?>">
          <img src="<?php echo esc_url( $feat_thumb ); ?>" alt="<?php echo esc_attr( $feat_title ); ?>" loading="eager" style="width:100%;height:100%;object-fit:cover;">
        </a>
      </div>
      <div style="padding:var(--space-xl);display:flex;flex-direction:column;justify-content:center;">
        <span class="badge badge-new" style="align-self:flex-start;margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('مميز · تطوير الويب', 'edtech') : _e('Featured · Development', 'edtech'); ?></span>
        <h2 style="margin-bottom:var(--space-md);"><a href="<?php echo esc_url( $feat_link ); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html( $feat_title ); ?></a></h2>
        <p style="color:var(--color-text-muted);margin-bottom:var(--space-md);font-size:14px;"><?php echo esc_html( wp_trim_words( $feat_excerpt, 20 ) ); ?></p>
        <a href="<?php echo esc_url( $feat_link ); ?>" class="btn btn-primary" style="align-self:flex-start;"><?php is_rtl() ? _e('اقرأ المقال ←', 'edtech') : _e('Read Article →', 'edtech'); ?></a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- Article Grid -->
<section class="section-padding-sm">
  <div class="container">
    <div class="grid grid-3" id="blog-grid">
      <?php
      $blog_query = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
      ) );

      if ( $blog_query->have_posts() ) :
        while ( $blog_query->have_posts() ) : $blog_query->the_post();
          $post_id    = get_the_ID();
          $post_thumb = get_the_post_thumbnail_url( $post_id, 'medium_large' ) ?: 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=600&auto=format&fit=crop&q=80';
          $post_cats  = get_the_category();
          $cat_name   = ! empty( $post_cats ) ? $post_cats[0]->name : ( is_rtl() ? 'تطوير الويب' : 'Web Dev' );

          // Generate data-category tag for client-side filtering
          $cat_slugs = array();
          if ( ! empty( $post_cats ) ) {
            foreach ( $post_cats as $c ) {
              $cat_slugs[] = strtolower( $c->slug );
              $cat_slugs[] = strtolower( $c->name );
            }
          }
          $title_lower = strtolower( get_the_title( $post_id ) );
          if ( strpos( $title_lower, 'react' ) !== false || strpos( $title_lower, 'web' ) !== false || strpos( $title_lower, 'ويب' ) !== false ) {
            $cat_slugs[] = 'web-development';
          }
          if ( strpos( $title_lower, 'figma' ) !== false || strpos( $title_lower, 'design' ) !== false || strpos( $title_lower, 'تصميم' ) !== false ) {
            $cat_slugs[] = 'design';
          }
          if ( strpos( $title_lower, 'pandas' ) !== false || strpos( $title_lower, 'polars' ) !== false || strpos( $title_lower, 'data' ) !== false || strpos( $title_lower, 'بيانات' ) !== false ) {
            $cat_slugs[] = 'data-science';
          }
          if ( strpos( $title_lower, 'growth' ) !== false || strpos( $title_lower, 'marketing' ) !== false || strpos( $title_lower, 'تسويق' ) !== false ) {
            $cat_slugs[] = 'digital-marketing';
          }
          if ( empty( $cat_slugs ) ) {
            $cat_slugs[] = 'web-development';
          }
          $data_cat_attr = esc_attr( implode( ' ', array_unique( $cat_slugs ) ) );
      ?>
          <article class="card course-card reveal" data-category="<?php echo $data_cat_attr; ?>" style="display:flex;flex-direction:column;height:100%;">
            <div class="card-thumbnail" style="aspect-ratio:16/9;overflow:hidden;">
              <a href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url( $post_thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
              </a>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;flex-grow:1;padding:var(--space-md);">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-xs);">
                <span class="badge badge-free" style="font-size:11px;"><?php echo esc_html( $cat_name ); ?></span>
                <span style="font-size:12px;color:var(--color-text-muted);"><?php echo esc_html( get_the_date() ); ?></span>
              </div>
              <h3 style="font-size:16px;line-height:1.35;margin-bottom:var(--space-xs);">
                <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
              </h3>
              <p style="font-size:13px;color:var(--color-text-muted);margin-bottom:var(--space-md);flex-grow:1;">
                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?>
              </p>
              <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--color-border);padding-top:var(--space-xs);margin-top:auto;">
                <span style="font-size:12px;color:var(--color-text-muted);font-weight:500;">👤 <?php the_author(); ?></span>
                <a href="<?php the_permalink(); ?>" style="font-size:13px;font-weight:700;color:var(--color-accent);text-decoration:none;"><?php is_rtl() ? _e('اقرأ المزيد ←', 'edtech') : _e('Read More →', 'edtech'); ?></a>
              </div>
            </div>
          </article>
      <?php
        endwhile;
        wp_reset_postdata();
      else :
      ?>
        <p><?php is_rtl() ? _e('لا توجد مقالات متوفرة حالياً.', 'edtech') : _e('No posts available currently.', 'edtech'); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
