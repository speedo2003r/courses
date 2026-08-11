<?php
/**
 * The Course Catalog Archive template
 *
 * Maps 1-to-1 to catalog.html and ar/catalog.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<!-- Catalog Header -->
<section style="background:linear-gradient(145deg,var(--color-bg-main) 0%,var(--color-bg-subtle) 100%);padding-block:var(--space-2xl);">
  <div class="container">
    <div class="reveal">
      <h1 style="margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('استكشف جميع الدورات التدريبية', 'edtech') : _e('Explore All Online Courses', 'edtech'); ?></h1>
      <p style="color:var(--color-text-muted);margin-bottom:var(--space-lg);"><?php is_rtl() ? _e('تصفح الدورات المتميزة — تصفية حسب المجال والمستوى والوتيرة.', 'edtech') : _e('Browse premium courses — filter by category, level, and pace.', 'edtech'); ?></p>

      <!-- Chip bar (dynamic from course_category taxonomy) -->
      <div style="display:flex;gap:var(--space-xs);flex-wrap:wrap;margin-bottom:var(--space-md);">
        <?php
        $cats = get_terms( array( 'taxonomy' => 'course_category', 'hide_empty' => false ) );
        if ( ! is_wp_error( $cats ) && $cats ) :
          foreach ( $cats as $cat ) : ?>
            <button class="chip filter-chip" data-category="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
          <?php endforeach;
        endif;
        ?>
      </div>
    </div>
  </div>
</section>

<!-- Catalog Layout -->
<section class="section-padding-sm">
  <div class="container">
    <div style="display:grid;grid-template-columns:280px 1fr;gap:var(--space-xl);align-items:start;">

      <!-- Sidebar Filters -->
      <aside class="filter-sidebar" style="position:sticky;top:88px;border:1px solid var(--color-border-subtle);border-radius:var(--radius-lg);background:var(--color-bg-card);padding:var(--space-lg);">
        <div style="margin-bottom:var(--space-lg);">
          <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;color:var(--color-text-muted);margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('المجال', 'edtech') : _e('Category', 'edtech'); ?></h4>
          <?php
          if ( ! is_wp_error( $cats ) && $cats ) :
            foreach ( $cats as $cat ) : ?>
              <label style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:14px;cursor:pointer;"><input type="checkbox" class="filter-cat" value="<?php echo esc_attr( $cat->slug ); ?>"> <?php echo esc_html( $cat->name ); ?></label>
            <?php endforeach;
          endif;
          ?>
        </div>
      </aside>

      <!-- Course Grid -->
      <div>
        <div id="catalog-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:var(--space-lg);">
          <?php
          if ( have_posts() ) :
            while ( have_posts() ) : the_post();
              get_template_part( 'template-parts/content-course-card' );
            endwhile;
          else : ?>
            <p style="grid-column:1/-1;text-align:center;color:var(--color-text-muted);padding:var(--space-3xl);"><?php is_rtl() ? _e('لا توجد دورات بعد. أضف دورات من لوحة التحكم.', 'edtech') : _e('No courses yet. Add courses from the admin.', 'edtech'); ?></p>
          <?php endif; ?>
        </div>
        <?php
        the_posts_pagination( array(
          'mid_size'  => 1,
          'prev_text' => is_rtl() ? '→' : '←',
          'next_text' => is_rtl() ? '←' : '→',
        ) );
        ?>
      </div>

    </div>
  </div>
</section>
</main>

<?php
get_footer();
