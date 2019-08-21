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
	 * Register the Schema settings.
	 */
	public function register() {
		$section = 'schema-settings';

		add_settings_section(
			$section,
			__( 'General Schema.org settings', 'yoast-seo-granular-control' ),
			[ $this, 'general_settings_intro' ],
			$this->page
		);

		$disable_parts = [
			'schema-disable'                => __( 'Disable schema output', 'yoast-seo-granular-control' ),
			'schema-disable-date-published' => __( 'Remove date published', 'yoast-seo-granular-control' ),
			'schema-disable-date-modified'  => __( 'Remove date modified', 'yoast-seo-granular-control' ),
		];
		$this->checkbox_list( $disable_parts, $section );

		$this->section_disable_pieces();

	}

	private function section_disable_pieces() {
		$section = 'schema-settings-disable-pieces';

		add_settings_section(
			$section,
			__( 'Disable specific Schema.org pieces', 'yoast-seo-granular-control' ),
			[ $this, 'disable_pieces_intro' ],
			$this->page
		);

		$disable_parts = [
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
		$this->checkbox_list( $disable_parts, $section );

	}

	/**
	 * Intro for the XML sitemap settings screen.
	 */
	public function general_settings_intro() {
		$this->intro_helper( __( 'Change the settings for Yoast SEO\'s extensive Schema output.', 'yoast-seo-granular-control' ) );
	}

	/**
	 * This section allows disabling specific pieces from the Yoast SEO Schema output.
	 */
	public function disable_pieces_intro() {
		$this->intro_helper( __( 'Disable specific pieces from the Yoast SEO Schema ouput.', 'yoast-seo-granular-control' ) );
	}
}