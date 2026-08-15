<?php
/**
 * WP Multilingual Migration Layer
 *
 * Migrates existing _ar custom meta fields to WP Multilingual plugin structure.
 *
 * @package EdTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Migrate all content with _ar meta to WP Multilingual translation posts.
 *
 * This function is idempotent - running it multiple times will not create duplicates.
 *
 * @return array Migration results with counts and details.
 */
function edtech_migrate_ar_content_to_wpm() {
	if ( ! function_exists( 'wpm_get_default_language' ) ) {
		return array(
			'success' => false,
			'message' => 'WP Multilingual plugin not active.',
		);
	}

	global $wpdb;

	$results = array(
		'posts_processed'       => 0,
		'translations_created'  => 0,
		'translations_skipped'  => 0,
		'errors'                => array(),
	);

	// Ensure languages are configured
	$lang_mgr     = WPMultilingual\LanguageManager::get_instance();
	$default_lang = $lang_mgr->get_default_language();
	$ar_lang      = $lang_mgr->get_language( 'ar' );

	if ( ! $default_lang ) {
		$results['errors'][] = 'No default language configured in WP Multilingual.';
		return $results;
	}

	if ( ! $ar_lang ) {
		$results['errors'][] = 'Arabic language not configured in WP Multilingual. Please add it first.';
		return $results;
	}

	$trans_mgr = WPMultilingual\TranslationManager::get_instance();
	$sync      = WPMultilingual\Sync::get_instance();

	// Get all translatable post types
	$post_types = array( 'post', 'page', 'course', 'instructor', 'learning_path', 'faq', 'testimonial', 'team' );

	foreach ( $post_types as $post_type ) {
		$posts = get_posts( array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'     => '_title_ar',
					'compare' => 'EXISTS',
				),
				array(
					'key'     => '_content_ar',
					'compare' => 'EXISTS',
				),
			),
		) );

		foreach ( $posts as $post ) {
			$results['posts_processed']++;

			// Get Arabic content
			$title_ar   = get_post_meta( $post->ID, '_title_ar', true );
			$content_ar = get_post_meta( $post->ID, '_content_ar', true );

			// Skip if no Arabic content
			if ( empty( $title_ar ) && empty( $content_ar ) ) {
				continue;
			}

			// Check if this post already has a language assigned
			$existing_lang = $trans_mgr->get_object_language( $post->ID, 'post' );

			if ( ! $existing_lang ) {
				// Assign English as the original language
				$group_id = $trans_mgr->create_group( 'post' );
				$trans_mgr->assign_language_and_group( $post->ID, $default_lang->code, $group_id, 'post', 'translated' );
			} else {
				$group_id = $trans_mgr->get_object_group_id( $post->ID, 'post' );
			}

			// Check if Arabic translation already exists in this group
			$existing_ar = $trans_mgr->get_translation( $post->ID, 'ar', 'post' );

			if ( $existing_ar ) {
				$results['translations_skipped']++;
				continue;
			}

			// Create Arabic translation post
			$ar_post_data = array(
				'post_title'   => $title_ar ?: $post->post_title,
				'post_content' => $content_ar ?: $post->post_content,
				'post_excerpt' => $post->post_excerpt,
				'post_type'    => $post->post_type,
				'post_status'  => $post->post_status,
				'post_author'  => $post->post_author,
			);

			$ar_post_id = wp_insert_post( $ar_post_data, true );

			if ( is_wp_error( $ar_post_id ) ) {
				$results['errors'][] = sprintf(
					'Failed to create Arabic translation for %s ID %d: %s',
					$post->post_type,
					$post->ID,
					$ar_post_id->get_error_message()
				);
				continue;
			}

			// Assign to same translation group
			$trans_mgr->assign_language_and_group( $ar_post_id, 'ar', $group_id, 'post', 'translated' );

			// Copy featured image
			$sync->sync_featured_image( $post->ID, $ar_post_id );

			// Copy taxonomies
			$sync->copy_taxonomies( $post->ID, $ar_post_id, 'ar' );

			// Migrate course-specific Arabic meta if this is a course
			if ( 'course' === $post->post_type ) {
				edtech_migrate_course_ar_meta( $post->ID, $ar_post_id );
			}

			// Migrate team/testimonial Arabic meta
			if ( 'team' === $post->post_type ) {
				$role_ar = get_post_meta( $post->ID, '_team_role_ar', true );
				if ( $role_ar ) {
					update_post_meta( $ar_post_id, '_team_role', $role_ar );
				}
				// Copy social link
				$social = get_post_meta( $post->ID, '_team_social', true );
				if ( $social ) {
					update_post_meta( $ar_post_id, '_team_social', $social );
				}
			}

			if ( 'testimonial' === $post->post_type ) {
				$role_ar = get_post_meta( $post->ID, '_testimonial_role_ar', true );
				if ( $role_ar ) {
					update_post_meta( $ar_post_id, '_testimonial_role', $role_ar );
				}
				// Copy rating
				$rating = get_post_meta( $post->ID, '_testimonial_rating', true );
				if ( $rating ) {
					update_post_meta( $ar_post_id, '_testimonial_rating', $rating );
				}
			}

			if ( 'learning_path' === $post->post_type ) {
				$badge_ar = get_post_meta( $post->ID, '_path_badge_ar', true );
				if ( $badge_ar ) {
					update_post_meta( $ar_post_id, '_path_badge', $badge_ar );
				}
				// Copy numeric fields
				$weeks = get_post_meta( $post->ID, '_path_weeks', true );
				if ( $weeks ) {
					update_post_meta( $ar_post_id, '_path_weeks', $weeks );
				}
				$pcourses = get_post_meta( $post->ID, '_path_courses', true );
				if ( $pcourses ) {
					update_post_meta( $ar_post_id, '_path_courses', $pcourses );
				}
			}

			$results['translations_created']++;
		}
	}

	// Migrate taxonomy terms with _ar meta
	$results = array_merge( $results, edtech_migrate_taxonomy_ar_terms() );

	return $results;
}

/**
 * Migrate course-specific Arabic meta from English post to Arabic translation.
 *
 * @param int $en_post_id English course post ID.
 * @param int $ar_post_id Arabic course post ID.
 */
function edtech_migrate_course_ar_meta( $en_post_id, $ar_post_id ) {
	// Text fields with _ar variants
	$text_fields = array( 'duration', 'badge', 'instructor' );
	foreach ( $text_fields as $field ) {
		$ar_value = get_post_meta( $en_post_id, '_course_' . $field . '_ar', true );
		if ( $ar_value ) {
			update_post_meta( $ar_post_id, '_course_' . $field, $ar_value );
		}
	}

	// HTML/textarea fields with _ar variants
	$html_fields = array( 'syllabus', 'outcomes', 'skills' );
	foreach ( $html_fields as $field ) {
		$ar_value = get_post_meta( $en_post_id, '_course_' . $field . '_ar', true );
		if ( $ar_value ) {
			update_post_meta( $ar_post_id, '_course_' . $field, $ar_value );
		}
	}

	// Copy numeric/non-translated fields
	$shared_fields = array( 'price', 'price_orig', 'lessons_count', 'rating', 'reviews_count', 'preview_url' );
	foreach ( $shared_fields as $field ) {
		$value = get_post_meta( $en_post_id, '_course_' . $field, true );
		if ( '' !== $value ) {
			update_post_meta( $ar_post_id, '_course_' . $field, $value );
		}
	}
}

/**
 * Migrate taxonomy terms with Arabic translations.
 *
 * @return array Results.
 */
function edtech_migrate_taxonomy_ar_terms() {
	$results = array(
		'terms_processed'       => 0,
		'term_translations'     => 0,
		'term_errors'           => array(),
	);

	if ( ! function_exists( 'wpm_get_default_language' ) ) {
		return $results;
	}

	$lang_mgr     = WPMultilingual\LanguageManager::get_instance();
	$trans_mgr    = WPMultilingual\TranslationManager::get_instance();
	$default_lang = $lang_mgr->get_default_language();
	$ar_lang      = $lang_mgr->get_language( 'ar' );

	if ( ! $default_lang || ! $ar_lang ) {
		return $results;
	}

	$taxonomies = array( 'course_category', 'course_level', 'category', 'post_tag' );

	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		) );

		if ( is_wp_error( $terms ) ) {
			continue;
		}

		foreach ( $terms as $term ) {
			$results['terms_processed']++;

			// Check if term already has language
			$existing_lang = $trans_mgr->get_object_language( $term->term_id, 'term' );

			if ( ! $existing_lang ) {
				// Assign English as original
				$group_id = $trans_mgr->create_group( 'term' );
				$trans_mgr->assign_language_and_group( $term->term_id, $default_lang->code, $group_id, 'term', 'translated' );
			}

			// Note: Taxonomy term translation requires manual creation via admin UI
			// as term structure is more complex (parent/child relationships, slug conflicts, etc.)
		}
	}

	return $results;
}

/**
 * Admin page for migration.
 */
function edtech_wpm_migration_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'edtech' ) );
	}

	$results = null;

	if ( isset( $_POST['edtech_run_migration'] ) && check_admin_referer( 'edtech_wpm_migration', 'edtech_wpm_nonce' ) ) {
		$results = edtech_migrate_ar_content_to_wpm();
	}

	?>
	<div class="wrap">
		<h1><?php _e( 'WP Multilingual Migration', 'edtech' ); ?></h1>

		<div class="card" style="max-width: 800px;">
			<h2><?php _e( 'Migrate Arabic Content to WP Multilingual', 'edtech' ); ?></h2>
			<p><?php _e( 'This tool migrates existing <code>_ar</code> custom meta fields to proper WP Multilingual translation posts.', 'edtech' ); ?></p>

			<h3><?php _e( 'What this migration does:', 'edtech' ); ?></h3>
			<ul style="line-height: 1.8;">
				<li><?php _e( 'Assigns English language to all existing posts', 'edtech' ); ?></li>
				<li><?php _e( 'Creates Arabic translation posts for content with <code>_title_ar</code> or <code>_content_ar</code>', 'edtech' ); ?></li>
				<li><?php _e( 'Links English and Arabic posts in translation groups', 'edtech' ); ?></li>
				<li><?php _e( 'Copies featured images, taxonomies, and course meta to Arabic posts', 'edtech' ); ?></li>
				<li><?php _e( 'Preserves existing <code>_ar</code> meta fields (does not delete)', 'edtech' ); ?></li>
			</ul>

			<p><strong><?php _e( 'Important:', 'edtech' ); ?></strong></p>
			<ul style="line-height: 1.8;">
				<li><?php _e( 'This migration is idempotent - safe to run multiple times', 'edtech' ); ?></li>
				<li><?php _e( 'Arabic language must be configured in WP Multilingual first', 'edtech' ); ?></li>
				<li><?php _e( 'Backup your database before running', 'edtech' ); ?></li>
			</ul>

			<form method="post" action="">
				<?php wp_nonce_field( 'edtech_wpm_migration', 'edtech_wpm_nonce' ); ?>
				<p>
					<button type="submit" name="edtech_run_migration" class="button button-primary button-large">
						<?php _e( 'Run Migration', 'edtech' ); ?>
					</button>
				</p>
			</form>
		</div>

		<?php if ( $results ) : ?>
		<div class="notice notice-success" style="margin-top: 20px; max-width: 800px;">
			<h3><?php _e( 'Migration Complete', 'edtech' ); ?></h3>
			<ul style="line-height: 1.8;">
				<li><?php printf( __( 'Posts processed: %d', 'edtech' ), $results['posts_processed'] ); ?></li>
				<li><?php printf( __( 'Translations created: %d', 'edtech' ), $results['translations_created'] ); ?></li>
				<li><?php printf( __( 'Translations skipped (already exist): %d', 'edtech' ), $results['translations_skipped'] ); ?></li>
				<?php if ( isset( $results['terms_processed'] ) ) : ?>
					<li><?php printf( __( 'Terms processed: %d', 'edtech' ), $results['terms_processed'] ); ?></li>
				<?php endif; ?>
			</ul>
			<?php if ( ! empty( $results['errors'] ) ) : ?>
				<h4 style="color: #d63638;"><?php _e( 'Errors:', 'edtech' ); ?></h4>
				<ul style="color: #d63638;">
					<?php foreach ( $results['errors'] as $error ) : ?>
						<li><?php echo esc_html( $error ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Add migration admin menu item.
 */
function edtech_wpm_migration_admin_menu() {
	add_management_page(
		__( 'WPM Migration', 'edtech' ),
		__( 'WPM Migration', 'edtech' ),
		'manage_options',
		'edtech-wpm-migration',
		'edtech_wpm_migration_admin_page'
	);
}
add_action( 'admin_menu', 'edtech_wpm_migration_admin_menu' );
