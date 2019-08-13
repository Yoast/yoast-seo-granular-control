<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Options Class for the Yoast_SEO_Granular_Control plugin.
 */
class Options {

	/**
	 * The default options for the Yoast_SEO_Granular_Control plugin.
	 *
	 * @var array
	 */
	public static $option_defaults = [
		'noindex-paginated-archives' => false,
		'xml-exclude-images'         => false,
		'xml-exclude-lastmod'        => false,
		'xml-exclude-prio'           => false,
		'xml-exclude-posts'          => '',
		'xml-exclude-terms'          => '',
	];

	/**
	 * Holds the type of variable that each option is, so we can cast it to that.
	 *
	 * @var array
	 */
	public static $option_var_types = [
		//		'site_id'          => 'string',
		//		'cookies_disable'  => 'bool',
		'noindex-paginated-archives' => 'bool',
		'xml-exclude-images'         => 'bool',
		'xml-exclude-lastmod'        => 'bool',
		'xml-exclude-prio'           => 'bool',
		'xml-exclude-posts'          => 'string',
		'xml-exclude-terms'          => 'string',
	];

	/**
	 * Name of the option we're using.
	 *
	 * @var string
	 */
	public static $option_name = 'yseo_granular';

	/**
	 * Saving active instance of this class in this static var.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Holds the actual options.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->load_options();
		$this->sanitize_options();
	}

	/**
	 * Loads Control-options set in WordPress.
	 *
	 * If already set: trim some option. Otherwise load defaults.
	 */
	private function load_options() {
		$options = get_option( self::$option_name );
		if ( ! is_array( $options ) ) {
			$this->options = self::$option_defaults;
			update_option( self::$option_name, $this->options );
		} else {
			$this->options = array_merge( self::$option_defaults, $options );
		}
	}

	/**
	 * Forces all options to be of the type we expect them to be of.
	 */
	private function sanitize_options() {
		foreach ( $this->options as $key => $value ) {
			if ( ! isset( self::$option_var_types[ $key ] ) ) {
				unset( $this->options[ $key ] );
			}
			switch ( self::$option_var_types[ $key ] ) {
				case 'string':
					$this->options[ $key ] = (string) $value;
					break;
				case 'bool':
					$this->options[ $key ] = (bool) $value;
			}
		}
	}

	/**
	 * Getting instance of this object. If instance doesn't exists it will be created.
	 *
	 * @return object|Options
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Options();
		}

		return self::$instance;
	}

	/**
	 * Returns the Yoast_SEO_Granular_Control options.
	 *
	 * @return array
	 */
	public function get() {
		return $this->options;
	}
}
