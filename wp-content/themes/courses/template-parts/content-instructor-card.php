<?php
/**
 * Template part for displaying an Instructor Authority Card.
 *
 * Works in two modes:
 *  1. Inside a WP loop (instructor CPT) — reads post data + meta.
 *  2. With explicit $args (name/title/img/audio/stats) — used by front-page
 *     static rendering when no CPT data is desired.
 *
 * @package EdTech
 */

$post_id = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();

if ( $post_id && 'instructor' === get_post_type( $post_id ) ) {
	// Loop / explicit post context — read everything from WP data.
	$name   = get_the_title( $post_id );
	$title  = get_post_meta( $post_id, '_instructor_title', true );
	$img    = edtech_get_post_image( $post_id, 'medium' );
	$audio  = get_post_meta( $post_id, '_instructor_audio_url', true );
	$rating = get_post_meta( $post_id, '_instructor_rating', true );
	if ( ! $rating ) {
		$rating = '4.9';
	}
	$students = get_post_meta( $post_id, '_instructor_students', true );
	$stats    = '★ ' . $rating . ' · ' . ( $students ? number_format_i18n( (float) $students ) : '—' ) . ' ' . ( is_rtl() ? 'طالب' : 'Students' );
	$link     = get_permalink( $post_id );
} else {
	// Explicit args fallback.
	$name   = ! empty( $args['name'] ) ? $args['name'] : ( is_rtl() ? 'م. طارق منصور' : 'Eng. Tariq Mansour' );
	$title  = ! empty( $args['title'] ) ? $args['title'] : ( is_rtl() ? 'مهندس Full-Stack أول' : 'Senior Full-Stack Architect' );
	$img    = ! empty( $args['img'] ) ? $args['img'] : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80';
	$audio  = ! empty( $args['audio'] ) ? $args['audio'] : '';
	$stats  = ! empty( $args['stats'] ) ? $args['stats'] : ( is_rtl() ? '★ 4.9 · 1,240 طالب' : '★ 4.9 · 1,240 Students' );
	$link   = '';
}

// Resolve audio path: leave absolute URLs alone, prefix relative theme paths.
$audio_src = '';
if ( $audio ) {
	if ( preg_match( '#^https?://#i', $audio ) ) {
		$audio_src = $audio;
	} else {
		$audio_src = get_template_directory_uri() . '/' . ltrim( $audio, '/' );
	}
}
?>
<div class="card card-hover instructor-card reveal"<?php echo $link ? ' style="cursor:pointer;" onclick="window.location.href=\'' . esc_js( $link ) . '\'"' : ''; ?>>
  <img class="instructor-avatar" src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
  <div style="display:flex;align-items:center;justify-content:center;gap:4px;margin-bottom:6px;">
    <?php if ( $link ) : ?><a href="<?php echo esc_url( $link ); ?>" style="color:inherit;text-decoration:none;"><?php endif; ?>
    <h4 style="margin:0;"><?php echo esc_html( $name ); ?></h4>
    <?php if ( $link ) : ?></a><?php endif; ?>
    <svg width="16" height="16" fill="var(--color-primary)" aria-label="<?php esc_attr_e('Verified', 'edtech'); ?>"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01" fill="none" stroke="white" stroke-width="2"/></svg>
  </div>
  <p style="font-size:13px;color:var(--color-primary);font-weight:600;margin-bottom:4px;"><?php echo esc_html( $title ); ?></p>
  <p style="font-size:12px;color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php echo esc_html( $stats ); ?></p>
  <?php if ( $audio_src ) : ?>
  <button class="audio-btn" data-audio="<?php echo esc_url( $audio_src ); ?>" aria-label="<?php esc_attr_e('Play voice intro', 'edtech'); ?>">
    <div class="audio-bars" aria-hidden="true">
      <div class="audio-bar"></div><div class="audio-bar"></div>
      <div class="audio-bar"></div><div class="audio-bar"></div>
      <div class="audio-bar"></div>
    </div>
    <span><?php is_rtl() ? _e('مقدمة صوتية', 'edtech') : _e('Voice Intro', 'edtech'); ?></span>
  </button>
  <?php endif; ?>
</div>
