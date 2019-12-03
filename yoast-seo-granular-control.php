<?php
/**
 * Yoast SEO: Granular Control
 *
 * @package   Yoast/Yoast-SEO-Granular-Control
 * @copyright Copyright (C) 2019 Yoast BV - support@yoast.com
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Yoast SEO: Granular Control
 * Version:     1.0
 * Plugin URI:  https://yoast.com/wordpress/plugins/granular-control/
 * Description: This plugin gives you granular control over some of the settings of Yoast SEO. If you love Yoast, but want more control over the fine details of canonicalization pagination and more, this plugin is for you.
 * Author:      Team Yoast
 * Author URI:  https://yoast.com/
 * Text Domain: yoast-seo-granular-control
 */

namespace Yoast_SEO_Granular_Control;

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

define( 'YSEO_GC_PLUGIN_FILE', __FILE__ );
define( 'YSEO_GC_PLUGIN_VERSION', '1.0' );
define( 'YSEO_GC_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'YSEO_GC_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Class Yoast SEO Granular Control base class.
 */
class Control {

	/**
	 * Initialize the plugin settings.
	 */
	public function __construct() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		require __DIR__ . '/vendor/autoload.php';

		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize the whole plugin.
	 */
	public function init() {
		if ( is_admin() ) {
			load_plugin_textdomain( 'yoast-seo-granular-control', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			new Admin();
		}
		else {
			new Frontend();
		}
	}
}

new Control();
