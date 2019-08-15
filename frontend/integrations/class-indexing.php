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
class Indexing implements Integration {
	/**
	 * Registers all hooks to WordPress.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'wpseo_robots', [ $this, 'filter_robots' ] );
		if ( Options::get( 'disable-rel-next-prev' ) ) {
			add_filter( 'wpseo_disable_adjacent_rel_links', '__return_true' );
		}
	}

	/**
	 * Filters the robots meta string.
	 *
	 * @param string $robots_string The robots meta string.
	 *
	 * @return string The robots meta string.
	 */
	public function filter_robots( $robots_string ) {
		if ( Options::get( 'noindex-paginated-archives' ) && is_paged() ) {
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
