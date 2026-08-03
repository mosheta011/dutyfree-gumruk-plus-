<?php
/**
 * The header for Gümrük Plus.
 *
 * Displays everything up to and including <div class="col-full"> that
 * wraps the main content. Keeps the same storefront_* hooks as the parent
 * theme so WooCommerce (breadcrumbs, cart fragments, content wrappers)
 * keeps working; only the masthead markup and navigation are custom.
 *
 * @package gumruk-plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">

	<?php gp_topbar(); ?>

	<?php gp_deals_marquee(); ?>

	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header gp-header" role="banner">
		<div class="col-full gp-header__inner">

			<div class="gp-header__branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gp-logo" rel="home">
						<span class="gp-logo__word"><?php esc_html_e( 'GÜMRÜK', 'gumruk-plus' ); ?></span><span class="gp-logo__plus">+</span>
					</a>
					<?php
					$description = get_bloginfo( 'description', 'display' );
					if ( $description ) {
						echo '<p class="gp-header__tagline">' . esc_html( $description ) . '</p>';
					}
					?>
				<?php endif; ?>
			</div><!-- .gp-header__branding -->

			<button class="gp-nav-toggle" type="button" aria-expanded="false" aria-controls="gp-primary-navigation">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'gumruk-plus' ); ?></span>
				<span class="gp-nav-toggle__bar"></span>
				<span class="gp-nav-toggle__bar"></span>
				<span class="gp-nav-toggle__bar"></span>
			</button>

			<nav id="gp-primary-navigation" class="gp-nav" aria-label="<?php esc_attr_e( 'Primary', 'gumruk-plus' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'gp-nav__menu',
						'walker'         => new GP_Nav_Walker(),
						'fallback_cb'    => false,
					)
				);
				?>
			</nav><!-- .gp-nav -->

			<div class="gp-header__actions">
				<?php
				if ( function_exists( 'storefront_product_search' ) ) {
					storefront_product_search();
				}
				if ( function_exists( 'storefront_header_cart' ) ) {
					storefront_header_cart();
				}
				?>
			</div><!-- .gp-header__actions -->

		</div><!-- .col-full -->
	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php do_action( 'storefront_content_top' ); ?>
