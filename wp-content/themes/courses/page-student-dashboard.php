<?php
/**
 * Template Name: Student Dashboard Page
 *
 * Handles both Login / Registration authentication and Student Dashboard view
 *
 * @package EdTech
 */

get_header();

$is_logged_in = is_user_logged_in();
$current_user = wp_get_current_user();
$active_tab   = isset( $_GET['tab'] ) && $_GET['tab'] === 'register' ? 'register' : 'login';
$auth_err     = isset( $_GET['auth_err'] ) ? sanitize_text_field( $_GET['auth_err'] ) : '';
$auth_msg     = isset( $_GET['auth_msg'] ) ? sanitize_text_field( $_GET['auth_msg'] ) : '';
?>

<main>
<section class="section-padding">
  <div class="container" style="max-width:<?php echo $is_logged_in ? '1200px' : '520px'; ?>;">

    <?php if ( ! $is_logged_in ) : ?>
      <!-- ===== AUTHENTICATION SECTION (LOGIN / REGISTER) ===== -->
      <div class="card reveal" style="padding:var(--space-xl);box-shadow:var(--shadow-xl);border-radius:var(--radius-lg);">
        
        <div style="text-align:center;margin-bottom:var(--space-lg);">
          <span class="badge badge-bestseller" style="margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('بوابة الطلاب والمعلمين', 'edtech') : _e('Student & Instructor Portal', 'edtech'); ?></span>
          <h1 style="font-size:24px;margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('مرحباً بك في منصة التعلم', 'edtech') : _e('Welcome to EdTech Platform', 'edtech'); ?></h1>
          <p style="font-size:14px;color:var(--color-text-muted);margin:0;"><?php is_rtl() ? _e('سجّل دخولك للوصول لدوراتك ومتابعة تقدّمك الأكاديمي', 'edtech') : _e('Log in to access your courses and track learning progress', 'edtech'); ?></p>
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
          <button class="tab-trigger <?php echo $active_tab === 'login' ? 'active' : ''; ?>" onclick="switchAuthTab('login')" id="tab-btn-login">
            <?php is_rtl() ? _e('تسجيل الدخول', 'edtech') : _e('Sign In', 'edtech'); ?>
          </button>
          <button class="tab-trigger <?php echo $active_tab === 'register' ? 'active' : ''; ?>" onclick="switchAuthTab('register')" id="tab-btn-register">
            <?php is_rtl() ? _e('حساب جديد (تسجيل)', 'edtech') : _e('Register Account', 'edtech'); ?>
          </button>
        </div>

        <!-- FORM 1: LOGIN -->
        <div id="auth-panel-login" style="display:<?php echo $active_tab === 'login' ? 'block' : 'none'; ?>;">
          <form method="post" action="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>">
            <?php wp_nonce_field( 'edtech_login_action', 'edtech_login_nonce' ); ?>
            <input type="hidden" name="edtech_action" value="login">

            <div style="margin-bottom:var(--space-md);">
              <label for="log" style="display:block;font-size:13px;font-weight:600;margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('اسم المستخدم أو البريد الإلكتروني', 'edtech') : _e('Username or Email Address', 'edtech'); ?></label>
              <input type="text" name="log" id="log" class="input-field" placeholder="user@example.com" required style="width:100%;">
            </div>

            <div style="margin-bottom:var(--space-md);">
              <label for="pwd" style="display:block;font-size:13px;font-weight:600;margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('كلمة المرور', 'edtech') : _e('Password', 'edtech'); ?></label>
              <input type="password" name="pwd" id="pwd" class="input-field" placeholder="••••••••" required style="width:100%;">
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-lg);font-size:13px;">
              <label style="display:flex;align-items:center;gap:var(--space-xs);cursor:pointer;">
                <input type="checkbox" name="rememberme" value="forever" checked>
                <span><?php is_rtl() ? _e('تذكرني في هذه الجلسة', 'edtech') : _e('Remember me', 'edtech'); ?></span>
              </label>
              <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" style="color:var(--color-primary);text-decoration:none;font-weight:500;"><?php is_rtl() ? _e('نسيت كلمة المرور؟', 'edtech') : _e('Forgot Password?', 'edtech'); ?></a>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;font-size:15px;"><?php is_rtl() ? _e('تسجيل الدخول الان ←', 'edtech') : _e('Sign In Now →', 'edtech'); ?></button>
          </form>
        </div>

        <!-- FORM 2: REGISTER -->
        <div id="auth-panel-register" style="display:<?php echo $active_tab === 'register' ? 'block' : 'none'; ?>;">
          <form method="post" action="<?php echo esc_url( edtech_page_url( 'student-dashboard' ) ); ?>">
            <?php wp_nonce_field( 'edtech_register_action', 'edtech_register_nonce' ); ?>
            <input type="hidden" name="edtech_action" value="register">

            <div style="margin-bottom:var(--space-md);">
              <label for="username" style="display:block;font-size:13px;font-weight:600;margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('اسم المستخدم (Username)', 'edtech') : _e('Username', 'edtech'); ?></label>
              <input type="text" name="username" id="username" class="input-field" placeholder="ahmed_student" required style="width:100%;">
            </div>

            <div style="margin-bottom:var(--space-md);">
              <label for="email" style="display:block;font-size:13px;font-weight:600;margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('البريد الإلكتروني', 'edtech') : _e('Email Address', 'edtech'); ?></label>
              <input type="email" name="email" id="email" class="input-field" placeholder="student@example.com" required style="width:100%;">
            </div>

            <div style="margin-bottom:var(--space-lg);">
              <label for="password" style="display:block;font-size:13px;font-weight:600;margin-bottom:var(--space-xs);"><?php is_rtl() ? _e('كلمة المرور جديدة', 'edtech') : _e('Create Password', 'edtech'); ?></label>
              <input type="password" name="password" id="password" class="input-field" placeholder="••••••••" required style="width:100%;">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;font-size:15px;"><?php is_rtl() ? _e('إنشاء حساب جديد وتسجيل الدخول ←', 'edtech') : _e('Create Account & Sign In →', 'edtech'); ?></button>
          </form>
        </div>

      </div>

      <script>
        function switchAuthTab(tab) {
          const loginPanel = document.getElementById('auth-panel-login');
          const registerPanel = document.getElementById('auth-panel-register');
          const loginBtn = document.getElementById('tab-btn-login');
          const registerBtn = document.getElementById('tab-btn-register');

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
      <!-- ===== DASHBOARD SECTION FOR LOGGED IN USERS ===== -->
      <?php
      $enrolled_ids = edtech_get_enrolled_course_ids( $current_user->ID );
      $my_courses  = $enrolled_ids ? get_posts( array( 'post_type' => 'course', 'posts_per_page' => -1, 'post__in' => $enrolled_ids, 'orderby' => 'post__in' ) ) : array();
      $active_course = null;
      foreach ( $my_courses as $mc ) {
        $prog = (int) get_user_meta( $current_user->ID, '_edtech_course_progress_' . $mc->ID, true );
        if ( $prog < 100 ) { $active_course = $mc; break; }
      }
      if ( ! $active_course && ! empty( $my_courses ) ) {
        $active_course = $my_courses[0];
      }
      $lesson_url = edtech_page_url( 'lesson-workspace' );
      ?>

      <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--space-md);margin-bottom:var(--space-lg);" class="reveal">
        <div>
          <span class="badge badge-free" style="margin-bottom:var(--space-xs);"><?php echo esc_html( $current_user->user_email ); ?></span>
          <h1 style="margin-bottom:4px;">
            <?php is_rtl() ? printf( __( 'مرحباً، %s! 👋', 'edtech' ), esc_html( $current_user->display_name ) ) : printf( __( 'Welcome, %s! 👋', 'edtech' ), esc_html( $current_user->display_name ) ); ?>
          </h1>
          <p style="color:var(--color-text-muted);margin:0;"><?php printf( esc_html( is_rtl() ? 'أنت مسجّل في %d دورة.' : 'You are enrolled in %d courses.' ), count( $my_courses ) ); ?></p>
        </div>
        <div style="display:flex;gap:var(--space-sm);align-items:center;">
          <a href="<?php echo esc_url( get_post_type_archive_link( 'course' ) ); ?>" class="btn btn-secondary"><?php is_rtl() ? _e( 'تصفح الدورات', 'edtech' ) : _e( 'Browse Courses', 'edtech' ); ?></a>
          <a href="<?php echo esc_url( wp_logout_url( edtech_page_url( 'student-dashboard' ) ) ); ?>" class="btn btn-ghost" style="color:var(--color-accent);"><?php is_rtl() ? _e( 'تسجيل الخروج', 'edtech' ) : _e( 'Log Out', 'edtech' ); ?></a>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 300px;gap:var(--space-xl);">
        <div>
          <!-- Active Course Banner -->
          <?php if ( $active_course ) :
            $active_prog = (int) get_user_meta( $current_user->ID, '_edtech_course_progress_' . $active_course->ID, true );
          ?>
            <div class="card reveal" style="background:linear-gradient(135deg,var(--color-primary) 0%,var(--color-secondary) 100%);color:white;padding:var(--space-xl);margin-bottom:var(--space-lg);">
              <span style="font-size:13px;opacity:0.8;display:block;margin-bottom:4px;"><?php is_rtl() ? _e( 'تابع من حيث توقفت', 'edtech' ) : _e( 'Continue Where You Left Off', 'edtech' ); ?></span>
              <h2 style="color:white;margin-bottom:6px;"><?php echo esc_html( $active_course->post_title ); ?></h2>
              <p style="opacity:0.85;font-size:14px;"><?php printf( esc_html( is_rtl() ? 'التقدم: %d%%' : 'Progress: %d%%' ), $active_prog ); ?></p>
              <a href="<?php echo esc_url( add_query_arg( 'course_id', $active_course->ID, $lesson_url ) ); ?>" class="btn btn-lg" style="background:white;color:var(--color-primary);margin-top:var(--space-md);"><?php is_rtl() ? _e( '▶ متابعة الدرس', 'edtech' ) : _e( '▶ Resume Lesson', 'edtech' ); ?></a>
            </div>
          <?php endif; ?>

          <!-- Enrolled Courses -->
          <div class="card reveal">
            <h3 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e( 'دوراتي المسجلة', 'edtech' ) : _e( 'My Enrolled Courses', 'edtech' ); ?></h3>
            <div style="display:grid;gap:var(--space-md);">
              <?php if ( ! empty( $my_courses ) ) :
                foreach ( $my_courses as $mc ) :
                  $mc_thumb = edtech_get_post_image( $mc->ID, 'medium', 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=300&auto=format&fit=crop&q=80' );
                  $mc_prog  = (int) get_user_meta( $current_user->ID, '_edtech_course_progress_' . $mc->ID, true );
                  ?>
                  <div style="display:flex;gap:var(--space-md);align-items:center;padding:var(--space-sm);background:var(--color-bg-subtle);border-radius:var(--radius-md);">
                    <img src="<?php echo esc_url( $mc_thumb ); ?>" alt="" style="width:80px;height:55px;border-radius:var(--radius-sm);object-fit:cover;">
                    <div style="flex-grow:1;">
                      <h4 style="font-size:14px;margin-bottom:2px;"><?php echo esc_html( $mc->post_title ); ?></h4>
                      <div style="width:100%;height:6px;background:var(--color-border);border-radius:3px;overflow:hidden;margin-top:6px;">
                        <div style="width:<?php echo esc_attr( $mc_prog ); ?>%;height:100%;background:var(--color-primary);"></div>
                      </div>
                    </div>
                    <a href="<?php echo esc_url( add_query_arg( 'course_id', $mc->ID, $lesson_url ) ); ?>" class="btn btn-secondary" style="font-size:12px;padding:6px 12px;"><?php is_rtl() ? _e( 'دخول', 'edtech' ) : _e( 'Open', 'edtech' ); ?></a>
                  </div>
                <?php endforeach;
              else : ?>
                <p style="color:var(--color-text-muted);text-align:center;padding:var(--space-md);"><?php is_rtl() ? _e( 'لم تسجّل في أي دورة بعد.', 'edtech' ) : _e( 'You haven\'t enrolled in any courses yet.', 'edtech' ); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <aside>
          <div class="card reveal" style="margin-bottom:var(--space-lg);">
            <h3 style="margin-bottom:var(--space-md);"><?php is_rtl() ? _e( 'إنجازاتك الدراسية', 'edtech' ) : _e( 'Achievement Stats', 'edtech' ); ?></h3>
            <?php
            $completed = 0;
            foreach ( $my_courses as $mc ) {
              $p = (int) get_user_meta( $current_user->ID, '_edtech_course_progress_' . $mc->ID, true );
              if ( 100 === $p ) $completed++;
            }
            ?>
            <p style="font-size:14px;color:var(--color-text-muted);margin-bottom:var(--space-sm);"><?php printf( esc_html( is_rtl() ? 'دورات مكتملة: %d من %d' : 'Courses completed: %d of %d' ), $completed, count( $my_courses ) ); ?></p>
            <a href="<?php echo esc_url( edtech_page_url( 'certificates' ) ); ?>" class="btn btn-secondary" style="width:100%;"><?php is_rtl() ? _e( 'عرض الشهادات', 'edtech' ) : _e( 'View Certificates', 'edtech' ); ?></a>
          </div>
        </aside>
      </div>

    <?php endif; ?>

  </div>
</section>
</main>

<?php
get_footer();
