<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package EdTech
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="container section-padding">
    <?php
    if ( have_posts() ) :
      if ( is_home() && ! is_front_page() ) :
        ?>
        <header>
          <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
        </header>
        <?php
      endif;

      /* Start the Loop */
      while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'card reveal' ); ?> style="margin-bottom:var(--space-lg);padding:var(--space-lg);">
          <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="entry-content" style="margin-top:var(--space-md);">
            <?php the_excerpt(); ?>
          </div>
        </article>
        <?php
      endwhile;

      the_posts_navigation();

    else :
      ?>
      <div class="card" style="padding:var(--space-xl);text-align:center;">
        <h2><?php wpm_is_rtl() ? _e('لم يتم العثور على محتوى', 'edtech') : _e('No Content Found', 'edtech'); ?></h2>
        <p><?php wpm_is_rtl() ? _e('يبدو أنه لا يوجد محتوى يعرض هنا.', 'edtech') : _e('It seems we cannot find what you are looking for.', 'edtech'); ?></p>
      </div>
      <?php
    endif;
    ?>
  </div>
</main>

<?php
get_footer();
