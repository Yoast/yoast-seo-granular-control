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
	 * Class constructor.
	 */
	public function __construct() {
		$integrations = array(
			new Indexing(),
			new Schema(),
			new XML_Sitemaps(),
		);

		foreach ( $integrations as $integration ) {
			$integration->register_hooks();
		}
	}

}
