<?php

// Custom wishlist shortcode with product cards
add_shortcode('nxt_wishlist', 'nxt_wishlist_shortcode');
function nxt_wishlist_shortcode($atts)
{
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'per_page' => 12,
        'columns' => 4,
        'pagination' => 'yes'
    ), $atts);

    // Check if user is logged in
    if (!is_user_logged_in()) {
        return '<p>' . __('Please log in to view your wishlist.', 'hello-elementor-child') . '</p>';
    }

    // Get current user's wishlist items using optimized helper function
    $wishlist_items = nxt_get_wishlist_items();

    if (empty($wishlist_items)) {
        return '
                <div class="nxt-no-orders nxt-no-orders-history nxt-wishlist-empty">
            <img src="' . get_stylesheet_directory_uri() . '/assets/images/order-empty.png' . '"
                alt="' . __('Wishlist', 'hello-elementor-child') . '">
            <h2>' . __('Your Favorites List is empty.', 'hello-elementor-child') . '</h2>
            <p>' . __('It looks like you haven’t added any products to your favorites list yet. Start exploring our products and add your first product to see it here!', 'hello-elementor-child') . '
            </p>
             <a class="nxt-btn nxt-btn-primary" href="' . esc_url(wc_get_page_permalink('shop')) . '" class="button">' . __('Continue Shopping', 'hello-elementor-child') . '</a>
        </div>';
    }
    $total_items = count($wishlist_items);

    // Handle pagination
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $per_page = intval($atts['per_page']);
    $offset = ($paged - 1) * $per_page;
    $wishlist_items_paged = array_slice($wishlist_items, $offset, $per_page);

    // Start output
    ob_start();
    ?>
    <div class="nxt-custom-wishlist">
        <div class="nxt-wishlist-header">
            <h2><?php _e('Favorite Products', 'hello-elementor-child'); ?></h2>
            <span
                class="nxt-wishlist-count"><?php printf(__('Total %d Products', 'hello-elementor-child'), $total_items); ?></span>
        </div>

        <div class="nxt-wishlist-products" data-columns="<?php echo esc_attr($atts['columns']); ?>">
            <?php
            foreach ($wishlist_items_paged as $item) {
                // Extract product ID from YITH_WCWL_Wishlist_Factory item
                $product_id = is_object($item) && method_exists($item, 'get_product_id')
                    ? $item->get_product_id()
                    : 0;

                if (!$product_id) {
                    continue;
                }

                $product = wc_get_product($product_id);

                if (!$product || !$product->is_visible()) {
                    continue;
                }

                // Set global product for the card template
                $GLOBALS['product'] = $product;
                $remove_from_wishlist_url = nxt_get_same_page_remove_url($item);


                // Include the custom product card
                include get_stylesheet_directory() . '/includes/card-product.php';

            }
            ?>
        </div>

        <?php if ($atts['pagination'] === 'yes' && $total_items > $per_page): ?>
            <div class="nxt-wishlist-pagination">
                <?php
                $total_pages = ceil($total_items / $per_page);
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'current' => $paged,
                    'total' => $total_pages,
                    'prev_text' => __('&laquo; Previous', 'hello-elementor-child'),
                    'next_text' => __('Next &raquo;', 'hello-elementor-child'),
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .nxt-wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .nxt-wishlist-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .nxt-wishlist-count {
            font-weight: 500;
            font-size: 16px;
            line-height: 24px;
            letter-spacing: -0.64px;
            color: #666;
        }

        .nxt-wishlist-products {
            display: grid;
            gap: 30px;
            margin-bottom: 40px;
        }

        .nxt-wishlist-products[data-columns="2"] {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .nxt-wishlist-products[data-columns="3"] {
            grid-template-columns: repeat(3, 1fr);
        }

        .nxt-wishlist-products[data-columns="4"] {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .nxt-wishlist-empty {
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .nxt-wishlist-empty p {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
        }

        .nxt-wishlist-pagination {
            text-align: center;
            margin-top: 40px;
        }

        .nxt-wishlist-pagination .page-numbers {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 4px;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nxt-wishlist-pagination .page-numbers:hover,
        .nxt-wishlist-pagination .page-numbers.current {
            background: #333;
            color: #fff;
        }

        @media (max-width: 768px) {
            .nxt-wishlist-products {
                grid-template-columns: 1fr !important;
                gap: 20px;
            }

            .nxt-wishlist-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
    <?php

    return ob_get_clean();
}



// Helper function to get wishlist items (reusable)
function nxt_get_wishlist_items()
{
    $user_id = get_current_user_id();
    $cache_key = 'nxt_wishlist_items_' . $user_id;

    // Try to get from cache first
    $wishlist_items = wp_cache_get($cache_key, 'nxt_wishlist');

    if ($wishlist_items === false) {
        $wishlist_items = array();

        if (class_exists('YITH_WCWL_Wishlist_Factory')) {
            try {
                $wishlist = YITH_WCWL_Wishlist_Factory::get_default_wishlist();
                if ($wishlist && method_exists($wishlist, 'get_items')) {
                    $wishlist_items = $wishlist->get_items();
                }
            } catch (Exception $e) {
                $wishlist_items = array();
            }
        }

        // Cache for 5 minutes
        wp_cache_set($cache_key, $wishlist_items, 'nxt_wishlist', 300);
    }

    return $wishlist_items;
}

/**
 * Function to replace remove URL with same page URL
 * Keeps all YITH functionality but redirects to current page
 */
function nxt_get_same_page_remove_url($item)
{
    if (!$item || !method_exists($item, 'get_remove_url')) {
        return '#';
    }

    $original_url = $item->get_remove_url();


    $parsed_url = parse_url($original_url);

    if (!isset($parsed_url['query'])) {
        return '#';
    }

    parse_str($parsed_url['query'], $query_params);


    $current_url = home_url(add_query_arg(array()));

    $current_url = remove_query_arg(array('remove_from_wishlist', 'wishlist_id', '_wpnonce'), $current_url);

    $same_page_remove_url = add_query_arg($query_params, $current_url);

    return esc_url($same_page_remove_url);
}

// Cache invalidation when wishlist is updated
add_action('yith_wcwl_added_to_wishlist', 'nxt_clear_wishlist_cache');
add_action('yith_wcwl_removed_from_wishlist', 'nxt_clear_wishlist_cache');
function nxt_clear_wishlist_cache($user_id = null)
{
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    wp_cache_delete('nxt_wishlist_items_' . $user_id, 'nxt_wishlist');
}

// Wishlist preview shortcode for dashboard
add_shortcode('nxt_wishlist_preview', 'nxt_wishlist_preview_shortcode');
function nxt_wishlist_preview_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'limit' => 4
    ), $atts);

    if (!is_user_logged_in()) {
        return '<p>' . __('Please log in to view your favorites.', 'hello-elementor-child') . '</p>';
    }

    $wishlist_items = nxt_get_wishlist_items();

    if (empty($wishlist_items)) {
        return '<div class="nxt-favorites-empty">
                    <p>' . __('No favorite products yet.', 'hello-elementor-child') . '</p>
                </div>';
    }

    $limit = intval($atts['limit']);
    $limited_items = array_slice($wishlist_items, 0, $limit);

    ob_start();
    ?>
    <div class="nxt-favorites-preview-grid">
        <?php
        foreach ($limited_items as $item) {
            $product_id = is_object($item) && method_exists($item, 'get_product_id')
                ? $item->get_product_id()
                : 0;

            if (!$product_id) {
                continue;
            }

            $product = wc_get_product($product_id);

            if (!$product || !$product->is_visible()) {
                continue;
            }

            $product_name = $product->get_name();
            $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail');
            $product_link = get_permalink($product_id);
            $product_image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();

            // Get date added
            $date_added = '';
            if (is_object($item) && method_exists($item, 'get_date_added')) {
                $date_added = $item->get_date_added();
                if ($date_added) {
                    $date_added = date_i18n('j M, Y', strtotime($date_added));
                }
            }
            ?>
            <div class="nxt-favorite-preview-item">
                <a href="<?php echo esc_url($product_link); ?>" class="nxt-favorite-preview-link">
                    <div class="nxt-favorite-preview-image">
                        <img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_name); ?>"
                            loading="lazy">
                    </div>
                    <div class="nxt-favorite-preview-content">
                        <h4><?php echo esc_html($product_name); ?></h4>
                        <?php if ($date_added): ?>
                            <span class="nxt-favorite-preview-date"><?php echo esc_html($date_added); ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>

    <style>
        .nxt-favorites-preview-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .nxt-favorite-preview-item {
            transition: all 0.3s ease;
        }

        .nxt-favorite-preview-item:hover {
            transform: translateX(4px);
        }

        .nxt-favorite-preview-link {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
            padding: 8px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .nxt-favorite-preview-link:hover {
            background-color: #f8f9fa;
        }

        .nxt-favorite-preview-image {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .nxt-favorite-preview-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nxt-favorite-preview-content {
            flex: 1;
            min-width: 0;
        }

        .nxt-favorite-preview-content h4 {
            margin: 0 0 4px 0;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .nxt-favorite-preview-date {
            font-size: 12px;
            color: #666;
            display: block;
        }

        .nxt-favorites-empty {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .nxt-favorites-empty p {
            margin-bottom: 16px;
        }
    </style>
    <?php

    return ob_get_clean();
}