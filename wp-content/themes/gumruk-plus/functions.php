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
		'tabler-icons',
		'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css',
		array(),
		null
	);

	wp_enqueue_style(
		'gumruk-plus-theme',
		get_stylesheet_directory_uri() . '/assets/css/theme.css',
		array( 'gumruk-plus-style' ),
		time()
	);

	wp_enqueue_script(
		'gumruk-plus-theme',
		get_stylesheet_directory_uri() . '/assets/js/theme.js',
		array( 'jquery' ),
		time(),
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
					<?php 
					woocommerce_template_loop_add_to_cart(); 
					
					// Quick Edit Button for Owner
					if ( ! is_admin() ) {
						$user = wp_get_current_user();
						$roles = (array) $user->roles;
						if ( in_array( 'gp_quick_edit', $roles ) || in_array( 'administrator', $roles ) ) {
							$edit_link = get_edit_post_link( $product->get_id() );
							if ( $edit_link ) {
								echo '<a href="' . esc_url( $edit_link ) . '" class="gp-frontend-edit-btn" style="display:inline-flex; align-items:center; gap:8px; background-color: var(--red, #C41E3A); color: white; padding: 10px 20px; border-radius: 4px; font-weight: 600; text-decoration: none; margin-top: 15px; width: 100%; justify-content: center; box-shadow: 0 4px 12px rgba(196, 30, 58, 0.3); transition: transform 0.2s ease;">
									<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
									Quick Edit in WP
								</a>';
							}
						}
					}
					?>
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
 * Inject "New / In stock / Free shipping" badge pills on product loop cards.
 * These appear between the product image and the title, matching the reference design.
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'gp_inject_product_badges', 5 );
function gp_inject_product_badges() {
	global $product;
	if ( ! $product ) {
		return;
	}

	$badges = array();

	// "New" — created within the last 30 days
	$created = $product->get_date_created();
	if ( $created && ( time() - $created->getTimestamp() ) < 30 * DAY_IN_SECONDS ) {
		$badges[] = '<span class="gp-badge gp-badge--new">' . esc_html__( 'Yeni', 'gumruk-plus' ) . '</span>';
	}

	// "In stock"
	if ( $product->is_in_stock() ) {
		$badges[] = '<span class="gp-badge gp-badge--instock">' . esc_html__( 'Stokta', 'gumruk-plus' ) . '</span>';
	}

	// "Free shipping" — check if product has free shipping class
	$shipping_class = $product->get_shipping_class();
	if ( 'free-shipping' === $shipping_class ) {
		$badges[] = '<span class="gp-badge gp-badge--freeship">' . esc_html__( 'Ücretsiz Kargo', 'gumruk-plus' ) . '</span>';
	}

	if ( ! empty( $badges ) ) {
		echo '<div class="gp-product-badges">' . implode( '', $badges ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Inject "Shop by category" section before the WooCommerce product loop
 * on the front/shop page, and "Featured products" title above the grid.
 */
add_action( 'woocommerce_before_main_content', 'gp_inject_shop_sections', 5 );
function gp_inject_shop_sections() {
	if ( ! is_front_page() && ! is_shop() ) {
		return;
	}

	// ── Shop by category ──
	$cats = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
		'number'     => 0,
		'exclude'    => get_option( 'default_product_cat' ),
	) );

	if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
		echo '<section class="gp-section gp-section--categories">';
		echo '<div class="woocommerce-loop__header" style="padding:0 0 0.2rem;"><h2 class="gp-section__title">' . esc_html__( 'Kategorilere Göre Alışveriş', 'gumruk-plus' ) . '</h2></div>';
		echo '<ul class="product-categories columns-6">';
		foreach ( $cats as $cat ) {
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$image_url    = $thumbnail_id
				? wp_get_attachment_url( $thumbnail_id )
				: wc_placeholder_img_src();
			$link         = get_term_link( $cat );
			echo '<li class="product-category product">';
			echo '<a href="' . esc_url( $link ) . '">';
			echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $cat->name ) . '">';
			echo '<h2 class="woocommerce-loop-category__title">' . esc_html( $cat->name );
			if ( $cat->count ) {
				echo '<mark class="count">' . esc_html( sprintf( _n( '%d product', '%d products', $cat->count, 'gumruk-plus' ), $cat->count ) ) . '</mark>';
			}
			echo '</h2>';
			echo '</a>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</section>';
	}

	// ── Featured products title ──
	echo '<section class="gp-section gp-section--products">';
	echo '<div class="woocommerce-loop__header" style="padding:0 0 0.2rem;"><h2 class="gp-section__title">' . esc_html__( 'Öne Çıkan Ürünler', 'gumruk-plus' ) . '</h2></div>';
}

add_action( 'woocommerce_after_main_content', 'gp_close_shop_section', 5 );
function gp_close_shop_section() {
	if ( ! is_front_page() && ! is_shop() ) {
		return;
	}
	echo '</section>';
}

/**
 * Hide native WooCommerce shop page title (we add our own).
 */
add_filter( 'woocommerce_show_page_title', function() {
	if ( is_shop() || is_front_page() ) {
		return false;
	}
	return true;
} );

/**
 * Disable WooCommerce Coming Soon Mode
 * This allows the store and login page to be visible to logged-out users.
 */
add_action( 'init', function() {
	update_option( 'woocommerce_coming_soon', 'no' );
	update_option( 'woocommerce_store_frontend_access', 'yes' );
} );

/**
 * Add a frontend "Quick Edit" button for the Owner.
 */
function gp_frontend_quick_edit_button() {
	if ( ! is_admin() ) {
		$user = wp_get_current_user();
		$roles = (array) $user->roles;
		if ( in_array( 'gp_quick_edit', $roles ) || in_array( 'administrator', $roles ) ) {
			global $post;
			if ( ! $post || 'product' !== $post->post_type ) {
				return;
			}
			$product = wc_get_product( $post->ID );
			if ( ! $product ) return;
			
			$price = $product->get_regular_price();
			$sale_price = $product->get_sale_price();
			$title = $product->get_name();
			
			echo '<a href="#" class="gp-frontend-edit-btn" onclick="gpOpenQuickEditModal(event, ' . $post->ID . ', \'' . esc_attr($title) . '\', \'' . esc_attr($price) . '\', \'' . esc_attr($sale_price) . '\')" style="display:inline-flex; align-items:center; gap:8px; background-color: var(--red, #C41E3A); color: white; padding: 10px 20px; border-radius: 4px; font-weight: 600; text-decoration: none; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(196, 30, 58, 0.3); transition: transform 0.2s ease;">
				<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
				Quick Edit Product
			</a><br>';
		}
	}
}
add_action( 'woocommerce_single_product_summary', 'gp_frontend_quick_edit_button', 1 );

/**
 * Add a "Manage Products" link to the My Account menu for the owner.
 */
function gp_add_manage_products_link_my_account( $items ) {
	$user = wp_get_current_user();
	$roles = (array) $user->roles;
	if ( in_array( 'gp_quick_edit', $roles ) || in_array( 'administrator', $roles ) ) {
		// Insert it after Dashboard or at the end
		$new_items = array();
		foreach ( $items as $key => $item ) {
			$new_items[$key] = $item;
			if ( 'dashboard' === $key ) {
				$new_items['manage-products'] = 'Manage Products';
			}
		}
		return $new_items;
	}
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'gp_add_manage_products_link_my_account' );

function gp_manage_products_endpoint_url( $url, $endpoint, $value, $permalink ) {
	if ( 'manage-products' === $endpoint ) {
		return get_permalink( wc_get_page_id( 'shop' ) );
	}
	return $url;
}
add_filter( 'woocommerce_get_endpoint_url', 'gp_manage_products_endpoint_url', 10, 4 );


/**
 * AJAX handler for frontend quick edit
 */
function gp_frontend_quick_edit_save_handler() {
	check_ajax_referer( 'gp_frontend_qe_nonce', 'security' );
	
	$user = wp_get_current_user();
	$roles = (array) $user->roles;
	if ( ! in_array( 'gp_quick_edit', $roles ) && ! in_array( 'administrator', $roles ) ) {
		wp_send_json_error( 'Permission denied.' );
	}
	
	$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
	if ( ! $product_id ) wp_send_json_error( 'Invalid product ID.' );
	
	if ( ! current_user_can( 'edit_product', $product_id ) ) {
		wp_send_json_error( 'Not allowed to edit this product.' );
	}
	
	$product = wc_get_product( $product_id );
	if ( ! $product ) wp_send_json_error( 'Product not found.' );
	
	if ( isset($_POST['title']) ) {
		$product->set_name( sanitize_text_field( wp_unslash( $_POST['title'] ) ) );
	}
	if ( isset($_POST['regular_price']) ) {
		$product->set_regular_price( sanitize_text_field( wp_unslash( $_POST['regular_price'] ) ) );
	}
	if ( isset($_POST['sale_price']) ) {
		$product->set_sale_price( sanitize_text_field( wp_unslash( $_POST['sale_price'] ) ) );
	}
	
	$product->save();
	
	// Handle Image Upload
	if ( ! empty( $_FILES['image']['name'] ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		
		$attachment_id = media_handle_upload( 'image', $product_id );
		if ( is_wp_error( $attachment_id ) ) {
			wp_send_json_error( 'Image upload failed: ' . $attachment_id->get_error_message() );
		} else {
			set_post_thumbnail( $product_id, $attachment_id );
		}
	}
	
	wp_send_json_success( 'Product updated successfully.' );
}
add_action( 'wp_ajax_gp_frontend_quick_edit_save', 'gp_frontend_quick_edit_save_handler' );
/**
 * Google Translate Integration for .lang-btn
 */
function gp_language_switcher_script() {
	?>
	<div id="google_translate_element" style="display:none;"></div>
	<script type="text/javascript">
		function googleTranslateElementInit() {
			new google.translate.TranslateElement({
				pageLanguage: 'tr', // Assuming default is Turkish
				includedLanguages: 'tr,en',
				layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
				autoDisplay: false
			}, 'google_translate_element');
		}
	</script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			const toggleBtn = document.querySelector('.lang-switcher__toggle');
			const switcherWrap = document.querySelector('.lang-switcher');
			const dropdownBtns = document.querySelectorAll('.lang-dropdown-btn');
			const currentSpan = document.querySelector('.lang-switcher__current');
			
			if (toggleBtn && switcherWrap) {
				toggleBtn.addEventListener('click', function(e) {
					e.preventDefault();
					switcherWrap.classList.toggle('is-open');
				});
				
				document.addEventListener('click', function(e) {
					if (!switcherWrap.contains(e.target)) {
						switcherWrap.classList.remove('is-open');
					}
				});
			}
			
			dropdownBtns.forEach(btn => {
				btn.addEventListener('click', function(e) {
					e.preventDefault();
					const targetLang = this.getAttribute('data-lang');
					
					// Change UI immediately for feedback
					if (currentSpan) {
						currentSpan.textContent = targetLang.toUpperCase();
					}
					switcherWrap.classList.remove('is-open');
					
					// Set the Google Translate cookie
					document.cookie = `googtrans=/tr/${targetLang}; path=/`;
					document.cookie = `googtrans=/tr/${targetLang}; path=/; domain=${location.hostname}`;
					
					// Reload the page to apply the translation everywhere
					window.location.reload();
				});
			});
			
			// Initialize current language from cookie on load
			const match = document.cookie.match(/(?:^|;)\s*googtrans=([^;]*)/);
			let initialLang = 'tr';
			if (match && match[1]) {
				const parts = match[1].split('/');
				initialLang = parts[2] || parts[1] || 'tr';
			}
			if (currentSpan) {
				currentSpan.textContent = initialLang.toUpperCase();
			}
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'gp_language_switcher_script' );

/**
 * Add a "Clear Cart" button to WooCommerce Cart Page
 */
add_action( 'woocommerce_cart_actions', 'gp_add_clear_cart_button' );
function gp_add_clear_cart_button() {
	echo '<a href="' . esc_url( add_query_arg( 'empty-cart', 'true', wc_get_cart_url() ) ) . '" class="button gp-clear-cart-btn" style="background-color: transparent; color: var(--red); border: 1px solid var(--red); margin-right: auto; float: left; font-family: \'Inter\', sans-serif; transition: all 0.2s;">' . esc_html__( 'Sepeti Temizle (Clear All)', 'gumruk-plus' ) . '</a>';
}

/**
 * Handle the "Clear Cart" action
 */
add_action( 'init', 'gp_handle_clear_cart_action' );
function gp_handle_clear_cart_action() {
	if ( isset( $_GET['empty-cart'] ) && 'true' === $_GET['empty-cart'] && function_exists( 'WC' ) && WC()->cart ) {
		WC()->cart->empty_cart();
		wp_safe_redirect( wc_get_cart_url() );
		exit;
	}
}
