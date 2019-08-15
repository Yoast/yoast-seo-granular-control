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
		'disable-rel-next-prev'         => false,
		'noindex-paginated-archives'    => false,
		'schema-disable'                => false,
		'schema-disable-date-published' => false,
		'schema-disable-date-modified'  => false,
		'schema-disable-organization'   => false,
		'schema-disable-website'        => false,
		'schema-disable-webpage'        => false,
		'schema-disable-breadcrumb'     => false,
		'schema-disable-article'        => false,
		'schema-disable-person'         => false,
		'schema-disable-author'         => false,
		'schema-disable-site-search'    => false,
		'schema-disable-faq'            => false,
		'schema-disable-howto'          => false,
		'xml-disable-ping'              => false,
		'xml-exclude-images'            => false,
		'xml-exclude-lastmod'           => false,
		'xml-exclude-prio'              => false,
		'xml-exclude-posts'             => '',
		'xml-exclude-terms'             => '',
		'xml-number-items'              => 0,
	];

	/**
	 * Holds the type of variable that each option is, so we can cast it to that.
	 *
	 * @var array
	 */
	public static $option_var_types = [
		'disable-rel-next-prev'         => 'bool',
		'noindex-paginated-archives'    => 'bool',
		'schema-disable'                => 'bool',
		'schema-disable-date-published' => 'bool',
		'schema-disable-date-modified'  => 'bool',
		'schema-disable-organization'   => 'bool',
		'schema-disable-website'        => 'bool',
		'schema-disable-webpage'        => 'bool',
		'schema-disable-breadcrumb'     => 'bool',
		'schema-disable-article'        => 'bool',
		'schema-disable-person'         => 'bool',
		'schema-disable-author'         => 'bool',
		'schema-disable-site-search'    => 'bool',
		'schema-disable-faq'            => 'bool',
		'schema-disable-howto'          => 'bool',
		'xml-disable-ping'              => 'bool',
		'xml-exclude-images'            => 'bool',
		'xml-exclude-lastmod'           => 'bool',
		'xml-exclude-prio'              => 'bool',
		'xml-exclude-posts'             => 'string',
		'xml-exclude-terms'             => 'string',
		'xml-number-items'              => 'int',
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
	public static $options = array();

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
	private static function load_options() {
		$options = get_option( self::$option_name );
		if ( is_array( $options ) ) {
			self::$options = array_merge( self::$option_defaults, $options );

			return;
		}

		self::$options = self::$option_defaults;
		update_option( self::$option_name, self::$options );
	}

	/**
	 * Forces all options to be of the type we expect them to be of.
	 */
	private function sanitize_options() {
		foreach ( self::$options as $key => $value ) {
			if ( ! isset( self::$option_var_types[ $key ] ) ) {
				unset( self::$options[ $key ] );
			}
			switch ( self::$option_var_types[ $key ] ) {
				case 'string':
					self::$options[ $key ] = (string) $value;
					break;
				case 'bool':
					self::$options[ $key ] = (bool) $value;
					break;
				case 'int':
					self::$options[ $key ] = (int) $value;
					break;
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
	 * @param string $key The option to retrieve.
	 *
	 * @return mixed The option.
	 */
	public static function get( $key ) {
		if ( self::$options === array() ) {
			self::load_options();
		}

		return self::$options[ $key ];
	}
}
