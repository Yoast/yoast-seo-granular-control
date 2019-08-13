<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Class Filter_Robots
 *
 * Filters the robots meta code based on settings.
 */
class Filter_Robots implements Integration {
	/**
	 * Holds the plugin options.
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Registers all hooks to WordPress.
	 *
	 * @param array $options The options for the plugin.
	 *
	 * @return void
	 */
	public function register_hooks( $options ) {
		$this->options = $options;

		add_filter( 'wpseo_robots', [ $this, 'filter_robots' ] );
	}

	/**
	 * Filters the robots meta string.
	 *
	 * @param string $robots_string The robots meta string.
	 *
	 * @return string The robots meta string.
	 */
	public function filter_robots( $robots_string ) {
		if ( $this->options['noindex-paginated-archives'] && is_paged() ) {
			$robots = explode( ',', $robots_string );
			if ( ( $key = array_search( 'index', $robots ) ) !== false ) {
				unset( $robots[ $key ] );
			}

			$robots[]      = 'noindex';
			$robots_string = implode( ',', array_unique( array_filter( $robots ) ) );
		}

		return $robots_string;
	}
}