<?php
/**
 * Content model and reusable theme helpers.
 *
 * @package EdTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function edtech_page_url( $slug ) {
	$page = get_page_by_path( sanitize_title( $slug ) );
	return $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
}

function edtech_safe_return_url( $candidate, $fallback = '' ) {
	$fallback = $fallback ?: home_url( '/' );
	return wp_validate_redirect( esc_url_raw( wp_unslash( $candidate ) ), $fallback );
}

function edtech_get_site_setting( $key, $default = '' ) {
	return get_theme_mod( 'edtech_' . $key, $default );
}

/**
 * Resolve a post's display image: real featured thumbnail first, then a
 * _thumbnail_url meta value (used by the seeder), then a placeholder.
 */
function edtech_get_post_image( $post_id = 0, $size = 'medium_large', $placeholder = '' ) {
	$post_id = $post_id ?: get_the_ID();
	$thumb   = get_the_post_thumbnail_url( $post_id, $size );
	if ( $thumb ) {
		return $thumb;
	}
	$url = get_post_meta( $post_id, '_thumbnail_url', true );
	if ( $url ) {
		return $url;
	}
	return $placeholder ?: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80';
}

function edtech_get_course_id_from_request() {
	$course_id = isset( $_REQUEST['course_id'] ) ? absint( $_REQUEST['course_id'] ) : 0;
	return 'course' === get_post_type( $course_id ) ? $course_id : 0;
}

function edtech_get_checkout_url( $course_id = 0 ) {
	$url = edtech_page_url( 'checkout' );
	return $course_id ? add_query_arg( 'course_id', absint( $course_id ), $url ) : $url;
}

function edtech_get_enrolled_course_ids( $user_id = 0 ) {
	$user_id = $user_id ?: get_current_user_id();
	$ids     = get_user_meta( $user_id, '_edtech_enrolled_courses', true );
	return array_values( array_filter( array_map( 'absint', is_array( $ids ) ? $ids : array() ) ) );
}

function edtech_user_is_enrolled( $course_id, $user_id = 0 ) {
	return in_array( absint( $course_id ), edtech_get_enrolled_course_ids( $user_id ), true );
}

function edtech_get_course_meta( $post_id ) {
	$defaults = array(
		'price'         => '49',
		'price_orig'    => '149',
		'duration'      => '12h 30m',
		'lessons_count' => '28',
		'rating'        => '4.9',
		'reviews_count' => '0',
		'badge'         => '',
		'instructor'    => '',
		'preview_url'   => '',
		'syllabus'      => '',
		'outcomes'      => '',
		'skills'        => '',
	);
	$values = array();
	foreach ( $defaults as $key => $default ) {
		$value          = get_post_meta( $post_id, '_course_' . $key, true );
		$values[ $key ] = '' !== $value ? $value : $default;
	}
	return $values;
}

function edtech_register_content_meta() {
	$course_fields = array(
		'price'         => 'number',
		'price_orig'    => 'number',
		'duration'      => 'string',
		'lessons_count' => 'integer',
		'rating'        => 'number',
		'reviews_count' => 'integer',
		'badge'         => 'string',
		'instructor'    => 'string',
		'preview_url'   => 'string',
		'syllabus'      => 'string',
		'outcomes'      => 'string',
		'skills'        => 'string',
	);

	foreach ( $course_fields as $key => $type ) {
		register_post_meta( 'course', '_course_' . $key, array(
			'type'              => $type,
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'string' === $type ? 'sanitize_text_field' : null,
			'auth_callback'     => function() {
				return current_user_can( 'edit_posts' );
			},
		) );
	}

	register_post_meta( 'instructor', '_instructor_title', array(
		'type' => 'string', 'single' => true, 'show_in_rest' => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );
	register_post_meta( 'instructor', '_instructor_audio_url', array(
		'type' => 'string', 'single' => true, 'show_in_rest' => true,
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );
}
add_action( 'init', 'edtech_register_content_meta', 20 );

function edtech_add_course_metabox() {
	add_meta_box(
		'edtech_course_details',
		__( 'Course Details', 'edtech' ),
		'edtech_render_course_metabox',
		'course',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_course', 'edtech_add_course_metabox' );

function edtech_render_course_metabox( $post ) {
	$meta = edtech_get_course_meta( $post->ID );
	$fields = array(
		'price' => __( 'Price', 'edtech' ),
		'price_orig' => __( 'Original price', 'edtech' ),
		'duration' => __( 'Duration', 'edtech' ),
		'lessons_count' => __( 'Lessons count', 'edtech' ),
		'rating' => __( 'Rating', 'edtech' ),
		'reviews_count' => __( 'Reviews count', 'edtech' ),
		'badge' => __( 'Badge', 'edtech' ),
		'instructor' => __( 'Instructor name', 'edtech' ),
		'preview_url' => __( 'Preview video URL', 'edtech' ),
	);
	wp_nonce_field( 'edtech_save_course', 'edtech_course_nonce' );
	echo '<table class="form-table"><tbody>';
	foreach ( $fields as $key => $label ) {
		printf(
			'<tr><th><label for="course-%1$s">%2$s</label></th><td><input class="regular-text" id="course-%1$s" name="_course_%1$s" value="%3$s"></td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $meta[ $key ] )
		);
	}
	echo '</tbody></table>';

	$textarea_fields = array(
		'skills'   => __( 'Skills Gained (one per line, prefix with ✓)', 'edtech' ),
		'outcomes' => __( 'Project Showcase / Outcomes (HTML allowed)', 'edtech' ),
		'syllabus' => __( 'Course Syllabus (HTML allowed — use accordion-item markup)', 'edtech' ),
	);
	echo '<table class="form-table"><tbody>';
	foreach ( $textarea_fields as $key => $label ) {
		printf(
			'<tr><th><label for="course-%1$s">%2$s</label></th><td><textarea rows="6" class="large-text" id="course-%1$s" name="_course_%1$s">%3$s</textarea></td></tr>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_textarea( $meta[ $key ] )
		);
	}
	echo '</tbody></table>';
}

function edtech_save_course_meta( $post_id ) {
	if ( ! isset( $_POST['edtech_course_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['edtech_course_nonce'] ) ), 'edtech_save_course' ) ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = array( 'duration', 'badge', 'instructor' );
	$int_fields  = array( 'lessons_count', 'reviews_count' );
	$num_fields  = array( 'price', 'price_orig', 'rating' );
	$html_fields = array( 'syllabus', 'outcomes', 'skills' );

	foreach ( $text_fields as $field ) {
		if ( isset( $_POST[ '_course_' . $field ] ) ) {
			update_post_meta( $post_id, '_course_' . $field, sanitize_text_field( wp_unslash( $_POST[ '_course_' . $field ] ) ) );
		}
	}
	foreach ( $int_fields as $field ) {
		if ( isset( $_POST[ '_course_' . $field ] ) ) {
			update_post_meta( $post_id, '_course_' . $field, absint( $_POST[ '_course_' . $field ] ) );
		}
	}
	foreach ( $num_fields as $field ) {
		if ( isset( $_POST[ '_course_' . $field ] ) ) {
			update_post_meta( $post_id, '_course_' . $field, max( 0, (float) wp_unslash( $_POST[ '_course_' . $field ] ) ) );
		}
	}
	if ( isset( $_POST['_course_preview_url'] ) ) {
		update_post_meta( $post_id, '_course_preview_url', esc_url_raw( wp_unslash( $_POST['_course_preview_url'] ) ) );
	}
	foreach ( $html_fields as $field ) {
		if ( isset( $_POST[ '_course_' . $field ] ) ) {
			update_post_meta( $post_id, '_course_' . $field, wp_kses_post( wp_unslash( $_POST[ '_course_' . $field ] ) ) );
		}
	}
}
add_action( 'save_post_course', 'edtech_save_course_meta' );

/**
 * Register meta for testimonial & team CPTs.
 */
function edtech_register_extra_meta() {
	register_post_meta( 'testimonial', '_testimonial_role', array(
		'type' => 'string', 'single' => true, 'show_in_rest' => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );
	register_post_meta( 'testimonial', '_testimonial_rating', array(
		'type' => 'number', 'single' => true, 'show_in_rest' => true,
		'sanitize_callback' => function( $v ) { return max( 0, min( 5, (float) $v ) ); },
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );
	register_post_meta( 'team', '_team_role', array(
		'type' => 'string', 'single' => true, 'show_in_rest' => true,
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );
	register_post_meta( 'team', '_team_social', array(
		'type' => 'string', 'single' => true, 'show_in_rest' => true,
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );
}
add_action( 'init', 'edtech_register_extra_meta', 25 );

/**
 * Add metaboxes for testimonial & team.
 */
function edtech_add_extra_metaboxes() {
	add_meta_box( 'edtech_testimonial_details', __( 'Testimonial Details', 'edtech' ), 'edtech_render_testimonial_metabox', 'testimonial', 'normal', 'high' );
	add_meta_box( 'edtech_team_details', __( 'Team Member Details', 'edtech' ), 'edtech_render_team_metabox', 'team', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'edtech_add_extra_metaboxes' );

function edtech_render_testimonial_metabox( $post ) {
	wp_nonce_field( 'edtech_save_testimonial', 'edtech_testimonial_nonce' );
	$role   = get_post_meta( $post->ID, '_testimonial_role', true );
	$rating = get_post_meta( $post->ID, '_testimonial_rating', true );
	?>
	<p>
		<label for="_testimonial_role"><strong><?php _e( 'Role / Company', 'edtech' ); ?></strong></label><br>
		<input type="text" id="_testimonial_role" name="_testimonial_role" value="<?php echo esc_attr( $role ); ?>" class="widefat" placeholder="Frontend Developer @ Acme">
	</p>
	<p>
		<label for="_testimonial_rating"><strong><?php _e( 'Rating (0-5)', 'edtech' ); ?></strong></label><br>
		<input type="number" step="0.1" min="0" max="5" id="_testimonial_rating" name="_testimonial_rating" value="<?php echo esc_attr( $rating ); ?>" class="small-text">
	</p>
	<?php
}

function edtech_render_team_metabox( $post ) {
	wp_nonce_field( 'edtech_save_team', 'edtech_team_nonce' );
	$role   = get_post_meta( $post->ID, '_team_role', true );
	$social = get_post_meta( $post->ID, '_team_social', true );
	?>
	<p>
		<label for="_team_role"><strong><?php _e( 'Role / Title', 'edtech' ); ?></strong></label><br>
		<input type="text" id="_team_role" name="_team_role" value="<?php echo esc_attr( $role ); ?>" class="widefat" placeholder="Founder & CEO">
	</p>
	<p>
		<label for="_team_social"><strong><?php _e( 'Social / LinkedIn URL', 'edtech' ); ?></strong></label><br>
		<input type="url" id="_team_social" name="_team_social" value="<?php echo esc_attr( $social ); ?>" class="widefat" placeholder="https://linkedin.com/in/...">
	</p>
	<?php
}

function edtech_save_testimonial_meta( $post_id ) {
	if ( ! isset( $_POST['edtech_testimonial_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['edtech_testimonial_nonce'] ) ), 'edtech_save_testimonial' ) ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['_testimonial_role'] ) ) {
		update_post_meta( $post_id, '_testimonial_role', sanitize_text_field( wp_unslash( $_POST['_testimonial_role'] ) ) );
	}
	if ( isset( $_POST['_testimonial_rating'] ) ) {
		update_post_meta( $post_id, '_testimonial_rating', max( 0, min( 5, (float) wp_unslash( $_POST['_testimonial_rating'] ) ) ) );
	}
}
add_action( 'save_post_testimonial', 'edtech_save_testimonial_meta' );

function edtech_save_team_meta( $post_id ) {
	if ( ! isset( $_POST['edtech_team_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['edtech_team_nonce'] ) ), 'edtech_save_team' ) ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['_team_role'] ) ) {
		update_post_meta( $post_id, '_team_role', sanitize_text_field( wp_unslash( $_POST['_team_role'] ) ) );
	}
	if ( isset( $_POST['_team_social'] ) ) {
		update_post_meta( $post_id, '_team_social', esc_url_raw( wp_unslash( $_POST['_team_social'] ) ) );
	}
}
add_action( 'save_post_team', 'edtech_save_team_meta' );

function edtech_catalog_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! is_post_type_archive( 'course' ) ) {
		return;
	}

	$tax_query = array();
	$category  = isset( $_GET['course_category'] ) ? sanitize_title( wp_unslash( $_GET['course_category'] ) ) : '';
	$level     = isset( $_GET['course_level'] ) ? sanitize_title( wp_unslash( $_GET['course_level'] ) ) : '';

	if ( $category ) {
		$tax_query[] = array( 'taxonomy' => 'course_category', 'field' => 'slug', 'terms' => $category );
	}
	if ( $level ) {
		$tax_query[] = array( 'taxonomy' => 'course_level', 'field' => 'slug', 'terms' => $level );
	}
	if ( $tax_query ) {
		$query->set( 'tax_query', $tax_query );
	}
	if ( isset( $_GET['q'] ) ) {
		$query->set( 's', sanitize_text_field( wp_unslash( $_GET['q'] ) ) );
	}
	$query->set( 'posts_per_page', 12 );
}
add_action( 'pre_get_posts', 'edtech_catalog_query' );

