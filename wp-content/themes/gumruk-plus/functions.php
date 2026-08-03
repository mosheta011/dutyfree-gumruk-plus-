<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_stylesheet_directory() . '/inc/template-tags.php';
require get_stylesheet_directory() . '/inc/nav-walker.php';
require get_stylesheet_directory() . '/inc/woocommerce.php';

function gp_enqueue_styles() {
	wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style(
		'gumruk-plus-style',
		get_stylesheet_uri(),
		array( 'storefront-style' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_style(
		'gp-fonts',
		'https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'gumruk-plus-theme',
		get_stylesheet_directory_uri() . '/assets/css/theme.css',
		array( 'gumruk-plus-style' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script(
		'gumruk-plus-theme',
		get_stylesheet_directory_uri() . '/assets/js/theme.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'gp_enqueue_styles' );

function gp_show_product_video() {
	global $product;
	if ( ! $product ) {
		return;
	}
	$video_url = get_post_meta( $product->get_id(), '_gp_product_video', true );
	if ( empty( $video_url ) ) {
		return;
	}
	echo '<div class="gp-product-video" style="margin-top:16px;">';
	if ( strpos( $video_url, 'youtube.com' ) !== false || strpos( $video_url, 'youtu.be' ) !== false || strpos( $video_url, 'vimeo.com' ) !== false ) {
		echo wp_oembed_get( esc_url( $video_url ) );
	} else {
		echo '<video controls style="width:100%;border:2px solid var(--gp-ink);border-radius:6px;"><source src="' . esc_url( $video_url ) . '"></video>';
	}
	echo '</div>';
}
add_action( 'woocommerce_product_thumbnails', 'gp_show_product_video', 20 );
