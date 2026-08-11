<?php
/**
 * Template Name: FAQ & Support Center Page
 *
 * Maps 1-to-1 to faq.html and ar/faq.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<!-- FAQ Search Hero -->
<section class="section-padding" style="background:linear-gradient(145deg,var(--color-bg-main) 0%,var(--color-bg-subtle) 100%);">
  <div class="container container-sm" style="text-align:center;">
    <div class="reveal">
      <h1 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('كيف يمكننا مساعدتك اليوم؟', 'edtech') : _e('How Can We Help You?', 'edtech'); ?></h1>
      <div class="input-search-wrapper" style="max-width:480px;margin-inline:auto;">
        <svg class="input-search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="search" class="input-field input-lg input-pill search-input" placeholder="<?php is_rtl() ? _e('كيف أحصل على شهادة الإتمام؟', 'edtech') : _e('How do I get my certificate?', 'edtech'); ?>" id="faq-search">
      </div>
    </div>
  </div>
</section>

<!-- FAQ Accordion -->
<section class="section-padding-sm">
  <div class="container container-md">
    <div class="accordion-group">
      <?php
      $faqs = new WP_Query( array(
        'post_type'      => 'faq',
        'posts_per_page' => 20,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
      ) );
      if ( $faqs->have_posts() ) :
        $first = true;
        while ( $faqs->have_posts() ) : $faqs->the_post(); ?>
          <div class="accordion-item<?php echo $first ? ' active' : ''; ?>">
            <button class="accordion-trigger" aria-expanded="<?php echo $first ? 'true' : 'false'; ?>">
              <?php the_title(); ?>
              <svg class="accordion-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="accordion-body">
              <?php the_content(); ?>
            </div>
          </div>
          <?php
          $first = false;
        endwhile;
        wp_reset_postdata();
      else : ?>
        <p style="color:var(--color-text-muted);text-align:center;"><?php is_rtl() ? _e('لا توجد أسئلة شائعة بعد. أضفها من لوحة التحكم.', 'edtech') : _e('No FAQ items yet. Add them from the admin.', 'edtech'); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
