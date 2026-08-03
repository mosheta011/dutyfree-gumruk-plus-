<?php
/**
 * Generic WooCommerce wrapper template.
 *
 * WooCommerce's WC_Template_Loader checks the theme for this file FIRST
 * (before its own plugin/archive-product.php) whenever the query matches a
 * product context: single product, product taxonomy archive, or the Shop
 * page — INCLUDING when the Shop page is also the site's static front
 * page. That last case is why this file, not front-page.php, is what
 * actually renders this site's homepage; see the note in front-page.php.
 *
 * @package gumruk-plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Product loop pages (shop, category, tag) run full width — no sidebar.
 * Only a single product page keeps the sidebar.
 */
$gp_show_sidebar = is_singular( 'product' );

get_header(); ?>

	<div id="primary" class="content-area<?php echo is_front_page() ? ' gp-front-page' : ''; ?><?php echo ! $gp_show_sidebar ? ' gp-full-width' : ''; ?>">
		<main id="main" class="site-main" role="main">

			<?php
			if ( is_front_page() ) {
				gp_hero_section();
				echo '<div id="gp-products"></div>';
			}

			woocommerce_content();
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
if ( $gp_show_sidebar ) {
	do_action( 'storefront_sidebar' );
}
get_footer();
