<?php
/**
 * Template Name: Certificates Page
 *
 * Maps 1-to-1 to certificates.html and ar/certificates.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<section class="section-padding">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-xl);" class="reveal">
      <div>
        <h1 style="margin-bottom:4px;"><?php is_rtl() ? _e('شهادة الإتمام الموثّقة', 'edtech') : _e('Certificate of Completion', 'edtech'); ?></h1>
        <p style="color:var(--color-text-muted);"><?php is_rtl() ? _e('تاريخ الإصدار: 10 أغسطس 2026 · معرف الشهادة: CERT-2026-FSW-0042', 'edtech') : _e('Issued on August 10, 2026 · Certificate ID: CERT-2026-FSW-0042', 'edtech'); ?></p>
      </div>
      <div style="display:flex;gap:var(--space-sm);flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="window.print()"><?php is_rtl() ? _e('تنزيل PDF', 'edtech') : _e('Download PDF', 'edtech'); ?></button>
        <button class="btn btn-primary"><?php is_rtl() ? _e('المشاركة على LinkedIn', 'edtech') : _e('Share to LinkedIn', 'edtech'); ?></button>
      </div>
    </div>

    <!-- Certificate Canvas -->
    <div class="certificate-canvas reveal" style="max-width:860px;margin-inline:auto;">
      <div class="certificate-seal">🎓</div>
      <p style="font-size:var(--font-size-body-sm);font-weight:600;text-transform:uppercase;color:var(--color-primary);margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('شهادة إتمام وتأهيل مهني', 'edtech') : _e('Certificate of Completion', 'edtech'); ?></p>
      <h2 style="font-size:2.5rem;font-weight:800;margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('أحمد السيد', 'edtech') : _e('Ahmed Al-Sayed', 'edtech'); ?></h2>
      <h3 style="font-size:1.5rem;color:var(--color-primary);margin-bottom:var(--space-sm);"><?php is_rtl() ? _e('تطوير الويب المتكامل', 'edtech') : _e('Full-Stack Web Development', 'edtech'); ?></h3>
      <p style="font-size:14px;color:var(--color-text-muted);margin-bottom:var(--space-xl);"><?php is_rtl() ? _e('28 درساً عملياً · 12 ساعة و30 دقيقة', 'edtech') : _e('28 Lessons · 12h 30m', 'edtech'); ?></p>
    </div>
  </div>
</section>
</main>

<?php
get_footer();
