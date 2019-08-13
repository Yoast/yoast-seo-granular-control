<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Backend Class for the Yoast_SEO_Granular_Control plugin options.
 */
class Options_Admin extends Options {

	/**
	 * The option group name.
	 *
	 * @var string
	 */
	public static $option_group = 'Yoast_SEO_Granular_Control';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		parent::__construct();
	}

	/**
	 * Register the needed option and its settings sections.
	 */
	public function admin_init() {
		register_setting( self::$option_group, parent::$option_name, array( $this, 'sanitize_options_on_save' ) );

		$this->register_indexing_settings();
		$this->register_xml_sitemap_settings();
	}

	/**
	 * Register the indexing settings.
	 */
	private function register_indexing_settings() {
		$page    = 'yoast-seo-granular-control-indexing';
		$section = 'indexing-settings';

		add_settings_section(
			$section,
			__( 'Indexing settings', 'yoast-seo-granular-control' ),
			[ $this, 'indexing_settings_intro' ],
			$page
		);

		$key = 'noindex-paginated-archives';
		add_settings_field(
			$key,
			__( 'Noindex paginated archives', 'yoast-seo-granular-control' ),
			array( $this, 'input_checkbox' ),
			$page,
			$section,
			array(
				'name'  => $key,
				'value' => isset( $this->options[ $key ] ) ? $this->options[ $key ] : false,
			)
		);
	}

	/**
	 * Register the XML sitemap settings.
	 */
	private function register_xml_sitemap_settings() {
		$page    = 'yoast-seo-granular-control-sitemaps';
		$section = 'sitemap-settings';

		add_settings_section(
			$section,
			__( 'XML sitemap settings', 'yoast-seo-granular-control' ),
			[ $this, 'xml_sitemap_settings_intro' ],
			$page
		);

		$key = 'xml-exclude-images';
		add_settings_field(
			$key,
			__( 'Exclude images', 'yoast-seo-granular-control' ),
			array( $this, 'input_checkbox' ),
			$page,
			$section,
			array(
				'name'  => $key,
				'value' => isset( $this->options[ $key ] ) ? $this->options[ $key ] : false,
			)
		);

		$exclude_lists = array(
			'xml-exclude-posts' => __( 'Exclude posts/pages by ID', 'yoast-seo-granular-control' ),
			'xml-exclude-terms' => __( 'Exclude tags/categories/terms by ID', 'yoast-seo-granular-control' ),
		);
		foreach ( $exclude_lists as $key => $label ) {
			$args = array(
				'name'  => 'yseo_granular[' . $key . ']',
				'value' => $this->options[ $key ],
				'desc' => __( 'Separate with commas', 'yoast-seo-granular-control' ),
			);
			add_settings_field(
				$key,
				$label,
				array( $this, 'input_text' ),
				$page,
				$section,
				$args
			);
		}

		$exclude_fields = [
			'xml-exclude-lastmod' => __( 'Exclude lastmod field from XML sitemaps', 'yoast-seo-granular-control' ),
			'xml-exclude-prio' => __( 'Exclude prio field from XML sitemaps', 'yoast-seo-granular-control' ),
		];
		foreach ( $exclude_fields as $key => $label ) {
			add_settings_field(
				$key,
				$label,
				array( $this, 'input_checkbox' ),
				$page,
				$section,
				array(
					'name'  => $key,
					'value' => isset( $this->options[ $key ] ) ? $this->options[ $key ] : false,
				)
			);
		}
	}

	/**
	 * Sanitizes and trims a string.
	 *
	 * @param string $string String to sanitize.
	 *
	 * @return string
	 */
	private function sanitize_string( $string ) {
		return (string) trim( sanitize_text_field( $string ) );
	}

	/**
	 * Sanitize options.
	 *
	 * @param array $new_options Options to sanitize.
	 *
	 * @return array
	 */
	public function sanitize_options_on_save( $new_options ) {
		foreach ( $new_options as $key => $value ) {
			switch ( self::$option_var_types[ $key ] ) {
				case 'string':
					$new_options[ $key ] = $this->sanitize_string( $new_options[ $key ] );
					break;
				case 'bool':
					if ( isset( $new_options[ $key ] ) ) {
						$new_options[ $key ] = true;
					} else {
						$new_options[ $key ] = false;
					}
					break;
			}
		}

		return $new_options;
	}

	/**
	 * Intro for the indexing settings screen.
	 */
	public function indexing_settings_intro() {
		echo '<p>';
		esc_html_e( 'If you want to change Yoast SEO\'s defaults for indexing, this is your spot. Note that the defaults you\'re changing are defaults in Yoast SEO for a good reason, so you\'re doing this at your own risk.', 'yoast-seo-granular-control' );
		echo '</p>';
	}

	/**
	 * Intro for the XML sitemap settings screen.
	 */
	public function xml_sitemap_settings_intro() {
		echo '<p>';
		esc_html_e( 'Change the settings for XML sitemaps.', 'yoast-seo-granular-control' );
		echo '</p>';
	}

	/**
	 * Output an optional input description.
	 *
	 * @param array $args Arguments to get data from.
	 */
	private function input_desc( $args ) {
		if ( isset( $args['desc'] ) ) {
			echo '<p class="description">' . esc_html( $args['desc'] ) . '</p>';
		}
	}

	/**
	 * Create a text input.
	 *
	 * @param array $args Arguments to get data from.
	 */
	public function input_text( $args ) {
		echo '<input type="text" class="text" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $args['value'] ) . '"/>';
		$this->input_desc( $args );
	}

	/**
	 * Create a checkbox input.
	 *
	 * @param array $args Arguments to get data from.
	 */
	public function input_checkbox( $args ) {
		$option = isset( $this->options[ $args['name'] ] ) ? $this->options[ $args['name'] ] : false;
		echo '<input class="checkbox" type="checkbox" ' . checked( $option, true, false ) . ' name="yseo_granular[' . esc_attr( $args['name'] ) . ']"/>';
		$this->input_desc( $args );
	}
}
