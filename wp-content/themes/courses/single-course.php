<?php
/**
 * The Single Course template
 *
 * Maps 1-to-1 to course-detail.html and ar/course-detail.html
 *
 * @package EdTech
 */

get_header();

while ( have_posts() ) : the_post();
	$post_id = get_the_ID();
	$meta    = edtech_get_course_meta( $post_id );
	$catalog = get_post_type_archive_link( 'course' );
	$checkout= edtech_get_checkout_url( $post_id );
	$img     = edtech_get_post_image( $post_id, 'full', 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=1400&auto=format&fit=crop&q=80' );
	?>

	<main>
	<!-- Cinema Hero -->
	<section style="position:relative;overflow:hidden;background:var(--color-bg-dark);min-height:360px;display:flex;align-items:center;">
	  <img src="<?php echo esc_url( $img ); ?>" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.25;" loading="eager">
	  <div class="container" style="position:relative;z-index:1;padding-block:var(--space-3xl);">
	    <p style="font-size:13px;color:rgba(255,255,255,0.6);margin-bottom:var(--space-sm);">
	      <a href="<?php echo esc_url( home_url('/') ); ?>" style="color:inherit;text-decoration:none;"><?php wpm_is_rtl() ? _e('الرئيسية', 'edtech') : _e('Home', 'edtech'); ?></a> &rsaquo;
	      <a href="<?php echo esc_url( $catalog ); ?>" style="color:inherit;text-decoration:none;"><?php wpm_is_rtl() ? _e('الدورات', 'edtech') : _e('Courses', 'edtech'); ?></a> &rsaquo;
	      <?php the_title(); ?>
	    </p>
	    <h1 style="color:white;font-size:var(--font-size-h1);max-width:680px;margin-bottom:var(--space-md);"><?php the_title(); ?></h1>
	    <?php if ( has_excerpt() ) : ?>
	      <p style="color:rgba(255,255,255,0.8);font-size:var(--font-size-body-lg);max-width:620px;margin-bottom:var(--space-md);"><?php echo esc_html( get_the_excerpt() ); ?></p>
	    <?php endif; ?>
	    <div style="display:flex;align-items:center;gap:var(--space-md);flex-wrap:wrap;margin-bottom:var(--space-md);">
	      <?php if ( ! empty( $meta['badge'] ) ) : ?><span class="badge badge-bestseller">★ <?php echo esc_html( $meta['badge'] ); ?></span><?php endif; ?>
	      <div style="display:flex;align-items:center;gap:4px;">
	        <span class="stars">★★★★★</span>
	        <span style="color:white;font-weight:700;"><?php echo esc_html( $meta['rating'] ); ?></span>
	        <span style="color:rgba(255,255,255,0.6);font-size:13px;">(<?php echo esc_html( $meta['reviews_count'] ); ?> <?php wpm_is_rtl() ? _e('تقييم', 'edtech') : _e('ratings', 'edtech'); ?>)</span>
	      </div>
	      <?php if ( $meta['instructor'] ) : ?>
	        <span style="color:rgba(255,255,255,0.6);font-size:13px;"><?php echo esc_html( $meta['instructor'] ); ?></span>
	      <?php endif; ?>
	    </div>
	  </div>
	</section>

	<!-- Course Body -->
	<section class="section-padding-sm">
	  <div class="container">
	    <div class="split-detail">

	      <!-- Main Content -->
	      <div>
	        <!-- Description -->
	        <?php if ( get_the_content() ) : ?>
	        <div class="card reveal" style="margin-bottom:var(--space-lg);">
	          <h2 style="margin-bottom:var(--space-md);"><?php wpm_is_rtl() ? _e('عن الدورة', 'edtech') : _e('About this Course', 'edtech'); ?></h2>
	          <div class="entry-content" style="line-height:1.75;"><?php the_content(); ?></div>
	        </div>
	        <?php endif; ?>

	        <!-- Outcomes -->
	        <?php if ( ! empty( $meta['skills'] ) || ! empty( $meta['outcomes'] ) ) : ?>
	        <div class="card reveal" style="margin-bottom:var(--space-lg);">
	          <h2 style="margin-bottom:var(--space-md);"><?php wpm_is_rtl() ? _e('ماذا ستبني وتكتسب؟', 'edtech') : _e('What You Will Build & Gain', 'edtech'); ?></h2>
	          <div class="tabs">
	            <?php if ( ! empty( $meta['skills'] ) ) : ?><button class="tab-trigger active" data-panel="outcomes-skills"><?php wpm_is_rtl() ? _e('المهارات المكتسبة', 'edtech') : _e('Skills Gained', 'edtech'); ?></button><?php endif; ?>
	            <?php if ( ! empty( $meta['outcomes'] ) ) : ?><button class="tab-trigger" data-panel="outcomes-project" <?php echo empty( $meta['skills'] ) ? 'active' : ''; ?>><?php wpm_is_rtl() ? _e('معرض المشاريع', 'edtech') : _e('Project Showcase', 'edtech'); ?></button><?php endif; ?>
	          </div>
	          <div data-tabs-container>
	            <?php if ( ! empty( $meta['skills'] ) ) : ?>
	            <div id="outcomes-skills" class="tab-panel active">
	              <?php echo wp_kses_post( wpautop( $meta['skills'] ) ); ?>
	            </div>
	            <?php endif; ?>
	            <?php if ( ! empty( $meta['outcomes'] ) ) : ?>
	            <div id="outcomes-project" class="tab-panel <?php echo empty( $meta['skills'] ) ? 'active' : ''; ?>">
	              <?php echo wp_kses_post( wpautop( $meta['outcomes'] ) ); ?>
	            </div>
	            <?php endif; ?>
	          </div>
	        </div>
	        <?php endif; ?>

	        <!-- Syllabus -->
	        <div class="reveal" style="margin-bottom:var(--space-lg);">
	          <h2 style="margin-bottom:var(--space-md);"><?php wpm_is_rtl() ? _e('منهج الدورة', 'edtech') : _e('Course Syllabus', 'edtech'); ?></h2>
	          <?php if ( ! empty( $meta['syllabus'] ) ) : ?>
	            <div class="accordion-group"><?php echo wp_kses_post( $meta['syllabus'] ); ?></div>
	          <?php else : ?>
	            <div class="accordion-group">
	              <div class="accordion-item active">
	                <button class="accordion-trigger" aria-expanded="true">
	                  <span><?php wpm_is_rtl() ? _e('الوحدة 1: المقدمة', 'edtech') : _e('Module 1: Introduction', 'edtech'); ?></span>
	                  <svg class="accordion-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
	                </button>
	                <div class="accordion-body">
	                  <p style="font-size:14px;color:var(--color-text-muted);"><?php wpm_is_rtl() ? _e('سيتم إضافة تفاصيل المنهج قريباً.', 'edtech') : _e('Syllabus details will be added soon.', 'edtech'); ?></p>
	            </div>
	              </div>
	            </div>
	          <?php endif; ?>
	        </div>
	      </div>

	      <!-- Sticky Sidebar -->
	      <aside style="position:sticky;top:88px;">
	        <div class="card" style="padding:var(--space-lg);">
	          <div style="display:flex;align-items:baseline;gap:var(--space-sm);margin-bottom:var(--space-xs);">
	            <span style="font-size:2rem;font-weight:800;color:var(--color-text-title);">$<?php echo esc_html( $meta['price'] ); ?></span>
	            <span style="font-size:1.125rem;color:var(--color-text-muted);text-decoration:line-through;">$<?php echo esc_html( $meta['price_orig'] ); ?></span>
	          </div>
	          <a href="<?php echo esc_url( $checkout ); ?>" class="btn btn-primary btn-lg" style="width:100%;margin-bottom:var(--space-sm);"><?php wpm_is_rtl() ? _e('اشترك الآن', 'edtech') : _e('Enroll Now', 'edtech'); ?></a>
	          <a href="<?php echo esc_url( edtech_page_url( 'free-masterclass' ) ); ?>" class="btn btn-secondary btn-lg" style="width:100%;"><?php wpm_is_rtl() ? _e('جرب درساً مجاناً', 'edtech') : _e('Try Free Lesson', 'edtech'); ?></a>
	          <?php
		  $level_terms = wp_get_post_terms( $post_id, 'course_level', array( 'fields' => 'names' ) );
		  $level_value = ( $level_terms && ! is_wp_error( $level_terms ) ) ? $level_terms[0] : '';
		  $course_meta_items = array(
	            wpm_is_rtl() ? 'المدة'       : 'Duration' => $meta['duration'],
	            wpm_is_rtl() ? 'عدد الدروس' : 'Lessons'   => $meta['lessons_count'],
	            wpm_is_rtl() ? 'المستوى'    : 'Level'     => $level_value,
	            wpm_is_rtl() ? 'المدرب'      : 'Instructor'=> $meta['instructor'],
		  );
		  ?>
	          <ul style="list-style:none;padding:0;margin:var(--space-md) 0 0;font-size:14px;color:var(--color-text-muted);display:flex;flex-direction:column;gap:8px;">
	            <?php foreach ( $course_meta_items as $label => $value ) : if ( '' === (string) $value ) { continue; } ?>
	              <li style="display:flex;justify-content:space-between;"><span><?php echo esc_html( $label ); ?></span><strong style="color:var(--color-text-title);"><?php echo esc_html( $value ); ?></strong></li>
	            <?php endforeach; ?>
	          </ul>
	        </div>
	      </aside>

	    </div>
	  </div>
	</section>
	</main>

	<?php
endwhile;

get_footer();
