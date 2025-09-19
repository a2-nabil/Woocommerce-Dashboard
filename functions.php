<?php

// all include files
include_once 'includes/login-register.php';
include_once 'includes/dashboards-functions.php';
include_once 'includes/wishlist-shortcodes.php';

// Enqueue styles and scripts
function hello_elementor_child_enqueue_assets()
{
    wp_enqueue_style('style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
    // Enqueue Google Fonts globally
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap', array(), wp_get_theme()->get('Version'));

    // Enqueue product card styles on shop/product pages
    if (is_shop() || is_product_category() || is_product_tag() || is_product()) {
        wp_enqueue_style(
            'product-card-style',
            get_stylesheet_uri(),
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'product-card-script',
            get_stylesheet_directory_uri() . '/assets/js/product-card.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );

        // Localize script for AJAX
        wp_localize_script('product-card-script', 'product_card_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('product_card_nonce'),
            'add_to_cart_text' => __('Add to Cart', 'hello-elementor-child'),
            'added_to_cart_text' => __('Added!', 'hello-elementor-child'),
        ));
    }

    // Login/Register pages
    if (is_page('login') || is_page('register') || is_account_page()) {
        wp_enqueue_style(
            'login-register-style',
            get_stylesheet_directory_uri() . '/assets/css/login-register.css',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'login-register-script',
            get_stylesheet_directory_uri() . '/assets/js/login-register.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_assets');



// Remove sale flash from product loop
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);

// Remove default add to cart button
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

// Add custom SVG add to cart button
add_action('woocommerce_after_shop_loop_item', 'custom_svg_add_to_cart_button', 10);

function custom_svg_add_to_cart_button()
{
    global $product;

    if (!$product || !$product->is_purchasable()) {
        return;
    }

    $product_id = $product->get_id();
    $cart_url = wc_get_cart_url();
    $add_to_cart_url = $product->add_to_cart_url();

    echo '<div class="nxt-product-card__add-btn"><a href="' . esc_url($add_to_cart_url) . '" 
            data-product_id="' . esc_attr($product_id) . '" 
            data-product_sku="' . esc_attr($product->get_sku()) . '" 
            class="nxt-add-to-cart-btn add_to_cart_button ajax_add_to_cart" 
            data-quantity="1" 
            aria-label="' . esc_attr__('Add to cart', 'hello-elementor-child') . '"><svg class="nxt-product-card__add-icon" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.7497 13.4164C21.5587 9.89298 20.0709 8.74976 15.6669 8.74976H11.2582C6.70251 8.74976 4.94026 9.90238 3.68039 14.2766C2.54561 18.2165 1.97822 20.1865 2.57143 21.7142C2.93472 22.6497 3.57973 23.461 4.42477 24.0452C6.11309 25.2125 10.4764 25.7644 14.583 25.6522" stroke="black" stroke-width="1.75" stroke-linecap="round" />
                <path d="M8.16748 9.33252V7.42343C8.16748 4.6118 10.518 2.33252 13.4175 2.33252C16.317 2.33252 18.6675 4.6118 18.6675 7.42343V9.33252" stroke="black" stroke-width="1.75" />
                <path d="M16.333 20.9992H25.6663M20.9997 25.6659L20.9997 16.3325" stroke="black" stroke-width="1.75" stroke-linecap="round" />
                <path d="M12.2485 12.8325H14.5819" stroke="black" stroke-width="1.75" stroke-linecap="round" />
            </svg></a></div>';
    echo '<svg class="nxt-product-card__clip-svg" viewBox="0 0 352 352" style="position: absolute; width: 0; height: 0; pointer-events: none;">
        <defs>
            <clipPath id="product-shape">
                <path d="M332 0C343.046 1.03081e-06 352 8.95431 352 20V247.131C352 257.786 337.614 264.508 327.282 261.902C323.033 260.83 318.583 260.261 314 260.261C284.177 260.261 260 284.369 260 314.107C260 318.624 260.558 323.01 261.608 327.202C264.202 337.554 257.472 352 246.8 352H20C8.95445 352 0.000232694 343.046 0 332V20C0 8.95431 8.95431 0 20 0H332Z" />
            </clipPath>
        </defs>
    </svg>';
    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
}
