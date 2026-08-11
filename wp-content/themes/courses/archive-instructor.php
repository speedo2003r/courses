<?php
/**
 * The Instructors Directory template
 *
 * Maps 1-to-1 to instructors.html and ar/instructors.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<!-- Hero -->
<section class="section-padding" style="background:linear-gradient(145deg,var(--color-bg-main) 0%,var(--color-bg-subtle) 100%);">
  <div class="container" style="text-align:center;">
    <div class="reveal">
      <h1><?php is_rtl() ? _e('تعلّم من خبراء الصناعة المعتمدين', 'edtech') : _e('Learn From Verified Industry Experts', 'edtech'); ?></h1>
      <p style="color:var(--color-text-muted);max-width:580px;margin-inline:auto;margin-top:var(--space-sm);"><?php is_rtl() ? _e('جميع المدربين لديهم خبرة عملية حقيقية. اضغط على أزرار الموجات الصوتية للاستماع لمقدمة صوتية.', 'edtech') : _e('All instructors have real-world experience. Tap the microphone to hear a 30-second voice greeting.', 'edtech'); ?></p>
    </div>
  </div>
</section>

<!-- Impact Stats Bar -->
<section style="background:var(--color-primary);padding-block:var(--space-lg);">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:var(--space-md);text-align:center;">
      <div><div style="font-size:1.75rem;font-weight:800;color:white;" data-target="45000">0</div><div style="font-size:13px;color:rgba(255,255,255,0.8);"><?php is_rtl() ? _e('طالب نشط', 'edtech') : _e('Active Students', 'edtech'); ?></div></div>
      <div><div style="font-size:1.75rem;font-weight:800;color:white;">★ 4.9</div><div style="font-size:13px;color:rgba(255,255,255,0.8);"><?php is_rtl() ? _e('متوسط التقييم', 'edtech') : _e('Average Rating', 'edtech'); ?></div></div>
      <div><div style="font-size:1.75rem;font-weight:800;color:white;" data-target="4">0</div><div style="font-size:13px;color:rgba(255,255,255,0.8);"><?php is_rtl() ? _e('مدربين خبراء', 'edtech') : _e('Expert Instructors', 'edtech'); ?></div></div>
      <div><div style="font-size:1.75rem;font-weight:800;color:white;" data-target="16">0</div><div style="font-size:13px;color:rgba(255,255,255,0.8);"><?php is_rtl() ? _e('دورة متميزة', 'edtech') : _e('Premium Courses', 'edtech'); ?></div></div>
    </div>
  </div>
</section>

<!-- Instructor Grid -->
<section class="section-padding">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:var(--space-xl);">
      <?php
      $instructors = array(
        array( 'name' => is_rtl() ? 'م. طارق منصور' : 'Eng. Tariq Mansour', 'title' => is_rtl() ? 'مهندس Full-Stack أول' : 'Senior Full-Stack Architect', 'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80', 'audio' => 'assets/media/audio/tariq-intro.mp3', 'stats' => '★ 4.9 · 1,240' ),
        array( 'name' => is_rtl() ? 'سارة الراشد' : 'Sarah Al-Rashid', 'title' => is_rtl() ? 'مصممة UI/UX رائدة' : 'Lead UI/UX Designer', 'img' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&auto=format&fit=crop&q=80', 'audio' => 'assets/media/audio/sarah-intro.mp3', 'stats' => '★ 4.8 · 890' ),
        array( 'name' => is_rtl() ? 'د. عمر فاروق' : 'Dr. Omar Farooq', 'title' => is_rtl() ? 'خبير علم البيانات' : 'Data Science Specialist', 'img' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&auto=format&fit=crop&q=80', 'audio' => 'assets/media/audio/omar-intro.mp3', 'stats' => '★ 4.9 · 2,100' ),
        array( 'name' => is_rtl() ? 'ليلى حسن' : 'Layla Hassan', 'title' => is_rtl() ? 'استراتيجية النمو الرقمي' : 'Digital Growth Strategist', 'img' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&auto=format&fit=crop&q=80', 'audio' => 'assets/media/audio/layla-intro.mp3', 'stats' => '★ 4.7 · 680' ),
      );
      foreach ( $instructors as $inst ) {
        get_template_part( 'template-parts/content-instructor-card', null, $inst );
      }
      ?>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
