<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://alephsf.com
 * @since      0.0.1
 *
 * @package    Gcp_Cache
 * @subpackage Gcp_Cache/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.0.1
 * @package    Gcp_Cache
 * @subpackage Gcp_Cache/includes
 * @author     Matt Glaser <ping@alephsf.com>
 */
class Gcp_Cache {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      Gcp_Cache_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function __construct() {
		if ( defined( 'GCP_CACHE_VERSION' ) ) {
			$this->version = GCP_CACHE_VERSION;
		} else {
			$this->version = '0.0.1';
		}
		$this->plugin_name = 'gcp-cache';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_header_hooks();
		$this->define_api_hooks();
		$this->define_user_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Gcp_Cache_Loader. Orchestrates the hooks of the plugin.
	 * - Gcp_Cache_i18n. Defines internationalization functionality.
	 * - Gcp_Cache_Admin. Defines all hooks for the admin area.
	 * - Gcp_Cache_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gcp-cache-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gcp-cache-i18n.php';

		/**
		 * The class responsible for defining all header logic.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gcp-cache-headers.php';

		/**
		 * The class responsible for the Google Cloud REST API Wrapper.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gcp-cache-api-wrapper.php';


		/**
		 * The class responsible for user cache and cachebusting administration.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gcp-cache-user.php';


		$this->loader = new Gcp_Cache_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Gcp_Cache_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Gcp_Cache_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_header_hooks() {

		$plugin_headers = new Gcp_Cache_Headers( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp', $plugin_headers, 'set_cache_headers' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_api_hooks() {

		// $plugin_api = new Gcp_Cache_Api_Wrapper( $this->get_plugin_name(), $this->get_version() );

		// $this->loader->add_action('save_post', $plugin_api , 'clear_path_cache', 10, 3);	
	}


	/**
	 * Register all of the hooks related to breaking cache for logged-in users
	 *
	 * @since    0.0.10
	 * @access   private
	 */
	private function define_user_hooks() {

		$plugin_user = new Gcp_Cache_User( $this->get_plugin_name(), $this->get_version() );
	
		$this->loader->add_action( 'wp_login', $plugin_user, 'set_cache_bust_login_cookie' );
		$this->loader->add_action( 'wp_logout', $plugin_user, 'clear_cache_bust_login_cookie' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_user, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.0.1
	 * @return    Gcp_Cache_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
