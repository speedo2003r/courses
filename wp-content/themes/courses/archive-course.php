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
      
      <!-- Chip bar -->
      <div style="display:flex;gap:var(--space-xs);flex-wrap:wrap;margin-bottom:var(--space-md);">
        <button class="chip filter-chip" data-category="Development"><?php is_rtl() ? _e('تطوير الويب', 'edtech') : _e('Web Development', 'edtech'); ?></button>
        <button class="chip filter-chip" data-category="Design"><?php is_rtl() ? _e('تصميم UI/UX', 'edtech') : _e('UI/UX Design', 'edtech'); ?></button>
        <button class="chip filter-chip" data-category="Data Science"><?php is_rtl() ? _e('علم البيانات', 'edtech') : _e('Data Science', 'edtech'); ?></button>
        <button class="chip filter-chip" data-category="Marketing"><?php is_rtl() ? _e('التسويق الرقمي', 'edtech') : _e('Digital Marketing', 'edtech'); ?></button>
        <button class="chip filter-chip" data-category="Business"><?php is_rtl() ? _e('الأعمال', 'edtech') : _e('Business', 'edtech'); ?></button>
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
          <label style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:14px;cursor:pointer;"><input type="checkbox" value="Development"> <?php is_rtl() ? _e('تطوير الويب', 'edtech') : _e('Web Development', 'edtech'); ?></label>
          <label style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:14px;cursor:pointer;"><input type="checkbox" value="Design"> <?php is_rtl() ? _e('تصميم UI/UX', 'edtech') : _e('UI/UX Design', 'edtech'); ?></label>
          <label style="display:flex;align-items:center;gap:8px;padding:4px 0;font-size:14px;cursor:pointer;"><input type="checkbox" value="Data Science"> <?php is_rtl() ? _e('علم البيانات', 'edtech') : _e('Data Science', 'edtech'); ?></label>
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
          else :
            // Fallback sample render matching static HTML
            for ( $i = 1; $i <= 6; $i++ ) :
              get_template_part( 'template-parts/content-course-card' );
            endfor;
          endif;
          ?>
        </div>
      </div>

    </div>
  </div>
</section>
</main>

<?php
get_footer();
