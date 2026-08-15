<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package EdTech
 */

get_header();
?>

<main id="primary" class="site-main">
  <div class="container section-padding" style="text-align:center;">
    <div class="card reveal" style="max-width:580px;margin-inline:auto;padding:var(--space-2xl);">
      <h1 style="font-size:4rem;color:var(--color-primary);margin-bottom:var(--space-xs);">404</h1>
      <h2 style="margin-bottom:var(--space-md);"><?php wpm_is_rtl() ? _e('عذراً! هذه الصفحة غير موجودة', 'edtech') : _e('Oops! That Page Can’t Be Found', 'edtech'); ?></h2>
      <p style="color:var(--color-text-muted);margin-bottom:var(--space-xl);"><?php wpm_is_rtl() ? _e('يبدو أن الرابط الذي حاولت الوصول إليه غير صحيح أو تم نقله.', 'edtech') : _e('It looks like nothing was found at this location. Try searching or go back to home.', 'edtech'); ?></p>
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary btn-lg"><?php wpm_is_rtl() ? _e('العودة للرئيسية', 'edtech') : _e('Back to Home', 'edtech'); ?></a>
    </div>
  </div>
</main>

<?php
get_footer();
