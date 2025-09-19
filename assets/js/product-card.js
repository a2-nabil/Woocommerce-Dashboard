jQuery(document).ready(function($) {
    'use strict';

    // Add to cart functionality
    $('.nxt-product-card__add-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        const $card = $button.closest('.nxt-product-card');
        
        // Prevent double clicks
        if ($button.hasClass('loading')) {
            return;
        }
        
        $button.addClass('loading');
        $button.find('.nxt-product-card__add-icon').addClass('rotating');
        
        $.ajax({
            type: 'POST',
            url: product_card_ajax.ajax_url,
            data: {
                action: 'add_to_cart',
                product_id: productId,
                quantity: 1,
                nonce: product_card_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Show success state
                    $button.addClass('success');
                    $button.attr('aria-label', product_card_ajax.added_to_cart_text);
                    
                    // Update cart count if you have a cart counter
                    if (response.data.cart_count) {
                        $('.cart-count').text(response.data.cart_count);
                    }
                    
                    // Show notification
                    showNotification(response.data.message, 'success');
                    
                    // Reset button after 2 seconds
                    setTimeout(function() {
                        $button.removeClass('success');
                        $button.attr('aria-label', product_card_ajax.add_to_cart_text);
                    }, 2000);
                    
                } else {
                    showNotification(response.data.message || 'Failed to add to cart', 'error');
                }
            },
            error: function() {
                showNotification('Something went wrong. Please try again.', 'error');
            },
            complete: function() {
                $button.removeClass('loading');
                $button.find('.nxt-product-card__add-icon').removeClass('rotating');
            }
        });
    });

    // Favorites functionality
    $('.nxt-product-card__favorite-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $button = $(this);
        const productId = $button.data('product-id');
        
        // Prevent double clicks
        if ($button.hasClass('loading')) {
            return;
        }
        
        $button.addClass('loading');
        
        $.ajax({
            type: 'POST',
            url: product_card_ajax.ajax_url,
            data: {
                action: 'toggle_favorite',
                product_id: productId,
                nonce: product_card_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.action === 'added') {
                        $button.addClass('is-favorite');
                        $button.attr('aria-label', 'Remove from favorites');
                    } else {
                        $button.removeClass('is-favorite');
                        $button.attr('aria-label', 'Add to favorites');
                    }
                    
                    showNotification(response.data.message, 'success');
                } else {
                    showNotification(response.data.message || 'Failed to update favorites', 'error');
                }
            },
            error: function() {
                showNotification('Something went wrong. Please try again.', 'error');
            },
            complete: function() {
                $button.removeClass('loading');
            }
        });
    });

    // Notification system
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        $('.nxt-notification').remove();
        
        const $notification = $('<div class="nxt-notification nxt-notification--' + type + '">' + message + '</div>');
        $('body').append($notification);
        
        // Show notification
        setTimeout(() => $notification.addClass('show'), 100);
        
        // Hide notification after 4 seconds
        setTimeout(() => {
            $notification.removeClass('show');
            setTimeout(() => $notification.remove(), 300);
        }, 4000);
    }

    // Prevent card click when clicking buttons
    $('.nxt-product-card__favorite-btn, .nxt-product-card__add-btn').on('click', function(e) {
        e.stopPropagation();
    });
});
