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

		$sections = array(
			new Options_Indexing(),
			new Options_Schema(),
			new Options_XML_Sitemap(),
		);

		foreach ( $sections as $section ) {
			$section->register();
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
		if ( isset( $_POST['yst_active_tab'] ) ) {
			set_transient( 'yst_active_tab', $_POST['yst_active_tab'] );
		}
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
				case 'int':
					$new_options[ $key ] = (int) $new_options[ $key ];
					break;
			}
		}

		return $new_options;
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
	 * Create a number input.
	 *
	 * @param array $args Arguments to get data from.
	 */
	public function input_number( $args ) {
		echo '<input type="number" class="text" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $args['value'] ) . '"/>';
		$this->input_desc( $args );
	}

	/**
	 * Create a checkbox input.
	 *
	 * @param array $args Arguments to get data from.
	 */
	public function input_checkbox( $args ) {
		$val    = Options::get( $args['name'] );
		$option = isset( $val ) ? $val : false;
		echo '<input class="checkbox" type="checkbox" ' . checked( $option, true, false ) . ' name="yseo_granular[' . esc_attr( $args['name'] ) . ']"/>';
		$this->input_desc( $args );
	}
}
