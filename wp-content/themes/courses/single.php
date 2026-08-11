<?php
/**
 * The template for displaying single Blog posts (Blog Details)
 *
 * @package EdTech
 */

get_header();

$current_id = get_the_ID();
$author_id  = get_the_author_meta( 'ID' );
$author_name = get_the_author();
$author_avatar = get_avatar_url( $author_id, array( 'size' => 96 ) );
$post_date  = get_the_date();
$categories = get_the_category();
$category_name = ! empty( $categories ) ? $categories[0]->name : ( is_rtl() ? 'تطوير الويب' : 'Development' );

// Thumbnail or fallback
$featured_img = get_the_post_thumbnail_url( $current_id, 'full' );
if ( ! $featured_img ) {
	$featured_img = 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1200&auto=format&fit=crop&q=80';
}
?>

<main>
<!-- Hero Header -->
<section style="position:relative;overflow:hidden;background:linear-gradient(135deg, var(--color-bg-dark) 0%, #1e1b4b 100%);color:white;padding-block:var(--space-2xl) var(--space-3xl);">
  <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(99,102,241,0.15) 1px, transparent 1px);background-size:24px 24px;pointer-events:none;"></div>
  
  <div class="container" style="position:relative;z-index:1;">
    <!-- Breadcrumb -->
    <div style="font-size:13px;color:rgba(255,255,255,0.7);margin-bottom:var(--space-md);display:flex;align-items:center;gap:var(--space-xs);flex-wrap:wrap;">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:inherit;text-decoration:none;"><?php is_rtl() ? _e( 'الرئيسية', 'edtech' ) : _e( 'Home', 'edtech' ); ?></a>
      <span>&rsaquo;</span>
      <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" style="color:inherit;text-decoration:none;"><?php is_rtl() ? _e( 'المدونة والموارد', 'edtech' ) : _e( 'Blog & Resources', 'edtech' ); ?></a>
      <span>&rsaquo;</span>
      <span style="color:var(--color-accent);"><?php echo esc_html( $category_name ); ?></span>
    </div>

    <!-- Category Badge -->
    <span class="badge badge-new" style="margin-bottom:var(--space-sm);display:inline-block;"><?php echo esc_html( $category_name ); ?></span>

    <!-- Article Title -->
    <h1 style="font-size:var(--font-size-h1);color:white;line-height:1.25;margin-bottom:var(--space-md);max-width:850px;"><?php the_title(); ?></h1>

    <!-- Meta Details: Author, Date, Read Time -->
    <div style="display:flex;align-items:center;gap:var(--space-md);flex-wrap:wrap;font-size:14px;color:rgba(255,255,255,0.85);margin-top:var(--space-md);">
      <div style="display:flex;align-items:center;gap:var(--space-xs);">
        <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--color-accent);">
        <span style="font-weight:600;"><?php echo esc_html( $author_name ); ?></span>
      </div>
      <span>•</span>
      <div>📅 <?php echo esc_html( $post_date ); ?></div>
      <span>•</span>
      <div>⏱️ <?php is_rtl() ? _e( 'قراءة 6 دقائق', 'edtech' ) : _e( '6 min read', 'edtech' ); ?></div>
    </div>
  </div>
</section>

<!-- Featured Image Showcase -->
<div class="container" style="margin-top:-30px;position:relative;z-index:2;">
  <div style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-xl);max-height:480px;">
    <img src="<?php echo esc_url( $featured_img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" style="width:100%;height:100%;object-fit:cover;display:block;">
  </div>
</div>

<!-- Main Body: Article Content & Sidebar -->
<section class="section-padding">
  <div class="container">
    <div style="display:grid;grid-template-columns:68fr 32fr;gap:var(--space-xl);align-items:start;">
      
      <!-- Article Content Column -->
      <article class="reveal" style="background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-lg);padding:var(--space-xl);box-shadow:var(--shadow-sm);">
        
        <!-- Post Lead Summary -->
        <?php if ( has_excerpt() ) : ?>
          <div style="font-size:18px;line-height:1.7;color:var(--color-text-main);font-weight:500;padding:var(--space-md);background:var(--color-bg-subtle);border-inline-start:4px solid var(--color-accent);border-radius:var(--radius-sm);margin-bottom:var(--space-lg);">
            <?php echo esc_html( get_the_excerpt() ); ?>
          </div>
        <?php endif; ?>

        <!-- Main Body Content -->
        <div class="entry-content" style="line-height:1.85;font-size:16px;color:var(--color-text-main);">
          <?php
          while ( have_posts() ) :
            the_post();
            the_content();
          endwhile;
          ?>
        </div>

        <!-- Tags / Social Share -->
        <hr style="margin-block:var(--space-xl);border:0;border-top:1px solid var(--color-border);">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:var(--space-md);">
          <div style="display:flex;align-items:center;gap:var(--space-xs);flex-wrap:wrap;">
            <span style="font-weight:700;font-size:14px;"><?php is_rtl() ? _e( 'الوسوم:', 'edtech' ) : _e( 'Tags:', 'edtech' ); ?></span>
            <span class="chip"><?php echo esc_html( $category_name ); ?></span>
            <span class="chip">React 19</span>
            <span class="chip">Web Dev</span>
          </div>

          <div style="display:flex;align-items:center;gap:var(--space-xs);">
            <span style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e( 'مشاركة:', 'edtech' ) : _e( 'Share:', 'edtech' ); ?></span>
            <button class="btn btn-secondary" style="padding:6px 12px;font-size:12px;" onclick="navigator.clipboard.writeText(window.location.href);alert('<?php is_rtl() ? _e( 'تم نسخ الرابط!', 'edtech' ) : _e( 'Link copied!', 'edtech' ); ?>');">🔗 <?php is_rtl() ? _e( 'نسخ الرابط', 'edtech' ) : _e( 'Copy Link', 'edtech' ); ?></button>
          </div>
        </div>

        <!-- Author Box Card -->
        <div style="margin-top:var(--space-xl);padding:var(--space-lg);background:linear-gradient(145deg, var(--color-bg-subtle) 0%, var(--color-bg-card) 100%);border:1px solid var(--color-border);border-radius:var(--radius-md);display:flex;gap:var(--space-md);align-items:center;">
          <img src="<?php echo esc_url( $author_avatar ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--color-accent);flex-shrink:0;">
          <div>
            <span class="badge badge-free" style="font-size:11px;margin-bottom:4px;"><?php is_rtl() ? _e( 'كاتب الخبير', 'edtech' ) : _e( 'Expert Author', 'edtech' ); ?></span>
            <h4 style="margin-bottom:var(--space-xs);font-size:16px;"><?php echo esc_html( $author_name ); ?></h4>
            <p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php is_rtl() ? _e( 'مطور متكامل وخبير تقني يشارك أحدث الأنماط المتقدمة وأفضل الممارسات البرمجية.', 'edtech' ) : _e( 'Full-stack architect & senior tech strategist sharing modern patterns & best practices.', 'edtech' ) ; ?></p>
          </div>
        </div>

      </article>

      <!-- Sidebar -->
      <aside class="reveal">
        <!-- CTA Course Card -->
        <div class="card" style="background:linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);color:white;margin-bottom:var(--space-lg);">
          <span class="badge badge-bestseller" style="margin-bottom:var(--space-sm);"><?php is_rtl() ? _e( 'دورة موصى بها', 'edtech' ) : _e( 'Recommended Course', 'edtech' ); ?></span>
          <h3 style="color:white;font-size:18px;margin-bottom:var(--space-sm);"><?php is_rtl() ? _e( 'احترف Full-Stack React 19 & Node.js', 'edtech' ) : _e( 'Master Full-Stack React 19 & Node.js', 'edtech' ); ?></h3>
          <p style="color:rgba(255,255,255,0.8);font-size:13px;margin-bottom:var(--space-md);"><?php is_rtl() ? _e( 'انضم إلى أكثر من 12,000 طالب وطوّر تطبيقات حقيقية مع شهادة معتمدة.', 'edtech' ) : _e( 'Join 12,000+ students building production apps with verified certificate.', 'edtech' ); ?></p>
          <a href="<?php echo esc_url( home_url( '/catalog' ) ); ?>" class="btn btn-primary" style="width:100%;text-align:center;"><?php is_rtl() ? _e( 'استكشف الدورة الان ←', 'edtech' ) : _e( 'Explore Course Now →', 'edtech' ); ?></a>
        </div>

        <!-- Recent Blog Posts Sidebar List -->
        <div class="card">
          <h4 style="margin-bottom:var(--space-md);border-bottom:1px solid var(--color-border);padding-bottom:var(--space-xs);"><?php is_rtl() ? _e( 'أحدث المقالات', 'edtech' ) : _e( 'Recent Articles', 'edtech' ); ?></h4>
          <div style="display:flex;flex-direction:column;gap:var(--space-md);">
            <?php
            $recent_posts = get_posts( array(
              'posts_per_page' => 3,
              'post__not_in'   => array( $current_id ),
              'post_type'      => 'post',
            ) );
            if ( ! empty( $recent_posts ) ) :
              foreach ( $recent_posts as $rp ) :
                $rp_thumb = get_the_post_thumbnail_url( $rp->ID, 'thumbnail' ) ?: 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=200&auto=format&fit=crop&q=80';
                ?>
                <div style="display:flex;gap:var(--space-sm);align-items:center;">
                  <img src="<?php echo esc_url( $rp_thumb ); ?>" alt="" style="width:60px;height:60px;border-radius:var(--radius-sm);object-fit:cover;flex-shrink:0;">
                  <div>
                    <h5 style="font-size:13px;line-height:1.3;margin-bottom:2px;">
                      <a href="<?php echo esc_url( get_permalink( $rp->ID ) ); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html( get_the_title( $rp->ID ) ); ?></a>
                    </h5>
                    <span style="font-size:11px;color:var(--color-text-muted);"><?php echo esc_html( get_the_date( '', $rp->ID ) ); ?></span>
                  </div>
                </div>
                <?php
              endforeach;
            else :
              ?>
              <p style="font-size:13px;color:var(--color-text-muted);"><?php is_rtl() ? _e( 'لا توجد مقالات أخرى حالياً.', 'edtech' ) : _e( 'No other articles currently.', 'edtech' ); ?></p>
            <?php endif; ?>
          </div>
        </div>
      </aside>

    </div>
  </div>
</section>

<!-- Related Articles Bottom Section -->
<section class="section-padding-sm" style="background:var(--color-bg-subtle);border-top:1px solid var(--color-border);">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:var(--space-lg);">
      <div>
        <h2 style="margin-bottom:4px;"><?php is_rtl() ? _e( 'مقالات قد تهمك', 'edtech' ) : _e( 'Related Articles', 'edtech' ); ?></h2>
        <p style="color:var(--color-text-muted);font-size:14px;margin:0;"><?php is_rtl() ? _e( 'واصل القراءة والتعلم مع مواضيع ذات صلة', 'edtech' ) : _e( 'Continue reading and exploring related topics', 'edtech' ); ?></p>
      </div>
      <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e( 'عرض كل المقالات ←', 'edtech' ) : _e( 'View All Articles →', 'edtech' ); ?></a>
    </div>

    <div class="grid grid-3">
      <?php
      $related_posts = get_posts( array(
        'posts_per_page' => 3,
        'post__not_in'   => array( $current_id ),
        'post_type'      => 'post',
      ) );
      if ( ! empty( $related_posts ) ) :
        foreach ( $related_posts as $rel ) :
          $rel_thumb = get_the_post_thumbnail_url( $rel->ID, 'medium_large' ) ?: 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=600&auto=format&fit=crop&q=80';
          ?>
          <article class="card course-card reveal">
            <div class="card-thumbnail" style="aspect-ratio:16/9;overflow:hidden;">
              <img src="<?php echo esc_url( $rel_thumb ); ?>" alt="<?php echo esc_attr( get_the_title( $rel->ID ) ); ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div class="card-body">
              <span class="badge badge-free" style="margin-bottom:var(--space-xs);"><?php is_rtl() ? _e( 'مقالة تعليمية', 'edtech' ) : _e( 'Tutorial Article', 'edtech' ); ?></span>
              <h3 style="font-size:16px;line-height:1.35;margin-bottom:var(--space-xs);">
                <a href="<?php echo esc_url( get_permalink( $rel->ID ) ); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html( get_the_title( $rel->ID ) ); ?></a>
              </h3>
              <p style="font-size:13px;color:var(--color-text-muted);"><?php echo esc_html( wp_trim_words( get_the_excerpt( $rel->ID ), 15 ) ); ?></p>
            </div>
          </article>
          <?php
        endforeach;
      endif;
      ?>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
