<?php
/**
 * The template for displaying archive pages
 *
 * @package EdTech
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="container section-padding">
    <header class="page-header reveal" style="margin-bottom:var(--space-xl);">
      <?php
      the_archive_title( '<h1 class="page-title">', '</h1>' );
      the_archive_description( '<div class="archive-description" style="color:var(--color-text-muted);margin-top:var(--space-xs);">', '</div>' );
      ?>
    </header>

    <div class="grid grid-3">
      <?php
      if ( have_posts() ) :
        while ( have_posts() ) : the_post();
          if ( 'course' === get_post_type() ) {
            get_template_part( 'template-parts/content-course-card' );
          } else {
            get_template_part( 'template-parts/content-post-card' );
          }
        endwhile;
      else :
        ?>
        <p><?php is_rtl() ? _e('لا توجد عناصر في هذا الأرشيف.', 'edtech') : _e('No posts found in this archive.', 'edtech'); ?></p>
        <?php
      endif;
      ?>
    </div>
  </div>
</main>

<?php
get_footer();
