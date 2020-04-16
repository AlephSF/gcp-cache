<?php

/**
 * Anything having to do with the Google API Wrapper
 *
 * @link       https://alephsf.com
 * @since      1.0.0
 *
 * @package    Gcp_Cache
 */

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

/**
 * Anything having to do with the Google API Wrapper
 *
 * Defines the plugin name, version, and sets up functions to interact with the GCP REST API
 *
 * @package    Gcp_Cache
 * @author     Matt Glaser <ping@alephsf.com>
 */
class Gcp_Cache_Api_Wrapper {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->client = new Google_Client();
		$this->client->useApplicationDefaultCredentials();
		$this->client->setScopes(['https://www.googleapis.com/auth/cloud-platform']);
		$this->service = new Google_Service_Compute($this->client);
	}

	/**
	 * Set cache header time
	 *
	 * @since    1.0.0
	 */
	private function get_url_map_name() {
		$results = $this->service->urlMaps->listUrlMaps(GCP_CACHE_PROJECT);
		foreach ($results['items'] as $urlMap) {
			if(strpos($urlMap->name, GCP_CACHE_MAP_NAME_FRAGMENT) !== false ){
				update_option('gcp_cache_url_map_name', $urlMap->name);
				return $urlMap->name;
			}
		}
	}

	/**
	 * Set cache header time
	 *
	 * @since    1.0.0
	 */
	public function clear_path_cache($post_id, $post, $update) {
		if(!$update){
			return;
		}
		$url_map_name = get_option('gcp_cache_url_map_name');
		$url_parts = parse_url(get_permalink($post_id));
		
		$request_body = new Google_Service_Compute_CacheInvalidationRule();
		$request_body->host = defined('GCP_CACHE_HOST') && GCP_CACHE_HOST ?: $url_parts['host'];
		$request_body->path = $url_parts['path'];
		try {
			$response = $this->service->urlMaps->invalidateCache(GCP_CACHE_PROJECT, $url_map_name, $request_body);
		} catch (Exception $e) {
			// $error = $e->getMessage();
			// echo '<pre><code>';
			// echo $error;
			// echo '</code></pre>';
			// die();
			$url_map_name = $this->get_url_map_name();
			$response = $this->service->urlMaps->invalidateCache(GCP_CACHE_PROJECT, $url_map_name, $request_body);
		}
	}

}
