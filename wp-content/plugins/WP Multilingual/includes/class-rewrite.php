<?php
/**
 * URL Rewriting & Permalink Filter Manager.
 *
 * @package WPMultilingual
 */

namespace WPMultilingual;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Rewrite
 */
class Rewrite {

	/**
	 * Singleton instance.
	 *
	 * @var Rewrite|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Rewrite
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {}

	/**
	 * Initialize rewrite hooks.
	 */
	public function init() {
		add_action( 'init', [ $this, 'register_rewrites' ], 1 );
		add_filter( 'query_vars', [ $this, 'register_query_vars' ] );

		// Permalink filters
		add_filter( 'post_link', [ $this, 'filter_post_link' ], 10, 2 );
		add_filter( 'page_link', [ $this, 'filter_page_link' ], 10, 2 );
		add_filter( 'post_type_link', [ $this, 'filter_post_type_link' ], 10, 2 );
		add_filter( 'term_link', [ $this, 'filter_term_link' ], 10, 3 );
		add_filter( 'home_url', [ $this, 'filter_home_url' ], 10, 4 );
	}

	/**
	 * Register rewrite tags and rules.
	 */
	public function register_rewrites() {
		add_rewrite_tag( '%lang%', '([a-zA-Z]{2,10})' );

		$languages = wpm_get_languages( [ 'enabled_only' => true ] );
		if ( empty( $languages ) ) {
			return;
		}

		$lang_codes = [];
		foreach ( $languages as $lang ) {
			$lang_codes[] = preg_quote( $lang->url_code ? $lang->url_code : $lang->code, '#' );
		}

		$pattern = implode( '|', $lang_codes );

		// Language home root (e.g. /ar/ or /en/)
		add_rewrite_rule(
			'^(' . $pattern . ')/?$',
			'index.php?lang=$matches[1]',
			'top'
		);

		// Language pagination root (e.g. /ar/page/2/)
		add_rewrite_rule(
			'^(' . $pattern . ')/page/?([0-9]{1,})/?$',
			'index.php?lang=$matches[1]&paged=$matches[2]',
			'top'
		);

		// Language search (e.g. /ar/search/query/)
		add_rewrite_rule(
			'^(' . $pattern . ')/search/(.+)/?$',
			'index.php?lang=$matches[1]&s=$matches[2]',
			'top'
		);

		// Single posts / pages / CPTs prefixed with language
		add_rewrite_rule(
			'^(' . $pattern . ')/(.+?)/page/?([0-9]{1,})/?$',
			'index.php?lang=$matches[1]&name=$matches[2]&paged=$matches[3]',
			'top'
		);

		add_rewrite_rule(
			'^(' . $pattern . ')/(.+?)/?$',
			'index.php?lang=$matches[1]&name=$matches[2]',
			'top'
		);
	}

	/**
	 * Whitelist lang query variable.
	 *
	 * @param array $vars
	 * @return array
	 */
	public function register_query_vars( $vars ) {
		$vars[] = 'lang';
		return $vars;
	}

	/**
	 * Check if default language URL prefix should be omitted.
	 *
	 * @param string $lang_code
	 * @return bool
	 */
	public function should_hide_language_prefix( $lang_code ) {
		$settings = wpm_get_settings();
		if ( ! empty( $settings['hide_default_language_url'] ) || 'mode_b' === ( $settings['url_mode'] ?? '' ) ) {
			return wpm_is_default_language( $lang_code );
		}
		return false;
	}

	/**
	 * Get language URL prefix for given code.
	 *
	 * @param string $lang_code
	 * @return string Prefix with trailing slash or empty string.
	 */
	public function get_language_prefix( $lang_code ) {
		if ( empty( $lang_code ) || $this->should_hide_language_prefix( $lang_code ) ) {
			return '';
		}

		$lang = wpm_get_language( $lang_code );
		$slug = ( $lang && ! empty( $lang->url_code ) ) ? $lang->url_code : $lang_code;

		return $slug . '/';
	}

	/**
	 * Add language prefix to an existing URL.
	 *
	 * @param string $url
	 * @param string $lang_code
	 * @return string
	 */
	public function add_language_to_url( $url, $lang_code ) {
		if ( empty( $url ) || empty( $lang_code ) ) {
			return $url;
		}

		$home = untrailingslashit( get_option( 'home' ) );
		if ( empty( $home ) || 0 !== strpos( $url, $home ) ) {
			return $url;
		}

		$path = substr( $url, strlen( $home ) );
		$path = ltrim( $path, '/' );

		// Strip existing language code if already in path
		$languages = wpm_get_languages();
		foreach ( $languages as $l ) {
			$code = $l->url_code ? $l->url_code : $l->code;
			if ( 0 === strpos( $path, $code . '/' ) ) {
				$path = substr( $path, strlen( $code . '/' ) );
				break;
			} elseif ( $path === $code ) {
				$path = '';
				break;
			}
		}

		$prefix  = $this->get_language_prefix( $lang_code );
		$new_url = trailingslashit( $home ) . $prefix . $path;

		// Clean multiple slashes except http:// or https://
		$scheme  = wp_parse_url( $new_url, PHP_URL_SCHEME );
		$without = preg_replace( '#^https?://#', '', $new_url );
		$cleaned = preg_replace( '#/+#', '/', $without );

		return $scheme . '://' . $cleaned;
	}

	/**
	 * Filter post permalinks.
	 *
	 * @param string   $permalink
	 * @param \WP_Post $post
	 * @return string
	 */
	public function filter_post_link( $permalink, $post ) {
		if ( ! is_object( $post ) ) {
			$post = get_post( $post );
		}
		if ( ! $post ) {
			return $permalink;
		}

		if ( class_exists( __NAMESPACE__ . '\\PostIntegration' ) && ! PostIntegration::get_instance()->is_translatable_post_type( $post->post_type ) ) {
			return $permalink;
		}

		$lang = wpm_get_post_language( $post->ID );
		if ( ! $lang ) {
			$lang = wpm_get_current_language();
		}

		$url = $this->add_language_to_url( $permalink, $lang );
		return apply_filters( 'wpm_post_link', $url, $post->ID, $lang );
	}

	/**
	 * Filter page permalinks.
	 *
	 * @param string $permalink
	 * @param int    $post_id
	 * @return string
	 */
	public function filter_page_link( $permalink, $post_id ) {
		if ( class_exists( __NAMESPACE__ . '\\PostIntegration' ) && ! PostIntegration::get_instance()->is_translatable_post_type( 'page' ) ) {
			return $permalink;
		}

		$lang = wpm_get_post_language( $post_id );
		if ( ! $lang ) {
			$lang = wpm_get_current_language();
		}

		$url = $this->add_language_to_url( $permalink, $lang );
		return apply_filters( 'wpm_page_link', $url, $post_id, $lang );
	}

	/**
	 * Filter custom post type permalinks.
	 *
	 * @param string   $permalink
	 * @param \WP_Post $post
	 * @return string
	 */
	public function filter_post_type_link( $permalink, $post ) {
		if ( ! is_object( $post ) ) {
			$post = get_post( $post );
		}
		if ( ! $post ) {
			return $permalink;
		}

		if ( class_exists( __NAMESPACE__ . '\\PostIntegration' ) && ! PostIntegration::get_instance()->is_translatable_post_type( $post->post_type ) ) {
			return $permalink;
		}

		$lang = wpm_get_post_language( $post->ID );
		if ( ! $lang ) {
			$lang = wpm_get_current_language();
		}

		$url = $this->add_language_to_url( $permalink, $lang );
		return apply_filters( 'wpm_post_type_link', $url, $post->ID, $lang );
	}

	/**
	 * Filter term permalinks.
	 *
	 * @param string   $url
	 * @param \WP_Term $term
	 * @param string   $taxonomy
	 * @return string
	 */
	public function filter_term_link( $url, $term, $taxonomy ) {
		if ( ! is_object( $term ) ) {
			return $url;
		}

		$lang = wpm_get_term_language( $term->term_id );
		if ( ! $lang ) {
			$lang = wpm_get_current_language();
		}

		return $this->add_language_to_url( $url, $lang );
	}

	/**
	 * Filter home URL when appropriate with recursion protection.
	 *
	 * @param string      $url
	 * @param string      $path
	 * @param string|null $orig_scheme
	 * @param int|null    $blog_id
	 * @return string
	 */
	public function filter_home_url( $url, $path, $orig_scheme, $blog_id ) {
		static $is_filtering = false;
		if ( $is_filtering ) {
			return $url;
		}

		// Don't modify in admin, rest, ajax, login
		if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || false !== strpos( (string) $path, 'wp-login.php' ) || false !== strpos( (string) $path, 'wp-admin' ) ) {
			return $url;
		}

		// Don't modify if path is an internal endpoint or asset
		if ( 0 === strpos( (string) $path, 'wp-json' ) || 0 === strpos( (string) $path, 'xmlrpc.php' ) ) {
			return $url;
		}

		$is_filtering = true;
		$current_lang = wpm_get_current_language();
		$filtered_url = $this->add_language_to_url( $url, $current_lang );
		$is_filtering = false;

		return $filtered_url;
	}

	/**
	 * Get homepage URL for specific language.
	 *
	 * @param string|null $lang_code
	 * @return string
	 */
	public function get_home_url( $lang_code = null ) {
		if ( null === $lang_code ) {
			$lang_code = wpm_get_current_language();
		}

		$home   = get_option( 'home' );
		$prefix = $this->get_language_prefix( $lang_code );

		return trailingslashit( trailingslashit( $home ) . $prefix );
	}

	/**
	 * Get arbitrary path URL for specific language.
	 *
	 * @param string $lang_code
	 * @param string $path
	 * @return string
	 */
	public function get_language_url( $lang_code, $path = '' ) {
		$home   = get_option( 'home' );
		$prefix = $this->get_language_prefix( $lang_code );
		$clean  = ltrim( $path, '/' );

		return trailingslashit( $home ) . $prefix . $clean;
	}
}
