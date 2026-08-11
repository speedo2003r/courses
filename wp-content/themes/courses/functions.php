<?php
/**
 * EdTech Platform Theme functions and definitions
 *
 * @package EdTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once get_template_directory() . '/inc/seeder.php';
require_once get_template_directory() . '/inc/content-model.php';
require_once get_template_directory() . '/inc/forms.php';
require_once get_template_directory() . '/inc/customizer.php';

/**
 * Theme Setup
 */
function edtech_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 48, 'width' => 220, 'flex-height' => true, 'flex-width' => true ) );

	register_nav_menus( array(
		'primary'        => __( 'Primary Navigation', 'edtech' ),
		'footer-quick'   => __( 'Footer Quick Links', 'edtech' ),
		'footer-support' => __( 'Footer Student Support', 'edtech' ),
	) );

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'edtech_setup' );

/**
 * Auto-create critical pages on theme activation.
 * Ensures pages like /course-builder/ exist on any server the theme is
 * activated on, even if the seeder hasn't been run yet.
 */
function edtech_ensure_pages_on_activation() {
	edtech_ensure_critical_pages();
}
add_action( 'after_switch_theme', 'edtech_ensure_pages_on_activation' );

/**
 * Self-healing: check for missing critical pages on init (once per hour).
 * If a page was deleted or never created, it gets recreated automatically.
 */
function edtech_ensure_critical_pages() {
	// Use a transient so we don't query on every single page load.
	if ( get_transient( 'edtech_pages_checked' ) ) {
		return;
	}

	$pages = array(
		'course-builder' => array(
			'title'    => 'Course Builder',
			'template'  => 'page-course-builder.php',
		),
	);

	$created = false;
	foreach ( $pages as $slug => $args ) {
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $existing ) {
			$page_id = wp_insert_post( array(
				'post_title'  => $args['title'],
				'post_name'   => $slug,
				'post_status' => 'publish',
				'post_type'   => 'page',
				'post_content' => '',
			) );
			if ( $page_id && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $args['template'] );
				$created = true;
			}
		}
	}

	if ( $created ) {
		flush_rewrite_rules();
	}

	// Don't check again for an hour.
	set_transient( 'edtech_pages_checked', 1, HOUR_IN_SECONDS );
}
add_action( 'init', 'edtech_ensure_critical_pages' );

/**
 * Language Switcher Handling (Arabic <-> English)
 */
function edtech_init_language() {
	$lang = '';
	if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], array( 'ar', 'en' ), true ) ) {
		$lang = sanitize_text_field( $_GET['lang'] );
		if ( ! headers_sent() ) {
			setcookie( 'edtech_lang', $lang, time() + ( 30 * DAY_IN_SECONDS ), '/' );
		}
	} elseif ( isset( $_COOKIE['edtech_lang'] ) && in_array( $_COOKIE['edtech_lang'], array( 'ar', 'en' ), true ) ) {
		$lang = $_COOKIE['edtech_lang'];
	}

	global $wp_locale;
	if ( $lang === 'en' ) {
		switch_to_locale( 'en_US' );
		if ( isset( $wp_locale ) ) {
			$wp_locale->text_direction = 'ltr';
		}
	} else {
		switch_to_locale( 'ar' );
		if ( isset( $wp_locale ) ) {
			$wp_locale->text_direction = 'rtl';
		}
	}
}
add_action( 'init', 'edtech_init_language', 1 );

/**
 * Handle Login & Registration Form Submissions
 */
function edtech_handle_auth_forms() {
	if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
		return;
	}

	// Login Handler
	if ( isset( $_POST['edtech_action'] ) && $_POST['edtech_action'] === 'login' ) {
		if ( ! isset( $_POST['edtech_login_nonce'] ) || ! wp_verify_nonce( $_POST['edtech_login_nonce'], 'edtech_login_action' ) ) {
			return;
		}

		$log = sanitize_text_field( $_POST['log'] );
		$pwd = $_POST['pwd'];
		$rem = ! empty( $_POST['rememberme'] );
		$redirect = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : edtech_page_url( 'student-dashboard' );

		$creds = array(
			'user_login'    => $log,
			'user_password' => $pwd,
			'remember'      => $rem,
		);

		$user = wp_signon( $creds, is_ssl() );

		if ( is_wp_error( $user ) ) {
			$error_msg = urlencode( strip_tags( $user->get_error_message() ) );
			wp_safe_redirect( add_query_arg( array( 'auth_err' => $error_msg, 'tab' => 'login' ), $redirect ) );
			exit;
		} else {
			wp_safe_redirect( $redirect );
			exit;
		}
	}

	// Register Handler
	if ( isset( $_POST['edtech_action'] ) && $_POST['edtech_action'] === 'register' ) {
		if ( ! isset( $_POST['edtech_register_nonce'] ) || ! wp_verify_nonce( $_POST['edtech_register_nonce'], 'edtech_register_action' ) ) {
			return;
		}

		$username = sanitize_user( $_POST['username'] );
		$email    = sanitize_email( $_POST['email'] );
		$password = $_POST['password'];
		$redirect = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : edtech_page_url( 'student-dashboard' );

		if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
			$error_msg = urlencode( is_rtl() ? 'يرجى ملء جميع الحقول المطلوبة.' : 'Please fill in all required fields.' );
			wp_safe_redirect( add_query_arg( array( 'auth_err' => $error_msg, 'tab' => 'register' ), $redirect ) );
			exit;
		}

		if ( username_exists( $username ) ) {
			$error_msg = urlencode( is_rtl() ? 'اسم المستخدم مستخدم بالفعل.' : 'Username already exists.' );
			wp_safe_redirect( add_query_arg( array( 'auth_err' => $error_msg, 'tab' => 'register' ), $redirect ) );
			exit;
		}

		if ( email_exists( $email ) ) {
			$error_msg = urlencode( is_rtl() ? 'البريد الإلكتروني مستخدم بالفعل.' : 'Email address is already in use.' );
			wp_safe_redirect( add_query_arg( array( 'auth_err' => $error_msg, 'tab' => 'register' ), $redirect ) );
			exit;
		}

		$user_id = wp_create_user( $username, $password, $email );

		if ( is_wp_error( $user_id ) ) {
			$error_msg = urlencode( strip_tags( $user_id->get_error_message() ) );
			wp_safe_redirect( add_query_arg( array( 'auth_err' => $error_msg, 'tab' => 'register' ), $redirect ) );
			exit;
		} else {
			// Auto signon after creation
			$creds = array(
				'user_login'    => $username,
				'user_password' => $password,
				'remember'      => true,
			);
			wp_signon( $creds, is_ssl() );
			wp_safe_redirect( add_query_arg( 'auth_msg', urlencode( is_rtl() ? 'تم إنشاء الحساب وتسجيل الدخول بنجاح!' : 'Account created and logged in!' ), $redirect ) );
			exit;
		}
	}
}
add_action( 'init', 'edtech_handle_auth_forms' );

/**
 * Enqueue scripts and styles.
 */
function edtech_enqueue_scripts() {
	wp_enqueue_style( 'edtech-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&family=Cairo:wght@400;600;700;800&family=Alexandria:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap', array(), null );
	wp_enqueue_style( 'edtech-tokens', get_template_directory_uri() . '/assets/css/tokens.css', array(), '1.2.0' );
	wp_enqueue_style( 'edtech-main', get_template_directory_uri() . '/assets/css/main.css', array( 'edtech-tokens' ), '1.2.0' );
	wp_enqueue_style( 'edtech-components', get_template_directory_uri() . '/assets/css/components.css', array( 'edtech-main' ), '1.2.0' );
	wp_enqueue_style( 'edtech-style', get_stylesheet_uri(), array( 'edtech-components' ), '1.2.0' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'edtech-rtl', get_template_directory_uri() . '/rtl.css', array( 'edtech-style' ), '1.2.0' );
	}

	wp_enqueue_script( 'edtech-app', get_template_directory_uri() . '/assets/js/app.js', array(), '1.2.0', true );
	wp_enqueue_script( 'edtech-audio', get_template_directory_uri() . '/assets/js/audio.js', array( 'edtech-app' ), '1.2.0', true );
	wp_enqueue_script( 'edtech-player', get_template_directory_uri() . '/assets/js/player.js', array( 'edtech-app' ), '1.2.0', true );
	wp_enqueue_script( 'edtech-filter', get_template_directory_uri() . '/assets/js/filter.js', array( 'edtech-app' ), '1.2.0', true );
	wp_enqueue_script( 'edtech-search', get_template_directory_uri() . '/assets/js/search.js', array( 'edtech-app' ), '1.2.0', true );

	// Localize real course data so search.js no longer depends on a hardcoded array.
	$search_courses = get_posts( array(
		'post_type'      => 'course',
		'posts_per_page' => 30,
		'post_status'    => 'publish',
	) );
	$search_data = array();
	foreach ( $search_courses as $c ) {
		$terms   = wp_get_post_terms( $c->ID, 'course_category', array( 'fields' => 'names' ) );
		$meta    = edtech_get_course_meta( $c->ID );
		$search_data[] = array(
			'title'    => get_the_title( $c ),
			'url'      => get_permalink( $c ),
			'category' => ! empty( $terms ) ? $terms[0] : '',
			'price'    => $meta['price'],
		);
	}
	wp_localize_script( 'edtech-search', 'EDTECH_SEARCH', array(
		'courses'  => $search_data,
		'catalog'  => get_post_type_archive_link( 'course' ),
		'is_rtl'   => is_rtl(),
	) );
}
add_action( 'wp_enqueue_scripts', 'edtech_enqueue_scripts' );

/**
 * Register Custom Post Types & Taxonomies
 */
function edtech_register_custom_post_types() {
	register_post_type( 'course', array(
		'labels' => array(
			'name'          => __( 'Courses', 'edtech' ),
			'singular_name' => __( 'Course', 'edtech' ),
			'add_new_item'  => __( 'Add New Course', 'edtech' ),
			'edit_item'     => __( 'Edit Course', 'edtech' ),
		),
		'public'       => true,
		'has_archive'  => 'catalog',
		'menu_icon'    => 'dashicons-welcome-learn-more',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'rewrite'      => array( 'slug' => 'course' ),
		'show_in_rest' => true,
	) );

	register_taxonomy( 'course_category', 'course', array(
		'labels' => array(
			'name'          => __( 'Course Categories', 'edtech' ),
			'singular_name' => __( 'Course Category', 'edtech' ),
		),
		'hierarchical' => true,
		'public'       => true,
		'show_in_rest' => true,
	) );

	register_taxonomy( 'course_level', 'course', array(
		'labels' => array(
			'name'          => __( 'Course Levels', 'edtech' ),
			'singular_name' => __( 'Course Level', 'edtech' ),
		),
		'hierarchical' => false,
		'public'       => true,
		'show_in_rest' => true,
	) );

	register_post_type( 'instructor', array(
		'labels' => array(
			'name'          => __( 'Instructors', 'edtech' ),
			'singular_name' => __( 'Instructor', 'edtech' ),
		),
		'public'       => true,
		'has_archive'  => 'instructors',
		'menu_icon'    => 'dashicons-businesswoman',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'rewrite'      => array( 'slug' => 'instructor' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'learning_path', array(
		'labels' => array(
			'name'          => __( 'Learning Paths', 'edtech' ),
			'singular_name' => __( 'Learning Path', 'edtech' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-chart-line',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'rewrite'      => array( 'slug' => 'learning-path' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'faq', array(
		'labels' => array(
			'name'          => __( 'FAQ Items', 'edtech' ),
			'singular_name' => __( 'FAQ Item', 'edtech' ),
			'add_new_item'  => __( 'Add New FAQ Item', 'edtech' ),
			'edit_item'     => __( 'Edit FAQ Item', 'edtech' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-format-status',
		'supports'     => array( 'title', 'editor', 'page-attributes' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'testimonial', array(
		'labels' => array(
			'name'          => __( 'Testimonials', 'edtech' ),
			'singular_name' => __( 'Testimonial', 'edtech' ),
			'add_new_item'  => __( 'Add New Testimonial', 'edtech' ),
			'edit_item'     => __( 'Edit Testimonial', 'edtech' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-testimonial',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'team', array(
		'labels' => array(
			'name'          => __( 'Team Members', 'edtech' ),
			'singular_name' => __( 'Team Member', 'edtech' ),
			'add_new_item'  => __( 'Add New Team Member', 'edtech' ),
			'edit_item'     => __( 'Edit Team Member', 'edtech' ),
		),
		'public'       => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-groups',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 'edtech_register_custom_post_types' );

/**
 * Admin Menu: Seeder Control Panel
 */
function edtech_add_seeder_admin_menu() {
	add_management_page(
		__( 'EdTech Data Seeder / مولّد البيانات', 'edtech' ),
		__( 'EdTech Data Seeder', 'edtech' ),
		'manage_options',
		'edtech-seeder',
		'edtech_render_seeder_admin_page'
	);
}
add_action( 'admin_menu', 'edtech_add_seeder_admin_menu' );

/**
 * Render Seeder Admin Page with Counter Controls
 */
function edtech_render_seeder_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$message = isset( $_GET['seeder_msg'] ) ? sanitize_text_field( $_GET['seeder_msg'] ) : '';
	?>
	<div class="wrap">
		<h1>🚀 EdTech Data Seeder / لوحة التحكم في توليد الصفحات والبيانات</h1>
		<p>استخدم هذه اللوحة لتوليد الصفحات والدورات والمدربين أو مسحها بالعدادات التي تحددها.</p>

		<?php if ( $message ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
		<?php endif; ?>

		<div class="card" style="max-width:600px;padding:20px;background:#fff;border:1px solid #ccd0d4;border-radius:4px;margin-top:20px;">
			<h2>التحكم في العدادات (Counter Controls)</h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'edtech_seeder_run_action', 'edtech_seeder_nonce' ); ?>
				<input type="hidden" name="action" value="run_edtech_seeder">

				<table class="form-table">
					<tr>
						<th scope="row"><label for="pages_count">عدد الصفحات الرئيسية (Pages Count):</label></th>
						<td><input type="number" id="pages_count" name="pages_count" value="12" min="1" max="15" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="courses_count">عدد الدورات (Courses Count):</label></th>
						<td><input type="number" id="courses_count" name="courses_count" value="6" min="1" max="20" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="instructors_count">عدد المدربين (Instructors Count):</label></th>
						<td><input type="number" id="instructors_count" name="instructors_count" value="4" min="1" max="10" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="posts_count">عدد المقالات (Posts Count):</label></th>
						<td><input type="number" id="posts_count" name="posts_count" value="3" min="1" max="10" class="regular-text"></td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" class="button button-primary button-hero" value="⚡ تشغيل مولّد البيانات والصفحات (Run Seeder)">
				</p>
			</form>

			<hr style="margin-block:20px;">

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('هل أنت تأكد من مسح جميع الصفحات والبيانات؟');">
				<?php wp_nonce_field( 'edtech_seeder_clear_action', 'edtech_seeder_nonce' ); ?>
				<input type="hidden" name="action" value="clear_edtech_seeder">
				<p class="submit">
					<input type="submit" class="button button-secondary" value="🗑️ مسح البيانات والصفحات المنشأة (Clear Seeded Data)">
				</p>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Handle Admin Post Action: Run Seeder
 */
function edtech_handle_run_seeder() {
	if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['edtech_seeder_nonce'] ) || ! wp_verify_nonce( $_POST['edtech_seeder_nonce'], 'edtech_seeder_run_action' ) ) {
		wp_die( 'Unauthorized request.' );
	}

	$courses_count     = isset( $_POST['courses_count'] ) ? intval( $_POST['courses_count'] ) : 6;
	$instructors_count = isset( $_POST['instructors_count'] ) ? intval( $_POST['instructors_count'] ) : 4;
	$posts_count       = isset( $_POST['posts_count'] ) ? intval( $_POST['posts_count'] ) : 3;
	$pages_count       = isset( $_POST['pages_count'] ) ? intval( $_POST['pages_count'] ) : 12;

	EdTech_Seeder::run( $courses_count, $instructors_count, $posts_count, $pages_count );

	wp_safe_redirect( admin_url( 'tools.php?page=edtech-seeder&seeder_msg=' . urlencode( "تم توليد الصفحات والبيانات بنجاح! ({$pages_count} صفحة، {$courses_count} دورة، {$instructors_count} مدرب، {$posts_count} مقال)" ) ) );
	exit;
}
add_action( 'admin_post_run_edtech_seeder', 'edtech_handle_run_seeder' );

/**
 * Handle Admin Post Action: Clear Seeder
 */
function edtech_handle_clear_seeder() {
	if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['edtech_seeder_nonce'] ) || ! wp_verify_nonce( $_POST['edtech_seeder_nonce'], 'edtech_seeder_clear_action' ) ) {
		wp_die( 'Unauthorized request.' );
	}

	EdTech_Seeder::clear_all();

	wp_safe_redirect( admin_url( 'tools.php?page=edtech-seeder&seeder_msg=' . urlencode( 'تم مسح جميع الصفحات والبيانات بنجاح.' ) ) );
	exit;
}
add_action( 'admin_post_clear_edtech_seeder', 'edtech_handle_clear_seeder' );

/**
 * Helper Functions
 */
function edtech_render_stars( $rating = 4.9 ) {
	$stars = '★★★★★';
	return '<span class="stars" style="font-size:13px;color:var(--color-accent);">' . $stars . '</span>';
}

/**
 * Fallback for wp_nav_menu() — prints the standard page list as nav links
 * so navigation works before an admin creates a custom menu.
 */
function edtech_nav_menu_fallback( $args = array() ) {
	$menu_class = ! empty( $args['menu_class'] ) ? $args['menu_class'] : 'nav-links';
	$items = array(
		'catalog'         => array( is_rtl() ? 'الدورات' : 'Courses', get_post_type_archive_link( 'course' ) ),
		'learning-paths'   => array( is_rtl() ? 'مسارات التعلم' : 'Learning Paths', edtech_page_url( 'learning-paths' ) ),
		'instructors'      => array( is_rtl() ? 'المدربون' : 'Instructors', get_post_type_archive_link( 'instructor' ) ),
		'free-masterclass' => array( is_rtl() ? 'ماستر كلاس مجاني' : 'Free Masterclass', edtech_page_url( 'free-masterclass' ) ),
		'blog'             => array( is_rtl() ? 'المدونة' : 'Blog', get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog' ) ),
		'about'            => array( is_rtl() ? 'من نحن' : 'About', edtech_page_url( 'about' ) ),
		'faq'              => array( is_rtl() ? 'الدعم' : 'FAQ', edtech_page_url( 'faq' ) ),
	);
	printf( '<ul class="%s" role="list">', esc_attr( $menu_class ) );
	foreach ( $items as $slug => $data ) {
		printf(
			'<li><a href="%s" class="nav-link" data-nav="%s">%s</a></li>',
			esc_url( $data[1] ),
			esc_attr( $slug ),
			esc_html( $data[0] )
		);
	}
	echo '</ul>';
}

/**
 * Footer quick-links fallback (mirrors the nav fallback for footer columns).
 */
function edtech_footer_menu_fallback( $args ) {
	$sets = array(
		'footer-quick'   => array(
			array( is_rtl() ? 'كتالوج الدورات' : 'Courses Catalog', get_post_type_archive_link( 'course' ) ),
			array( is_rtl() ? 'مسارات التعلم' : 'Learning Paths', edtech_page_url( 'learning-paths' ) ),
			array( is_rtl() ? 'المدربون' : 'Instructors', get_post_type_archive_link( 'instructor' ) ),
			array( is_rtl() ? 'المدونة والموارد' : 'Blog & Resources', get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog' ) ),
			array( is_rtl() ? 'من نحن' : 'About Us', edtech_page_url( 'about' ) ),
		),
		'footer-support' => array(
			array( is_rtl() ? 'الأسئلة الشائعة' : 'FAQ & Help Center', edtech_page_url( 'faq' ) ),
			array( is_rtl() ? 'لوحة الطالب' : 'Student Dashboard', edtech_page_url( 'student-dashboard' ) ),
			array( is_rtl() ? 'شهاداتي' : 'My Certificates', edtech_page_url( 'certificates' ) ),
			array( is_rtl() ? 'إعدادات الحساب' : 'Account Settings', edtech_page_url( 'student-settings' ) ),
			array( is_rtl() ? 'الدفع والتسجيل' : 'Checkout', edtech_page_url( 'checkout' ) ),
		),
	);
	$items = isset( $sets[ $args['theme_location'] ] ) ? $sets[ $args['theme_location'] ] : array();
	foreach ( $items as $item ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $item[1] ), esc_html( $item[0] ) );
	}
}


/**
 * Add Instructor Meta Box for Audio Intro URL & Job Title in WP Admin
 */
function edtech_add_instructor_metaboxes() {
	add_meta_box(
		'edtech_instructor_details',
		__( 'بيانات المدرب والمقدمة الصوتية (Instructor Details & Audio Intro)', 'edtech' ),
		'edtech_render_instructor_metabox',
		'instructor',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'edtech_add_instructor_metaboxes' );

function edtech_render_instructor_metabox( $post ) {
	wp_nonce_field( 'edtech_instructor_meta_nonce', 'instructor_meta_nonce' );
	$audio_url = get_post_meta( $post->ID, '_instructor_audio_url', true );
	$title     = get_post_meta( $post->ID, '_instructor_title', true );
	$title_ar  = get_post_meta( $post->ID, '_instructor_title_ar', true );
	?>
	<p>
		<label for="_instructor_title"><strong><?php _e( 'المسمى الوظيفي (Job Title):', 'edtech' ); ?></strong></label><br>
		<input type="text" id="_instructor_title" name="_instructor_title" value="<?php echo esc_attr( $title ); ?>" class="widefat" placeholder="Senior Full-Stack Architect">
	</p>
	<p>
		<label for="_instructor_title_ar"><strong><?php _e( 'المسمى الوظيفي بالعربية (Job Title - Arabic):', 'edtech' ); ?></strong></label><br>
		<input type="text" id="_instructor_title_ar" name="_instructor_title_ar" value="<?php echo esc_attr( $title_ar ); ?>" class="widefat" placeholder="مهندس Full-Stack أول">
	</p>
	<p>
		<label for="_instructor_audio_url"><strong><?php _e( 'رابط المقدمة الصوتية (Voice Intro Audio URL):', 'edtech' ); ?></strong></label><br>
		<input type="text" id="_instructor_audio_url" name="_instructor_audio_url" value="<?php echo esc_attr( $audio_url ); ?>" class="widefat" placeholder="assets/media/audio/tariq-intro.mp3">
		<span class="description"><?php _e( 'أدخل مسار الملف الصوتي داخل القالب (مثل assets/media/audio/tariq-intro.mp3) أو رابط MP3 مباشر.', 'edtech' ); ?></span>
	</p>
	<?php
}

function edtech_save_instructor_meta( $post_id ) {
	if ( ! isset( $_POST['instructor_meta_nonce'] ) || ! wp_verify_nonce( $_POST['instructor_meta_nonce'], 'edtech_instructor_meta_nonce' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['_instructor_audio_url'] ) ) {
		update_post_meta( $post_id, '_instructor_audio_url', sanitize_text_field( $_POST['_instructor_audio_url'] ) );
	}
	if ( isset( $_POST['_instructor_title'] ) ) {
		update_post_meta( $post_id, '_instructor_title', sanitize_text_field( $_POST['_instructor_title'] ) );
	}
	if ( isset( $_POST['_instructor_title_ar'] ) ) {
		update_post_meta( $post_id, '_instructor_title_ar', sanitize_text_field( $_POST['_instructor_title_ar'] ) );
	}
}
add_action( 'save_post_instructor', 'edtech_save_instructor_meta' );
