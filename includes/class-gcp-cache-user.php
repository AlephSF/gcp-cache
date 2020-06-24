<?php

/**
 * Anything having to do with user caches
 *
 * @link       https://alephsf.com
 * @since      0.0.10
 *
 * @package    Gcp_Cache
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

/**
 * Define cookie and nonce values
 */
define( 'GCP_CACHE_LOGGED_IN_COOKIE', 'gcp_cache_logged_in' );

/**
 * Anything having to do with the user caches
 *
 * Defines the plugin name, version, and sets up functions to disable caches for logged in users
 *
 * @package    Gcp_Cache
 * @author     Matt Glaser <ping@alephsf.com>
 */
class Gcp_Cache_User {

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
	 * Set cookie on log in
	 *
	 * @since    0.0.10
	 */
	public function set_cache_bust_login_cookie() {

		setcookie(GCP_CACHE_LOGGED_IN_COOKIE, '1', time()+60*60*24*14, '/');
	}

	/**
	 * Clears cookie on log out
	 *
	 * @since    0.0.10
	 */
	public function clear_cache_bust_login_cookie() {

		setcookie(GCP_CACHE_LOGGED_IN_COOKIE, '', time()+60*60*24*14, '/');
	}

	/**
	 * Register the JavaScript that sets/unsets cookie on log in/out and adds fix for showing WP Admin Bar despite cache
	 *
	 * @since    0.0.10
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name . '-user-loggedin-cachebust', plugin_dir_url( __FILE__ ) . 'js/gcp-logged-in-cache-bust.js', array( 'jquery' ), $this->version, false );

    wp_localize_script( $this->plugin_name . '-user-loggedin-cachebust', 'ajaxLoggedInObject', array(
        'gcpCacheLoggedInCookie' => GCP_CACHE_LOGGED_IN_COOKIE
    ));
	}

}
