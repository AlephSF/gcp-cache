<?php

/**
 * Anything having to do with php headers and cache control.
 *
 * @link       https://alephsf.com
 * @since      0.0.1
 *
 * @package    Gcp_Cache
 */

/**
 * Anything having to do with php headers and cache control.
 *
 * Defines the plugin name, version, and sets up headers for cache control via the Google CDN cache.
 *
 * @package    Gcp_Cache
 * @author     Matt Glaser <ping@alephsf.com>
 */
class Gcp_Cache_Headers {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Set cache header time
	 *
	 * @since    0.0.1
	 */
	public function set_cache_headers( $headers ) {
		$front_end_cache_time = GCP_CACHE_SECONDS ?: 600;
		global $post;
		$do_not_cache = is_object($post) && $post->ID ? get_post_meta($post->ID, '_gcp_do_not_cache', true) : true;

		if (is_admin() || is_user_logged_in() || $post->post_password || $do_not_cache) {
			header('Cache-Control: no-cache, must-revalidate, max-age=0');
		} else {
			header('Cache-Control: public, s-maxage=' . $front_end_cache_time);
		}
	}

}