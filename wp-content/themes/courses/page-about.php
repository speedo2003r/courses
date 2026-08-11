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
        <?php is_rtl() ? _e('بنينا المنصة التي تمنينا<br>وجودها عندما بدأنا', 'edtech') : _e('We Built the School We Wished<br>Existed When We Started', 'edtech'); ?>
      </h1>
      <p style="color:rgba(255,255,255,0.8);font-size:var(--font-size-body-lg);max-width:600px;margin-inline:auto;margin-bottom:var(--space-xl);">
        <?php is_rtl() ? _e('انطلقنا من شعور بالاستياء من الدورات السطحية والشعارات الزائفة، لنبني تجربة تعليمية حقيقية وصادقة تركز كلياً على النتائج الوظيفية للمتعلم.', 'edtech') : _e('Frustrated by surface-level tutorials and marketing hype, we built a real, practitioner-led learning platform focused entirely on career outcomes.', 'edtech'); ?>
      </p>
    </div>
  </div>
</section>

<!-- Philosophy Pillars -->
<section class="section-padding">
  <div class="container">
    <div style="text-align:center;max-width:580px;margin-inline:auto;margin-bottom:var(--space-xl);" class="reveal">
      <h2><?php is_rtl() ? _e('فلسفتنا التعليمية', 'edtech') : _e('Our Educational Philosophy', 'edtech'); ?></h2>
    </div>
    <div class="grid grid-4 reveal">
      <div class="card card-hover text-center">
        <div style="font-size:2rem;margin-bottom:var(--space-md);">🎯</div>
        <h3 style="font-size:var(--font-size-h4);margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('التركيز على النتيجة', 'edtech') : _e('Outcome-First', 'edtech'); ?></h3>
        <p style="font-size:14px;color:var(--color-text-muted);"><?php is_rtl() ? _e('صممت كل دورة بالرجوع من الوظيفة المستهدفة.', 'edtech') : _e('Every course is built backwards from actual job outcomes.', 'edtech'); ?></p>
      </div>
      <div class="card card-hover text-center">
        <div style="font-size:2rem;margin-bottom:var(--space-md);">🤝</div>
        <h3 style="font-size:var(--font-size-h4);margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('إرشاد بشري حقيقي', 'edtech') : _e('Human Mentorship', 'edtech'); ?></h3>
        <p style="font-size:14px;color:var(--color-text-muted);"><?php is_rtl() ? _e('مدربون حقيقيون بخبرات موثّقة.', 'edtech') : _e('Real practitioners with verified track records.', 'edtech'); ?></p>
      </div>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
