<?php
/**
 * Template Name: Front Page / Home
 * The Front Page template (Home / Discovery Hub)
 *
 * Maps 1-to-1 to index.html and ar/index.html
 *
 * @package EdTech
 */

get_header();

// Resolve dynamic stats (counts come from the DB; marketing claims from customizer).
$course_count     = wp_count_posts( 'course' );
$instructor_count = wp_count_posts( 'instructor' );
$stat_courses     = isset( $course_count->publish ) ? (int) $course_count->publish : 0;
$stat_instructors = isset( $instructor_count->publish ) ? (int) $instructor_count->publish : 0;
$stat_students    = edtech_get_site_setting( 'stats_students', '45000' );
$stat_completion  = edtech_get_site_setting( 'stats_completion', '98%' );

// Hero title supports a 3-segment split via "||" so the middle segment keeps the accent color.
$hero_title_raw = edtech_get_site_setting( 'hero_title', is_rtl() ? 'أتقن المهارات الرقمية||الأكثر طلباً|| وابنِ مشاريع حقيقية' : 'Master High-Impact||Digital Skills|| & Build Real Products' );
$hero_title_parts = array_map( 'trim', explode( '||', $hero_title_raw ) );
$hero_eyebrow   = edtech_get_site_setting( 'hero_eyebrow', is_rtl() ? 'تقييم 4.9' : '4.9 Rating' );
$hero_subtitle  = edtech_get_site_setting( 'hero_subtitle', is_rtl() ? '+45,000 طالب نشط' : '45,000+ Active Students' );
$hero_desc      = edtech_get_site_setting( 'hero_description', is_rtl() ? 'توقف عن مشاهدة الشروحات السطحية. تعلّم من خبراء الصناعة المعتمدين، وابنِ مشاريع قوية لمحفظتك.' : 'Stop watching passive tutorials. Learn from verified industry practitioners, build portfolio-grade projects, and launch your digital career.' );
$hero_cta       = edtech_get_site_setting( 'hero_cta_label', is_rtl() ? 'اشترك الآن — 49$' : 'Enroll Now — $49' );
$hero_cta2      = edtech_get_site_setting( 'hero_cta2_label', is_rtl() ? 'شاهد الماستر كلاس المجاني' : 'Watch Free Masterclass' );

$catalog_link   = get_post_type_archive_link( 'course' );
?>

<main id="main-content">

<!-- ===== HERO — Fluid Canvas (60/40 Asymmetric) ===== -->
<section class="section-padding-hero" style="position:relative;overflow:hidden;background:linear-gradient(145deg,hsl(210,20%,98%) 0%,hsl(220,30%,96%) 100%);">
  <div class="hero-glow hero-glow-1" aria-hidden="true"></div>
  <div class="hero-glow hero-glow-2" aria-hidden="true"></div>

  <div class="container" style="position:relative;z-index:1;">
    <div class="split-hero" style="align-items:center;">

      <!-- Left: Copy + Search -->
      <div class="reveal">
        <div style="display:flex;align-items:center;gap:var(--space-sm);margin-bottom:var(--space-md);">
          <span class="badge badge-bestseller">★ <?php echo esc_html( $hero_eyebrow ); ?></span>
          <span style="font-size:13px;color:var(--color-text-muted);"><?php echo esc_html( $hero_subtitle ); ?></span>
        </div>
        <h1 style="font-size:var(--font-size-display);margin-bottom:var(--space-lg);line-height:1.1;letter-spacing:-0.025em;">
          <?php
          if ( count( $hero_title_parts ) >= 3 ) {
            echo esc_html( $hero_title_parts[0] ) . '<br>';
            echo '<span style="color:var(--color-primary);">' . esc_html( $hero_title_parts[1] ) . '</span><br>';
            echo esc_html( $hero_title_parts[2] );
          } else {
            echo esc_html( $hero_title_raw );
          }
          ?>
        </h1>
        <p style="font-size:var(--font-size-body-lg);color:var(--color-text-muted);max-width:520px;margin-bottom:var(--space-xl);line-height:1.65;">
          <?php echo esc_html( $hero_desc ); ?>
        </p>

        <!-- Search Engine -->
        <div class="input-search-wrapper" style="max-width:520px;margin-bottom:var(--space-lg);">
          <svg class="input-search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input
            type="search"
            class="input-field input-lg input-pill search-input"
            placeholder="<?php is_rtl() ? _e('ابحث عن دورات أو مهارات أو مدربين…', 'edtech') : _e('Search courses, skills, or instructors…', 'edtech'); ?>"
            aria-label="<?php esc_attr_e('Search courses', 'edtech'); ?>"
            autocomplete="off"
            id="hero-search"
          >
        </div>

        <!-- Category Chips (dynamic from course_category taxonomy) -->
        <div style="display:flex;gap:var(--space-xs);flex-wrap:wrap;margin-bottom:var(--space-xl);">
          <?php
          $chip_terms = get_terms( array( 'taxonomy' => 'course_category', 'hide_empty' => false, 'number' => 4 ) );
          if ( ! is_wp_error( $chip_terms ) && $chip_terms ) :
            foreach ( $chip_terms as $term ) : ?>
              <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="chip"><?php echo esc_html( $term->name ); ?></a>
            <?php endforeach;
          else : ?>
            <a href="<?php echo esc_url( $catalog_link ); ?>" class="chip"><?php is_rtl() ? _e('تصفح الدورات', 'edtech') : _e('Browse Courses', 'edtech'); ?></a>
          <?php endif; ?>
        </div>

        <div style="display:flex;gap:var(--space-md);align-items:center;flex-wrap:wrap;">
          <a href="<?php echo esc_url( edtech_page_url( 'checkout' ) ); ?>" class="btn btn-primary btn-lg"><?php echo esc_html( $hero_cta ); ?></a>
          <a href="<?php echo esc_url( edtech_page_url( 'free-masterclass' ) ); ?>" class="btn btn-secondary btn-lg"><?php echo esc_html( $hero_cta2 ); ?></a>
        </div>

        <!-- Trust signals -->
        <div style="display:flex;gap:var(--space-lg);margin-top:var(--space-xl);flex-wrap:wrap;">
          <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-text-muted);">
            <svg width="16" height="16" fill="none" stroke="var(--color-success)" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <?php is_rtl() ? _e('ضمان استرداد الأموال خلال 30 يوماً', 'edtech') : _e('30-Day Money-Back Guarantee', 'edtech'); ?>
          </div>
          <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-text-muted);">
            <svg width="16" height="16" fill="none" stroke="var(--color-success)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <?php is_rtl() ? _e('تشفير SSL آمن 256-bit', 'edtech') : _e('256-bit SSL Secure Checkout', 'edtech'); ?>
          </div>
        </div>
      </div>

      <!-- Right: Live Skill Sandbox Card -->
      <div class="reveal" style="--reveal-delay:100ms;">
        <div class="card" style="padding:0;overflow:hidden;border-radius:var(--radius-lg);box-shadow:var(--elevation-hover);">
          <div style="display:flex;border-bottom:1px solid var(--color-border-subtle);background:var(--color-bg-subtle);">
            <button class="tab-trigger active" data-panel="sandbox-dev" style="flex:1;border-radius:0;height:48px;font-size:13px;"><?php is_rtl() ? _e('تطبيق متكامل', 'edtech') : _e('Full-Stack App', 'edtech'); ?></button>
            <button class="tab-trigger" data-panel="sandbox-des" style="flex:1;border-radius:0;height:48px;font-size:13px;"><?php is_rtl() ? _e('نظام Figma', 'edtech') : _e('Figma UI System', 'edtech'); ?></button>
          </div>
          <div data-tabs-container>
            <div id="sandbox-dev" class="tab-panel active" style="padding:0;position:relative;aspect-ratio:4/3;overflow:hidden;">
              <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=700&auto=format&fit=crop&q=80" alt="" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
              <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.7),transparent);display:flex;flex-direction:column;justify-content:flex-end;padding:var(--space-md);">
                <span class="badge badge-free" style="align-self:flex-start;margin-bottom:8px;"><?php is_rtl() ? _e('مشروع طالب حقيقي', 'edtech') : _e('Student Project Output', 'edtech'); ?></span>
                <p style="color:white;font-size:14px;font-weight:600;margin:0;"><?php is_rtl() ? _e('تطبيق متجر إلكتروني كامل بـ React وNode.js', 'edtech') : _e('Full-Stack E-Commerce App built with React & Node.js', 'edtech'); ?></p>
              </div>
            </div>
            <div id="sandbox-des" class="tab-panel" style="padding:0;position:relative;aspect-ratio:4/3;overflow:hidden;">
              <img src="https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=700&auto=format&fit=crop&q=80" alt="" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
              <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.7),transparent);display:flex;flex-direction:column;justify-content:flex-end;padding:var(--space-md);">
                <span class="badge badge-free" style="align-self:flex-start;margin-bottom:8px;"><?php is_rtl() ? _e('مشروع طالب حقيقي', 'edtech') : _e('Student Project Output', 'edtech'); ?></span>
                <p style="color:white;font-size:14px;font-weight:600;margin:0;"><?php is_rtl() ? _e('نظام تصميم Figma متكامل لمنتج SaaS', 'edtech') : _e('Complete Figma Design System for SaaS Product', 'edtech'); ?></p>
              </div>
            </div>
          </div>
          <div style="padding:var(--space-md);background:var(--color-bg-subtle);display:flex;justify-content:space-between;align-items:center;">
            <div>
              <p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php is_rtl() ? _e('من كتالوج الدورات', 'edtech') : _e('From the catalog:', 'edtech'); ?>
                <a href="<?php echo esc_url( $catalog_link ); ?>" style="color:var(--color-primary);font-weight:600;"><?php is_rtl() ? _e('استكشف الدورات', 'edtech') : _e('Explore Courses', 'edtech'); ?></a>
              </p>
            </div>
            <a href="<?php echo esc_url( edtech_page_url( 'free-masterclass' ) ); ?>" class="btn btn-primary" style="height:36px;font-size:13px;"><?php is_rtl() ? _e('جرّب مجاناً ←', 'edtech') : _e('Try Free Sample →', 'edtech'); ?></a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ===== SAMPLE VIDEO WORKSPACE ===== -->
<section class="section-padding" style="background:var(--color-bg-subtle);">
  <div class="container">
    <div style="text-align:center;max-width:660px;margin-inline:auto;margin-bottom:var(--space-xl);" class="reveal">
      <h2><?php is_rtl() ? _e('تجربة درس عملي واقعي — قبل التسجيل', 'edtech') : _e('Experience a Real Lesson — Before You Enroll', 'edtech'); ?></h2>
      <p style="color:var(--color-text-muted);margin-top:var(--space-sm);"><?php is_rtl() ? _e('شاهد درساً كاملاً بجودة عالية HD مع ترجمة والتحكم في السرعة وملفات قابلة للتنزيل. بدون تسجيل.', 'edtech') : _e('Watch a full HD sample lesson with Arabic & English captions, speed control, and downloadable resources. No sign-up required.', 'edtech'); ?></p>
    </div>
    <div class="reveal" style="max-width:860px;margin-inline:auto;">
      <div class="video-container" data-pip-trigger style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--elevation-hover);">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200&auto=format&fit=crop&q=80" alt="" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
        <div class="video-play-overlay" role="button" tabindex="0" aria-label="<?php esc_attr_e('Play sample lesson video', 'edtech'); ?>">
          <div class="video-play-btn">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="var(--color-primary)"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          </div>
        </div>
        <div class="video-controls" style="display:none;" aria-hidden="true">
          <svg width="20" height="20" fill="white"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          <div class="progress-bar" style="flex:1;"><div class="progress-fill" style="width:35%;"></div></div>
          <span style="font-size:12px;color:rgba(255,255,255,0.8);">18:42 / 45:00</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== INSTRUCTOR AUTHORITY GRID ===== -->
<section class="section-padding">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:var(--space-xl);flex-wrap:wrap;gap:var(--space-md);">
      <div class="reveal">
        <h2><?php is_rtl() ? _e('تعلّم من خبراء الصناعة المعتمدين', 'edtech') : _e('Learn From Verified Industry Experts', 'edtech'); ?></h2>
        <p style="color:var(--color-text-muted);margin-top:var(--space-xs);"><?php is_rtl() ? _e('اضغط على الميكروفون للاستماع لمقدمة صوتية قصيرة من المدرب.', 'edtech') : _e('Tap the microphone to hear a 30-second voice greeting from each instructor.', 'edtech'); ?></p>
      </div>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'instructor' ) ); ?>" class="btn btn-secondary reveal"><?php is_rtl() ? _e('عرض جميع المدربين', 'edtech') : _e('View All Instructors', 'edtech'); ?></a>
    </div>

    <div class="grid grid-4">
      <?php
      $instructors_query = new WP_Query( array(
        'post_type'      => 'instructor',
        'posts_per_page' => 4,
        'post_status'    => 'publish',
      ) );

      if ( $instructors_query->have_posts() ) :
        while ( $instructors_query->have_posts() ) : $instructors_query->the_post();
          get_template_part( 'template-parts/content-instructor-card' );
        endwhile;
        wp_reset_postdata();
      else :
        // Fallback static cards so the section still renders before any instructors exist.
        $fallback = array(
          array( 'name' => is_rtl() ? 'م. طارق منصور' : 'Eng. Tariq Mansour', 'title' => is_rtl() ? 'مهندس Full-Stack أول' : 'Senior Full-Stack Architect', 'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80', 'stats' => is_rtl() ? '★ 4.9 · 1,240 طالب' : '★ 4.9 · 1,240 Students' ),
          array( 'name' => is_rtl() ? 'سارة الراشد' : 'Sarah Al-Rashid', 'title' => is_rtl() ? 'مصممة UI/UX رائدة' : 'Lead UI/UX Designer', 'img' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&auto=format&fit=crop&q=80', 'stats' => is_rtl() ? '★ 4.8 · 890 طالب' : '★ 4.8 · 890 Students' ),
          array( 'name' => is_rtl() ? 'د. عمر فاروق' : 'Dr. Omar Farooq', 'title' => is_rtl() ? 'خبير علم البيانات' : 'Data Science Specialist', 'img' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&auto=format&fit=crop&q=80', 'stats' => is_rtl() ? '★ 4.9 · 2,100 طالب' : '★ 4.9 · 2,100 Students' ),
          array( 'name' => is_rtl() ? 'ليلى حسن' : 'Layla Hassan', 'title' => is_rtl() ? 'استراتيجية النمو الرقمي' : 'Digital Growth Strategist', 'img' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&auto=format&fit=crop&q=80', 'stats' => is_rtl() ? '★ 4.7 · 680 طالب' : '★ 4.7 · 680 Students' ),
        );
        foreach ( $fallback as $inst ) {
          get_template_part( 'template-parts/content-instructor-card', null, $inst );
        }
      endif;
      ?>
    </div>
  </div>
</section>

<!-- ===== PROOF CANVAS ===== -->
<section class="section-padding" style="background:var(--color-bg-subtle);">
  <div class="container">
    <div style="max-width:860px;margin-inline:auto;">
      <div class="grid grid-2" style="align-items:center;gap:var(--space-xl);">
        <div class="reveal">
          <h2 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('نتائج حقيقية من طلاب حقيقيين', 'edtech') : _e('Real Results From Real Students', 'edtech'); ?></h2>
          <div style="display:flex;align-items:center;gap:var(--space-md);margin-bottom:var(--space-lg);">
            <span style="font-size:4rem;font-weight:800;color:var(--color-text-title);line-height:1;">4.9</span>
            <div>
              <div class="stars" style="font-size:1.5rem;">★★★★★</div>
              <p style="color:var(--color-text-muted);font-size:14px;margin-top:4px;"><?php is_rtl() ? _e('متوسط التقييم من أكثر من 4,400 مراجعة موثّقة', 'edtech') : _e('Average rating from 4,400+ verified reviews', 'edtech'); ?></p>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;align-items:center;gap:var(--space-sm);">
              <span style="font-size:13px;width:40px;">5 ★</span>
              <div class="progress-bar" style="flex:1;"><div class="progress-fill" style="width:78%;"></div></div>
              <span style="font-size:13px;width:36px;">78%</span>
            </div>
            <div style="display:flex;align-items:center;gap:var(--space-sm);">
              <span style="font-size:13px;width:40px;">4 ★</span>
              <div class="progress-bar" style="flex:1;"><div class="progress-fill" style="width:16%;background:var(--color-accent);"></div></div>
              <span style="font-size:13px;width:36px;">16%</span>
            </div>
          </div>
        </div>

        <div class="reveal">
          <div class="grid grid-2" style="gap:var(--space-md);">
            <div class="stat-card text-center"><div class="stat-value" data-target="<?php echo esc_attr( (int) $stat_students ); ?>">0</div><div class="stat-label"><?php is_rtl() ? _e('طالب نشط', 'edtech') : _e('Active Students', 'edtech'); ?></div></div>
            <div class="stat-card text-center"><div class="stat-value" data-target="<?php echo esc_attr( $stat_courses ); ?>">0</div><div class="stat-label"><?php is_rtl() ? _e('دورة متميزة', 'edtech') : _e('Premium Courses', 'edtech'); ?></div></div>
            <div class="stat-card text-center"><div class="stat-value" data-target="<?php echo esc_attr( $stat_instructors ); ?>">0</div><div class="stat-label"><?php is_rtl() ? _e('مدرب خبير', 'edtech') : _e('Expert Instructors', 'edtech'); ?></div></div>
            <div class="stat-card text-center"><div class="stat-value"><?php echo esc_html( $stat_completion ); ?></div><div class="stat-label"><?php is_rtl() ? _e('معدل الإكمال', 'edtech') : _e('Completion Rate', 'edtech'); ?></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== SKILL TREE TEASER ===== -->
<section class="section-padding">
  <div class="container">
    <div style="text-align:center;max-width:660px;margin-inline:auto;margin-bottom:var(--space-xl);" class="reveal">
      <h2><?php is_rtl() ? _e('مسارات تعلم منظمة لتأهيلك وظيفياً', 'edtech') : _e('Structured Learning Paths to Career-Ready Skills', 'edtech'); ?></h2>
      <p style="color:var(--color-text-muted);margin-top:var(--space-xs);"><?php is_rtl() ? _e('اتبع مسارات مهنية متعددة الدورات مصممة للوصول بك للهدف بشكل أسرع.', 'edtech') : _e('Follow curated multi-course career tracks and arrive at your destination faster.', 'edtech'); ?></p>
    </div>

    <div style="max-width:720px;margin-inline:auto;" class="reveal">
      <div class="skill-tree">
        <?php
        $paths_query = new WP_Query( array(
          'post_type'      => 'learning_path',
          'posts_per_page' => 3,
          'post_status'    => 'publish',
        ) );
        if ( $paths_query->have_posts() ) :
          $phase = 1;
          while ( $paths_query->have_posts() ) : $paths_query->the_post();
            $weeks   = get_post_meta( get_the_ID(), '_path_weeks', true );
            $pcourses= get_post_meta( get_the_ID(), '_path_courses', true );
            $badge   = get_post_meta( get_the_ID(), '_path_badge', true );
            $badge_class = $badge ? 'badge-' . strtolower( $badge ) : 'badge-new';
            ?>
            <div class="skill-node">
              <div class="skill-node-card" style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                  <span class="badge <?php echo esc_attr( $badge_class ); ?>" style="margin-bottom:4px;"><?php is_rtl() ? printf( 'المرحلة %d', $phase ) : printf( 'Phase %d', $phase ); ?></span>
                  <h4 style="margin:0;"><?php the_title(); ?></h4>
                  <p style="font-size:13px;color:var(--color-text-muted);margin:4px 0 0;"><?php echo esc_html( sprintf( '%s %s · %s %s', $weeks, ( is_rtl() ? 'أسابيع' : 'Weeks' ), $pcourses, ( is_rtl() ? 'دورات' : 'Courses' ) ) ); ?></p>
                </div>
                <a href="<?php echo esc_url( get_permalink() ); ?>" style="color:var(--color-text-muted);"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
              </div>
            </div>
            <?php
            $phase++;
          endwhile;
          wp_reset_postdata();
        else :
          // Fallback single node (structural) before any learning paths exist.
          ?>
          <div class="skill-node">
            <div class="skill-node-card" style="display:flex;justify-content:space-between;align-items:center;">
              <div>
                <span class="badge badge-new" style="margin-bottom:4px;"><?php is_rtl() ? _e('المرحلة 1', 'edtech') : _e('Phase 1', 'edtech'); ?></span>
                <h4 style="margin:0;"><?php is_rtl() ? _e('أساسيات الواجهة الأمامية (HTML, CSS, React)', 'edtech') : _e('Frontend Foundations (HTML, CSS, React)', 'edtech'); ?></h4>
                <p style="font-size:13px;color:var(--color-text-muted);margin:4px 0 0;"><?php is_rtl() ? _e('4 أسابيع · 3 دورات', 'edtech') : _e('4 Weeks · 3 Courses', 'edtech'); ?></p>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div style="text-align:center;margin-top:var(--space-xl);">
        <a href="<?php echo esc_url( edtech_page_url( 'learning-paths' ) ); ?>" class="btn btn-primary btn-lg"><?php is_rtl() ? _e('استكشف جميع مسارات التعلم', 'edtech') : _e('Explore All Learning Paths', 'edtech'); ?></a>
      </div>
    </div>
  </div>
</section>

<!-- ===== ENROLLMENT HUB ===== -->
<section class="section-padding" style="background:linear-gradient(135deg,hsl(222,85%,56%) 0%,hsl(262,80%,50%) 100%);">
  <div class="container" style="text-align:center;">
    <div class="reveal">
      <h2 style="color:white;font-size:var(--font-size-h1);margin-bottom:var(--space-md);"><?php echo esc_html( edtech_get_site_setting( 'enroll_banner_title', is_rtl() ? 'هل أنت جاهز لبناء مستقبلك؟' : 'Ready to Build Your Future?' ) ); ?></h2>
      <p style="color:rgba(255,255,255,0.85);font-size:var(--font-size-body-lg);margin-bottom:var(--space-xl);max-width:560px;margin-inline:auto;">
        <?php echo esc_html( edtech_get_site_setting( 'enroll_banner_text', is_rtl() ? 'انضم إلى 45,000+ طالب يتعلمون بالفعل. ابدأ بالماستر كلاس المجاني — لا حاجة لبطاقة ائتمان.' : 'Join 45,000+ students already learning. Start with a free masterclass — no credit card required.' ) ); ?>
      </p>
      <div style="display:flex;gap:var(--space-md);justify-content:center;flex-wrap:wrap;">
        <a href="<?php echo esc_url( edtech_page_url( 'free-masterclass' ) ); ?>" class="btn btn-lg" style="background:white;color:var(--color-primary);"><?php echo esc_html( $hero_cta2 ); ?></a>
        <a href="<?php echo esc_url( $catalog_link ); ?>" class="btn btn-lg btn-outline" style="border-color:rgba(255,255,255,0.6);color:white;"><?php is_rtl() ? _e('تصفح جميع الدورات', 'edtech') : _e('Browse All Courses', 'edtech'); ?></a>
      </div>
    </div>
  </div>
</section>

</main>

<!-- PIP Video Float -->
<div id="video-pip" class="video-pip" aria-hidden="true">
  <div class="video-container" style="border-radius:var(--radius-md);">
    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&auto=format&fit=crop&q=80" alt="" style="width:100%;height:100%;object-fit:cover;">
    <div class="video-play-overlay">
      <div class="video-play-btn" style="width:44px;height:44px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="var(--color-primary)"><polygon points="5 3 19 12 5 21 5 3"/></svg>
      </div>
    </div>
  </div>
</div>

<?php
get_footer();
