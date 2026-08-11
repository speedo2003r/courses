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

/**
 * Theme Setup
 */
function edtech_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

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
 * Language Switcher Filter (Arabic <-> English)
 */
function edtech_setup_locale( $locale ) {
	if ( isset( $_GET['lang'] ) ) {
		$lang = sanitize_text_field( $_GET['lang'] );
		if ( in_array( $lang, array( 'ar', 'en' ), true ) ) {
			if ( ! headers_sent() ) {
				setcookie( 'edtech_lang', $lang, time() + ( 30 * DAY_IN_SECONDS ), '/' );
			}
			return $lang === 'ar' ? 'ar' : 'en_US';
		}
	}
	if ( isset( $_COOKIE['edtech_lang'] ) ) {
		return $_COOKIE['edtech_lang'] === 'ar' ? 'ar' : 'en_US';
	}
	return 'ar';
}
add_filter( 'locale', 'edtech_setup_locale' );

function edtech_force_rtl( $is_rtl ) {
	if ( isset( $_GET['lang'] ) ) {
		return $_GET['lang'] === 'ar';
	}
	if ( isset( $_COOKIE['edtech_lang'] ) ) {
		return $_COOKIE['edtech_lang'] === 'ar';
	}
	return true; // Default to AR / RTL
}
add_filter( 'is_rtl', 'edtech_force_rtl' );

/**
 * Enqueue scripts and styles.
 */
function edtech_enqueue_scripts() {
	wp_enqueue_style( 'edtech-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&family=Cairo:wght@400;600;700;800&family=Alexandria:wght@600;700;800&family=JetBrains+Mono:wght@400;500&display=swap', array(), null );
	wp_enqueue_style( 'edtech-tokens', get_template_directory_uri() . '/assets/css/tokens.css', array(), '1.0.0' );
	wp_enqueue_style( 'edtech-main', get_template_directory_uri() . '/assets/css/main.css', array( 'edtech-tokens' ), '1.0.0' );
	wp_enqueue_style( 'edtech-components', get_template_directory_uri() . '/assets/css/components.css', array( 'edtech-main' ), '1.0.0' );
	wp_enqueue_style( 'edtech-style', get_stylesheet_uri(), array( 'edtech-components' ), '1.0.0' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'edtech-rtl', get_template_directory_uri() . '/rtl.css', array( 'edtech-style' ), '1.0.0' );
	}

	wp_enqueue_script( 'edtech-app', get_template_directory_uri() . '/assets/js/app.js', array(), '1.0.0', true );
	wp_enqueue_script( 'edtech-audio', get_template_directory_uri() . '/assets/js/audio.js', array( 'edtech-app' ), '1.0.0', true );
	wp_enqueue_script( 'edtech-player', get_template_directory_uri() . '/assets/js/player.js', array( 'edtech-app' ), '1.0.0', true );
	wp_enqueue_script( 'edtech-filter', get_template_directory_uri() . '/assets/js/filter.js', array( 'edtech-app' ), '1.0.0', true );
	wp_enqueue_script( 'edtech-search', get_template_directory_uri() . '/assets/js/search.js', array( 'edtech-app' ), '1.0.0', true );
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
		'has_archive'  => true,
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
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-chart-line',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'rewrite'      => array( 'slug' => 'learning-path' ),
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

	wp_redirect( admin_url( 'tools.php?page=edtech-seeder&seeder_msg=' . urlencode( "تم توليد الصفحات والبيانات بنجاح! ({$pages_count} صفحة، {$courses_count} دورة، {$instructors_count} مدرب، {$posts_count} مقال)" ) ) );
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

	wp_redirect( admin_url( 'tools.php?page=edtech-seeder&seeder_msg=' . urlencode( 'تم مسح جميع الصفحات والبيانات بنجاح.' ) ) );
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

function edtech_get_course_meta( $post_id ) {
	return array(
		'price'         => get_post_meta( $post_id, '_course_price', true ) ?: '49',
		'price_orig'    => get_post_meta( $post_id, '_course_price_orig', true ) ?: '149',
		'duration'      => get_post_meta( $post_id, '_course_duration', true ) ?: '12h 30m',
		'lessons_count' => get_post_meta( $post_id, '_course_lessons_count', true ) ?: '28',
		'rating'        => get_post_meta( $post_id, '_course_rating', true ) ?: '4.9',
		'reviews_count' => get_post_meta( $post_id, '_course_reviews_count', true ) ?: '1,240',
		'badge'         => get_post_meta( $post_id, '_course_badge', true ) ?: 'Bestseller',
		'instructor'    => get_post_meta( $post_id, '_course_instructor_name', true ) ?: 'Eng. Tariq Mansour',
	);
}
