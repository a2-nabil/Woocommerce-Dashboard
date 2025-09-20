<?php

/**
 * Dynamic Product Card Template
 * Can be used with include or get_template_part
 * Expects $product variable to be set
 */

if (! defined('ABSPATH')) {
    exit;
}

// If no product is passed, try to get global product
if (! isset($product)) {
    global $product;
}

// Ensure we have a valid product
if (empty($product) || ! $product->is_visible()) {
    return;
}

// Get product data
$product_id = $product->get_id();
$product_name = $product->get_name();
$product_price = $product->get_price_html();
$add_to_cart_url = $product->add_to_cart_url();
$product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'full');
$product_link = get_permalink($product_id);
$product_image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();
$product_image_alt = get_post_meta(get_post_thumbnail_id($product_id), '_wp_attachment_image_alt', true);

?>



<div class="nxt-product-card" data-product-id="<?php echo esc_attr($product_id); ?>">
    <!-- Hidden SVG for clip-path definition -->
    <svg class="nxt-product-card__clip-svg" viewBox="0 0 352 352" style="position: absolute; width: 0; height: 0; pointer-events: none;">
        <defs>
            <clipPath id="product-shape-<?php echo esc_attr($product_id); ?>">
                <path d="M332 0C343.046 1.03081e-06 352 8.95431 352 20V247.131C352 257.786 337.614 264.508 327.282 261.902C323.033 260.83 318.583 260.261 314 260.261C284.177 260.261 260 284.369 260 314.107C260 318.624 260.558 323.01 261.608 327.202C264.202 337.554 257.472 352 246.8 352H20C8.95445 352 0.000232694 343.046 0 332V20C0 8.95431 8.95431 0 20 0H332Z" />
            </clipPath>
        </defs>
    </svg>
    <a href="<?php echo esc_url($product_link); ?>" class="nxt-product-card-anchor"></a>

    <!-- Product Image Container -->
    <div class="nxt-product-card__image-container">
        <div class="nxt-product-card__image">
            <!-- Product Image -->
            <img src="<?php echo esc_url($product_image_url); ?>"
                alt="<?php echo esc_attr($product_image_alt ? $product_image_alt : $product_name); ?>"
                class="nxt-product-card__product-image"
                style="clip-path: url(#product-shape-<?php echo esc_attr($product_id); ?>);">
        </div>

        <?php if (isset($remove_from_wishlist_url) && !empty($remove_from_wishlist_url)): ?>
            <div class="nxt-product-card__favorite-btn">      
                <a href="<?php echo esc_url($remove_from_wishlist_url); ?>" class="remove remove_from_wishlist" title="<?php echo esc_html( apply_filters( 'yith_wcwl_remove_product_wishlist_message_title', __( 'Remove this product', 'yith-woocommerce-wishlist' ) ) ); ?>">
                    <svg class="nxt-product-card__favorite-icon" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.76196 2.23677C4.74928 1.01775 6.5321 1.50358 7.60912 2.31241C7.78631 2.44547 7.90791 2.53653 7.99844 2.59803C8.08896 2.53653 8.21057 2.44547 8.38775 2.31241C9.46478 1.50358 11.2476 1.01775 13.2349 2.23677C14.6091 3.07968 15.3823 4.8404 15.111 6.86341C14.8383 8.89618 13.5226 11.1953 10.7362 13.2577C9.76863 13.9743 9.05848 14.5002 7.99844 14.5002C6.9384 14.5002 6.22825 13.9743 5.26066 13.2577C2.47426 11.1953 1.15854 8.89618 0.885903 6.86341C0.61458 4.8404 1.3878 3.07968 2.76196 2.23677Z" />
                    </svg>
                </a>
            </div>
        <?php else:  echo do_shortcode('[yith_wcwl_add_to_wishlist]'); 
        endif; ?>


        <!-- Add to cart button -->
        <div class="nxt-product-card__add-btn">
            <a href="<?php echo esc_url($add_to_cart_url); ?>" 
            data-product_id="<?php echo esc_attr($product_id); ?>" 
            data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" 
            class="nxt-add-to-cart-btn add_to_cart_button ajax_add_to_cart" 
            data-quantity="1" 
            aria-label="<?php esc_attr_e('Add to cart', 'hello-elementor-child'); ?>"><svg class="nxt-product-card__add-icon" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.7497 13.4164C21.5587 9.89298 20.0709 8.74976 15.6669 8.74976H11.2582C6.70251 8.74976 4.94026 9.90238 3.68039 14.2766C2.54561 18.2165 1.97822 20.1865 2.57143 21.7142C2.93472 22.6497 3.57973 23.461 4.42477 24.0452C6.11309 25.2125 10.4764 25.7644 14.583 25.6522" stroke="black" stroke-width="1.75" stroke-linecap="round" />
                <path d="M8.16748 9.33252V7.42343C8.16748 4.6118 10.518 2.33252 13.4175 2.33252C16.317 2.33252 18.6675 4.6118 18.6675 7.42343V9.33252" stroke="black" stroke-width="1.75" />
                <path d="M16.333 20.9992H25.6663M20.9997 25.6659L20.9997 16.3325" stroke="black" stroke-width="1.75" stroke-linecap="round" />
                <path d="M12.2485 12.8325H14.5819" stroke="black" stroke-width="1.75" stroke-linecap="round" />
            </svg></a>
        </div>
    </div>

    <!-- Product information -->
    <div class="nxt-product-card__info">
        <h3 class="nxt-product-card__title"><?php echo esc_html($product_name); ?></h3>
        <p class="nxt-product-card__price">
            <?php echo $product_price; ?>
        </p>
    </div>
</div>