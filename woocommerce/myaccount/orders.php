<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); 

require_once get_stylesheet_directory() . '/includes/payment-methods-functions.php';

$total_orders = nxt_get_user_total_orders();
?>

<div class="nxt-orders-page">
    <?php if ( $has_orders ) : ?>
        <div class="nxt-orders-header">
        <div class="count_wrap">
            <h2><?php esc_html_e( 'Your orders', 'hello-elementor-child' ); ?></h2>
            <span class="count_number"> <?php esc_html_e( 'Total ', 'hello-elementor-child' ); echo esc_html( $total_orders ); esc_html_e( ' Orders', 'hello-elementor-child' ); ?></span>
        </div>


            <div class="nxt-orders-filter">
                <select id="nxt-order-filter" class="nxt-filter-select">
                    <option value=""><?php esc_html_e( 'Default sorting', 'hello-elementor-child' ); ?></option>
                    <option value="completed"><?php esc_html_e( 'Completed', 'hello-elementor-child' ); ?></option>
                    <option value="processing"><?php esc_html_e( 'Processing', 'hello-elementor-child' ); ?></option>
                    <option value="pending"><?php esc_html_e( 'Pending', 'hello-elementor-child' ); ?></option>
                    <option value="on-hold"><?php esc_html_e( 'On Hold', 'hello-elementor-child' ); ?></option>
                    <option value="cancelled"><?php esc_html_e( 'Cancelled', 'hello-elementor-child' ); ?></option>
                </select>
            </div>
        </div>
        <div class="nxt-orders-list">
            <?php
            foreach ( $customer_orders->orders as $customer_order ) {
                $order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                ?>
                <details class="nxt-order-card" data-status="<?php echo esc_attr( $order->get_status() ); ?>">
                    <summary class="nxt-order-summary-header">
                        <!-- Order Header -->
                        <div class="nxt-order-header">
                            <div class="nxt-order-info">
                                <div class="nxt-order-id">
                                    <?php esc_html_e( 'ID:', 'hello-elementor-child' ); ?>
                                    <span>#<?php echo esc_html( $order->get_order_number() ); ?></span>
                                </div>
                                <div class="nxt-order-date">
                                    <span><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
                                </div>
                            </div>
                            
                            <div class="nxt-order-summary">
                                <div class="nxt-order-summary-item">
                                    <span class="nxt-summary-label"><?php esc_html_e( 'Total products', 'hello-elementor-child' ); ?></span>
                                    <span class="nxt-summary-value"><?php echo esc_html( $item_count ); ?> <?php esc_html_e( 'Products', 'hello-elementor-child' ); ?></span>
                                </div>
                                <div class="nxt-order-summary-item">
                                    <span class="nxt-summary-label"><?php esc_html_e( 'Total payment', 'hello-elementor-child' ); ?></span>
                                    <span class="nxt-summary-value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
                                </div>
                                <div class="nxt-order-summary-item">
                                    <span class="nxt-summary-label"><?php esc_html_e( 'Payment type', 'hello-elementor-child' ); ?></span>
                                    <span class="nxt-summary-value"><?php echo esc_html( $order->get_payment_method_title() ); ?></span>
                                </div>
                                <div class="nxt-order-summary-item">
                                    <span class="nxt-summary-label"><?php esc_html_e( 'Status order', 'hello-elementor-child' ); ?></span>
                                    <span class="nxt-summary-value">
                                        <span class="nxt-order-status nxt-status-<?php echo esc_attr( $order->get_status() ); ?>">
                                            <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="nxt-order-actions">
                                <div class="nxt-expand-btn plus_icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0001 2.29169C10.5754 2.29169 11.0417 2.75806 11.0417 3.33335V8.95835H16.6667C17.242 8.95835 17.7084 9.42472 17.7084 10C17.7084 10.5753 17.242 11.0417 16.6667 11.0417H11.0417V16.6667C11.0417 17.242 10.5754 17.7084 10.0001 17.7084C9.42478 17.7084 8.95841 17.242 8.95841 16.6667V11.0417H3.33341C2.75812 11.0417 2.29175 10.5753 2.29175 10C2.29175 9.42472 2.75812 8.95835 3.33341 8.95835H8.95841V3.33335C8.95841 2.75806 9.42478 2.29169 10.0001 2.29169Z" fill="black"/>
                                    </svg>
                                </div>
                                <div class="nxt-expand-btn minus_icon">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.7083 10C17.7083 10.5753 17.2419 11.0417 16.6666 11.0417H3.33325C2.75795 11.0417 2.29159 10.5753 2.29159 10C2.29159 9.42474 2.75795 8.95837 3.33325 8.95837H16.6666C17.2419 8.95837 17.7083 9.42474 17.7083 10Z" fill="black"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </summary>
                    <div class="nxt-order-content-wrapper">
                        <div class="nxt-wrap_order_content">
                        <!-- Order Progress -->
                        <div class="nxt-order-progress">
                            <?php nxt_display_order_progress( $order ); ?>
                        </div>
                        <!-- Expandable Order Details -->
                        <div class="nxt-order-details">
                            <div class="nxt-order-content">
                                <!-- Product Details -->
                                <div class="nxt-order-products">
                                    <h3><?php esc_html_e( 'Product details', 'hello-elementor-child' ); ?></h3>
                                    <div class="nxt-products-list">
                                        <?php
                                        foreach ( $order->get_items() as $item_id => $item ) {
                                            $product = $item->get_product();
                                            if ( ! $product ) {
                                                continue;
                                            }
                                            $terms = get_the_terms( $product->get_id(), 'product_cat' );
                                            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                                                $category = $terms[0];
                                            }
                                            ?>
                                            <div class="nxt-product-item">
                                                <div class="nxt-product-image">
                                                    <?php
                                                    $thumbnail = $product->get_image( 'thumbnail' );
                                                    if ( $thumbnail ) {
                                                        echo wp_kses_post( $thumbnail );
                                                    } else {
                                                        echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr__( 'Product image', 'hello-elementor-child' ) . '">';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="nxt-product-details">
                                                    <h4><?php echo esc_html( $product->get_name() ); ?></h4>
                                                    <div class="nxt-product-price"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></div>
                                                    <div class="nxt-product-meta">
                                                        <?php
                                                        $meta_data = $item->get_formatted_meta_data();
                                                        foreach ( $meta_data as $meta ) {
                                                            echo '<div class="nxt-meta-item">';
                                                            echo '<span class="nxt-meta-label">' . wp_kses_post( $meta->display_key ) . ':</span> ';
                                                            echo '<span class="nxt-meta-value">' . wp_kses_post( $meta->display_value ) . '</span>';
                                                            echo '</div>';
                                                        }
                                                        ?>
                                                         <?php if ( $category ) : ?>
                                                        <div class="nxt-meta-item">
                                                            <span class="nxt-meta-label"><?php esc_html_e( 'Category:', 'hello-elementor-child' ); ?></span>
                                                            <span class="nxt-meta-value"><?php echo esc_html( $category->name ); ?> </span>
                                                        </div>
                                                        <?php endif; ?>
                                                        <div class="nxt-meta-item">
                                                            <span class="nxt-meta-label"><?php esc_html_e( 'Quantity:', 'hello-elementor-child' ); ?></span>
                                                            <span class="nxt-meta-value"><?php echo esc_html( $item->get_quantity() ); ?> <?php esc_html_e( 'Pcs', 'hello-elementor-child' ); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                        
                                    <!-- Summary -->
                                    <div class="nxt-summary-section">
                                        <h3><?php esc_html_e( 'Summary', 'hello-elementor-child' ); ?></h3>
                                        <div class="nxt-summary-details">
                                            <div class="nxt-summary-row">
                                                <span><?php esc_html_e( 'Total products', 'hello-elementor-child' ); ?></span>
                                                <span><?php echo esc_html( $item_count ); ?> <?php esc_html_e( 'Products', 'hello-elementor-child' ); ?></span>
                                            </div>
                                            <div class="nxt-summary-row">
                                                <span><?php esc_html_e( 'Subtotal', 'hello-elementor-child' ); ?></span>
                                                <span><?php echo wp_kses_post( wc_price( $order->get_subtotal() ) ); ?></span>
                                            </div>
                                            <?php if ( $order->get_shipping_total() > 0 ) : ?>
                                            <div class="nxt-summary-row">
                                                <span><?php esc_html_e( 'Estimated shipping', 'hello-elementor-child' ); ?></span>
                                                <span><?php echo wp_kses_post( wc_price( $order->get_shipping_total() ) ); ?></span>
                                            </div>
                                            <?php endif; ?>
                                            <?php if ( $order->get_total_tax() > 0 ) : ?>
                                            <div class="nxt-summary-row">
                                                <span><?php esc_html_e( 'Estimated tax', 'hello-elementor-child' ); ?></span>
                                                <span><?php echo wp_kses_post( wc_price( $order->get_total_tax() ) ); ?></span>
                                            </div>
                                            <?php endif; ?>
                                            <?php if ( $order->get_total_discount() > 0 ) : ?>
                                            <div class="nxt-summary-row discount">
                                                <span><?php esc_html_e( 'Discount', 'hello-elementor-child' ); ?> 
                                                    <?php 
                                                    $subtotal = $order->get_subtotal();
                                                    $discount_amount = $order->get_total_discount();
                                                    if ( $subtotal > 0 ) {
                                                        $discount_percentage = round( ( $discount_amount / $subtotal ) * 100, 1 );
                                                        echo '<span class="discount-percentage">' . esc_html( $discount_percentage ) . '%</span>';
                                                    }
                                                    ?>
                                                </span>
                                                <span>-<?php echo wp_kses_post( wc_price( $order->get_total_discount() ) ); ?></span>
                                            </div>
                                            <?php endif; ?>
                                            <div class="nxt-summary-row total">
                                                <span><?php esc_html_e( 'Total payment', 'hello-elementor-child' ); ?></span>
                                                <span><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
                                            </div>
                                        </div>
                                    </div>

                
                                
                                

                           
                                <!-- Shipping Details -->
                                <?php if ( $order->has_shipping_address() ) : ?>
                                    <div class="nxt-shipping-section">
                                        <h3><?php esc_html_e( 'Shipping details', 'hello-elementor-child' ); ?></h3>
                                        <div class="nxt-address-section">
                                            <h4><?php esc_html_e( 'SHIPPING ADDRESS', 'hello-elementor-child' ); ?></h4>
                                            <div class="nxt-address">
                                                <?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?>
                                                <div class="nxt-customer-phone"><?php echo esc_html( $order->get_shipping_phone() ); ?></div>
                                            </div>
                                        </div>
                                        <div class="nxt-extra-info">
                                            <div class="nxt-order-summary-item">
                                                <span class="nxt-summary-label"><?php esc_html_e( 'Shipping type', 'hello-elementor-child' ); ?></span>
                                                <span class="nxt-summary-value"><?php echo esc_html( $order->get_shipping_method() ); ?></span>
                                            </div>
                                            <div class="nxt-order-summary-item">
                                                <span class="nxt-summary-label"><?php esc_html_e( 'Estimated arrive', 'hello-elementor-child' ); ?></span>
                                                <span class="nxt-summary-value"><?php echo esc_html( nxt_get_estimated_delivery_date( $order ) ); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Payment Details -->
                                    <div class="nxt-payment-section">
                                        <h3><?php esc_html_e( 'Payment Details', 'hello-elementor-child' ); ?></h3>
                                        <div class="nxt-address-section">
                                            <h4><?php esc_html_e( 'BILLING ADDRESS', 'hello-elementor-child' ); ?></h4>
                                            <div class="nxt-address">
                                                <?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?>
                                                <div class="nxt-customer-email"><?php echo esc_html( $order->get_billing_email() ); ?></div>
                                                <div class="nxt-customer-phone"><?php echo esc_html( $order->get_billing_phone() ); ?></div>
                                            </div>
                                        </div>
                                        <div class="nxt-extra-info nxt-payments-info_wrapper">
                                            <?php
                                            
                                            $payment_details = extract_payment_details( $order );
                                            display_payment_debug_info( $payment_details, $order );
                                            
                                            // Use the extracted details
                                            $card_last4 = $payment_details['card_last4'];
                                            $card_brand = $payment_details['card_brand'];
                                            $card_expiry = $payment_details['card_expiry'];
                                            ?>
                                            
                                            <?php if ( $card_last4 || $card_expiry ) : ?>
                                                <div class="nxt-order-summary-item">
                                                    <span class="nxt-summary-label"><?php esc_html_e( 'Card ending', 'hello-elementor-child' ); ?></span>
                                                    <span class="nxt-summary-value">
                                                        <?php 
                                                        if ( $card_last4 ) {
                                                            if ( $card_brand ) {
                                                                echo '<div class="nxt-payment-method-display">';
                                                                echo get_card_brand_display( $card_brand, 'large' );
                                                                echo '<span class="card-last4">' . esc_html( $card_last4 ) . '</span>';
                                                                echo '</div>';
                                                            } else {
                                                                echo sprintf( esc_html__( '%s', 'hello-elementor-child' ), esc_html( $card_last4 ) );
                                                            }
                                                        } else {
                                                            esc_html_e( 'N/A', 'hello-elementor-child' );
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                                <div class="nxt-order-summary-item">
                                                    <span class="nxt-summary-label"><?php esc_html_e( 'Expiration date', 'hello-elementor-child' ); ?></span>
                                                    <span class="nxt-summary-value">
                                                        <?php 
                                                        echo $card_expiry ? esc_html( $card_expiry ) : esc_html__( 'N/A', 'hello-elementor-child' );
                                                        ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="nxt-order-summary-item">
                                                <span class="nxt-summary-label"><?php esc_html_e( 'Payment type', 'hello-elementor-child' ); ?></span>
                                                <span class="nxt-summary-value"><?php echo esc_html( $order->get_payment_method_title() ); ?></span>
                                            </div>
                                        </div>
                                    </div>
                            </div>

                             <div class="nxt-order-actions__wrapper">
                                <div class="nxt-order-actions__info">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.3332 9.99998C18.3332 5.39761 14.6022 1.66665 9.99984 1.66665C5.39746 1.66665 1.6665 5.39761 1.6665 9.99998C1.6665 14.6024 5.39746 18.3333 9.99984 18.3333C14.6022 18.3333 18.3332 14.6024 18.3332 9.99998Z" stroke="#666666" stroke-width="1.25"/><path d="M10.202 14.1667V10C10.202 9.60718 10.202 9.41076 10.0799 9.28873C9.95791 9.16669 9.76149 9.16669 9.36865 9.16669" stroke="#666666" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.99349 6.66669H10.001" stroke="#666666" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/></svg>

                                    <?php esc_html_e( 'Order cancellations can only be made during the Review stage. If you cancel at this point, your full payment will be refunded.', 'hello-elementor-child' ); ?>
                                </div>
                                    <!-- Order Actions -->
                                    <div class="nxt-order-actions-section">
                                        <?php
                                        $actions = wc_get_account_orders_actions( $order );
                                        $displayed_actions = [];
                                        
                                        if ( ! empty( $actions ) ) {
                                            foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                                if ( $key === 'view' ) {
                                                    continue;
                                                }
                                                $displayed_actions[] = $key;
                                                
                                                if ( $key === 'cancel' ) {
                                                    echo '<a href="' . esc_url( $action['url'] ) . '" class="nxt-btn nxt-btn-cancel">' . esc_html( $action['name'] ) . '</a>';
                                                } elseif ( $key === 'order-again' ) {
                                                    echo '<a href="' . esc_url( $action['url'] ) . '" class="nxt-btn nxt-btn-primary">' . esc_html( $action['name'] ) . '</a>';
                                                } else {
                                                    echo '<a href="' . esc_url( $action['url'] ) . '" class="nxt-btn nxt-btn-primary">' . esc_html( $action['name'] ) . '</a>';
                                                }
                                            }
                                        }
                                        
                                        // Add custom "Order Cancel" button ONLY if WooCommerce didn't already provide one
                                        if ( !in_array('cancel', $displayed_actions) && $order->has_status( array( 'on-hold', 'pending' ) ) ) {
                                            $cancel_url = wp_nonce_url( add_query_arg( 'cancel_order', $order->get_id(), wc_get_page_permalink( 'myaccount' ) ), 'woocommerce-cancel_order' );
                                            echo '<a href="' . esc_url( $cancel_url ) . '" class="nxt-btn nxt-btn-cancel">' . esc_html__( 'Cancel Order', 'hello-elementor-child' ) . '</a>';
                                        }
                           
                                        // Add custom "Order Again" button ONLY if WooCommerce didn't already provide one
                                        if ( !in_array('order-again', $displayed_actions) && $order->has_status( array( 'completed', 'shipped' ) ) ) {
                                            $order_again_url = wp_nonce_url( add_query_arg( 'order_again', $order->get_id(), wc_get_cart_url() ), 'woocommerce-order_again' );
                                            echo '<a href="' . esc_url( $order_again_url ) . '" class="nxt-btn nxt-btn-primary">' . esc_html__( 'Order Again', 'hello-elementor-child' ) . '</a>';
                                        }
                                        ?>
                                    </div>
                                
                            </div>
                        </div>

                        </div>
                    </div>
                </details>
                <?php
            }
            ?>
        </div>

        <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

        <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
            <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
                <?php if ( 1 !== $current_page ) : ?>
                    <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
                <?php endif; ?>

                <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
                    <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="nxt-no-orders nxt-no-orders-history">
            <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/order-empty.png' ); ?>" alt="<?php esc_html_e( 'No orders', 'hello-elementor-child' ); ?>">
            <h2><?php esc_html_e( 'Your order history is empty!', 'hello-elementor-child' ); ?></h2>
            <p><?php esc_html_e( 'It looks like you haven’t placed any orders yet. Start exploring our products and place your first order to see it here!', 'hello-elementor-child' ); ?></p>
            <a class="nxt-btn nxt-btn-primary" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                <?php esc_html_e( 'Shop now', 'hello-elementor-child' ); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
