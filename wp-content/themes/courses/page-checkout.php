<?php
/**
 * Template Name: Checkout Page
 *
 * Maps 1-to-1 to checkout.html and ar/checkout.html
 *
 * @package EdTech
 */

get_header();
?>

<main>
<section class="section-padding">
  <div class="container container-wide">
    <h1 style="margin-bottom:var(--space-xl);"><?php is_rtl() ? _e('الدفع والتسجيل الآمن', 'edtech') : _e('Secure Checkout', 'edtech'); ?></h1>

    <div style="display:grid;grid-template-columns:1fr 400px;gap:var(--space-xl);">

      <!-- Payment Form -->
      <div>
        <div class="card reveal" style="margin-bottom:var(--space-lg);">
          <h3 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('تسجيل الدخول السريع', 'edtech') : _e('Quick Sign-In', 'edtech'); ?></h3>
          <div style="display:flex;gap:var(--space-sm);flex-direction:column;">
            <button class="btn btn-secondary btn-lg" style="display:flex;align-items:center;gap:var(--space-sm);justify-content:center;">
              <?php is_rtl() ? _e('المتابعة باستخدام Google', 'edtech') : _e('Continue with Google', 'edtech'); ?>
            </button>
            <button class="btn" style="background:#000;color:white;display:flex;align-items:center;gap:var(--space-sm);justify-content:center;height:52px;">
              <?php is_rtl() ? _e('المتابعة باستخدام Apple', 'edtech') : _e('Continue with Apple', 'edtech'); ?>
            </button>
          </div>
        </div>

        <div class="card reveal">
          <h3 style="margin-bottom:var(--space-lg);"><?php is_rtl() ? _e('تفاصيل البطاقة البنكية', 'edtech') : _e('Card Payment Details', 'edtech'); ?></h3>
          <form id="checkout-form" onsubmit="event.preventDefault();showToast('<?php is_rtl() ? _e('تم التسجيل بنجاح! تفقد بريدك الإلكتروني. 🎉', 'edtech') : _e('Enrollment successful! Check your email. 🎉', 'edtech'); ?>');">
            <div class="form-group">
              <label class="form-label" for="cardholder-name"><?php is_rtl() ? _e('اسم صاحب البطاقة', 'edtech') : _e('Cardholder Name', 'edtech'); ?></label>
              <input id="cardholder-name" type="text" class="input-field" placeholder="<?php is_rtl() ? _e('أحمد السيد', 'edtech') : _e('Ahmed Al-Sayed', 'edtech'); ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="card-number"><?php is_rtl() ? _e('رقم البطاقة', 'edtech') : _e('Card Number', 'edtech'); ?></label>
              <input id="card-number" type="text" class="input-field" placeholder="4242 4242 4242 4242" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;"><?php is_rtl() ? _e('إتمام الاشتراك — 49$', 'edtech') : _e('Complete Enrollment — $49', 'edtech'); ?></button>
          </form>
        </div>
      </div>

      <!-- Order Summary -->
      <aside>
        <div class="card">
          <h3 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('ملخص الطلب', 'edtech') : _e('Order Summary', 'edtech'); ?></h3>
          <div style="display:flex;justify-content:space-between;font-weight:700;font-size:1.1rem;">
            <span><?php is_rtl() ? _e('المجموع الإجمالي', 'edtech') : _e('Total', 'edtech'); ?></span>
            <span>$49</span>
          </div>
        </div>
      </aside>

    </div>
  </div>
</section>
</main>

<?php
get_footer();
