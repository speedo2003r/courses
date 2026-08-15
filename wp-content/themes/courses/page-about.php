<?php
/**
 * Template Name: About Us Page
 *
 * Maps 1-to-1 to about.html and ar/about.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<!-- Documentary Hero -->
<section style="position:relative;overflow:hidden;background:var(--color-bg-dark);min-height:480px;display:flex;align-items:center;">
  <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1400&auto=format&fit=crop&q=80" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.2;" loading="lazy">
  <div class="container" style="position:relative;z-index:1;text-align:center;padding-block:var(--space-3xl);">
    <div class="reveal">
      <h1 style="color:white;font-size:var(--font-size-display);margin-bottom:var(--space-lg);">
        <?php wpm_is_rtl() ? _e('بنينا المنصة التي تمنينا<br>وجودها عندما بدأنا', 'edtech') : _e('We Built the School We Wished<br>Existed When We Started', 'edtech'); ?>
      </h1>
      <p style="color:rgba(255,255,255,0.8);font-size:var(--font-size-body-lg);max-width:600px;margin-inline:auto;margin-bottom:var(--space-xl);">
        <?php wpm_is_rtl() ? _e('انطلقنا من شعور بالاستياء من الدورات السطحية والشعارات الزائفة، لنبني تجربة تعليمية حقيقية وصادقة تركز كلياً على النتائج الوظيفية للمتعلم.', 'edtech') : _e('Frustrated by surface-level tutorials and marketing hype, we built a real, practitioner-led learning platform focused entirely on career outcomes.', 'edtech'); ?>
      </p>
    </div>
  </div>
</section>

<!-- Philosophy Pillars (page content) -->
<section class="section-padding">
  <div class="container container-md">
    <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
  </div>
</section>

<!-- Team -->
<section class="section-padding" style="background:var(--color-bg-subtle);">
  <div class="container">
    <div style="text-align:center;max-width:580px;margin-inline:auto;margin-bottom:var(--space-xl);" class="reveal">
      <h2><?php wpm_is_rtl() ? _e('فريقنا', 'edtech') : _e('Our Team', 'edtech'); ?></h2>
    </div>
    <div class="grid grid-4 reveal">
      <?php
      $team = new WP_Query( array( 'post_type' => 'team', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
      if ( $team->have_posts() ) :
        while ( $team->have_posts() ) : $team->the_post();
          $role = edtech_get_bilingual_meta( get_the_ID(), '_team_role' );
          $img  = edtech_get_post_image( get_the_ID(), 'medium', 'https://placehold.co/300x300/1f2937/e5e7eb?text=Team' );
          $social = get_post_meta( get_the_ID(), '_team_social', true );
          ?>
          <div class="card text-center">
            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-inline:auto;margin-bottom:var(--space-sm);" loading="lazy">
            <h3 style="font-size:var(--font-size-h4);margin-bottom:var(--space-xs);"><?php echo esc_html( get_the_title() ); ?></h3>
            <p style="font-size:14px;color:var(--color-text-muted);"><?php echo esc_html( $role ); ?></p>
            <?php if ( $social ) : ?>
              <a href="<?php echo esc_url( $social ); ?>" target="_blank" rel="noopener noreferrer" style="font-size:13px;color:var(--color-primary);"><?php wpm_is_rtl() ? _e('تابع ←', 'edtech') : _e('Follow →', 'edtech'); ?></a>
            <?php endif; ?>
          </div>
        <?php endwhile;
        wp_reset_postdata();
      else : ?>
        <p style="color:var(--color-text-muted);text-align:center;grid-column:1/-1;"><?php wpm_is_rtl() ? _e('لا يوجد أعضاء فريق بعد.', 'edtech') : _e('No team members yet.', 'edtech'); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
