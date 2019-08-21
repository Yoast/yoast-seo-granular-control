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
interface Options_Section {
	/**
	 * Registers the options section.
	 *
	 * @return void
	 */
	public function register();
}