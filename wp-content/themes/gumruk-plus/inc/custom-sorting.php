<?php
// Custom Sorting Menu for Gumruk Plus
add_action( 'woocommerce_archive_description', 'gp_custom_sorting_menu', 20 );

function gp_custom_sorting_menu() {
    if ( ! woocommerce_products_will_display() ) {
        return;
    }

    $current_orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : 'menu_order';
    
    $sort_options = array(
        'menu_order' => __( 'Varsayılan', 'gumruk-plus' ),
        'popularity' => __( 'Popüler', 'gumruk-plus' ),
        'date'       => __( 'En Yeni', 'gumruk-plus' ),
        'price'      => __( 'Fiyat: Düşükten Yükseğe', 'gumruk-plus' ),
        'price-desc' => __( 'Fiyat: Yüksekten Düşüğe', 'gumruk-plus' )
    );
    
    echo '<div class="gp-custom-sorting-wrapper">';
    echo '<ul class="gp-custom-sorting-list">';
    
    foreach ( $sort_options as $id => $name ) {
        $is_active = ( $current_orderby === $id ) ? 'is-active' : '';
        $url = add_query_arg( 'orderby', $id );
        echo '<li><a href="' . esc_url( $url ) . '" class="gp-sort-link ' . esc_attr( $is_active ) . '">' . esc_html( $name ) . '</a></li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
