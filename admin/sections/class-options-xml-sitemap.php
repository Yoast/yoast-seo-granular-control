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
	 * Register the XML sitemap settings.
	 */
	public function register() {
		$this->general_settings_section();
		$this->exclude_by_id_section();
		$this->exclude_users_section();
	}

	/**
	 * General XML sitemaps settings section.
	 *
	 * @return void
	 */
	private function general_settings_section() {
		$section = $section = 'sitemap-settings';

		add_settings_section(
			$section,
			__( 'General XML sitemap settings', 'yoast-seo-granular-control' ),
			[ $this, 'intro' ],
			$this->page
		);

		$exclude_fields = [
			'xml-exclude-images'  => __( 'Exclude images', 'yoast-seo-granular-control' ),
			'xml-exclude-lastmod' => __( 'Exclude lastmod field from XML sitemaps', 'yoast-seo-granular-control' ),
			'xml-disable-ping'    => __( 'Disable automatic ping of search engines on update', 'yoast-seo-granular-control' ),
		];
		$this->checkbox_list( $exclude_fields, $section );

		$key = 'xml-number-items';
		add_settings_field(
			$key,
			'<label for="' . $key . '">' . __( 'Maximum number of items in an XML sitemap', 'yoast-seo-granular-control' ) . '</label>',
			[ $this, 'input_number' ],
			$this->page,
			$section,
			[
				'name'  => $key,
				'value' => Options::get( $key ),
				'desc'  => __( 'Set to 0 to stick to default.', 'yoast-seo-granular-control' ),
			]
		);
	}

	/**
	 * This section allows excluding URLs by post or term ID.
	 *
	 * @return void
	 */
	private function exclude_by_id_section() {
		$section = 'sitemap-settings-exclude-id';

		add_settings_section(
			$section,
			__( 'Exclude URLs by ID', 'yoast-seo-granular-control' ),
			[ $this, 'exclude_by_id_intro' ],
			$this->page
		);

		$exclude_lists = [
			'xml-exclude-posts' => __( 'Exclude posts/pages by ID', 'yoast-seo-granular-control' ),
			'xml-exclude-terms' => __( 'Exclude tags/categories/terms by ID', 'yoast-seo-granular-control' ),
		];
		foreach ( $exclude_lists as $key => $label ) {
			$args = [
				'name'  => $key,
				'value' => Options::get( $key ),
				'desc'  => __( 'Separate with commas', 'yoast-seo-granular-control' ),
			];
			add_settings_field(
				$key,
				'<label for="' . $key . '">' . $label . '<label>',
				[ $this, 'input_text' ],
				$this->page,
				$section,
				$args
			);
		}

	}

	/**
	 * This section allows excluding users from the XML sitemap by role.
	 *
	 * @return void
	 */
	private function exclude_users_section() {
		$section = 'sitemap-settings-users';

		add_settings_section(
			$section,
			__( 'Exclude users by role', 'yoast-seo-granular-control' ),
			[ $this, 'per_user_section_intro' ],
			$this->page
		);

		foreach ( $this->get_role_names() as $role => $label ) {
			$key = 'xml-exclude-roles';
			$id  = $key . '_' . $role;
			add_settings_field(
				$key . '_' . $role,
				// Translators: %s becomes the name of the role.
				'<label for="' . $id . '">' . $label . ' <code>' . $role . '</code></label>',
				[ $this, 'input_checkbox_array' ],
				$this->page,
				$section,
				[
					'id'    => $id,
					'name'  => $key,
					'value' => $role,
				]
			);
		}
	}

	/**
	 * Intro for the XML sitemap settings section.
	 *
	 * @return void
	 */
	public function intro() {
		$this->intro_helper( __( 'Change the general settings for XML sitemaps.', 'yoast-seo-granular-control' ) );
	}

	/**
	 * Intro for the XML sitemap per user settings section.
	 *
	 * @return void
	 */
	public function per_user_section_intro() {
		$this->intro_helper( __( 'Exclude users with the following roles from XML sitemaps:', 'yoast-seo-granular-control' ) );
	}

	/**
	 * Intro for the exclude by ID section.
	 *
	 * @return void
	 */
	public function exclude_by_id_intro() {
		$this->intro_helper( __( 'Exclude pages or terms from XML sitemaps by ID.', 'yoast-seo-granular-control' ) );
	}
}