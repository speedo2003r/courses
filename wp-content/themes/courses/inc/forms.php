<?php
/**
 * Secure front-end form handlers.
 *
 * @package EdTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function edtech_form_redirect( $url, $type, $message ) {
	wp_safe_redirect( add_query_arg( array( 'edtech_notice' => $type, 'edtech_message' => rawurlencode( $message ) ), $url ) );
	exit;
}

function edtech_require_logged_in_form( $nonce_action, $nonce_name ) {
	if ( ! is_user_logged_in() ) {
		auth_redirect();
	}
	check_admin_referer( $nonce_action, $nonce_name );
}

function edtech_handle_profile_update() {
	edtech_require_logged_in_form( 'edtech_profile_update', 'edtech_profile_nonce' );
	$user_id = get_current_user_id();
	$return  = edtech_page_url( 'student-settings' );
	$name    = isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';
	$email   = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ( email_exists( $email ) && email_exists( $email ) !== $user_id ) ) {
		edtech_form_redirect( $return, 'error', __( 'Please enter a valid name and unique email address.', 'edtech' ) );
	}

	$result = wp_update_user( array( 'ID' => $user_id, 'display_name' => $name, 'user_email' => $email ) );
	if ( is_wp_error( $result ) ) {
		edtech_form_redirect( $return, 'error', $result->get_error_message() );
	}

	update_user_meta( $user_id, '_edtech_headline', sanitize_text_field( wp_unslash( $_POST['headline'] ?? '' ) ) );
	update_user_meta( $user_id, '_edtech_portfolio', esc_url_raw( wp_unslash( $_POST['portfolio'] ?? '' ) ) );
	edtech_form_redirect( $return, 'success', __( 'Profile saved successfully.', 'edtech' ) );
}
add_action( 'admin_post_edtech_profile_update', 'edtech_handle_profile_update' );

function edtech_handle_enrollment() {
	edtech_require_logged_in_form( 'edtech_enroll', 'edtech_enroll_nonce' );
	$course_id = edtech_get_course_id_from_request();
	$return    = edtech_get_checkout_url( $course_id );

	if ( ! $course_id ) {
		edtech_form_redirect( $return, 'error', __( 'Please select a valid course.', 'edtech' ) );
	}

	$ids = edtech_get_enrolled_course_ids();
	if ( ! in_array( $course_id, $ids, true ) ) {
		$ids[] = $course_id;
		update_user_meta( get_current_user_id(), '_edtech_enrolled_courses', array_values( array_unique( $ids ) ) );
		update_user_meta( get_current_user_id(), '_edtech_course_progress_' . $course_id, 0 );
	}

	edtech_form_redirect( edtech_page_url( 'student-dashboard' ), 'success', __( 'Enrollment completed. The course is now in your dashboard.', 'edtech' ) );
}
add_action( 'admin_post_edtech_enroll', 'edtech_handle_enrollment' );

function edtech_handle_mark_complete() {
	edtech_require_logged_in_form( 'edtech_mark_complete', 'edtech_mark_complete_nonce' );
	$course_id = edtech_get_course_id_from_request();
	$return    = add_query_arg( 'course_id', $course_id, edtech_page_url( 'lesson-workspace' ) );

	if ( ! $course_id ) {
		edtech_form_redirect( $return, 'error', __( 'Invalid course.', 'edtech' ) );
	}

	if ( ! edtech_user_is_enrolled( $course_id ) ) {
		edtech_form_redirect( $return, 'error', __( 'You must be enrolled to mark lessons complete.', 'edtech' ) );
	}

	$user_id  = get_current_user_id();
	$progress = (int) get_user_meta( $user_id, '_edtech_course_progress_' . $course_id, true );
	// Increment by 20% per completed lesson, capped at 100.
	$progress = min( 100, $progress + 20 );
	update_user_meta( $user_id, '_edtech_course_progress_' . $course_id, $progress );

	edtech_form_redirect( $return, 'success', 100 === $progress ? __( 'Course completed! Check your certificates.', 'edtech' ) : __( 'Lesson marked complete.', 'edtech' ) );
}
add_action( 'admin_post_edtech_mark_complete', 'edtech_handle_mark_complete' );

function edtech_handle_course_builder() {
	edtech_require_logged_in_form( 'edtech_course_builder', 'edtech_course_builder_nonce' );
	if ( ! current_user_can( 'edit_courses' ) && ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You are not allowed to create courses.', 'edtech' ), 403 );
	}

	$title = sanitize_text_field( wp_unslash( $_POST['course_title'] ?? '' ) );
	if ( ! $title ) {
		edtech_form_redirect( edtech_page_url( 'course-builder' ), 'error', __( 'Course title is required.', 'edtech' ) );
	}

	$post_id = wp_insert_post( array(
		'post_type' => 'course', 'post_status' => 'draft', 'post_title' => $title,
		'post_content' => wp_kses_post( wp_unslash( $_POST['course_description'] ?? '' ) ),
		'post_author' => get_current_user_id(),
	), true );

	if ( is_wp_error( $post_id ) ) {
		edtech_form_redirect( edtech_page_url( 'course-builder' ), 'error', $post_id->get_error_message() );
	}

	update_post_meta( $post_id, '_course_price', max( 0, (float) wp_unslash( $_POST['course_price'] ?? 0 ) ) );
	wp_safe_redirect( get_edit_post_link( $post_id, 'raw' ) );
	exit;
}
add_action( 'admin_post_edtech_course_builder', 'edtech_handle_course_builder' );

function edtech_render_notice() {
	$type = isset( $_GET['edtech_notice'] ) ? sanitize_key( wp_unslash( $_GET['edtech_notice'] ) ) : '';
	$text = isset( $_GET['edtech_message'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['edtech_message'] ) ) ) : '';
	if ( ! $text || ! in_array( $type, array( 'success', 'error' ), true ) ) {
		return;
	}
	printf( '<div class="edtech-notice edtech-notice-%s" role="status">%s</div>', esc_attr( $type ), esc_html( $text ) );
}

