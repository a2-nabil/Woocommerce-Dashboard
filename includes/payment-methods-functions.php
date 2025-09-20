<?php
/**
 * Payment Methods Functions
 * 
 * Universal payment method details extractor for WooCommerce orders
 * Supports multiple payment gateways: Stripe, PayPal, BACS, COD, Cheque, and more
 * 
 * @package Hello Elementor Child
 * @version 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Extract payment details from WooCommerce order
 * 
 * @param WC_Order $order The WooCommerce order object
 * @return array Payment details array
 */
function extract_payment_details( $order ) {
    $payment_method = $order->get_payment_method();
    $payment_method_title = $order->get_payment_method_title();
    $payment_tokens = WC_Payment_Tokens::get_order_tokens( $order->get_id() );
    
    // Initialize payment details array
    $details = [
        'method' => $payment_method,
        'title' => $payment_method_title,
        'card_last4' => '',
        'card_brand' => '',
        'card_expiry' => '',
        'transaction_id' => $order->get_transaction_id(),
        'raw_details' => []
    ];
    
    // Try payment tokens first (universal approach)
    if ( !empty($payment_tokens) ) {
        $token = reset($payment_tokens);
        if ( method_exists($token, 'get_last4') ) {
            $details['card_last4'] = $token->get_last4();
        }
        if ( method_exists($token, 'get_card_type') ) {
            $details['card_brand'] = $token->get_card_type();
        }
        if ( method_exists($token, 'get_expiry_month') && method_exists($token, 'get_expiry_year') ) {
            $exp_month = $token->get_expiry_month();
            $exp_year = $token->get_expiry_year();
            if ( $exp_month && $exp_year ) {
                $details['card_expiry'] = sprintf('%02d/%s', $exp_month, substr($exp_year, -2));
            }
        }
    }
    
    // Gateway-specific extraction
    switch ( $payment_method ) {
        case 'stripe':
            $details = extract_stripe_details( $order, $details );
            break;
        case 'paypal':
        case 'ppcp-gateway':
            $details = extract_paypal_details( $order, $details );
            break;
        case 'bacs':
            $details = extract_bacs_details( $order, $details );
            break;
        case 'cod':
            $details = extract_cod_details( $order, $details );
            break;
        case 'cheque':
            $details = extract_cheque_details( $order, $details );
            break;
        default:
            $details = extract_generic_details( $order, $details );
            break;
    }
    
    return $details;
}

/**
 * Extract Stripe payment details
 * 
 * @param WC_Order $order The WooCommerce order object
 * @param array $details Current payment details array
 * @return array Updated payment details array
 */
function extract_stripe_details( $order, $details ) {
    // Extract all Stripe meta data
    $stripe_source_id = $order->get_meta('_stripe_source_id');
    $stripe_intent_id = $order->get_meta('_stripe_intent_id');
    $stripe_customer_id = $order->get_meta('_stripe_customer_id');
    $stripe_fee = $order->get_meta('_stripe_fee');
    $stripe_net = $order->get_meta('_stripe_net');
    $stripe_currency = $order->get_meta('_stripe_currency');
    
    $details['raw_details']['source_id'] = $stripe_source_id;
    $details['raw_details']['intent_id'] = $stripe_intent_id;
    $details['raw_details']['customer_id'] = $stripe_customer_id;
    $details['raw_details']['fee'] = $stripe_fee;
    $details['raw_details']['net'] = $stripe_net;
    $details['raw_details']['currency'] = $stripe_currency;
    
    // Try to get card details via API if not found in tokens
    if ( empty($details['card_last4']) && $stripe_source_id ) {
        $stripe_gateway = WC()->payment_gateways->payment_gateways()['stripe'] ?? null;
        
        if ( $stripe_gateway ) {
            // Get secret key using the working method
            $secret_key = $stripe_gateway->get_option('secret_key') ?: $stripe_gateway->get_option('test_secret_key');
            
            if ( $secret_key ) {
                // Use WordPress HTTP API to get payment method details
                $response = wp_remote_get('https://api.stripe.com/v1/payment_methods/' . $stripe_source_id, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $secret_key,
                        'Stripe-Version' => '2020-08-27'
                    ],
                    'timeout' => 10
                ]);
                
                if ( !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200 ) {
                    $body = wp_remote_retrieve_body($response);
                    $data = json_decode($body, true);
                    
                    if ( isset($data['card']) ) {
                        $details['card_last4'] = $data['card']['last4'] ?? '';
                        $details['card_brand'] = $data['card']['brand'] ?? '';
                        if ( isset($data['card']['exp_month']) && isset($data['card']['exp_year']) ) {
                            $details['card_expiry'] = sprintf('%02d/%s', $data['card']['exp_month'], substr($data['card']['exp_year'], -2));
                        }
                    }
                }
            }
        }
    }
    
    return $details;
}

/**
 * Extract PayPal payment details
 * 
 * @param WC_Order $order The WooCommerce order object
 * @param array $details Current payment details array
 * @return array Updated payment details array
 */
function extract_paypal_details( $order, $details ) {
    $paypal_txn_id = $order->get_meta('_paypal_transaction_id') ?: $order->get_meta('PayPal Transaction ID');
    $paypal_fee = $order->get_meta('_paypal_fee') ?: $order->get_meta('PayPal Fee');
    $paypal_payer_id = $order->get_meta('Payer PayPal ID') ?: $order->get_meta('_paypal_payer_id');
    $paypal_email = $order->get_meta('Payer PayPal address') ?: $order->get_meta('_paypal_email');
    
    $details['raw_details']['transaction_id'] = $paypal_txn_id;
    $details['raw_details']['fee'] = $paypal_fee;
    $details['raw_details']['payer_id'] = $paypal_payer_id;
    $details['raw_details']['payer_email'] = $paypal_email;
    
    return $details;
}

/**
 * Extract Bank Transfer (BACS) payment details
 * 
 * @param WC_Order $order The WooCommerce order object
 * @param array $details Current payment details array
 * @return array Updated payment details array
 */
function extract_bacs_details( $order, $details ) {
    $details['raw_details']['method_type'] = 'Bank Transfer';
    return $details;
}

/**
 * Extract Cash on Delivery payment details
 * 
 * @param WC_Order $order The WooCommerce order object
 * @param array $details Current payment details array
 * @return array Updated payment details array
 */
function extract_cod_details( $order, $details ) {
    $details['raw_details']['method_type'] = 'Cash on Delivery';
    return $details;
}

/**
 * Extract Cheque payment details
 * 
 * @param WC_Order $order The WooCommerce order object
 * @param array $details Current payment details array
 * @return array Updated payment details array
 */
function extract_cheque_details( $order, $details ) {
    $details['raw_details']['method_type'] = 'Check Payment';
    return $details;
}

/**
 * Extract generic payment details for unknown payment methods
 * 
 * @param WC_Order $order The WooCommerce order object
 * @param array $details Current payment details array
 * @return array Updated payment details array
 */
function extract_generic_details( $order, $details ) {
    // Try to extract common meta fields
    $all_meta = $order->get_meta_data();
    foreach ( $all_meta as $meta ) {
        $key = $meta->key;
        $value = $meta->value;
        
        // Look for common payment-related fields
        if ( strpos($key, 'last4') !== false || strpos($key, 'card_last4') !== false ) {
            $details['card_last4'] = $value;
        }
        if ( strpos($key, 'brand') !== false || strpos($key, 'card_type') !== false ) {
            $details['card_brand'] = $value;
        }
        if ( strpos($key, 'exp_') !== false && strpos($key, 'month') !== false ) {
            $exp_month = $value;
        }
        if ( strpos($key, 'exp_') !== false && strpos($key, 'year') !== false ) {
            $exp_year = $value;
            if ( isset($exp_month) ) {
                $details['card_expiry'] = sprintf('%02d/%s', $exp_month, substr($exp_year, -2));
            }
        }
        
        // Store payment-related meta for display
        if ( strpos($key, 'payment') !== false || 
             strpos($key, 'transaction') !== false ||
             strpos($key, 'fee') !== false ) {
            $details['raw_details'][str_replace('_', '', $key)] = $value;
        }
    }
    
    return $details;
}

/**
 * Display payment method debug information (only in debug mode)
 * 
 * @param array $payment_details Extracted payment details
 * @param WC_Order $order The WooCommerce order object
 */
function display_payment_debug_info( $payment_details, $order ) {
    if ( !defined('WP_DEBUG') || !WP_DEBUG ) {
        return;
    }
    
    echo '<div style="background: #f0f0f0; padding: 15px; margin: 10px 0; border: 1px solid #ddd;">';
    echo '<h4>🔍 Payment Method Analysis (Debug Mode)</h4>';
    echo '<strong>Payment Method:</strong> ' . $payment_details['method'] . '<br>';
    echo '<strong>Payment Title:</strong> ' . $payment_details['title'] . '<br>';
    echo '<strong>Order ID:</strong> ' . $order->get_id() . '<br>';
    echo '<strong>Customer ID:</strong> ' . $order->get_customer_id() . '<br>';
    echo '</div>';
    
    echo '<div style="background: #e8f5e8; padding: 15px; margin: 10px 0; border: 1px solid #4caf50;">';
    echo '<h4>Extracted Payment Details (Debug Mode)</h4>';
    echo '<strong>Method:</strong> ' . $payment_details['method'] . '<br>';
    echo '<strong>Title:</strong> ' . $payment_details['title'] . '<br>';
    echo '<strong>Card Last 4:</strong> ' . ($payment_details['card_last4'] ?: 'N/A') . '<br>';
    echo '<strong>Card Brand:</strong> ' . ($payment_details['card_brand'] ?: 'N/A') . '<br>';
    echo '<strong>Card Expiry:</strong> ' . ($payment_details['card_expiry'] ?: 'N/A') . '<br>';
    echo '<strong>Transaction ID:</strong> ' . ($payment_details['transaction_id'] ?: 'N/A') . '<br>';
    echo '<strong>Additional Details:</strong><br>';
    foreach ( $payment_details['raw_details'] as $key => $value ) {
        if ( $value ) {
            echo '&nbsp;&nbsp;• ' . ucwords(str_replace('_', ' ', $key)) . ': ' . $value . '<br>';
        }
    }
    echo '</div>';
}

/**
 * Format card brand name for display
 * 
 * @param string $brand Raw card brand from payment gateway
 * @return string Formatted card brand name
 */
function format_card_brand( $brand ) {
    if ( empty($brand) ) {
        return '';
    }
    
    $brand_map = [
        'visa' => 'Visa',
        'mastercard' => 'Mastercard',
        'amex' => 'American Express',
        'discover' => 'Discover',
        'diners' => 'Diners Club',
        'jcb' => 'JCB',
        'unionpay' => 'UnionPay'
    ];
    
    return $brand_map[strtolower($brand)] ?? ucfirst($brand);
}

/**
 * Get card brand logo HTML using images
 * 
 * @param string $brand Raw card brand from payment gateway
 * @param string $size Logo size (small, medium, large)
 * @return string HTML for card brand logo
 */
function get_card_brand_logo( $brand, $size = 'small' ) {
    if ( empty($brand) ) {
        return '';
    }
    
    $brand = strtolower($brand);
    $size_class = 'card-logo-' . $size;
    
    // Card brand logo URLs (using reliable CDN sources)
    $logo_urls = [
        'visa' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png',
        'mastercard' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/2560px-Mastercard-logo.svg.png',
        'amex' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/American_Express_logo.svg/2560px-American_Express_logo.svg.png',
        'discover' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/57/Discover_Card_logo.svg/2560px-Discover_Card_logo.svg.png',
        'diners' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/Diners_Club_International_logo.svg/2560px-Diners_Club_International_logo.svg.png',
        'jcb' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/JCB_logo.svg/2560px-JCB_logo.svg.png',
        'unionpay' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/UnionPay_logo.svg/2560px-UnionPay_logo.svg.png'
    ];
    
    // Alternative: Use local assets if available
    $local_logos = [
        'visa' => get_stylesheet_directory_uri() . '/assets/images/card-logos/visa.png',
        'mastercard' => get_stylesheet_directory_uri() . '/assets/images/card-logos/mastercard.png',
        'amex' => get_stylesheet_directory_uri() . '/assets/images/card-logos/amex.png',
        'discover' => get_stylesheet_directory_uri() . '/assets/images/card-logos/discover.png',
        'diners' => get_stylesheet_directory_uri() . '/assets/images/card-logos/diners.png',
        'jcb' => get_stylesheet_directory_uri() . '/assets/images/card-logos/jcb.png',
        'unionpay' => get_stylesheet_directory_uri() . '/assets/images/card-logos/unionpay.png'
    ];
    
    // Check if local logo exists, otherwise use CDN
    $logo_url = '';
    if ( isset($local_logos[$brand]) && file_exists(get_stylesheet_directory() . '/assets/images/card-logos/' . $brand . '.png') ) {
        $logo_url = $local_logos[$brand];
    } elseif ( isset($logo_urls[$brand]) ) {
        $logo_url = $logo_urls[$brand];
    }
    
    if ( $logo_url ) {
        return '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(ucfirst($brand)) . '" class="' . $size_class . '" loading="lazy" />';
    }
    
    return '';
}

/**
 * Get card brand logo with fallback text
 * 
 * @param string $brand Raw card brand from payment gateway
 * @param string $size Logo size (small, medium, large)
 * @return string HTML for card brand logo with fallback
 */
function get_card_brand_display( $brand, $size = 'small' ) {
    if ( empty($brand) ) {
        return '';
    }
    
    $logo = get_card_brand_logo( $brand, $size );
    $text = format_card_brand( $brand );
    
    if ( $logo ) {
        return '<span class="card-brand-display">' . $logo . '</span>';
    }
    
    return '<span class="card-brand-text-only">' . $text . '</span>';
}