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

	wp_enqueue_script(
		'gumruk-plus-global-bg',
		get_stylesheet_directory_uri() . '/assets/js/global-bg.js',
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

/**
 * Split loop links for lightbox integration.
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

add_action( 'woocommerce_before_shop_loop_item', 'gp_lightbox_link_open', 10 );
function gp_lightbox_link_open() {
	global $product;
	$id = $product->get_id();
	echo '<a href="#gp-quick-view-' . esc_attr( $id ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link gp-glightbox" data-gallery="gp-products">';
}

add_action( 'woocommerce_before_shop_loop_item_title', 'gp_lightbox_link_close', 15 );
function gp_lightbox_link_close() {
	echo '</a>';
}

add_action( 'woocommerce_before_shop_loop_item_title', 'gp_product_link_open', 20 );
function gp_product_link_open() {
	global $product;
	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
	echo '<a href="' . esc_url( $link ) . '" class="gp-loop-text-link" style="text-decoration:none; color:inherit; display:block;">';
}

add_action( 'woocommerce_after_shop_loop_item_title', 'gp_product_link_close', 999 );
function gp_product_link_close() {
	echo '</a>';
}

add_action( 'woocommerce_after_shop_loop_item', 'gp_add_quick_view_inline_content', 20 );
function gp_add_quick_view_inline_content() {
	global $product;
	$id = $product->get_id();
	$image_id = $product->get_image_id();
	$full_image = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : wc_placeholder_img_src( 'full' );
	?>
	<div id="gp-quick-view-<?php echo esc_attr( $id ); ?>" style="display:none;">
		<div class="gp-quick-view-container">
			<!-- Left Column: Image -->
			<div class="gp-quick-view-left-col">
				<div class="gp-quick-view-img-card">
					<img src="<?php echo esc_url( $full_image ); ?>" alt="<?php echo esc_attr( $product->get_title() ); ?>">
				</div>
			</div>
			<!-- Right Column: Title + Price + Button -->
			<div class="gp-quick-view-right-col">
				<div class="gp-quick-view-title-card">
					<h3><?php echo esc_html( $product->get_title() ); ?></h3>
				</div>
				<div class="gp-quick-view-price">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
				</div>
				<div class="gp-quick-view-actions">
					<?php woocommerce_template_loop_add_to_cart(); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'wp_head', 'gp_quick_view_css' );
function gp_quick_view_css() {
	?>
	<style>
		.gp-quick-view-container {
			display: flex;
			flex-direction: row;
			gap: 32px;
			max-width: 900px;
			margin: 0 auto;
			align-items: stretch;
			justify-content: center;
			height: auto;
			min-height: 450px;
			background: transparent;
			border: none;
			box-shadow: none;
			position: relative;
		}
		
		.gp-quick-view-left-col {
			display: flex;
			flex-direction: column;
			flex: 1 1 50%;
			background: #FFFFFF;
			border: 2px solid #1A1A1A;
			border-radius: 0;
			box-shadow: 6px 6px 0px rgba(26, 26, 26, 1);
			align-items: center;
			justify-content: center;
			padding: 40px;
			position: relative;
		}
		
		body.gp-dark .gp-quick-view-left-col {
			background: #182030;
			border-color: #2A3848;
			box-shadow: 6px 6px 0px #2A3848;
		}
		
		.gp-quick-view-right-col {
			display: flex;
			flex-direction: column;
			flex: 1 1 50%;
			justify-content: center;
			background: transparent; /* No background here, so price floats outside */
		}
		
		.gp-quick-view-img-card {
			width: 100%;
			height: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		
		.gp-quick-view-img-card img {
			width: 100%;
			height: auto;
			max-height: 350px;
			object-fit: contain;
			filter: drop-shadow(0 15px 25px rgba(0,0,0,0.08));
			transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
		}
		.gp-quick-view-img-card:hover img {
			transform: scale(1.08) translateY(-5px);
		}
		
		.gp-quick-view-title-card {
			background: #FFFFFF;
			border: 2px solid #1A1A1A;
			border-radius: 0;
			box-shadow: 6px 6px 0px rgba(26, 26, 26, 1);
			padding: 30px;
			margin-bottom: 24px;
			text-align: center;
		}
		body.gp-dark .gp-quick-view-title-card {
			background: #182030;
			border-color: #2A3848;
			box-shadow: 6px 6px 0px #2A3848;
		}
		
		.gp-quick-view-title-card h3 {
			font-family: 'Anton', sans-serif;
			font-size: 2.8rem;
			font-weight: 400;
			color: #1A1A1A;
			margin: 0;
			line-height: 1.1;
			text-transform: uppercase;
			letter-spacing: 0.02em;
		}
		body.gp-dark .gp-quick-view-title-card h3 {
			color: #EFF2F7;
		}
		
		.gp-quick-view-price {
			font-family: 'JetBrains Mono', monospace;
			font-size: 2.4rem;
			font-weight: 800;
			color: #FFFFFF; /* High contrast since it's floating on the dark overlay */
			text-align: center;
			margin-bottom: 32px;
			display: flex;
			align-items: baseline;
			justify-content: center;
			gap: 12px;
			text-shadow: 0 4px 10px rgba(0,0,0,0.5); /* Helps legibility outside boxes */
		}
		body.gp-dark .gp-quick-view-price {
			color: #EFF2F7;
			text-shadow: 0 4px 10px rgba(0,0,0,0.8);
		}
		
		.gp-quick-view-price del {
			font-size: 1.4rem;
			color: #bbb;
			font-weight: 500;
			text-decoration-thickness: 2px;
		}
		
		.gp-quick-view-price ins {
			text-decoration: none;
			color: #D6392C;
			background: transparent;
			padding: 0;
			border: none;
			box-shadow: none;
		}
		
		.gp-quick-view-actions {
			width: 100%;
			margin-top: auto;
		}
		
		.gp-quick-view-actions .button {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			background: #D6392C;
			color: #fff;
			padding: 18px 32px;
			text-decoration: none;
			border: 2px solid #1A1A1A;
			border-radius: 0;
			font-family: 'Inter', sans-serif;
			font-size: 16px;
			font-weight: 800;
			text-transform: uppercase;
			letter-spacing: 0.06em;
			transition: all 0.2s ease;
			box-shadow: 6px 6px 0px rgba(26, 26, 26, 1);
			cursor: pointer;
		}
		body.gp-dark .gp-quick-view-actions .button {
			border-color: #2A3848;
			box-shadow: 6px 6px 0px #2A3848;
		}
		.gp-quick-view-actions .button:hover {
			transform: translate(2px, 2px);
			box-shadow: 4px 4px 0px rgba(26, 26, 26, 1);
			background: #FFFFFF;
			color: #D6392C;
		}
		body.gp-dark .gp-quick-view-actions .button:hover {
			background: #111820;
			box-shadow: 4px 4px 0px #2A3848;
		}
		
		/* GLightbox Close button overrides */
		.gclose {
			background: #1A1A1A !important;
			border-radius: 0 !important;
			opacity: 1 !important;
			transition: all 0.2s ease !important;
			margin: 10px !important;
		}
		body.gp-dark .gclose {
			background: #2A3848 !important;
		}
		.gclose:hover {
			background: #D6392C !important;
		}
		.gclose svg {
			fill: #FFFFFF !important;
		}
		body.gp-dark .gclose svg {
			fill: #FFFFFF !important;
		}
		
		@media (max-width: 768px) {
			.gp-quick-view-container {
				flex-direction: column;
				max-width: 90vw;
				gap: 24px;
			}
			.gp-quick-view-left-col {
				padding: 30px;
			}
			.gp-quick-view-img-card img {
				max-height: 250px;
			}
			.gp-quick-view-title-card {
				padding: 20px;
			}
			.gp-quick-view-title-card h3 {
				font-size: 2.2rem;
			}
		}
		
		/* Custom Animations for the popup */
		.gslide-active .gp-quick-view-img-card {
			opacity: 0;
			animation: gpPopInCard 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.1s forwards;
		}
		.gslide-active .gp-quick-view-actions {
			opacity: 0;
			animation: gpPopInCard 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.25s forwards;
		}
		.gslide-active .gp-quick-view-title-card {
			opacity: 0;
			animation: gpPopInCard 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.25s forwards;
		}
		.gslide-active .gp-quick-view-price {
			opacity: 0;
			animation: gpPopInCard 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.4s forwards;
		}
		@keyframes gpPopInCard {
			0% {
				opacity: 0;
				transform: scale(0.8) translateY(30px);
			}
			60% {
				opacity: 1;
				transform: scale(1.05) translateY(-5px);
			}
			100% {
				opacity: 1;
				transform: scale(1) translateY(0);
			}
		}
		/* GLightbox Overrides for Inline Content */
		.gslide-inline {
			background: transparent !important;
			box-shadow: none !important;
			padding: 0 !important;
		}
		.gslide-inner-content {
			background: transparent !important;
		}
		.glightbox-clean .gslide-inline {
			background: transparent !important;
			box-shadow: none !important;
		}
		/* Ultimate Scrollbar Nuke */
		.glightbox-container *::-webkit-scrollbar {
			display: none !important;
		}
		.glightbox-container * {
			-ms-overflow-style: none !important;
			scrollbar-width: none !important;
		}
		html.glightbox-open,
		body.glightbox-open,
		.glightbox-container,
		.gwindow,
		.gslide {
			overflow: hidden !important;
		}
	</style>
	<?php
}

/**
 * Enqueue GLightbox
 */
add_action( 'wp_enqueue_scripts', 'gp_enqueue_glightbox' );
function gp_enqueue_glightbox() {
	if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_category() || is_product_tag() || is_front_page() ) ) {
		wp_enqueue_style( 'glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', array(), null );
		wp_enqueue_script( 'glightbox', 'https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js', array(), null, true );
		wp_add_inline_script( 'glightbox', 'document.addEventListener("DOMContentLoaded", function() { if (typeof GLightbox !== "undefined") { GLightbox({ selector: ".gp-glightbox", openEffect: "zoom", closeEffect: "fade", slideEffect: "slide", touchNavigation: true, loop: true }); } });' );
	}
}

/**
 * Custom Sorting
 */
require_once get_theme_file_path( '/inc/custom-sorting.php' );

/**
 * Disable WooCommerce Coming Soon Mode
 * This allows the store and login page to be visible to logged-out users.
 */
add_action( 'init', function() {
    update_option( 'woocommerce_coming_soon', 'no' );
    update_option( 'woocommerce_store_frontend_access', 'yes' );
} );
