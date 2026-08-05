<?php
// Script to generate WooCommerce dummy data for the active user upon page load
add_action( 'init', 'gp_generate_demo_data' );
function gp_generate_demo_data() {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    if ( get_option( 'gp_demo_data_installed' ) === 'yes' ) return;
    if ( ! is_user_logged_in() ) return;
    
    $user_id = get_current_user_id();
    
    // Create a dummy product
    $product = new WC_Product_Simple();
    $product->set_name( 'Premium Gumruk Plus Membership' );
    $product->set_regular_price( '99.90' );
    $product->set_virtual( true );
    
    $product->save();
    $product_id = $product->get_id();
    
    $address = [
        'first_name' => 'Hasan',
        'last_name'  => 'Gumruk',
        'company'    => 'Gumruk Plus Inc',
        'email'      => 'hasan@example.com',
        'phone'      => '555-1234',
        'address_1'  => 'Moda Cd. 123',
        'address_2'  => 'Kadikoy',
        'city'       => 'Istanbul',
        'state'      => 'TR34',
        'postcode'   => '34710',
        'country'    => 'TR'
    ];
    
    // Order 1: Completed
    $order1 = wc_create_order( ['customer_id' => $user_id] );
    $order1->add_product( wc_get_product($product_id), 1 );
    $order1->set_address( $address, 'billing' );
    $order1->set_address( $address, 'shipping' );
    $order1->calculate_totals();
    $order1->update_status( 'completed', 'Demo completed order' );
    wc_downloadable_product_permissions( $order1->get_id(), true );
    
    // Order 2: Processing
    $order2 = wc_create_order( ['customer_id' => $user_id] );
    $order2->add_product( wc_get_product($product_id), 2 );
    $order2->set_address( $address, 'billing' );
    $order2->set_address( $address, 'shipping' );
    $order2->calculate_totals();
    $order2->update_status( 'processing', 'Demo processing order' );
    
    // Update User Addresses
    foreach ( $address as $key => $value ) {
        update_user_meta( $user_id, 'billing_' . $key, $value );
        update_user_meta( $user_id, 'shipping_' . $key, $value );
    }
    
    update_option( 'gp_demo_data_installed', 'yes' );
}
