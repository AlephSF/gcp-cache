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
	 * Register the JavaScript that sets or unsets localstorage if user is logged in or out.
	 *
	 * @since    0.0.10
	 */
	public function enqueue_scripts() {

		if(is_user_logged_in()){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gcp-cache-user-logged-in.js', array( 'jquery' ), $this->version, false );
		} else {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gcp-cache-user-logged-out.js', null, $this->version, false );
		}
	}

}
