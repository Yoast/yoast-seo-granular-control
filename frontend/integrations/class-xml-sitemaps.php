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
		add_filter( 'wpseo_sitemap_exclude_author', [ $this, 'filter_users' ] );
		add_filter( 'wpseo_sitemap_exclude_taxonomy', [ $this, 'exclude_taxonomy' ], 10, 2 );
		add_filter( 'wpseo_sitemap_exclude_post_type', [ $this, 'exclude_post_type' ], 10, 2 );
		add_filter( 'wpseo_sitemap_exclude_empty_terms_taxonomy', [ $this, 'show_empty_terms'], 10, 2 );
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

	/**
	 * Filters the users that'll show up in the XML sitemap.
	 *
	 * @param \WP_User[] $users Array of users for the XML sitemap.
	 *
	 * @return \WP_User[] $users Array of users for the XML sitemap.
	 */
	public function filter_users( $users ) {
		$roles = array_keys( Options::get( 'xml-exclude-roles' ) );
		if ( count( $roles ) > 0 ) {
			$exclude_user_ids = wp_list_pluck( get_users( [ 'role__in' => $roles, 'fields' => [ 'ID' ] ] ), 'ID' );
			foreach ( $users as $key => $user ) {
				if ( in_array( $user->ID, $exclude_user_ids ) ) {
					unset( $users[ $key ] );
				}
			}
		}

		return $users;
	}

	/**
	 * Disables a taxonomy XML sitemap, if needed.
	 *
	 * @param bool $bool Whether or not the XML sitemap should be disabled.
	 * @param string $taxonomy The taxonomy we're checking.
	 *
	 * @return bool Whether or not the XML sitemap should be disabled.
	 */
	public function exclude_taxonomy( $bool, $taxonomy ) {
		$exclude = Options::get( 'xml-disable-taxonomy' );
		if ( $exclude[ $taxonomy ] === 'on' ) {
			return true;
		}

		return $bool;
	}

	/**
	 * Disables a post type XML sitemap, if needed.
	 *
	 * @param bool $bool Whether or not the XML sitemap should be disabled.
	 * @param string $post_type The post type we're checking.
	 *
	 * @return bool Whether or not the XML sitemap should be disabled.
	 */
	public function exclude_post_type( $bool, $post_type ) {
		$exclude = Options::get( 'xml-disable-post_type' );
		if ( $exclude[ $post_type ] === 'on' ) {
			return true;
		}

		return $bool;
	}

	public function show_empty_terms( $bool, $taxonomy ) {
		$show_empty = Options::get( 'xml-include-empty-taxonomy' );
		if ( $show_empty[ $taxonomy ] === 'on' ) {
			return false;
		}
		return $bool;
	}

	public function disable_author_sitemap() {

	}
}
