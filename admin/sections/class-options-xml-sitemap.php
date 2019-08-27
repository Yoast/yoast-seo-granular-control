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
		$this->deep_sitemap_controls();
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

		$key = 'xml-disable-author';
		add_settings_field(
			$key,
			'<label for="'.$key.'">'.__('Disable the XML author sitemap entirely', 'yoast-seo-granular-control').'</label>',
			[ $this, 'input_checkbox' ],
			$this->page,
			$section,
			[
				'name' => $key,
				'value' => Options::get( $key )
			]
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
		$this->intro_helper( __( 'Exclude users with the following roles from the author XML sitemap:', 'yoast-seo-granular-control' ) );
	}

	/**
	 * Intro for the exclude by ID section.
	 *
	 * @return void
	 */
	public function exclude_by_id_intro() {
		$this->intro_helper( __( 'Exclude pages or terms from XML sitemaps by ID.', 'yoast-seo-granular-control' ) );
	}

	/**
	 * Intro for the exclude by ID section.
	 *
	 * @return void
	 */
	public function deep_sitemap_controls_intro() {
		$this->intro_helper( __( 'Influence settings per specific sitemap. If you\'re missing a specific XML sitemap for a post type or taxonomy, that post type or taxonomy might be set to noindex, in which case it\'s already not showing here.', 'yoast-seo-granular-control' ) );
	}

	private function deep_sitemap_controls() {
		$section = 'sitemap-settings-deep';

		add_settings_section(
			$section,
			__( 'Settings per XML sitemap', 'yoast-seo-granular-control' ),
			[ $this, 'deep_sitemap_controls_intro' ],
			$this->page
		);

		foreach ( \WPSEO_Post_Type::get_accessible_post_types() as $post_type ) {
			$this->per_post_type_sitemap_settings( $post_type );
		}

		$taxonomies = array_merge(
			get_taxonomies( [ 'public' => true, '_builtin' => true ] ),
			get_taxonomies( [ 'public' => true, '_builtin' => false ] )
		);
		foreach ( $taxonomies as $taxonomy ) {
			if ( $this->is_valid_taxonomy( $taxonomy ) ) {
				$this->per_taxonomy_sitemap_settings( $taxonomy );
			}
		}
	}

	/**
	 * Creates a group of settings for an XML sitemap.
	 *
	 * @param string $post_type The post type we're working on.
	 */
	private function per_post_type_sitemap_settings( $post_type ) {
		$post_type = get_post_type_object( $post_type );
		$fields    = [
			'xml-disable-post_type' => __( 'Disable XML sitemap', 'yoast-seo-granular-control' ),
		];
		$this->per_sitemap_settings( $post_type->name, 'Post type: ' . $post_type->label. ' <code>' . $post_type->name . '</code>', $fields );
	}

	/**
	 * Creates a group of settings for an XML sitemap.
	 *
	 * @param $taxonomy
	 */
	private function per_taxonomy_sitemap_settings( $taxonomy ) {
		$taxonomy = get_taxonomy( $taxonomy );
		$fields   = [
			'xml-disable-taxonomy'       => __( 'Disable XML sitemap', 'yoast-seo-granular-control' ),
			'xml-include-empty-taxonomy' => __( 'Include empty term archives', 'yoast-seo-granular-control' ),
		];
		$this->per_sitemap_settings( $taxonomy->name, 'Taxonomy: ' . $taxonomy->label. ' <code>' . $taxonomy->name . '</code>', $fields );
	}

	/**
	 * Check if taxonomy by name is valid to appear in sitemaps.
	 *
	 * @param string $taxonomy_name Taxonomy name to check.
	 *
	 * @return bool
	 */
	private function is_valid_taxonomy( $taxonomy_name ) {

		if ( \WPSEO_Options::get( "noindex-tax-{$taxonomy_name}" ) === true ) {
			return false;
		}

		if ( in_array( $taxonomy_name, array( 'link_category', 'nav_menu' ), true ) ) {
			return false;
		}

		if ( 'post_format' === $taxonomy_name && \WPSEO_Options::get( 'disable-post_format', false ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Creates a group of settings for an XML sitemap.
	 *
	 * @param string $name  The internal name of the post type, taxonomy or role.
	 * @param string $label The label of the post type, taxonomy or role.
	 * @param        $fields
	 */
	private function per_sitemap_settings( $name, $label, $fields ) {

		$section = 'sitemap-settings-deep-' . $name;

		add_settings_section(
			$section,
			$label,
			'__return_false',
			$this->page
		);

		foreach ( $fields as $key => $field_label ) {
			$id = $key . '_' . $name;
			add_settings_field(
				$id,
				// Translators: %s becomes the name of the role.
				'<label for="' . $id . '">' . $field_label . '</label>',
				[ $this, 'input_checkbox_array' ],
				$this->page,
				$section,
				[
					'id'    => $id,
					'name'  => $key,
					'value' => $name,
				]
			);
		}
	}
}