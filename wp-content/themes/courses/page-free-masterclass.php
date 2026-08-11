<?php
/**
 * Template Name: Free Masterclass Page
 *
 * Maps 1-to-1 to free-masterclass.html and ar/free-masterclass.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<?php while ( have_posts() ) : the_post(); ?>

<!-- Masterclass Header -->
<section style="background:var(--color-bg-dark);padding-block:var(--space-xl);">
  <div class="container">
    <div class="reveal">
      <span class="badge badge-free" style="margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('مجاني 100% — لا يتطلب تسجيلاً', 'edtech') : _e('100% Free — No Credit Card Required', 'edtech'); ?></span>
      <h1 style="color:white;font-size:var(--font-size-h1);margin-bottom:var(--space-sm);"><?php the_title(); ?></h1>
      <?php if ( has_excerpt() ) : ?>
        <p style="color:rgba(255,255,255,0.8);font-size:var(--font-size-body-lg);max-width:640px;"><?php echo esc_html( get_the_excerpt() ); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Content -->
<section class="section-padding-sm">
  <div class="container">
    <div style="display:grid;grid-template-columns:70fr 30fr;gap:var(--space-xl);align-items:start;">

      <!-- Video Player -->
      <div>
        <div class="video-container reveal" data-pip-trigger style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--elevation-hover);margin-bottom:var(--space-lg);">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:100%;object-fit:cover;', 'loading' => 'eager' ) ); ?>
          <?php else : ?>
            <img src="https://images.unsplash.com/photo-1593720213428-28a5b9e94613?w=1200&auto=format&fit=crop&q=80" alt="" style="width:100%;height:100%;object-fit:cover;" loading="eager">
          <?php endif; ?>
          <div class="video-play-overlay" role="button" tabindex="0">
            <div class="video-play-btn" style="width:80px;height:80px;">
              <svg width="30" height="30" viewBox="0 0 24 24" fill="var(--color-primary)"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
          </div>
        </div>

        <div class="card reveal" style="margin-bottom:var(--space-md);">
          <div class="tabs">
            <button class="tab-trigger active" data-panel="tab-overview"><?php is_rtl() ? _e('نظرة عامة', 'edtech') : _e('Overview', 'edtech'); ?></button>
          </div>
          <div data-tabs-container>
            <div id="tab-overview" class="tab-panel active">
              <?php the_content(); ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Resource Sidebar -->
      <aside>
        <div class="card" style="background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-secondary) 100%);border-color:transparent;">
          <h3 style="color:white;margin-bottom:var(--space-sm);"><?php echo esc_html( edtech_get_site_setting( 'enroll_banner_title', is_rtl() ? 'واصل التعلم' : 'Continue Learning' ) ); ?></h3>
          <p style="color:rgba(255,255,255,0.85);font-size:14px;margin-bottom:var(--space-md);"><?php echo esc_html( edtech_get_site_setting( 'enroll_banner_text', is_rtl() ? 'استكشف دوراتنا الكاملة.' : 'Explore our full courses.' ) ); ?></p>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'course' ) ); ?>" class="btn" style="width:100%;background:white;color:var(--color-primary);font-weight:700;"><?php is_rtl() ? _e('تصفح الدورات ←', 'edtech') : _e('Browse Courses →', 'edtech'); ?></a>
        </div>
      </aside>

    </div>
  </div>
</section>

<?php endwhile; ?>
</main>

<?php
get_footer();
