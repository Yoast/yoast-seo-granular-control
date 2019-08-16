<?php
/**
 * Clicky for WordPress plugin file.
 *
 * @package Yoast/Clicky/View
 */

namespace Yoast_SEO_Granular_Control;

?><div class="wrap">
	<h2>
		<?php esc_html_e( 'Yoast SEO Granular controls', 'yoast-seo-granular-control' ); ?>
        <?php settings_errors(); ?>
	</h2>

	<form action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>" method="post">
        <input type="hidden" name="yst_active_tab" id="yst_active_tab" value="<?php echo get_transient( 'yst_active_tab' ); ?>" />
		<?php
		settings_fields( Options_Admin::$option_group );
		?>
		<div id="yoast_wrapper">
			<h2 class="nav-tab-wrapper" id="yoast-tabs">
				<a class="nav-tab" id="indexing-tab" href="#top#indexing"><?php esc_html_e( 'Indexing', 'yoast-seo-granular-control' ); ?></a>
                <a class="nav-tab" id="schema-tab" href="#top#schema"><?php esc_html_e( 'Schema', 'yoast-seo-granular-control' ); ?></a>
				<a class="nav-tab" id="xml-tab" href="#top#xml"><?php esc_html_e( 'XML Sitemaps', 'yoast-seo-granular-control' ); ?></a>
			</h2>

			<div class="tabwrapper">
				<div id="indexing" class="yoast_tab">
					<?php do_settings_sections( 'yoast-seo-granular-control-indexing' ); ?>
				</div>
                <div id="schema" class="yoast_tab">
					<?php do_settings_sections( 'yoast-seo-granular-control-schema' ); ?>
                </div>
				<div id="xml" class="yoast_tab">
					<?php do_settings_sections( 'yoast-seo-granular-control-sitemaps' ); ?>
				</div>
			</div>
			<?php
			submit_button( __( 'Save settings', 'yoast-seo-granular-control' ) );
			?>
		</div>
	</form>
	<div id="yoast_sidebar">
		<?php
		$this->yoast_news();
		?>
	</div>
</div>
