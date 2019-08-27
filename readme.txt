=== Granular control for Yoast SEO ===
Contributors: yoast, joostdevalk
Tags: yoast, xml sitemaps, schema, seo
Requires at least: 5.0
Tested up to: 5.2
Stable tag: 1.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin enables expert users and developers to change advanced settings in Yoast SEO, and to alter its defaults. Not for everyone.

== Description ==

Granular Control for Yoast SEO enables expert users and developers to change advanced settings in Yoast SEO, and to alter its defaults. Please take care when using these options; most users won't need to use this plugin, and those who do should take care not to _harm_ their site's SEO.

Unfortunately, due to the nature of this plugin’s features, we can't be held responsible nor provide support for any problems with your website, visibility in search engines, drops in rankings or other issues related to your changes. Installing and using this plugin is solely your own responsibility.

=== Features ===

==== Feature switches ====

* Disable Yoast Gutenberg / block editor blocks.
* Disable author archives (by role), and return a 301 to the homepage.

==== Crawl & indexing ====

* Disable (remove) `rel next/prev`
* Disable/enable `noindex` on paginated requests
* Enable indexing/noindexing of feeds, either all or just archive feeds or comment feeds
* Homepage meta robots settings
* Force canonical protocol (http / https / automatic)

==== Schema ====

* Disable entirely
* Remove `datePublished` and / or `dateModified` properties
* Disabling/removing specific pieces:
  * `Organization`, `WebSite`, `WebPage`, `Article`
  * `Person`
  * `Breadcrumb`
  * Site search
  * `FAQ`, `HowTo`
  
==== XML sitemaps ====

* Exclude images.
* Disable author, post type and taxonomy type sitemaps as you wish.
* Exclude posts/pages (by ID).
* Exclude terms (by ID).
* Exclude author(s) by role.
* Change max number of entries per sitemap.
* Include empty archives for terms.
* Exclude `<lastmod>` from XML sitemap.
* Disable search engine notifications about sitemap changes.

== Screenshots ==

1. General settings.
2. Indexing settings.
3. Schema settings.
4. XML Sitemaps settings.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/granular-control-yoast-seo` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the SEO -> Granular control screen to configure the plugin

== Changelog ==

= 1.0 =

* Initial version.