<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Adds general options.
 */
class Options_General extends Options_Admin implements Options_Section {
	/**
	 * @var string
	 */
	var $page = 'yoast-seo-granular-control-general';

	/**
	 * Registers the options section.
	 *
	 * @return void
	 */
	public function register() {
		$this->section_gutenberg();
		$this->section_user_archives();
	}

	/**
	 * The Gutenberg section.
	 */
	private function section_gutenberg() {
		$section = 'general-settings-gutenberg';

		add_settings_section(
			$section,
			__( 'Gutenberg', 'yoast-seo-granular-control' ),
			[ $this, 'gutenberg_intro' ],
			$this->page
		);

		// Note that the functionality that makes this feature work is in class-admin.php.
		$key = 'disable-structured-data-blocks';
		add_settings_field(
			$key,
			// Translators: %s becomes the name of the role.
			'<label for="' . $key . '">' . __( 'Disable Gutenberg blocks', 'yoast-seo-granular-control' ) . '</label>',
			[ $this, 'input_checkbox' ],
			$this->page,
			$section,
			[
				'name'  => $key,
				'value' => Options::get( $key ),
			]
		);
	}


	/**
	 * This section allows disabling user archives by role.
	 */
	private function section_user_archives() {
		$section = 'indexing-settings-user-archives';

		add_settings_section(
			$section,
			__( 'Disable user archives by role', 'yoast-seo-granular-control' ),
			[ $this, 'intro_user_archives' ],
			$this->page
		);

		foreach ( $this->get_role_names() as $role => $label ) {
			$key = 'disable-archives';
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
	 * The Gutenberg section's intro.
	 *
	 * @return void
	 */
	public function gutenberg_intro() {
		$this->intro_helper( __( 'Change how Yoast SEO integrates with the Gutenberg editor.', 'yoast-seo-granular-control' ) );
	}

	/**
	 * Intro for the user archives section.
	 */
	public function intro_user_archives() {
		$this->intro_helper( __( 'Disable user archives based on the user\'s role:', 'yoast-seo-granular-control' ) );
	}

}
