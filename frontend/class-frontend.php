<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Frontend Class the Yoast_SEO_Granular_Control plugin.
 */
class Frontend {

	/**
	 * Holds the plugin options.
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->options = Options::instance()->get();

		$integrations = array(
			new Filter_Robots(),
			new XML_Sitemaps(),
		);

		foreach ( $integrations as $integration ) {
			$integration->register_hooks( $this->options );
		}
	}

}
