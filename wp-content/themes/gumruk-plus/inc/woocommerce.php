<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_woocommerce' ) ) {
	return;
}

/**
 * Products per row / per page for shop and category archives.
 */
add_filter(
	'loop_shop_columns',
	function () {
		return 4;
	}
);

add_filter(
	'loop_shop_per_page',
	function () {
		return 12;
	},
	20
);

/**
 * Replace the default "Sale!" flash with a badge that matches the brand tone.
 */
add_filter(
	'woocommerce_sale_flash',
	function () {
		return '<span class="onsale">' . esc_html__( 'İndirim', 'gumruk-plus' ) . '</span>';
	}
);

/**
 * Trim the breadcrumb wrapper markup to plain <nav> for our own CSS.
 */
add_filter(
	'woocommerce_breadcrumb_defaults',
	function ( $defaults ) {
		$defaults['wrap_before'] = '<nav class="gp-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'gumruk-plus' ) . '">';
		$defaults['wrap_after']  = '</nav>';
		$defaults['delimiter']   = ' &#47; ';
		return $defaults;
	}
);

/**
 * Related products: fewer columns to match the theme's card grid.
 */
add_filter(
	'woocommerce_output_related_products_args',
	function ( $args ) {
		$args['posts_per_page'] = 4;
		$args['columns']        = 4;
		return $args;
	}
);
