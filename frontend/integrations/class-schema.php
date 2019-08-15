<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Class Schema
 *
 * Filters the schema code based on settings.
 */
class Schema implements Integration {
	/**
	 * Registers all hooks to WordPress.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'wpseo_json_ld_output', [ $this, 'disable_output' ] );
		add_filter( 'wpseo_schema_article', [ $this, 'filter_date_output' ] );
		add_filter( 'wpseo_schema_webpage', [ $this, 'filter_date_output' ] );
		add_filter( 'wpseo_schema_website', [ $this, 'filter_site_search_output' ] );
		$pieces = [
			'organization',
			'breadcrumb',
			'website',
			'webpage',
			'article',
			'person',
			'author',
		];
		foreach ( $pieces as $piece ) {
			if ( Options::get( 'schema-disable-' . $piece ) ) {
				add_filter( 'wpseo_schema_' . $piece, '__return_false' );
			}
		}

		$block_pieces = [
			'faq',
			'how-to',
		];
		foreach ( $block_pieces as $piece ) {
			add_filter( 'wpseo_schema_block_yoast/' . $piece . '-block', [ $this, 'filter_block' ], PHP_INT_MAX - 1 );
		}
	}

	/**
	 * Removes blocks from Schema output if needed.
	 *
	 * @param array $output The schema output.
	 *
	 * @return array $output The schema output.
	 */
	public function filter_block( $output ) {
		foreach( $output as $key => $piece ) {
			if ( $piece['@type'] === 'HowTo' && Options::get( 'schema-disable-howto' ) ) {
				unset( $output[ $key ] );
			}
			if ( isset( $piece[0] ) && strpos( $piece[0]['mainEntityOfPage']['@id'], 'faq-block' ) !== false && Options::get( 'schema-disable-faq' ) ) {
				unset( $output[ $key ] );
			}
			if ( $piece['@type'] === 'Question' && Options::get( 'schema-disable-faq' ) ) {
				unset( $output[ $key ] );
			}
		}
		return $output;
	}

	/**
	 * Filters the schema output.
	 *
	 * @param array $output The schema output.
	 *
	 * @return bool|array False when disabled, array with output when not.
	 */
	public function disable_output( $output ) {
		if ( Options::get( 'schema-disable' ) ) {
			return false;
		}

		return $output;
	}

	/**
	 * Removes the date from output when settings are set
	 *
	 * @param array $output The Schema output.
	 *
	 * @return array $output The Schema output.
	 */
	public function filter_date_output( $output ) {
		if ( Options::get( 'schema-disable-date-published' ) ) {
			unset( $output['datePublished'] );
		}
		if ( Options::get( 'schema-disable-date-modified' ) ) {
			unset( $output['dateModified'] );
		}

		return $output;
	}

	/**
	 * Removes the site search schema if wanted.
	 *
	 * @param array $output The Schema output.
	 *
	 * @return array $output The Schema output.
	 */
	public function filter_site_search_output( $output ) {
		if ( Options::get( 'schema-disable-site-search' ) ) {
			unset( $output['potentialAction'] );
		}

		return $output;
	}

}