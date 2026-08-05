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

	<div class="info-bar">
		<?php gp_topbar(); ?>
	</div>

	<div class="ticker-bar">
		<?php gp_deals_marquee(); ?>
	</div>

	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="top-nav" role="banner">
		<div class="container">

			<div class="gp-header__branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" rel="home">
						Gümrük<span class="logo-plus">+</span>
					</a>
				<?php endif; ?>
			</div><!-- .gp-header__branding -->

			<nav id="gp-primary-navigation" class="main-menu" aria-label="<?php esc_attr_e( 'Primary', 'gumruk-plus' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'gp-nav__menu', // Kept for compatibility if needed, but styling will target main-menu
						'walker'         => new GP_Nav_Walker(),
						'fallback_cb'    => false,
					)
				);
				?>
			</nav><!-- .main-menu -->

			<div class="nav-actions">
				<button class="lang-btn">EN / TR</button>
				<?php
				if ( function_exists( 'storefront_header_cart' ) ) {
					// We'll wrap the cart in our button style using a filter or just let it render and style it.
					// For now, let's output it and apply .cart-btn styles to it in CSS.
					storefront_header_cart();
				}
				?>
			</div><!-- .nav-actions -->

		</div><!-- .container -->
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
