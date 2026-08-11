<?php
/**
 * Template Name: Checkout Page
 *
 * Maps 1-to-1 to checkout.html and ar/checkout.html
 *
 * @package EdTech
 */

get_header();

$is_logged_in = is_user_logged_in();
$current_user = wp_get_current_user();
$active_tab   = isset( $_GET['tab'] ) && $_GET['tab'] === 'register' ? 'register' : 'login';
$auth_err     = isset( $_GET['auth_err'] ) ? sanitize_text_field( $_GET['auth_err'] ) : '';
$auth_msg     = isset( $_GET['auth_msg'] ) ? sanitize_text_field( $_GET['auth_msg'] ) : '';

$course_id   = edtech_get_course_id_from_request();
$course_meta = $course_id ? edtech_get_course_meta( $course_id ) : null;
$checkout_self = $course_id ? edtech_get_checkout_url( $course_id ) : edtech_page_url( 'checkout' );
?>

<main>
<section class="section-padding">
  <div class="container container-wide">
    <h1 style="margin-bottom:var(--space-xl);"><?php is_rtl() ? _e('الدفع والتسجيل الآمن', 'edtech') : _e('Secure Checkout', 'edtech'); ?></h1>

    <div class="layout-content-aside-wide">

      <!-- Left Column: Auth + Payment Form -->
      <div>

        <?php if ( ! $is_logged_in ) : ?>
          <!-- QUICK AUTHENTICATION BOX FOR GUESTS -->
          <div class="card reveal" style="margin-bottom:var(--space-lg);border-radius:var(--radius-lg);padding:var(--space-xl);">
            <div style="margin-bottom:var(--space-md);">
              <span class="badge badge-bestseller" style="margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('خطوة 1 من 2', 'edtech') : _e('Step 1 of 2', 'edtech'); ?></span>
              <h3 style="margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('تسجيل الدخول أو إنشاء حساب للمتابعة', 'edtech') : _e('Sign In or Register to Continue', 'edtech'); ?></h3>
              <p style="font-size:13px;color:var(--color-text-muted);margin:0;"><?php is_rtl() ? _e('سجّل دخولك لحفظ بيانات الدورة في حسابك ومتابعتها من أي جهاز', 'edtech') : _e('Sign in to attach this course to your account and track progress anywhere', 'edtech'); ?></p>
            </div>

            <!-- Error & Success Notices -->
            <?php if ( $auth_err ) : ?>
              <div style="padding:var(--space-sm) var(--space-md);background:hsl(0,84%,97%);color:hsl(0,74%,42%);border:1px solid hsl(0,74%,88%);border-radius:var(--radius-md);margin-bottom:var(--space-md);font-size:13px;display:flex;align-items:center;gap:var(--space-xs);">
                ⚠️ <?php echo esc_html( urldecode( $auth_err ) ); ?>
              </div>
            <?php endif; ?>

            <?php if ( $auth_msg ) : ?>
              <div style="padding:var(--space-sm) var(--space-md);background:hsl(142,76%,96%);color:hsl(142,76%,28%);border:1px solid hsl(142,76%,85%);border-radius:var(--radius-md);margin-bottom:var(--space-md);font-size:13px;display:flex;align-items:center;gap:var(--space-xs);">
                ✅ <?php echo esc_html( urldecode( $auth_msg ) ); ?>
              </div>
            <?php endif; ?>

            <!-- Tabs Navigation -->
            <div class="tabs" style="margin-bottom:var(--space-lg);grid-template-columns:1fr 1fr;display:grid;">
              <button class="tab-trigger <?php echo $active_tab === 'login' ? 'active' : ''; ?>" onclick="switchCheckoutAuthTab('login')" id="co-tab-login">
                <?php is_rtl() ? _e('تسجيل الدخول الحساب', 'edtech') : _e('Sign In', 'edtech'); ?>
              </button>
              <button class="tab-trigger <?php echo $active_tab === 'register' ? 'active' : ''; ?>" onclick="switchCheckoutAuthTab('register')" id="co-tab-register">
                <?php is_rtl() ? _e('إنشاء حساب جديد', 'edtech') : _e('Register Account', 'edtech'); ?>
              </button>
            </div>

            <!-- LOGIN FORM -->
            <div id="co-panel-login" style="display:<?php echo $active_tab === 'login' ? 'block' : 'none'; ?>;">
              <form method="post" action="<?php echo esc_url( $checkout_self ); ?>">
                <?php wp_nonce_field( 'edtech_login_action', 'edtech_login_nonce' ); ?>
                <input type="hidden" name="edtech_action" value="login">
                <input type="hidden" name="redirect_to" value="<?php echo esc_url( $checkout_self ); ?>">

                <div style="margin-bottom:var(--space-sm);">
                  <label for="co-log" style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;"><?php is_rtl() ? _e('اسم المستخدم أو البريد', 'edtech') : _e('Username or Email', 'edtech'); ?></label>
                  <input type="text" name="log" id="co-log" class="input-field" placeholder="user@example.com" required style="width:100%;">
                </div>

                <div style="margin-bottom:var(--space-sm);">
                  <label for="co-pwd" style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;"><?php is_rtl() ? _e('كلمة المرور', 'edtech') : _e('Password', 'edtech'); ?></label>
                  <input type="password" name="pwd" id="co-pwd" class="input-field" placeholder="••••••••" required style="width:100%;">
                </div>

                <button type="submit" class="btn btn-secondary" style="width:100%;margin-top:var(--space-xs);"><?php is_rtl() ? _e('تسجيل الدخول والمتابعة ←', 'edtech') : _e('Sign In & Continue ←', 'edtech'); ?></button>
              </form>
            </div>

            <!-- REGISTER FORM -->
            <div id="co-panel-register" style="display:<?php echo $active_tab === 'register' ? 'block' : 'none'; ?>;">
              <form method="post" action="<?php echo esc_url( $checkout_self ); ?>">
                <?php wp_nonce_field( 'edtech_register_action', 'edtech_register_nonce' ); ?>
                <input type="hidden" name="edtech_action" value="register">
                <input type="hidden" name="redirect_to" value="<?php echo esc_url( $checkout_self ); ?>">

                <div style="margin-bottom:var(--space-sm);">
                  <label for="co-reg-user" style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;"><?php is_rtl() ? _e('اسم المستخدم', 'edtech') : _e('Username', 'edtech'); ?></label>
                  <input type="text" name="username" id="co-reg-user" class="input-field" placeholder="student_name" required style="width:100%;">
                </div>

                <div style="margin-bottom:var(--space-sm);">
                  <label for="co-reg-email" style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;"><?php is_rtl() ? _e('البريد الإلكتروني', 'edtech') : _e('Email Address', 'edtech'); ?></label>
                  <input type="email" name="email" id="co-reg-email" class="input-field" placeholder="student@example.com" required style="width:100%;">
                </div>

                <div style="margin-bottom:var(--space-sm);">
                  <label for="co-reg-pwd" style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;"><?php is_rtl() ? _e('كلمة المرور', 'edtech') : _e('Create Password', 'edtech'); ?></label>
                  <input type="password" name="password" id="co-reg-pwd" class="input-field" placeholder="••••••••" required style="width:100%;">
                </div>

                <button type="submit" class="btn btn-secondary" style="width:100%;margin-top:var(--space-xs);"><?php is_rtl() ? _e('إنشاء الحساب والمتابعة ←', 'edtech') : _e('Create Account & Continue ←', 'edtech'); ?></button>
              </form>
            </div>

            <!-- Social Auth Options -->
            <hr style="margin-block:var(--space-md);border:0;border-top:1px solid var(--color-border);">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xs);">
              <button class="btn btn-ghost" style="font-size:12px;border:1px solid var(--color-border);">Google</button>
              <button class="btn btn-ghost" style="font-size:12px;border:1px solid var(--color-border);">Apple</button>
            </div>
          </div>

          <script>
            function switchCheckoutAuthTab(tab) {
              const loginPanel = document.getElementById('co-panel-login');
              const registerPanel = document.getElementById('co-panel-register');
              const loginBtn = document.getElementById('co-tab-login');
              const registerBtn = document.getElementById('co-tab-register');

              if (tab === 'login') {
                loginPanel.style.display = 'block';
                registerPanel.style.display = 'none';
                loginBtn.classList.add('active');
                registerBtn.classList.remove('active');
              } else {
                loginPanel.style.display = 'none';
                registerPanel.style.display = 'block';
                loginBtn.classList.remove('active');
                registerBtn.classList.add('active');
              }
            }
          </script>

        <?php else : ?>
          <!-- LOGGED IN USER BADGE -->
          <div class="card reveal" style="margin-bottom:var(--space-lg);background:linear-gradient(145deg, var(--color-bg-subtle) 0%, var(--color-bg-card) 100%);padding:var(--space-md) var(--space-lg);border-inline-start:4px solid var(--color-success);border-radius:var(--radius-md);">
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-xs);">
              <div>
                <span style="font-size:12px;color:var(--color-success);font-weight:700;"><?php is_rtl() ? _e('✓ مسجّل الدخول حالياً', 'edtech') : _e('✓ Currently Signed In', 'edtech'); ?></span>
                <h4 style="font-size:15px;margin:2px 0 0 0;"><?php echo esc_html( $current_user->display_name ); ?> <span style="font-weight:normal;font-size:13px;color:var(--color-text-muted);">(<?php echo esc_html( $current_user->user_email ); ?>)</span></h4>
              </div>
              <a href="<?php echo esc_url( wp_logout_url( $checkout_self ) ); ?>" style="font-size:12px;color:var(--color-text-muted);text-decoration:underline;"><?php is_rtl() ? _e('تبديل الحساب', 'edtech') : _e('Switch Account', 'edtech'); ?></a>
            </div>
          </div>
        <?php endif; ?>

        <!-- Enrollment Form -->
        <div class="card reveal">
          <h3 style="margin-bottom:var(--space-lg);"><?php is_rtl() ? _e('إتمام التسجيل', 'edtech') : _e('Complete Enrollment', 'edtech'); ?></h3>
          <?php if ( $course_id && $course_meta ) : ?>
            <?php if ( $is_logged_in ) : ?>
              <p style="font-size:14px;color:var(--color-text-muted);margin-bottom:var(--space-md);">
                <?php is_rtl() ? _e('اضغط الزر أدناه للتسجيل في الدورة. سيتم إضافتها فوراً إلى لوحة تحكم الطالب.', 'edtech') : _e('Click the button below to enroll. The course will be added to your student dashboard instantly.', 'edtech'); ?>
              </p>
              <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'edtech_enroll', 'edtech_enroll_nonce' ); ?>
                <input type="hidden" name="action" value="edtech_enroll">
                <input type="hidden" name="course_id" value="<?php echo esc_attr( $course_id ); ?>">
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
                  <?php is_rtl() ? printf( __( 'إتمام التسجيل — $%s', 'edtech' ), esc_html( $course_meta['price'] ) ) : printf( __( 'Complete Enrollment — $%s', 'edtech' ), esc_html( $course_meta['price'] ) ); ?>
                </button>
              </form>
            <?php else : ?>
              <p style="font-size:14px;color:var(--color-text-muted);"><?php is_rtl() ? _e('سجّل الدخول أو أنشئ حساباً بالأعلى لإتمام التسجيل.', 'edtech') : _e('Sign in or register above to complete enrollment.', 'edtech'); ?></p>
            <?php endif; ?>
          <?php else : ?>
            <p style="font-size:14px;color:var(--color-text-muted);"><?php is_rtl() ? _e('لم يتم اختيار دورة. تصفح الكتالوج لاختيار دورة.', 'edtech') : _e('No course selected. Browse the catalog to choose a course.', 'edtech'); ?></p>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'course' ) ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e('تصفح الدورات ←', 'edtech') : _e('Browse Courses →', 'edtech'); ?></a>
          <?php endif; ?>
        </div>

      </div>

      <!-- Right Column: Order Summary -->
      <aside>
        <div class="card">
          <h3 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e('ملخص الطلب', 'edtech') : _e('Order Summary', 'edtech'); ?></h3>
          <?php if ( $course_id && $course_meta ) : ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:var(--space-sm);border-bottom:1px solid var(--color-border);margin-bottom:var(--space-sm);">
              <span style="font-size:14px;"><?php echo esc_html( get_the_title( $course_id ) ); ?></span>
              <?php if ( $course_meta['price_orig'] && $course_meta['price_orig'] > $course_meta['price'] ) : ?>
                <span><span style="text-decoration:line-through;color:var(--color-text-muted);font-size:12px;margin-inline-end:4px;">$<?php echo esc_html( $course_meta['price_orig'] ); ?></span><span style="font-weight:600;">$<?php echo esc_html( $course_meta['price'] ); ?></span></span>
              <?php else : ?>
                <span style="font-weight:600;">$<?php echo esc_html( $course_meta['price'] ); ?></span>
              <?php endif; ?>
            </div>
            <div style="display:flex;justify-content:space-between;font-weight:700;font-size:1.1rem;margin-top:var(--space-sm);">
              <span><?php is_rtl() ? _e('المجموع الإجمالي', 'edtech') : _e('Total', 'edtech'); ?></span>
              <span style="color:var(--color-primary);">$<?php echo esc_html( $course_meta['price'] ); ?></span>
            </div>
          <?php else : ?>
            <p style="font-size:14px;color:var(--color-text-muted);"><?php is_rtl() ? _e('لا توجد دورة محددة.', 'edtech') : _e('No course selected.', 'edtech'); ?></p>
          <?php endif; ?>
        </div>
      </aside>

    </div>
  </div>
</section>
</main>

<?php
get_footer();
