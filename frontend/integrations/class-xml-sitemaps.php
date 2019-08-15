<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Class XML_Sitemaps
 *
 * Filters the XML sitemaps output based on settings.
 */
class XML_Sitemaps implements Integration {
	/**
	 * Registers all hooks to WordPress.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'wpseo_xml_sitemap_include_images', [ $this, 'filter_images' ] );
		add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', [ $this, 'exclude_posts' ] );
		add_filter( 'wpseo_exclude_from_sitemap_by_term_ids', [ $this, 'exclude_terms' ] );
		add_filter( 'wpseo_sitemap_entry', [ $this, 'filter_entry' ] );
		add_filter( 'wpseo_sitemap_entries_per_page', [ $this, 'filter_entries_per_page' ] );
		add_filter( 'wpseo_allow_xml_sitemap_ping', [ $this, 'filter_ping' ] );
	}

	/**
	 * Filters the robots meta string.
	 *
	 * @param bool $include_images The robots meta string.
	 *
	 * @return string The robots meta string.
	 */
	public function filter_images( $include_images ) {
		if ( Options::get( 'xml-exclude-images' ) === true ) {
			return false;
		}

		return $include_images;
	}

	/**
	 * Returns the array of posts to exclude from the XML sitemap.
	 *
	 * @param array $exploded_post_ids The posts to exclude.
	 *
	 * @return array $posts_to_exclude The posts to exclude.
	 */
	public function exclude_posts( $exploded_post_ids ) {
		$excluded_posts = array_map( 'intval', explode( ',', Options::get( 'xml-exclude-posts' ) ) );

		return array_merge( $exploded_post_ids, $excluded_posts );
	}

	/**
	 * Returns the array of posts to exclude from the XML sitemap.
	 *
	 * @param array $exploded_post_ids The posts to exclude.
	 *
	 * @return array $posts_to_exclude The posts to exclude.
	 */
	public function exclude_terms( $exploded_post_ids ) {
		$excluded_terms = array_map( 'intval', explode( ',', Options::get( 'xml-exclude-terms' ) ) );

		return array_merge( $exploded_post_ids, $excluded_terms );
	}

	/**
	 * Filters each individual XML sitemap entry.
	 *
	 * @param array $entry The XML sitemap entries data.
	 *
	 * @return array $entry The XML sitemap entries data.
	 */
	public function filter_entry( $entry ) {
		if ( Options::get( 'xml-exclude-lastmod' ) ) {
			unset( $entry['mod'] );
		}

		return $entry;
	}

	/**
	 * Filters the maximum number of pages in an XML sitemap.
	 *
	 * @param int $max_entries The maximum number of entries.
	 *
	 * @return int $max_entries The maximum number of entries.
	 */
	public function filter_entries_per_page( $max_entries ) {
		if ( Options::get( 'xml-number-items' ) > 0 ) {
			return Options::get( 'xml-number-items' );
		}

		return $max_entries;
	}

	/**
	 * Disables the automatic pinging of Search engines when enabled.
	 *
	 * @param bool $allow_ping Whether or not the search engines should be pinged.
	 *
	 * @return bool $allow_ping Whether or not the search engines should be pinged.
	 */
	public function filter_ping( $allow_ping ) {
		if ( Options::get( 'xml-disable-ping' ) ) {
			return false;
		}

		return $allow_ping;
	}
}