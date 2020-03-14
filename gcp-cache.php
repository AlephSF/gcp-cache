<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://alephsf.com
 * @since             1.0.0
 * @package           Gcp_Cache
 *
 * @wordpress-plugin
 * Plugin Name:       Google Cloud Project Cache
 * Plugin URI:        https://alephsf.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Matt Glaser
 * Author URI:        https://alephsf.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gcp-cache
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GCP_CACHE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gcp-cache-activator.php
 */
function activate_gcp_cache() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gcp-cache-activator.php';
	Gcp_Cache_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gcp-cache-deactivator.php
 */
function deactivate_gcp_cache() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gcp-cache-deactivator.php';
	Gcp_Cache_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gcp_cache' );
register_deactivation_hook( __FILE__, 'deactivate_gcp_cache' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gcp-cache.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_gcp_cache() {
	if ( defined('GOOGLE_APPLICATION_CREDENTIALS') && defined('GCP_CACHE_MAP_NAME_FRAGMENT') && defined('GCP_CACHE_PROJECT')){
		$plugin = new Gcp_Cache();
		$plugin->run();
	}

}
run_gcp_cache();
