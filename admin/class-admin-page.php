<?php
/**
 * Clicky for WordPress plugin file.
 *
 * @package Yoast\Clicky\Admin
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Class for the Clicky plugin admin page.
 */
class Admin_Page extends Admin {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		new Options_Admin();

		add_action( 'admin_print_scripts', array( $this, 'config_page_scripts' ) );
		add_action( 'admin_print_styles', array( $this, 'config_page_styles' ) );
	}

	/**
	 * Enqueue the styles for the admin page.
	 */
	public function config_page_styles() {
		wp_enqueue_style( 'yseo-gc-admin-css', YSEO_GC_DIR_URL . 'css/dist/admin.css', null, YSEO_GC_PLUGIN_VERSION );
	}

	/**
	 * Enqueue the scripts for the admin page.
	 */
	public function config_page_scripts() {
		wp_enqueue_script( 'yseo-gc-admin-js', YSEO_GC_DIR_URL . 'js/admin.min.js', null, YSEO_GC_PLUGIN_VERSION );
	}

	/**
	 * Creates the configuration page.
	 */
	public function config_page() {
		require YSEO_GC_DIR_PATH . 'admin/views/admin-page.php';
	}

	/**
	 * Create a postbox widget.
	 *
	 * @param string $title   Title to display.
	 * @param string $content Content to display.
	 */
	private function box( $title, $content ) {
		// @codingStandardsIgnoreLine
		echo '<div class="yoast_box"><h3>' . esc_html( $title ) . '</h3><div class="inside">' . $content . '</div></div>';
	}

	/**
	 * Create a "plugin like" box.
	 */
	public function like_text() {
		require YSEO_GC_DIR_PATH . 'admin/views/like-box.php';
	}

	/**
	 * Generate an RSS box.
	 *
	 * @param string $feed        Feed URL to parse.
	 * @param string $title       Title of the box.
	 * @param string $extra_links Additional links to add to the output, after the RSS subscribe link.
	 */
	private function rss_news( $feed, $title, $extra_links = '' ) {
		include_once ABSPATH . WPINC . '/feed.php';
		$rss = fetch_feed( $feed );

		if ( is_wp_error( $rss ) ) {
			$rss = '<li class="yoast">' . __( 'No news items, feed might be broken...', 'yoast-seo-granular-control' ) . '</li>';
		}
		else {
			$rss_items = $rss->get_items( 0, $rss->get_item_quantity( 3 ) );

			$rss = '';
			foreach ( $rss_items as $item ) {
				$url  = preg_replace( '/#.*/', '', esc_url( $item->get_permalink(), $protocols = null, 'display' ) );
				$rss .= '<li class="yoast">';
				$rss .= '<a href="' . $url . '#utm_source=wpadmin&utm_medium=sidebarwidget&utm_term=newsitem&utm_campaign=clickywpplugin">' . $item->get_title() . '</a> ';
				$rss .= '</li>';
			}
		}

		$content  = '<ul>';
		$content .= $rss;
		$content .= $extra_links;
		$content .= '</ul>';

		$this->box( $title, $content );
	}

	/**
	 * Box with latest news from Yoast.com for sidebar.
	 */
	private function yoast_news() {
		$extra_links  = '<li class="facebook"><a href="https://www.facebook.com/yoast">' . __( 'Like Yoast on Facebook', 'yoast-seo-granular-control' ) . '</a></li>';
		$extra_links .= '<li class="twitter"><a href="https://twitter.com/yoast">' . __( 'Follow Yoast on Twitter', 'yoast-seo-granular-control' ) . '</a></li>';
		$extra_links .= '<li class="email"><a href="https://yoast.com/newsletter/">' . __( 'Subscribe by email', 'yoast-seo-granular-control' ) . '</a></li>';

		$this->rss_news( 'https://yoast.com/feed/', __( 'Latest news from Yoast', 'yoast-seo-granular-control' ), $extra_links );
	}

}
