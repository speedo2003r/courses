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
<!-- Masterclass Header -->
<section style="background:var(--color-bg-dark);padding-block:var(--space-xl);">
  <div class="container">
    <div class="reveal">
      <span class="badge badge-free" style="margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('مجاني 100% — لا يتطلب تسجيلاً', 'edtech') : _e('100% Free — No Credit Card Required', 'edtech'); ?></span>
      <h1 style="color:white;font-size:var(--font-size-h1);margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('ماستر كلاس مجاني: أساسيات React 19', 'edtech') : _e('Free Masterclass: React 19 Architecture', 'edtech'); ?></h1>
      <div style="display:flex;align-items:center;gap:var(--space-md);flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:var(--space-sm);">
          <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&auto=format&fit=crop&q=80" alt="" style="width:36px;height:36px;border-radius:50%;object-fit:cover;" loading="lazy">
          <span style="color:rgba(255,255,255,0.8);font-size:14px;"><?php is_rtl() ? _e('م. طارق منصور · مهندس Full-Stack أول', 'edtech') : _e('Eng. Tariq Mansour · Senior Full-Stack Architect', 'edtech'); ?></span>
        </div>
        <span style="color:rgba(255,255,255,0.6);font-size:14px;"><?php is_rtl() ? _e('⏱ 45 دقيقة · فيديو HD', 'edtech') : _e('⏱ 45 mins · Full HD Video', 'edtech'); ?></span>
      </div>
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
          <img src="https://images.unsplash.com/photo-1593720213428-28a5b9e94613?w=1200&auto=format&fit=crop&q=80" alt="" style="width:100%;height:100%;object-fit:cover;" loading="eager">
          <div class="video-play-overlay" role="button" tabindex="0">
            <div class="video-play-btn" style="width:80px;height:80px;">
              <svg width="30" height="30" viewBox="0 0 24 24" fill="var(--color-primary)"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </div>
          </div>
        </div>

        <div class="card reveal" style="margin-bottom:var(--space-md);">
          <div class="tabs">
            <button class="tab-trigger active" data-panel="tab-overview"><?php is_rtl() ? _e('نظرة عامة', 'edtech') : _e('Overview', 'edtech'); ?></button>
            <button class="tab-trigger" data-panel="tab-timestamps"><?php is_rtl() ? _e('الطوابع الزمنية', 'edtech') : _e('Timestamps', 'edtech'); ?></button>
          </div>
          <div data-tabs-container>
            <div id="tab-overview" class="tab-panel active">
              <p style="font-size:14px;color:var(--color-text-body);"><?php is_rtl() ? _e('في هذا الماستر كلاس المجاني المدعوم بالتطبيق، يأخذك المهندس طارق منصور عبر أساسيات React 19.', 'edtech') : _e('In this free 45-minute hands-on masterclass, Eng. Tariq Mansour walks you through React 19 fundamentals.', 'edtech'); ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Resource Sidebar -->
      <aside>
        <div class="card" style="background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-secondary) 100%);border-color:transparent;">
          <h3 style="color:white;margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('واصل التعلم', 'edtech') : _e('Continue Learning', 'edtech'); ?></h3>
          <p style="color:rgba(255,255,255,0.85);font-size:14px;margin-bottom:var(--space-md);"><?php is_rtl() ? _e('احصل على 28 درساً مقابل 49$ فقط.', 'edtech') : _e('Get all 28 lessons for just $49.', 'edtech'); ?></p>
          <a href="<?php echo esc_url( home_url('/checkout') ); ?>" class="btn" style="width:100%;background:white;color:var(--color-primary);font-weight:700;"><?php is_rtl() ? _e('اشترك بالدورة الكاملة — 49$', 'edtech') : _e('Enroll in Full Course — $49', 'edtech'); ?></a>
        </div>
      </aside>

    </div>
  </div>
</section>
</main>

<?php
get_footer();
