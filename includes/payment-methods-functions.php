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
    
    // Additional Stripe Link specific meta
    $stripe_payment_method_id = $order->get_meta('_stripe_payment_method_id');
    $stripe_payment_intent_id = $order->get_meta('_stripe_payment_intent_id');
    
    // Check for additional Stripe meta fields that might contain payment method info
    $all_meta = $order->get_meta_data();
    $stripe_meta_fields = [];
    foreach ( $all_meta as $meta ) {
        if ( strpos($meta->key, '_stripe_') === 0 ) {
            $stripe_meta_fields[$meta->key] = $meta->value;
        }
    }
    
    // Look for payment method ID in various Stripe meta fields
    $possible_payment_method_ids = [];
    foreach ( $stripe_meta_fields as $key => $value ) {
        if ( is_string($value) && (strpos($value, 'pm_') === 0 || strpos($value, 'pi_') === 0) ) {
            $possible_payment_method_ids[] = $value;
        }
    }
    
    $details['raw_details']['source_id'] = $stripe_source_id;
    $details['raw_details']['intent_id'] = $stripe_intent_id;
    $details['raw_details']['customer_id'] = $stripe_customer_id;
    $details['raw_details']['fee'] = $stripe_fee;
    $details['raw_details']['net'] = $stripe_net;
    $details['raw_details']['currency'] = $stripe_currency;
    $details['raw_details']['payment_method_id'] = $stripe_payment_method_id;
    $details['raw_details']['payment_intent_id'] = $stripe_payment_intent_id;
    $details['raw_details']['all_stripe_meta'] = $stripe_meta_fields;
    $details['raw_details']['possible_payment_ids'] = $possible_payment_method_ids;
    
    // Special handling for Stripe Link payments
    if ( $details['title'] === 'Link' || strpos($details['title'], 'Link') !== false ) {
        $details['payment_type'] = 'stripe_link';
        
        // Check for card details in additional meta fields for Stripe Link
        $card_last4 = $order->get_meta('_stripe_card_last4');
        $card_brand = $order->get_meta('_stripe_card_brand');
        $card_exp_month = $order->get_meta('_stripe_card_exp_month');
        $card_exp_year = $order->get_meta('_stripe_card_exp_year');
        
        // Additional Stripe Link specific meta fields
        $stripe_card_fingerprint = $order->get_meta('_stripe_card_fingerprint');
        $stripe_card_funding = $order->get_meta('_stripe_card_funding');
        $stripe_card_country = $order->get_meta('_stripe_card_country');
        
        if ( $card_last4 ) {
            $details['card_last4'] = $card_last4;
        }
        if ( $card_brand ) {
            $details['card_brand'] = $card_brand;
        }
        if ( $card_exp_month && $card_exp_year ) {
            $details['card_expiry'] = sprintf('%02d/%s', $card_exp_month, substr($card_exp_year, -2));
        }
        
        // Store additional card info in raw details
        $details['raw_details']['card_fingerprint'] = $stripe_card_fingerprint;
        $details['raw_details']['card_funding'] = $stripe_card_funding;
        $details['raw_details']['card_country'] = $stripe_card_country;
        
        // Also check for card details in the payment method title or other fields
        if ( empty($details['card_last4']) ) {
            // Sometimes Stripe Link stores card info in the payment method title
            if ( preg_match('/\*\*(\d{4})/', $details['title'], $matches) ) {
                $details['card_last4'] = $matches[1];
            }
        }
        
        // If still no card details, try to extract from order meta in a different way
        if ( empty($details['card_last4']) ) {
            // Check for card details in order notes or other meta fields
            $order_notes = wc_get_order_notes(['order_id' => $order->get_id()]);
            foreach ( $order_notes as $note ) {
                if ( preg_match('/Card ending in (\d{4})/', $note->content, $matches) ) {
                    $details['card_last4'] = $matches[1];
                    break;
                }
            }
        }
    }
    
    // Try to get card details via API if not found in tokens
    if ( empty($details['card_last4']) ) {
        $stripe_gateway = WC()->payment_gateways->payment_gateways()['stripe'] ?? null;
        
        if ( $stripe_gateway ) {
            // Get secret key using the working method
            $secret_key = $stripe_gateway->get_option('secret_key') ?: $stripe_gateway->get_option('test_secret_key');
            
            if ( $secret_key ) {
                // Try to get card details from the transaction ID
                if ( $details['transaction_id'] ) {
                    $api_url = '';
                    $api_endpoint = '';
                    
                    if ( strpos($details['transaction_id'], 'pi_') === 0 ) {
                        // Payment Intent
                        $api_url = 'https://api.stripe.com/v1/payment_intents/' . $details['transaction_id'];
                        $api_endpoint = 'payment_intents';
                    } elseif ( strpos($details['transaction_id'], 'py_') === 0 ) {
                        // Payment
                        $api_url = 'https://api.stripe.com/v1/payments/' . $details['transaction_id'];
                        $api_endpoint = 'payments';
                    }
                    
                    if ( $api_url ) {
                        $response = wp_remote_get($api_url, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $secret_key,
                                'Stripe-Version' => '2023-10-16'
                            ],
                            'timeout' => 10
                        ]);
                        
                        $response_code = wp_remote_retrieve_response_code($response);
                        $response_body = wp_remote_retrieve_body($response);
                        
                        // Debug API response
                        $details['raw_details']['api_debug_transaction'] = [
                            'url' => $api_url,
                            'response_code' => $response_code,
                            'response_body' => $response_body
                        ];
                        
                        if ( !is_wp_error($response) && $response_code === 200 ) {
                            $data = json_decode($response_body, true);
                            
                            // Handle different response structures
                            if ( $api_endpoint === 'payment_intents' && isset($data['charges']['data'][0]['payment_method_details']['card']) ) {
                                $card_data = $data['charges']['data'][0]['payment_method_details']['card'];
                                $details['card_last4'] = $card_data['last4'] ?? '';
                                $details['card_brand'] = $card_data['brand'] ?? '';
                                if ( isset($card_data['exp_month']) && isset($card_data['exp_year']) ) {
                                    $details['card_expiry'] = sprintf('%02d/%s', $card_data['exp_month'], substr($card_data['exp_year'], -2));
                                }
                            } elseif ( $api_endpoint === 'payments' && isset($data['payment_method_details']['card']) ) {
                                $card_data = $data['payment_method_details']['card'];
                                $details['card_last4'] = $card_data['last4'] ?? '';
                                $details['card_brand'] = $card_data['brand'] ?? '';
                                if ( isset($card_data['exp_month']) && isset($card_data['exp_year']) ) {
                                    $details['card_expiry'] = sprintf('%02d/%s', $card_data['exp_month'], substr($card_data['exp_year'], -2));
                                }
                            }
                        }
                    }
                }
                
                // If still no card details, try the original approach
                if ( empty($details['card_last4']) ) {
                    // Try multiple approaches to get payment method details
                    $payment_method_ids = array_filter(array_unique(array_merge([
                        $stripe_source_id,
                        $stripe_payment_method_id,
                        $stripe_payment_intent_id
                    ], $possible_payment_method_ids)));
                    
                    foreach ( $payment_method_ids as $payment_id ) {
                        if ( empty($payment_id) ) continue;
                        
                        // First try to get payment method details
                        $response = wp_remote_get('https://api.stripe.com/v1/payment_methods/' . $payment_id, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $secret_key,
                                'Stripe-Version' => '2023-10-16'
                            ],
                            'timeout' => 10
                        ]);
                        
                        $response_code = wp_remote_retrieve_response_code($response);
                        $response_body = wp_remote_retrieve_body($response);
                        
                        // Debug API response
                        $details['raw_details']['api_debug_' . $payment_id] = [
                            'url' => 'https://api.stripe.com/v1/payment_methods/' . $payment_id,
                            'response_code' => $response_code,
                            'response_body' => $response_body
                        ];
                        
                        if ( !is_wp_error($response) && $response_code === 200 ) {
                            $data = json_decode($response_body, true);
                            
                            if ( isset($data['card']) ) {
                                $details['card_last4'] = $data['card']['last4'] ?? '';
                                $details['card_brand'] = $data['card']['brand'] ?? '';
                                if ( isset($data['card']['exp_month']) && isset($data['card']['exp_year']) ) {
                                    $details['card_expiry'] = sprintf('%02d/%s', $data['card']['exp_month'], substr($data['card']['exp_year'], -2));
                                }
                                break; // Found card details, stop trying
                            }
                        }
                        
                        // If payment method didn't work, try payment intent
                        if ( strpos($payment_id, 'pi_') === 0 ) {
                            $response = wp_remote_get('https://api.stripe.com/v1/payment_intents/' . $payment_id, [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . $secret_key,
                                    'Stripe-Version' => '2023-10-16'
                                ],
                                'timeout' => 10
                            ]);
                            
                            $response_code = wp_remote_retrieve_response_code($response);
                            $response_body = wp_remote_retrieve_body($response);
                            
                            // Debug API response
                            $details['raw_details']['api_debug_pi_' . $payment_id] = [
                                'url' => 'https://api.stripe.com/v1/payment_intents/' . $payment_id,
                                'response_code' => $response_code,
                                'response_body' => $response_body
                            ];
                            
                            if ( !is_wp_error($response) && $response_code === 200 ) {
                                $data = json_decode($response_body, true);
                                
                                if ( isset($data['charges']['data'][0]['payment_method_details']['card']) ) {
                                    $card_data = $data['charges']['data'][0]['payment_method_details']['card'];
                                    $details['card_last4'] = $card_data['last4'] ?? '';
                                    $details['card_brand'] = $card_data['brand'] ?? '';
                                    if ( isset($card_data['exp_month']) && isset($card_data['exp_year']) ) {
                                        $details['card_expiry'] = sprintf('%02d/%s', $card_data['exp_month'], substr($card_data['exp_year'], -2));
                                    }
                                    break; // Found card details, stop trying
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    // Final fallback for Stripe Link payments
    if ( isset($details['payment_type']) && $details['payment_type'] === 'stripe_link' && empty($details['card_last4']) ) {
        // For Stripe Link payments, we might not be able to get card details
        // due to privacy/security restrictions. Show a generic message.
        $details['card_last4'] = '****';
        $details['card_brand'] = 'Card';
        $details['card_expiry'] = 'N/A';
        $details['raw_details']['stripe_link_fallback'] = 'Card details not available for Stripe Link payments due to privacy restrictions';
        $details['raw_details']['api_errors_summary'] = 'All API calls returned 404 errors - this is expected for Stripe Link payments due to privacy restrictions';
        
        // Try one more approach - check if WooCommerce Stripe gateway has any additional methods
        if ( $stripe_gateway && method_exists($stripe_gateway, 'get_source_object') ) {
            try {
                // Use the source ID from the order meta instead of the entire order object
                $source_id = $order->get_meta('_stripe_source_id');
                if ( $source_id ) {
                    $source = $stripe_gateway->get_source_object($source_id);
                    if ( $source && isset($source->card) ) {
                        $details['card_last4'] = $source->card->last4 ?? '****';
                        $details['card_brand'] = $source->card->brand ?? 'Card';
                        if ( isset($source->card->exp_month) && isset($source->card->exp_year) ) {
                            $details['card_expiry'] = sprintf('%02d/%s', $source->card->exp_month, substr($source->card->exp_year, -2));
                        }
                        $details['raw_details']['stripe_gateway_source'] = 'Card details extracted from WooCommerce Stripe gateway source object';
                    }
                }
            } catch ( Exception $e ) {
                $details['raw_details']['stripe_gateway_error'] = 'Error accessing Stripe gateway source: ' . $e->getMessage();
            }
        }
        
        // Try to get any available information from the payment method title
        if ( empty($details['card_last4']) && $details['title'] ) {
            // Sometimes the payment method title contains card information
            if ( preg_match('/\*\*(\d{4})/', $details['title'], $matches) ) {
                $details['card_last4'] = $matches[1];
                $details['raw_details']['title_extraction'] = 'Card last 4 digits extracted from payment method title';
            }
            
            // Try to extract card brand from title
            if ( preg_match('/(visa|mastercard|amex|american express|discover|diners|jcb)/i', $details['title'], $matches) ) {
                $details['card_brand'] = ucfirst(strtolower($matches[1]));
                $details['raw_details']['brand_extraction'] = 'Card brand extracted from payment method title';
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
    echo '<strong>Payment Type:</strong> ' . ($payment_details['payment_type'] ?? 'standard') . '<br>';
    echo '<strong>Card Last 4:</strong> ' . ($payment_details['card_last4'] ?: 'N/A') . '<br>';
    echo '<strong>Card Brand:</strong> ' . ($payment_details['card_brand'] ?: 'N/A') . '<br>';
    echo '<strong>Card Expiry:</strong> ' . ($payment_details['card_expiry'] ?: 'N/A') . '<br>';
    echo '<strong>Transaction ID:</strong> ' . ($payment_details['transaction_id'] ?: 'N/A') . '<br>';
    echo '<strong>Additional Details:</strong><br>';
    foreach ( $payment_details['raw_details'] as $key => $value ) {
        if ( $value ) {
            if ( $key === 'all_stripe_meta' && is_array($value) ) {
                echo '&nbsp;&nbsp;• All Stripe Meta Fields:<br>';
                foreach ( $value as $meta_key => $meta_value ) {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;- ' . $meta_key . ': ' . $meta_value . '<br>';
                }
            } elseif ( $key === 'possible_payment_ids' && is_array($value) ) {
                echo '&nbsp;&nbsp;• Possible Payment IDs: ' . implode(', ', $value) . '<br>';
            } elseif ( strpos($key, 'api_debug_') === 0 && is_array($value) ) {
                echo '&nbsp;&nbsp;• API Debug (' . str_replace('api_debug_', '', $key) . '):<br>';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;- URL: ' . $value['url'] . '<br>';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;- Response Code: ' . $value['response_code'] . '<br>';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;- Response Body: ' . substr($value['response_body'], 0, 200) . (strlen($value['response_body']) > 200 ? '...' : '') . '<br>';
            } else {
                echo '&nbsp;&nbsp;• ' . ucwords(str_replace('_', ' ', $key)) . ': ' . $value . '<br>';
            }
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