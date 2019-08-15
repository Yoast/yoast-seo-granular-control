<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Adds the options for Schema changes.
 */
class Options_Schema extends Options_Admin implements Options_Section {
	/**
	 * @var string
	 */
	public $page = 'yoast-seo-granular-control-schema';

	/**
	 * @var string
	 */
	public $section = 'schema-settings';

	/**
	 * Register the Schema settings.
	 */
	public function register() {
		add_settings_section(
			$this->section,
			__( 'Schema settings', 'yoast-seo-granular-control' ),
			[ $this, 'intro' ],
			$this->page
		);

		$disable_parts = [
			'schema-disable'                => __( 'Disable schema output', 'yoast-seo-granular-control' ),
			'schema-disable-date-published' => __( 'Remove date published', 'yoast-seo-granular-control' ),
			'schema-disable-date-modified'  => __( 'Remove date modified', 'yoast-seo-granular-control' ),
			'schema-disable-organization'   => __( 'Disable Organization Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-website'        => __( 'Disable WebSite Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-webpage'        => __( 'Disable WebPage Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-breadcrumb'     => __( 'Disable Breadcrumb Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-article'        => __( 'Disable Article Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-person'         => __( 'Disable Person Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-author'         => __( 'Disable Author\'s Person Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-site-search'    => __( 'Disable Site Search Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-faq'            => __( 'Disable FAQ Schema output', 'yoast-seo-granular-control' ),
			'schema-disable-howto'          => __( 'Disable HowTo Schema output', 'yoast-seo-granular-control' ),
		];
		foreach ( $disable_parts as $key => $label ) {
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
	 * Intro for the XML sitemap settings screen.
	 */
	public function intro() {
		echo '<p>';
		esc_html_e( 'Change the settings for Yoast SEO\'s extensive Schema output.', 'yoast-seo-granular-control' );
		echo '</p>';
	}

}