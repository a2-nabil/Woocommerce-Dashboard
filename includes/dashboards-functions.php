<?php
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

// === Add "Favorites" to My Account menu ===
add_filter('woocommerce_account_menu_items', function ($items) {
    $new = [];
    foreach ($items as $key => $label) {
        $new[$key] = $label;
        if ($key === 'dashboard') {
            $new['favorites'] = __('Favorites', 'hello-elementor-child');
        }
    }
    return $new;
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



