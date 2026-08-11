<?php
/**
 * Template part for displaying an Instructor Authority Card
 *
 * @package EdTech
 */

$name = isset($args['name']) ? $args['name'] : (is_rtl() ? 'م. طارق منصور' : 'Eng. Tariq Mansour');
$title = isset($args['title']) ? $args['title'] : (is_rtl() ? 'مهندس Full-Stack أول' : 'Senior Full-Stack Architect');
$img = isset($args['img']) ? $args['img'] : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80';
$audio = isset($args['audio']) ? $args['audio'] : 'public/assets/media/audio/tariq-intro.mp3';
$stats = isset($args['stats']) ? $args['stats'] : (is_rtl() ? '★ 4.9 · 1,240 طالب' : '★ 4.9 · 1,240 Students');
?>
<div class="card card-hover instructor-card reveal">
  <img class="instructor-avatar" src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
  <div style="display:flex;align-items:center;justify-content:center;gap:4px;margin-bottom:6px;">
    <h4 style="margin:0;"><?php echo esc_html($name); ?></h4>
    <svg width="16" height="16" fill="var(--color-primary)" aria-label="<?php esc_attr_e('Verified', 'edtech'); ?>"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01" fill="none" stroke="white" stroke-width="2"/></svg>
  </div>
  <p style="font-size:13px;color:var(--color-primary);font-weight:600;margin-bottom:4px;"><?php echo esc_html($title); ?></p>
  <p style="font-size:12px;color:var(--color-text-muted);margin-bottom:var(--space-md);"><?php echo esc_html($stats); ?></p>
  <?php
  $audio_src = ( strpos( $audio, 'http://' ) === 0 || strpos( $audio, 'https://' ) === 0 ) 
    ? $audio 
    : get_template_directory_uri() . '/' . ltrim( $audio, '/' );
  ?>
  <button class="audio-btn" data-audio="<?php echo esc_url( $audio_src ); ?>" aria-label="<?php esc_attr_e('Play voice intro', 'edtech'); ?>">
    <div class="audio-bars" aria-hidden="true">
      <div class="audio-bar"></div><div class="audio-bar"></div>
      <div class="audio-bar"></div><div class="audio-bar"></div>
      <div class="audio-bar"></div>
    </div>
    <span><?php is_rtl() ? _e('مقدمة صوتية', 'edtech') : _e('Voice Intro', 'edtech'); ?></span>
  </button>
</div>
