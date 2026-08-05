<?php
/**
 * Plugin Name: Gümrük Plus — Quick Edit
 * Description: Creates a restricted "Quick Edit" role for the owner: can only
 *              change product photos, product video, and price on EXISTING
 *              products. Cannot touch stock status, categories, orders, or
 *              settings — that stays in the full admin, used by the agency.
 * Version:     0.1.0
 * Author:      Gümrük Plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GP_QE_ROLE', 'gp_quick_edit' );
define( 'GP_QE_VIDEO_META', '_gp_product_video' );

function gp_qe_activate() {
	remove_role( GP_QE_ROLE );

	add_role(
		GP_QE_ROLE,
		'Quick Edit (Owner)',
		array(
			'read'                    => true,
			'edit_products'           => true,
			'edit_others_products'    => true,
			'edit_published_products' => true,
			'edit_private_products'   => true,
			'upload_files'            => true,
			'edit_posts'              => true,
		)
	);
}
register_activation_hook( __FILE__, 'gp_qe_activate' );

function gp_qe_deactivate() {
	remove_role( GP_QE_ROLE );
}
register_deactivation_hook( __FILE__, 'gp_qe_deactivate' );

function gp_qe_restrict_admin_menu() {
	if ( ! gp_qe_is_current_user_quick_edit() ) {
		return;
	}

	global $menu, $submenu;

	$allowed_slugs = array( 'edit.php?post_type=product', 'upload.php' );

	foreach ( $menu as $key => $item ) {
		if ( ! in_array( $item[2], $allowed_slugs, true ) ) {
			remove_menu_page( $item[2] );
		}
	}

	if ( isset( $submenu['edit.php?post_type=product'] ) ) {
		foreach ( $submenu['edit.php?post_type=product'] as $key => $item ) {
			$slug = $item[2];
			if ( strpos( $slug, 'post-new.php' ) !== false
				|| strpos( $slug, 'product_cat' ) !== false
				|| strpos( $slug, 'product_tag' ) !== false
				|| strpos( $slug, 'product_attributes' ) !== false ) {
				unset( $submenu['edit.php?post_type=product'][ $key ] );
			}
		}
	}
}
add_action( 'admin_menu', 'gp_qe_restrict_admin_menu', 999 );

function gp_qe_strip_metaboxes() {
	if ( ! gp_qe_is_current_user_quick_edit() ) {
		return;
	}

	remove_meta_box( 'product_catdiv', 'product', 'side' );
	remove_meta_box( 'product_tagdiv', 'product', 'side' );
	remove_meta_box( 'woocommerce-product-images', 'product', 'side' );
	remove_meta_box( 'commentsdiv', 'product', 'normal' );
	remove_meta_box( 'commentstatusdiv', 'product', 'normal' );
	remove_meta_box( 'slugdiv', 'product', 'normal' );

	add_meta_box(
		'woocommerce-product-images',
		__( 'Product Photos', 'gp-quick-edit' ),
		'WC_Meta_Box_Product_Images::output',
		'product',
		'side',
		'low'
	);
}
add_action( 'add_meta_boxes', 'gp_qe_strip_metaboxes', 999 );

function gp_qe_admin_styles() {
	if ( ! gp_qe_is_current_user_quick_edit() ) {
		return;
	}
	?>
	<style>
		.product_data_tabs .inventory_tab,
		.product_data_tabs .shipping_tab,
		.product_data_tabs .linked_product_tab,
		.product_data_tabs .attribute_tab,
		.product_data_tabs .variations_tab,
		.product_data_tabs .advanced_tab,
		#woocommerce-product-data .inventory_options,
		#woocommerce-product-data .stock_status_field,
		#misc-publishing-actions,
		#minor-publishing-actions,
		#delete-action {
			display: none !important;
		}
	</style>
	<?php
}
add_action( 'admin_head-post.php', 'gp_qe_admin_styles' );
add_action( 'admin_head-post-new.php', 'gp_qe_admin_styles' );

function gp_qe_lock_down_save( $product ) {
	if ( ! gp_qe_is_current_user_quick_edit() ) {
		return $product;
	}

	$original = wc_get_product( $product->get_id() );
	if ( $original ) {
		$product->set_category_ids( $original->get_category_ids() );
		$product->set_stock_status( $original->get_stock_status() );
		$product->set_manage_stock( $original->get_manage_stock() );
		$product->set_stock_quantity( $original->get_stock_quantity() );
	}

	return $product;
}
add_filter( 'woocommerce_admin_process_product_object', 'gp_qe_lock_down_save' );

function gp_qe_register_video_metabox() {
	add_meta_box(
		'gp_product_video',
		__( 'Product Video (optional)', 'gp-quick-edit' ),
		'gp_qe_video_metabox_html',
		'product',
		'side',
		'low'
	);
}
add_action( 'add_meta_boxes', 'gp_qe_register_video_metabox' );

function gp_qe_video_metabox_html( $post ) {
	$video_url = get_post_meta( $post->ID, GP_QE_VIDEO_META, true );
	wp_nonce_field( 'gp_qe_save_video', 'gp_qe_video_nonce' );
	?>
	<p>
		<label for="gp_qe_video_url"><?php esc_html_e( 'Video URL (YouTube, Vimeo, or an uploaded .mp4 link)', 'gp-quick-edit' ); ?></label>
		<input type="url" id="gp_qe_video_url" name="gp_qe_video_url"
			value="<?php echo esc_attr( $video_url ); ?>"
			style="width:100%;margin-top:6px;" placeholder="https://" />
	</p>
	<p>
		<button type="button" class="button" id="gp_qe_upload_video_btn">
			<?php esc_html_e( 'Or upload a video file', 'gp-quick-edit' ); ?>
		</button>
	</p>
	<script>
	jQuery(document).ready(function($){
		$('#gp_qe_upload_video_btn').on('click', function(e){
			e.preventDefault();
			var frame = wp.media({
				title: '<?php echo esc_js( __( 'Select or upload video', 'gp-quick-edit' ) ); ?>',
				library: { type: 'video' },
				multiple: false
			});
			frame.on('select', function(){
				var attachment = frame.state().get('selection').first().toJSON();
				$('#gp_qe_video_url').val(attachment.url);
			});
			frame.open();
		});
	});
	</script>
	<?php
}

function gp_qe_save_video_meta( $post_id ) {
	if ( ! isset( $_POST['gp_qe_video_nonce'] ) || ! wp_verify_nonce( $_POST['gp_qe_video_nonce'], 'gp_qe_save_video' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_product', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['gp_qe_video_url'] ) ) {
		update_post_meta( $post_id, GP_QE_VIDEO_META, esc_url_raw( wp_unslash( $_POST['gp_qe_video_url'] ) ) );
	}
}
add_action( 'save_post_product', 'gp_qe_save_video_meta' );

function gp_qe_is_current_user_quick_edit() {
	$user = wp_get_current_user();
	if ( ! $user || ! $user->exists() ) {
		return false;
	}
	return in_array( GP_QE_ROLE, (array) $user->roles, true )
		&& ! in_array( 'administrator', (array) $user->roles, true );
}

function gp_qe_login_redirect( $redirect_to, $request, $user ) {
	if ( isset( $user->roles ) && in_array( GP_QE_ROLE, (array) $user->roles, true ) ) {
		return get_permalink( wc_get_page_id( 'shop' ) );
	}
	return $redirect_to;
}
add_filter( 'login_redirect', 'gp_qe_login_redirect', 10, 3 );
