<?php
/**
 * The template for displaying all pages
 *
 * @package EdTech
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="container section-padding">
    <?php
    while ( have_posts() ) :
      the_post();
      ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'card reveal' ); ?> style="padding:var(--space-xl);">
        <h1 class="entry-title" style="margin-bottom:var(--space-md);"><?php the_title(); ?></h1>
        <div class="entry-content" style="line-height:1.75;">
          <?php the_content(); ?>
        </div>
      </article>
      <?php
    endwhile;
    ?>
  </div>
</main>

<?php
get_footer();
