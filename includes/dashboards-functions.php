<?php

// Replace WooCommerce My Account menu items
add_filter( 'woocommerce_account_menu_items', 'nxt_custom_my_account_menu_items', 10, 1 );

function nxt_custom_my_account_menu_items( $items ) {
    // Define only the items you want
    $new_items = array(
        'dashboard'       => __( 'Overview', 'hello-elementor-child' ), 
        'orders'          => __( 'My Orders', 'hello-elementor-child' ),
        'edit-address'    => __( 'My address', 'hello-elementor-child' ),
        'favorites'      => __( 'Favorite Products', 'hello-elementor-child' ),
        'edit-account'    => __( 'Account & profile', 'hello-elementor-child' ),  
    );

    return $new_items;
}


// === Navigation Icons Function ===
function nxt_get_account_nav_icon($endpoint) {
    $icons = array(
        'dashboard' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15.0303 1.04163C15.6463 1.04162 16.1525 1.04161 16.5621 1.08055C16.9877 1.12102 17.373 1.2079 17.7235 1.42267C18.0714 1.63588 18.3639 1.92841 18.5771 2.27634C18.7919 2.6268 18.8788 3.01209 18.9192 3.4377C18.9582 3.84727 18.9582 4.35352 18.9582 4.96945V4.96949V5.03043V5.03047C18.9582 5.6464 18.9582 6.15265 18.9192 6.56222C18.8788 6.98783 18.7919 7.37312 18.5771 7.72358C18.3639 8.07151 18.0714 8.36404 17.7235 8.57725C17.373 8.79202 16.9877 8.8789 16.5621 8.91937C16.1525 8.95831 15.6463 8.9583 15.0303 8.95829H15.0303H14.9694H14.9693C14.3534 8.9583 13.8471 8.95831 13.4376 8.91937C13.012 8.8789 12.6267 8.79202 12.2762 8.57725C11.9283 8.36404 11.6358 8.07151 11.4225 7.72358C11.2078 7.37312 11.1209 6.98783 11.0804 6.56222C11.0415 6.15264 11.0415 5.64638 11.0415 5.03043V5.03042V4.9695V4.9695C11.0415 4.35354 11.0415 3.84728 11.0804 3.4377C11.1209 3.01209 11.2078 2.6268 11.4225 2.27634C11.6358 1.92841 11.9283 1.63588 12.2762 1.42267C12.6267 1.2079 13.012 1.12102 13.4376 1.08055C13.8472 1.04161 14.3534 1.04162 14.9694 1.04163H14.9694H15.0303H15.0303Z" fill="#666666"/>
<path d="M5.0303 1.04163C5.64626 1.04162 6.15252 1.04161 6.5621 1.08055C6.98771 1.12102 7.37299 1.2079 7.72346 1.42267C8.07139 1.63588 8.36392 1.92841 8.57713 2.27634C8.79189 2.6268 8.87878 3.01209 8.91925 3.4377C8.95819 3.84727 8.95818 4.35352 8.95817 4.96945V4.96949V5.03043V5.03047C8.95818 5.6464 8.95819 6.15265 8.91925 6.56222C8.87878 6.98783 8.79189 7.37312 8.57713 7.72358C8.36392 8.07151 8.07139 8.36404 7.72346 8.57725C7.37299 8.79202 6.98771 8.8789 6.5621 8.91937C6.15253 8.95831 5.64628 8.9583 5.03034 8.95829H5.03031H4.96937H4.96933C4.3534 8.9583 3.84715 8.95831 3.43758 8.91937C3.01197 8.8789 2.62668 8.79202 2.27622 8.57725C1.92829 8.36404 1.63576 8.07151 1.42255 7.72358C1.20778 7.37312 1.1209 6.98783 1.08043 6.56222C1.04149 6.15264 1.04149 5.64638 1.0415 5.03043V5.03042V4.9695V4.9695C1.04149 4.35354 1.04149 3.84728 1.08043 3.4377C1.1209 3.01209 1.20778 2.6268 1.42255 2.27634C1.63576 1.92841 1.92829 1.63588 2.27622 1.42267C2.62668 1.2079 3.01197 1.12102 3.43758 1.08055C3.84716 1.04161 4.35342 1.04162 4.96937 1.04163H4.96938H5.03029H5.0303Z" fill="#666666"/>
<path d="M15.0303 11.0416C15.6463 11.0416 16.1525 11.0416 16.5621 11.0806C16.9877 11.121 17.373 11.2079 17.7235 11.4227C18.0714 11.6359 18.3639 11.9284 18.5771 12.2763C18.7919 12.6268 18.8788 13.0121 18.9192 13.4377C18.9582 13.8473 18.9582 14.3535 18.9582 14.9695V14.9695V15.0304V15.0305C18.9582 15.6464 18.9582 16.1526 18.9192 16.5622C18.8788 16.9878 18.7919 17.3731 18.5771 17.7236C18.3639 18.0715 18.0714 18.364 17.7235 18.5773C17.373 18.792 16.9877 18.8789 16.5621 18.9194C16.1525 18.9583 15.6463 18.9583 15.0303 18.9583H15.0303H14.9694H14.9693C14.3534 18.9583 13.8471 18.9583 13.4376 18.9194C13.012 18.8789 12.6267 18.792 12.2762 18.5773C11.9283 18.364 11.6358 18.0715 11.4225 17.7236C11.2078 17.3731 11.1209 16.9878 11.0804 16.5622C11.0415 16.1526 11.0415 15.6464 11.0415 15.0304V15.0304V14.9695V14.9695C11.0415 14.3535 11.0415 13.8473 11.0804 13.4377C11.1209 13.0121 11.2078 12.6268 11.4225 12.2763C11.6358 11.9284 11.9283 11.6359 12.2762 11.4227C12.6267 11.2079 13.012 11.121 13.4376 11.0806C13.8472 11.0416 14.3534 11.0416 14.9694 11.0416H14.9694H15.0303H15.0303Z" fill="#666666"/>
<path d="M5.0303 11.0416C5.64626 11.0416 6.15252 11.0416 6.5621 11.0806C6.98771 11.121 7.37299 11.2079 7.72346 11.4227C8.07139 11.6359 8.36392 11.9284 8.57713 12.2763C8.79189 12.6268 8.87878 13.0121 8.91925 13.4377C8.95819 13.8473 8.95818 14.3535 8.95817 14.9695V14.9695V15.0304V15.0305C8.95818 15.6464 8.95819 16.1526 8.91925 16.5622C8.87878 16.9878 8.79189 17.3731 8.57713 17.7236C8.36392 18.0715 8.07139 18.364 7.72346 18.5773C7.37299 18.792 6.98771 18.8789 6.5621 18.9194C6.15253 18.9583 5.64628 18.9583 5.03034 18.9583H5.03031H4.96937H4.96933C4.3534 18.9583 3.84715 18.9583 3.43758 18.9194C3.01197 18.8789 2.62668 18.792 2.27622 18.5773C1.92829 18.364 1.63576 18.0715 1.42255 17.7236C1.20778 17.3731 1.1209 16.9878 1.08043 16.5622C1.04149 16.1526 1.04149 15.6464 1.0415 15.0304V15.0304V14.9695V14.9695C1.04149 14.3535 1.04149 13.8473 1.08043 13.4377C1.1209 13.0121 1.20778 12.6268 1.42255 12.2763C1.63576 11.9284 1.92829 11.6359 2.27622 11.4227C2.62668 11.2079 3.01197 11.121 3.43758 11.0806C3.84716 11.0416 4.35342 11.0416 4.96937 11.0416H4.96938H5.03029H5.0303Z" fill="#666666"/>
</svg>
',
        'orders' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10.0024 1.04138C12.5094 1.04138 14.5854 3.01858 14.5854 5.51111V6.05408C14.9504 6.19358 15.2825 6.37218 15.5845 6.59802C16.5713 7.33625 17.1228 8.49008 17.563 10.0248L17.5728 10.0599C17.9677 11.4368 18.2783 12.518 18.4331 13.39C18.5902 14.2756 18.6046 15.0335 18.3335 15.7347C18.0291 16.5219 17.4907 17.201 16.7896 17.6879C16.1024 18.1649 15.0343 18.4743 13.8735 18.6693C12.6894 18.8682 11.3206 18.9618 9.96436 18.9574C8.60802 18.953 7.24627 18.8508 6.07568 18.6498C4.93013 18.453 3.88064 18.1477 3.21826 17.6879C2.51714 17.201 1.97872 16.5219 1.67432 15.7347C1.40321 15.0335 1.41756 14.2756 1.57471 13.39C1.72946 12.5181 2.04014 11.4368 2.43506 10.0599L2.44482 10.0248C2.90514 8.41991 3.49369 7.26521 4.49756 6.54431C4.78041 6.34122 5.0876 6.18084 5.41943 6.0531V5.51111C5.41943 3.01861 7.49554 1.04143 10.0024 1.04138ZM9.17041 8.3324C8.71024 8.33248 8.3374 8.7062 8.3374 9.16638C8.33758 9.62642 8.71035 9.99931 9.17041 9.99939H10.8374C11.2974 9.99926 11.6702 9.62639 11.6704 9.16638C11.6704 8.70622 11.2975 8.33252 10.8374 8.3324H9.17041ZM10.0024 2.7074C8.3673 2.70745 7.08545 3.98705 7.08545 5.51111V5.68396C7.50635 5.64183 7.95606 5.62439 8.43604 5.62439H11.5718C12.0531 5.62439 12.5015 5.64326 12.9194 5.68591V5.51111C12.9194 3.98702 11.6376 2.7074 10.0024 2.7074Z" fill="#666666"/>
</svg>
',
        'edit-address' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M9.46188 15.4317C9.5464 15.4752 9.99837 15.6868 9.99837 15.6868C9.99837 15.6868 10.4503 15.4752 10.5349 15.4317C10.7038 15.3448 10.9425 15.2166 11.2276 15.0482C11.7965 14.7122 12.5577 14.2124 13.3218 13.5575C14.8314 12.2635 16.4567 10.272 16.4567 7.66626C16.4567 4.02409 13.5817 1.04126 9.99837 1.04126C6.41505 1.04126 3.54004 4.02409 3.54004 7.66626C3.54004 10.272 5.16531 12.2635 6.67496 13.5575C7.43908 14.2124 8.2002 14.7122 8.76913 15.0482C9.05428 15.2166 9.29292 15.3448 9.46188 15.4317ZM9.99837 9.99959C11.3791 9.99959 12.4984 8.88031 12.4984 7.49959C12.4984 6.11888 11.3791 4.99959 9.99837 4.99959C8.61766 4.99959 7.49837 6.11888 7.49837 7.49959C7.49837 8.88031 8.61766 9.99959 9.99837 9.99959Z" fill="#666666"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M6.83552 16.647C7.60191 17.0302 8.71898 17.2913 9.99837 17.2913C11.2778 17.2913 12.3948 17.0302 13.1612 16.647C13.9775 16.2389 14.165 15.8293 14.165 15.6246H15.8317C15.8317 16.8007 14.9 17.6411 13.9066 18.1378C12.8633 18.6594 11.4804 18.958 9.99837 18.958C8.51634 18.958 7.13341 18.6594 6.09016 18.1378C5.09675 17.6411 4.16504 16.8007 4.16504 15.6246H5.83171C5.83171 15.8293 6.01928 16.2389 6.83552 16.647Z" fill="#666666"/>
</svg>
',
        'favorites' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M3.45392 2.79596C5.93806 1.27219 8.16658 1.87948 9.51287 2.89051C9.73435 3.05683 9.88635 3.17067 9.99951 3.24754C10.1127 3.17067 10.2647 3.05683 10.4862 2.89051C11.8324 1.87948 14.061 1.27219 16.5451 2.79596C18.2628 3.8496 19.2293 6.0505 18.8902 8.57926C18.5494 11.1202 16.9047 13.9941 13.4217 16.5721C12.2122 17.4678 11.3246 18.1253 9.99951 18.1253C8.67446 18.1253 7.78678 17.4678 6.57729 16.5721C3.09429 13.9941 1.44963 11.1202 1.10884 8.57926C0.76969 6.0505 1.73622 3.8496 3.45392 2.79596Z" fill="#666666"/>
</svg>
',
        'edit-account' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M14.8386 12.3643C14.9355 12.422 15.0553 12.4896 15.1908 12.5661C15.7848 12.9012 16.6827 13.4078 17.2979 14.0099C17.6826 14.3865 18.0481 14.8827 18.1146 15.4906C18.1853 16.1372 17.9032 16.7439 17.3374 17.283C16.3612 18.213 15.1897 18.9584 13.6744 18.9584H6.32587C4.81061 18.9584 3.63913 18.213 2.66292 17.283C2.09707 16.7439 1.81502 16.1372 1.88569 15.4906C1.95215 14.8827 2.31769 14.3865 2.70241 14.0099C3.31755 13.4078 4.21548 12.9012 4.80949 12.5661C4.94503 12.4896 5.06478 12.422 5.1617 12.3643C8.12341 10.6008 11.8769 10.6008 14.8386 12.3643Z" fill="#666666"/>
<path d="M5.62515 5.41669C5.62515 3.00044 7.5839 1.04169 10.0001 1.04169C12.4164 1.04169 14.3751 3.00044 14.3751 5.41669C14.3751 7.83293 12.4164 9.79169 10.0001 9.79169C7.5839 9.79169 5.62515 7.83293 5.62515 5.41669Z" fill="#666666"/>
</svg>
'
    );
    
    return isset($icons[$endpoint]) ? $icons[$endpoint] : '';
}

// === Register "favorites" endpoint ===
add_action('init', 'nxt_add_favorites_endpoint');
function nxt_add_favorites_endpoint()
{
    add_rewrite_endpoint('favorites', EP_PAGES);
}

add_filter('query_vars', function ($vars) {
    $vars[] = 'favorites';
    return $vars;
});


// === Load template for endpoint ===
add_action('woocommerce_account_favorites_endpoint', function () {
    wc_get_template('myaccount/favorites.php');
});

// === Flush rewrite rules when theme activated ===
add_action('after_switch_theme', function () {
    nxt_add_favorites_endpoint();
    flush_rewrite_rules();
});

/**
 * Get total orders count for current user
 */
function nxt_get_user_total_orders() {
    if (!is_user_logged_in()) {
        return 0;
    }

    $user_id = get_current_user_id();
    return wc_get_customer_order_count($user_id);
}

/**
 * Get completed orders count for current user
 */
function nxt_get_user_completed_orders() {
    if (!is_user_logged_in()) {
        return 0;
    }
    
    $user_id = get_current_user_id();
    $orders = wc_get_orders(array(
        'customer_id' => $user_id,
        'limit' => -1,
        'status' => array('wc-completed')
    ));
    
    return count($orders);
}

/**
 * Get pending/processing orders count for current user
 */
function nxt_get_user_pending_orders() {
    if (!is_user_logged_in()) {
        return 0;
    }
    
    $user_id = get_current_user_id();
    $orders = wc_get_orders(array(
        'customer_id' => $user_id,
        'limit' => -1,
        'status' => array('wc-pending', 'wc-processing', 'wc-on-hold')
    ));
    
    return count($orders);
}

/**
 * Get wishlist count for current user
 */
function nxt_get_user_wishlist_count() {

    $wishlist_items = nxt_get_wishlist_items();
    
    return count($wishlist_items);
}

/**
 * Display recent orders table
 */
function nxt_display_recent_orders($limit = 3) {
    if (!is_user_logged_in()) {
        return;
    }
    
    $user_id = get_current_user_id();
    $orders = wc_get_orders(array(
        'customer_id' => $user_id,
        'limit' => $limit,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if (empty($orders)) {
        echo '<p>' . esc_html__('No orders found.', 'hello-elementor-child') . '</p>';
        return;
    }
    
    echo '<div class="nxt-orders-table">';
    echo '<div class="nxt-orders-header">';
    echo '<span>' . esc_html__('Product', 'hello-elementor-child') . '</span>';
    echo '<span>' . esc_html__('Price', 'hello-elementor-child') . '</span>';
    echo '<span>' . esc_html__('Status', 'hello-elementor-child') . '</span>';
    echo '</div>';
    
    foreach ($orders as $order) {
        $items = $order->get_items();
        $first_item = array_shift($items);
        $product = $first_item ? $first_item->get_product() : null;
        
        echo '<div class="nxt-order-row">';
        echo '<div class="nxt-order-product">';
        
        if ($product && $product->get_image_id()) {
            echo '<img src="' . esc_url(wp_get_attachment_image_url($product->get_image_id(), 'thumbnail')) . '" alt="' . esc_attr($product->get_name()) . '">';
        }
        
        echo '<div class="nxt-product-info">';
        echo '<h4>' . ($product ? esc_html($product->get_name()) : esc_html__('Product', 'hello-elementor-child')) . '</h4>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="nxt-order-price">';
        echo wc_price($order->get_total());
        echo '</div>';
        
        echo '<div class="nxt-order-status">';
        $status = $order->get_status();
        $status_class = 'nxt-status-' . $status;
        echo '<span class="nxt-status-badge ' . esc_attr($status_class) . '">';
        echo esc_html(ucfirst($status));
        echo '</span>';
        echo '</div>';
        
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display popular products carousel
 */
function nxt_display_popular_products_carousel( $limit = 6 ) {

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $limit,
        'meta_key'       => 'total_sales', 
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    );
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        ?>
        <div class="nxt-dashboard-section nxt-popular-products">
        <div class="nxt-section-header">
            <h2><?php esc_html_e( 'Popular products', 'hello-elementor-child' ); ?></h2>
            <div class="nxt-carousel-controls">
                <button class="nxt-carousel-prev" aria-label="Previous">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.4999 6.99988C17.4999 6.99988 10.5 12.1553 10.5 13.9999C10.5 15.8446 17.5 20.9999 17.5 20.9999" stroke="black" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                </button>
                <button class="nxt-carousel-next" aria-label="Next">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5001 7C10.5001 7 17.5 12.1554 17.5 14.0001C17.5 15.8447 10.5 21 10.5 21" stroke="black" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                </button>
            </div>
        </div>
        <div class="nxt-popular-products-carousel">
            <div class="swiper-container nxt-products-swiper">
                <div class="swiper-wrapper">
        <?php
        while ($products->have_posts()) {
            $products->the_post();
            global $product;
            ?>
            <div class="swiper-slide">
            <?php include get_stylesheet_directory() . '/includes/card-product.php'; ?>
            </div>
        <?php 
        }

        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        wp_reset_postdata();
    }
}



/**
 * Get estimated delivery date for an order
 */
function nxt_get_estimated_delivery_date($order) {
    if (!$order) {
        return '';
    }
    
    $order_date = $order->get_date_created();
    if (!$order_date) {
        return '';
    }
    
    // Get order status to determine processing time
    $status = $order->get_status();
    $processing_days = 0;
    $shipping_days = 0;
    
    switch ($status) {
        case 'pending':
        case 'on-hold':
            $processing_days = 2; // 2 days for processing
            $shipping_days = 3;   // 3 days for shipping
            break;
        case 'processing':
            $processing_days = 1; // 1 day remaining for processing
            $shipping_days = 3;   // 3 days for shipping
            break;
        case 'shipped':
            $processing_days = 0; // Already processed
            $shipping_days = 2;   // 2 days remaining for shipping
            break;
        case 'completed':
            return __('Delivered', 'hello-elementor-child');
        case 'cancelled':
        case 'refunded':
        case 'failed':
            return __('N/A', 'hello-elementor-child');
        default:
            $processing_days = 2;
            $shipping_days = 3;
    }
    
    $total_days = $processing_days + $shipping_days;
    $delivery_date = clone $order_date;
    $delivery_date->add(new DateInterval('P' . $total_days . 'D'));
    
    // Format the date
    return $delivery_date->format('M j, Y');
}

/**
 * Display order progress indicators
 */
function nxt_display_order_progress($order) {
    $status = $order->get_status();
    $steps = array(
        'reviewing' => __('Reviewing', 'hello-elementor-child'),
        'preparing' => __('Preparing', 'hello-elementor-child'),
        'shipped' => __('Shipped', 'hello-elementor-child'),
        'delivered' => __('Delivered', 'hello-elementor-child')
    );
    
    // Determine current step based on order status
    $current_step = 0;
    switch ($status) {
        case 'pending':
        case 'on-hold':
            $current_step = 1;
            break;
        case 'processing':
            $current_step = 2;
            break;
        case 'shipped':
            $current_step = 3;
            break;
        case 'completed':
            $current_step = 4;
            break;
        case 'cancelled':
        case 'refunded':
        case 'failed':
            $current_step = 0;
            break;
    }
    
    echo '<div class="nxt-progress-indicator">';
    echo '<div class="nxt-progress-line-container">';
    echo '<div class="nxt-progress-line"></div>';
    echo '</div>';
    
    $step_num = 1;
    foreach ($steps as $key => $label) {
        $is_active = $step_num <= $current_step;
        $is_current = $step_num === $current_step;
        
        echo '<div class="nxt-progress-step' . ($is_active ? ' active' : '') . ($is_current ? ' current' : '') . '">';
        echo '<div class="nxt-step-icon">';
        
        // Define SVG icons for each step
        $svg_icons = [
            1 => '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="8.49854" y="2.99622" width="8.00333" height="4.00167" rx="1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.5016 4.99707H18.5024C19.6074 4.99707 20.5032 5.89287 20.5032 6.9979V19.0029C20.5032 20.1079 19.6074 21.0037 18.5024 21.0037H6.49742C5.39239 21.0037 4.49658 20.1079 4.49658 19.0029V6.9979C4.49658 5.89287 5.39239 4.99707 6.49742 4.99707H8.49825" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.734 12.8516L11.9429 15.6428L10.2642 13.9701" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            2 => '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.3337 2.99622V7.9983" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.33154 17.0021H9.33238" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M21.3376 9.99919C21.3376 8.89416 20.4418 7.99835 19.3367 7.99835H5.33091C4.22588 7.99835 3.33008 8.89416 3.33008 9.99919" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path fill-rule="evenodd" clip-rule="evenodd" d="M19.3367 21.0037H5.58102C4.33786 21.0037 3.33008 19.9959 3.33008 18.7528V8.49851C3.32976 7.9582 3.43936 7.42347 3.65221 6.92685L4.74667 4.36279C5.10058 3.53376 5.91512 2.99598 6.81653 2.99622H17.8501C18.7515 2.99598 19.5661 3.53376 19.92 4.36279L21.0204 6.92685C21.2314 7.42387 21.3393 7.95856 21.3376 8.49851V19.0029C21.3376 20.1079 20.4418 21.0037 19.3367 21.0037Z" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            3 => '<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.3703 18.296C19.9893 18.915 19.9893 19.918 19.3703 20.536C18.7513 21.155 17.7483 21.155 17.1303 20.536C16.5113 19.917 16.5113 18.914 17.1303 18.296C17.7493 17.677 18.7523 17.677 19.3703 18.296" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.37038 18.296C8.98938 18.915 8.98938 19.918 8.37038 20.536C7.75138 21.155 6.74838 21.155 6.13038 20.536C5.51238 19.917 5.51138 18.914 6.13038 18.296C6.74938 17.678 7.75138 17.677 8.37038 18.296" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.6665 4H14.6665C15.2185 4 15.6665 4.448 15.6665 5V15H2.6665" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.6665 19.416H3.6665C3.1145 19.416 2.6665 18.968 2.6665 18.416V13" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.6665 7H19.9895C20.3985 7 20.7665 7.249 20.9175 7.629L22.5235 11.643C22.6175 11.879 22.6665 12.131 22.6665 12.385V18.333C22.6665 18.885 22.2185 19.333 21.6665 19.333H19.8355" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.6664 19.42H8.83643" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M22.6665 14H18.6665V10H21.8665" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.6665 4H7.6665" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.6665 7H5.6665" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.6665 10H2.6665" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            4 => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.0001 12L6.146 17.8029" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path fill-rule="evenodd" clip-rule="evenodd" d="M9 3H15V5C15 5.55228 14.5523 6 14 6H10C9.44772 6 9 5.55228 9 5V3Z" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 15.108V19.2757C2.99999 19.733 3.18164 20.1716 3.505 20.495C3.82835 20.8183 4.26692 21 4.72421 21H9.874" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 6V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21H16.5913" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 15.108L10.1981 21.2777C10.7418 21.7438 11.4343 22 12.1504 22H15.4391C15.8919 22 16.3026 21.7342 16.4881 21.3211C16.6737 20.908 16.5995 20.4245 16.2986 20.086L14 17.5V16.5H16.0629C16.8566 16.5 17.5 15.8566 17.5 15.0629V15.0629C17.5 14.4085 17.058 13.8368 16.4248 13.672L10 12L3 6" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
        ];
        
        echo $svg_icons[$step_num];
        echo '</div>';
        echo '<div class="nxt-step-circle' . ($is_active ? ' active' : '') . '"></div>';
        echo '<span class="nxt-step-label">' . esc_html($label) . '</span>';
        echo '</div>';
        
        $step_num++;
    }
    echo '</div>';
}

