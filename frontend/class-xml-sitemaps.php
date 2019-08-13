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
 * Filters the robots meta code based on settings.
 */
class XML_Sitemaps implements Integration {
	/**
	 * Holds the plugin options.
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Registers all hooks to WordPress.
	 *
	 * @param array $options The options for the plugin.
	 *
	 * @return void
	 */
	public function register_hooks( $options ) {
		$this->options = $options;

		add_filter( 'wpseo_xml_sitemap_include_images', [ $this, 'filter_images' ] );
		add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', [ $this, 'exclude_posts' ] );
		add_filter( 'wpseo_exclude_from_sitemap_by_term_ids', [ $this, 'exclude_terms' ] );
		add_filter( 'wpseo_sitemap_entry', [ $this, 'filter_entry' ] );
	}

	/**
	 * Filters the robots meta string.
	 *
	 * @param bool $include_images The robots meta string.
	 *
	 * @return string The robots meta string.
	 */
	public function filter_images( $include_images ) {
		if ( $this->options['xml-exclude-images'] ) {
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
		$excluded_posts = array_map( 'intval', explode( ',', $this->options['xml-exclude-posts'] ) );
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
		$excluded_terms = array_map( 'intval', explode( ',', $this->options['xml-exclude-terms'] ) );
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
		if ( $this->options['xml-exclude-lastmod'] ) {
			unset( $entry['mod'] );
		}
		return $entry;
	}
}