<?php
/**
 * Yoast_SEO_Granular_Control plugin file.
 *
 * @package Yoast_SEO_Granular_Control
 */

namespace Yoast_SEO_Granular_Control;

/**
 * Class Indexing
 *
 * Filters the robots meta and other indexing code based on settings.
 */
class Indexing implements Integration {
	/**
	 * Registers all hooks to WordPress.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_filter( 'wpseo_robots', [ $this, 'filter_robots' ] );
		add_action( 'template_redirect', [ $this, 'maybe_noindex_feeds' ] );
		add_filter( 'wpseo_canonical', [ $this, 'force_canonical_protocol' ] );
		add_action( 'wp', [ $this, 'user_archive_redirect' ] );

		if ( Options::get( 'disable-rel-next-prev' ) ) {
			add_filter( 'wpseo_disable_adjacent_rel_links', '__return_true' );
		}
	}

	/**
	 * Filters the robots meta string.
	 *
	 * @param string $robots_string The robots meta string.
	 *
	 * @return string The robots meta string.
	 */
	public function filter_robots( $robots_string ) {
		if ( is_paged() ) {
			$robots_string = $this->filter_paginated_archives( $robots_string );
		}

		if ( is_home() ) {
			$robots_string = $this->filter_homepage_robots( $robots_string );
		}

		return $robots_string;
	}

	/**
	 * Noindexes RSS feeds when necessary.
	 */
	public function maybe_noindex_feeds() {
		global $wp_query;
		if ( ! is_feed() ) {
			return;
		}
		if ( Options::get( 'noindex-feeds' ) ) {
			$this->noindex_header();
			return;
		}
		if ( $wp_query->is_archive && Options::get( 'noindex-archive-feeds' ) ) {
			$this->noindex_header();
			return;
		}
		if ( $wp_query->query['withcomments'] && Options::get( 'noindex-comment-feeds' ) ) {
			$this->noindex_header();
		}
	}

	/**
	 * Outputs noindex header.
	 */
	private function noindex_header() {
		header( 'X-Robots-Tag: noindex, follow', true );
	}

	/**
	 * Forces the protocol on all canonical URLs to either http or https.
	 *
	 * @param string $canonical The canonical URL.
	 *
	 * @return string $canonical The canonical URL.
	 */
	public function force_canonical_protocol( $canonical ) {
		if ( Options::get( 'force-canonical' ) !== 'default' ) {
			$canonical = preg_replace( '/^(https?)/', Options::get( 'force-canonical' ), $canonical );
		}

		return $canonical;
	}

	/**
	 * If the user has a role that is not allowed to have an archive, redirect it.
	 *
	 * @return void
	 */
	public function user_archive_redirect() {
		global $wp_query;
		if ( $wp_query->is_author ) {
			$roles = array_keys( Options::get( 'disable-archives' ) );
			if ( count( $roles ) > 0 ) {
				$user = get_userdata( $wp_query->query_vars['author'] );
				foreach( $roles as $role ) {
					if ( isset( $user->caps[ $role ] ) ) {
						wp_redirect( get_bloginfo( 'url' ), 301 );
						exit;
					}
				}
			}
		}
	}

	/**
	 * Filters the robots meta string for paginated archives.
	 *
	 * @param string $robots_string The robots meta string.
	 *
	 * @return string The robots meta string.
	 */
	private function filter_paginated_archives( $robots_string ) {
		if ( Options::get( 'noindex-paginated-archives' ) ) {
			$robots = explode( ',', $robots_string );
			if ( ( $key = array_search( 'index', $robots ) ) !== false ) {
				unset( $robots[ $key ] );
			}

			$robots[]      = 'noindex';
			$robots_string = implode( ',', array_unique( array_filter( $robots ) ) );
		}
		return $robots_string;
	}

	/**
	 * Filters the homepage robots meta string.
	 *
	 * @param string $robots_string The robots meta string.
	 *
	 * @return string The robots meta string.
	 */
	private function filter_homepage_robots( $robots_string ) {
		$robots = explode( ',', $robots_string );
		if ( Options::get( 'homepage-noindex' ) ) {
			if ( ( $key = array_search( 'index', $robots ) ) !== false ) {
				unset( $robots[ $key ] );
			}
			$robots[] = 'noindex';
		}
		if ( Options::get( 'homepage-nofollow' ) ) {
			if ( ( $key = array_search( 'follow', $robots ) ) !== false ) {
				unset( $robots[ $key ] );
			}
			$robots[] = 'nofollow';
		}
		if ( Options::get( 'homepage-noarchive' ) ) {
			$robots[] = 'noarchive';
		}
		if ( Options::get( 'homepage-nosnippet' ) ) {
			$robots[] = 'nosnippet';
		}
		$robots_string = implode( ',', array_unique( array_filter( $robots ) ) );
		return $robots_string;
	}

}
