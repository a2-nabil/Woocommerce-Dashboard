<?php
/**
 * Custom Order Status
 * 
 * Add custom order status: Shipped
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function add_shipped_order_status() {
    register_post_status( 'wc-shipped', array(
        'label'                     => _x( 'Shipped', 'Order status', 'hello-elementor-child' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Shipped (%s)', 'Shipped (%s)', 'hello-elementor-child' )
    ) );
}
add_action( 'init', 'add_shipped_order_status' );

/**
 * Add "Shipped" to WooCommerce order statuses
 */
function add_shipped_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    
    // Add all existing statuses
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        
        // Add "Shipped" after "Processing"
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-shipped'] = _x( 'Shipped', 'Order status', 'hello-elementor-child' );
        }
    }
    
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_shipped_to_order_statuses' );