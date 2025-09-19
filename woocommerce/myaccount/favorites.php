<?php
defined( 'ABSPATH' ) || exit;
?>

<div class="nxt-account-favorites">
    <h2><?php esc_html_e( 'Your Favorites', 'hello-elementor-child' ); ?></h2>

    <?php
    // Option 1: Use YITH shortcode
    echo do_shortcode('[yith_wcwl_wishlist]');

    ?>
</div>
