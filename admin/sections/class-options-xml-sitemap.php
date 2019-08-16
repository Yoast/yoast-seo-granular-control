<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Adds the options for XML Sitemap changes.
 */
class Options_XML_Sitemap extends Options_Admin implements Options_Section {
	/**
	 *
	 */
	public $page = 'yoast-seo-granular-control-sitemaps';

	/**
	 *
	 */
	public $section = 'sitemap-settings';

	/**
	 * Register the XML sitemap settings.
	 */
	public function register() {
		add_settings_section(
			$this->section,
			__( 'XML sitemap settings', 'yoast-seo-granular-control' ),
			[ $this, 'intro' ],
			$this->page
		);

		$exclude_fields = [
			'xml-exclude-images'  => __( 'Exclude images', 'yoast-seo-granular-control' ),
			'xml-exclude-lastmod' => __( 'Exclude lastmod field from XML sitemaps', 'yoast-seo-granular-control' ),
			'xml-disable-ping'    => __( 'Disable automatic ping of search engines on update', 'yoast-seo-granular-control' ),
		];
		foreach ( $exclude_fields as $key => $label ) {
			$val = Options::get( $key );
			add_settings_field(
				$key,
				$label,
				[ $this, 'input_checkbox' ],
				$this->page,
				$this->section,
				[
					'name'  => $key,
					'value' => isset( $val ) ? $val : false,
				]
			);
		}

		$exclude_lists = [
			'xml-exclude-posts' => __( 'Exclude posts/pages by ID', 'yoast-seo-granular-control' ),
			'xml-exclude-terms' => __( 'Exclude tags/categories/terms by ID', 'yoast-seo-granular-control' ),
		];
		foreach ( $exclude_lists as $key => $label ) {
			$args = [
				'name'  => 'yseo_granular[' . $key . ']',
				'value' => Options::get( $key ),
				'desc'  => __( 'Separate with commas', 'yoast-seo-granular-control' ),
			];
			add_settings_field(
				$key,
				$label,
				[ $this, 'input_text' ],
				$this->page,
				$this->section,
				$args
			);
		}

		$key = 'xml-number-items';
		add_settings_field(
			$key,
			__( 'Maximum number of items in an XML sitemap', 'yoast-seo-granular-control' ),
			[ $this, 'input_number' ],
			$this->page,
			$this->section,
			[
				'name'  => 'yseo_granular[' . $key . ']',
				'value' => Options::get( $key ),
				'desc'  => __( 'Set to 0 to stick to default.', 'yoast-seo-granular-control' ),
			]
		);
	}

	/**
	 * Intro for the XML sitemap settings screen.
	 */
	public function intro() {
		echo '<p>';
		esc_html_e( 'Change the settings for XML sitemaps.', 'yoast-seo-granular-control' );
		echo '</p>';
	}
}