<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Backend Class the Yoast_SEO_Granular_Control plugin.
 */
class Admin {
	/**
	 * Menu slug for WordPress admin.
	 *
	 * @access private
	 * @var string
	 */
	public $hook = 'yoast-seo-granular-control';

	/**
	 * Construct of class Clicky_admin.
	 *
	 * @access private
	 * @link   https://codex.wordpress.org/Function_Reference/add_action
	 * @link   https://codex.wordpress.org/Function_Reference/add_filter
	 */
	public function __construct() {
		add_filter( 'plugin_action_links', array( $this, 'add_action_link' ), 10, 2 );

		add_action( 'publish_post', array( $this, 'insert_post' ) );
		add_action( 'admin_menu', array( $this, 'admin_init' ) );
	}

	/**
	 * Initialize needed actions.
	 */
	public function admin_init() {
		$this->register_menu_pages();
	}

	/**
	 * Creates the dashboard and options pages.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/add_options_page
	 * @link https://codex.wordpress.org/Function_Reference/add_dashboard_page
	 */
	private function register_menu_pages() {
		add_submenu_page(
			'wpseo_dashboard',
			__( 'Yoast SEO Granular Control', 'yoast-seo-granular-control' ),
			__( 'Granular Control', 'yoast-seo-granular-control' ),
			'manage_options',
			$this->hook,
			array( new Admin_Page(), 'config_page' )
		);

	}

	/**
	 * Returns the plugins settings page URL.
	 *
	 * @return string Admin URL to the current plugins settings URL.
	 */
	private function plugin_options_url() {
		return admin_url( 'options-general.php?page=' . $this->hook );
	}

	/**
	 * Add a link to the settings page to the plugins list.
	 *
	 * @param array  $links Links to add.
	 * @param string $file  Plugin file name.
	 *
	 * @return array
	 */
	public function add_action_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = YSEO_GC_PLUGIN_FILE;
		}
		if ( $file === $this_plugin ) {
			$settings_link = '<a href="' . $this->plugin_options_url() . '">' . __( 'Settings', 'yoast-seo-granular-control' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}
}
