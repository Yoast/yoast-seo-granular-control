<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Adds the options for Indexing changes.
 */
class Options_Indexing extends Options_Admin implements Options_Section {
	/**
	 * @var string
	 */
	public $page = 'yoast-seo-granular-control-indexing';

	/**
	 * @var string
	 */
	public $section = 'indexing-settings';


	/**
	 * Register the indexing settings.
	 */
	public function register() {
		add_settings_section(
			$this->section,
			__( 'Indexing settings', 'yoast-seo-granular-control' ),
			[ $this, 'intro' ],
			$this->page
		);

		$settings = [
			'noindex-paginated-archives' => __( 'Noindex paginated archives', 'yoast-seo-granular-control' ),
			'disable-rel-next-prev'      => __( 'Disable rel="next"/rel="prev"', 'yoast-seo-granular-control' ),
		];
		foreach ( $settings as $key => $label ) {
			add_settings_field(
				$key,
				$label,
				array( $this, 'input_checkbox' ),
				$this->page,
				$this->section,
				array(
					'name'  => $key,
					'value' => Options::get( $key ),
				)
			);
		}
	}

	/**
	 * Intro for the indexing settings screen.
	 */
	public function intro() {
		echo '<p>';
		esc_html_e( 'If you want to change Yoast SEO\'s defaults for indexing, this is your spot. Note that the defaults you\'re changing are defaults in Yoast SEO for a good reason, so you\'re doing this at your own risk.', 'yoast-seo-granular-control' );
		echo '</p>';
	}
}