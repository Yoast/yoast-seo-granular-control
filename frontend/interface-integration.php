<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * An interface for registering integrations with WordPress.
 */
interface Integration {
	/**
	 * Registers all hooks to WordPress.
	 *
	 * @param array $options The options for the plugin.
	 *
	 * @return void
	 */
	public function register_hooks( $options );
}
