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
	 * Register the indexing settings.
	 */
	public function register() {
		$this->section_general_indexing();
		$this->section_feeds();
		$this->section_homepage();
	}

	/**
	 * The general indexing settings.
	 */
	private function section_general_indexing() {
		$section = 'indexing-settings';

		add_settings_section(
			$section,
			__( 'General indexing settings', 'yoast-seo-granular-control' ),
			[ $this, 'intro_general_indexing' ],
			$this->page
		);

		$settings = [
			'noindex-paginated-archives' => __( 'Noindex paginated archives', 'yoast-seo-granular-control' ),
			'disable-rel-next-prev'      => __( 'Disable rel="next"/rel="prev"', 'yoast-seo-granular-control' ),
		];
		$this->checkbox_list( $settings, $section );

		$key = 'force-canonical';
		add_settings_field(
			$key,
			__( 'Force canonical to a protocol', 'yoast-seo-granular-control' ),
			array( $this, 'input_radio' ),
			$this->page,
			$section,
			array(
				'name'   => $key,
				'value'  => Options::get( $key ),
				'values' => [
					'default' => __( 'Leave default', 'yoast-seo-granular-control' ),
					'http'    => 'HTTP',
					'https'   => 'HTTPS',
				],
			)
		);
	}

	/**
	 * The general indexing settings.
	 */
	private function section_feeds() {
		$section = 'indexing-settings-feeds';

		add_settings_section(
			$section,
			__( 'Feed settings', 'yoast-seo-granular-control' ),
			[ $this, 'intro_feeds' ],
			$this->page
		);

		$settings = [
			'noindex-feeds'         => __( 'Noindex all RSS feeds', 'yoast-seo-granular-control' ),
			'noindex-comment-feeds' => __( 'Noindex comment RSS feeds', 'yoast-seo-granular-control' ),
			'noindex-archive-feeds' => __( 'Noindex RSS feeds for categories, tags & other archives', 'yoast-seo-granular-control' ),
		];
		$this->checkbox_list( $settings, $section );
	}

	/**
	 * The general indexing settings.
	 */
	private function section_homepage() {
		$section = 'indexing-settings-homepage';

		add_settings_section(
			$section,
			__( 'Homepage settings', 'yoast-seo-granular-control' ),
			[ $this, 'intro_homepage' ],
			$this->page
		);

		$settings = [
			// Translators: %s expands to noindex.
			'homepage-noindex'   => sprintf( __( 'Add %s', 'yoast-seo-granular-control' ), '<code>noindex</code>' ),
			// Translators: %s expands to nofollow.
			'homepage-nofollow'  => sprintf( __( 'Add %s', 'yoast-seo-granular-control' ), '<code>nofollow</code>' ),
			// Translators: %s expands to noarchive.
			'homepage-noarchive' => sprintf( __( 'Add %s', 'yoast-seo-granular-control' ), '<code>noarchive</code>' ),
			// Translators: %s expands to nosnippet.
			'homepage-nosnippet' => sprintf( __( 'Add %s', 'yoast-seo-granular-control' ), '<code>nosnippet</code>' ),
		];
		$this->checkbox_list( $settings, $section );
	}

	/**
	 * Intro for the general indexing settings section.
	 */
	public function intro_general_indexing() {
		$this->intro_helper( __( 'If you want to change Yoast SEO\'s defaults for indexing, this is your spot. Note that the defaults you\'re changing are defaults in Yoast SEO for a good reason, so you\'re doing this at your own risk.', 'yoast-seo-granular-control' ) );
	}

	/**
	 * Intro for the homepage meta robots section.
	 */
	public function intro_homepage() {
		$this->intro_helper( __( 'Set the index settings for the homepage.', 'yoast-seo-granular-control' ) );
	}

	/**
	 * Intro for the feeds section.
	 */
	public function intro_feeds() {
		$this->intro_helper( __( 'Set the SEO settings for your site\'s RSS feeds.', 'yoast-seo-granular-control' ) );
	}
}
