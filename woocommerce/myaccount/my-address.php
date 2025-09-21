<?php
/**
 * My Addresses - Custom Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing' => __('Billing address', 'woocommerce'),
            'shipping' => __('Shipping address', 'woocommerce'),
        ),
        $customer_id
    );
} else {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing' => __('Billing address', 'woocommerce'),
        ),
        $customer_id
    );
}
?>

<div class="nxt-addresses-page">
    <!-- Page Header -->
    <div class="nxt-addresses-header">
        <h1 class="nxt-page-title"><?php esc_html_e('Your address', 'hello-elementor-child'); ?></h1>
        <button type="button" class="nxt-btn nxt-btn-primary nxt-add-address-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12 2.75C12.6904 2.75 13.25 3.30964 13.25 4V10.75H20C20.6904 10.75 21.25 11.3096 21.25 12C21.25 12.6904 20.6904 13.25 20 13.25H13.25V20C13.25 20.6904 12.6904 21.25 12 21.25C11.3096 21.25 10.75 20.6904 10.75 20V13.25H4C3.30964 13.25 2.75 12.6904 2.75 12C2.75 11.3096 3.30964 10.75 4 10.75H10.75V4C10.75 3.30964 11.3096 2.75 12 2.75Z"
                    fill="white" />
            </svg>

            <?php esc_html_e('Add', 'hello-elementor-child'); ?>
        </button>
    </div>

    <!-- Addresses List -->
    <div class="nxt-addresses-list">
        <?php
        $all_custom_addresses = nxt_get_all_custom_addresses($customer_id);
        if (!empty($all_custom_addresses)): ?>
            <?php foreach ($all_custom_addresses as $address_id => $address_data): ?>
                <div class="nxt-address-card">
                    <div class="nxt-address-header">
                        <div class="nxt-address-type">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.3594 18.5193C11.4608 18.5714 12.0032 18.8254 12.0032 18.8254C12.0032 18.8254 12.5455 18.5714 12.647 18.5193C12.8497 18.415 13.1361 18.2611 13.4783 18.059C14.161 17.6559 15.0743 17.0561 15.9913 16.2702C17.8029 14.7174 19.7532 12.3276 19.7532 9.20073C19.7532 4.83013 16.3032 1.25073 12.0032 1.25073C7.70319 1.25073 4.25317 4.83013 4.25317 9.20073C4.25317 12.3276 6.2035 14.7174 8.01508 16.2702C8.93202 17.0561 9.84536 17.6559 10.5281 18.059C10.8703 18.2611 11.1566 18.415 11.3594 18.5193ZM12.0032 12.0007C13.66 12.0007 15.0032 10.6576 15.0032 9.00073C15.0032 7.34388 13.66 6.00073 12.0032 6.00073C10.3463 6.00073 9.00317 7.34388 9.00317 9.00073C9.00317 10.6576 10.3463 12.0007 12.0032 12.0007Z"
                                    fill="black" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.20775 19.9773C9.12741 20.4371 10.4679 20.7504 12.0032 20.7504C13.5384 20.7504 14.8789 20.4371 15.7986 19.9773C16.7781 19.4875 17.0032 18.996 17.0032 18.7504H19.0032C19.0032 20.1616 17.8851 21.1701 16.693 21.7661C15.4411 22.3921 13.7816 22.7504 12.0032 22.7504C10.2247 22.7504 8.56522 22.3921 7.31332 21.7661C6.12123 21.1701 5.00317 20.1616 5.00317 18.7504H7.00317C7.00317 18.996 7.22826 19.4875 8.20775 19.9773Z"
                                    fill="black" />
                            </svg>

                            <span class="nxt-address-label"><?php echo esc_html($address_data['address_title']); ?></span>
                            <?php if (isset($address_data['is_default']) && $address_data['is_default']): ?>
                                <span class="nxt-default-badge"
                                    title="<?php echo esc_attr(sprintf(__('Default %s address', 'hello-elementor-child'), $address_data['default_type'])); ?>">
                                    <?php echo esc_html(ucfirst($address_data['default_type'])); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="nxt-address-actions">
                            <?php if (isset($address_data['is_default']) && $address_data['is_default']): ?>
                                <button type="button" class="nxt-address-action nxt-delete-default-address"
                                    data-address-type="<?php echo esc_attr($address_data['default_type']); ?>"
                                    title="<?php esc_attr_e('Delete address', 'hello-elementor-child'); ?>">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13 3.66663L12.5868 10.35C12.4813 12.0576 12.4285 12.9114 12.0005 13.5252C11.7889 13.8287 11.5164 14.0848 11.2005 14.2773C10.5614 14.6666 9.70599 14.6666 7.99516 14.6666C6.28208 14.6666 5.42554 14.6666 4.78604 14.2766C4.46987 14.0837 4.19733 13.8272 3.98579 13.5232C3.55792 12.9084 3.5063 12.0534 3.40307 10.3434L3 3.66663"
                                            stroke="#141B34" stroke-linecap="round" />
                                        <path
                                            d="M2 3.66671H14M10.7038 3.66671L10.2487 2.72786C9.94638 2.10421 9.79522 1.79239 9.53448 1.59791C9.47664 1.55477 9.4154 1.5164 9.35135 1.48317C9.06261 1.33337 8.71608 1.33337 8.02302 1.33337C7.31255 1.33337 6.95732 1.33337 6.66379 1.48945C6.59873 1.52405 6.53666 1.56397 6.4782 1.60882C6.21443 1.81117 6.06709 2.13441 5.7724 2.78088L5.36862 3.66671"
                                            stroke="#141B34" stroke-linecap="round" />
                                        <path d="M6.33331 11L6.33331 7" stroke="#141B34" stroke-linecap="round" />
                                        <path d="M9.66669 11L9.66669 7" stroke="#141B34" stroke-linecap="round" />
                                    </svg>
                                </button>
                                <!-- Default address - edit via WooCommerce -->
                                <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $address_data['default_type'])); ?>"
                                    class="nxt-address-action nxt-edit-default-address"
                                    title="<?php esc_attr_e('Edit address', 'hello-elementor-child'); ?>">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_1_5768)">
                                            <path
                                                d="M9.38248 2.59034C9.87927 2.0521 10.1277 1.78297 10.3916 1.62599C11.0285 1.24721 11.8127 1.23543 12.4602 1.59492C12.7286 1.74391 12.9846 2.00545 13.4967 2.52855C14.0088 3.05164 14.2648 3.31319 14.4106 3.58732C14.7626 4.24879 14.751 5.0499 14.3802 5.70051C14.2266 5.97015 13.9631 6.22389 13.4362 6.73138L7.16709 12.7696C6.16859 13.7313 5.66934 14.2122 5.04538 14.4559C4.42142 14.6996 3.73548 14.6816 2.36359 14.6458L2.17693 14.6409C1.75928 14.63 1.55046 14.6245 1.42907 14.4867C1.30768 14.349 1.32425 14.1363 1.3574 13.7108L1.37539 13.4798C1.46868 12.2824 1.51533 11.6837 1.74915 11.1455C1.98297 10.6073 2.3863 10.1704 3.19296 9.29637L9.38248 2.59034Z"
                                                stroke="#141B34" stroke-linejoin="round" />
                                            <path d="M8.66666 2.66663L13.3333 7.33329" stroke="#141B34" stroke-linejoin="round" />
                                            <path d="M9.33334 14.6666L14.6667 14.6666" stroke="#141B34" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1_5768">
                                                <rect width="16" height="16" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>


                                </a>
                            <?php else: ?>
                                <!-- Custom address - full actions -->
                                <button type="button" class="nxt-address-action nxt-set-default-billing"
                                    data-address-id="<?php echo esc_attr($address_data['custom_id']); ?>"
                                    title="<?php esc_attr_e('Set as default billing', 'hello-elementor-child'); ?>">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8 1.33333L10.472 5.52733L15.3333 6.33333L12.1667 9.66667L12.944 14.6667L8 12.472L3.056 14.6667L3.83333 9.66667L0.666667 6.33333L5.528 5.52733L8 1.33333Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <button type="button" class="nxt-address-action nxt-set-default-shipping"
                                    data-address-id="<?php echo esc_attr($address_data['custom_id']); ?>"
                                    title="<?php esc_attr_e('Set as default shipping', 'hello-elementor-child'); ?>">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.3703 18.296C19.9893 18.915 19.9893 19.918 19.3703 20.536C18.7513 21.155 17.7483 21.155 17.1303 20.536C16.5113 19.917 16.5113 18.914 17.1303 18.296C17.7493 17.677 18.7523 17.677 19.3703 18.296"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path
                                            d="M8.37038 18.296C8.98938 18.915 8.98938 19.918 8.37038 20.536C7.75138 21.155 6.74838 21.155 6.13038 20.536C5.51238 19.917 5.51138 18.914 6.13038 18.296C6.74938 17.678 7.75138 17.677 8.37038 18.296"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path d="M10.6665 4H14.6665C15.2185 4 15.6665 4.448 15.6665 5V15H2.6665" stroke="#141B34"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M5.6665 19.416H3.6665C3.1145 19.416 2.6665 18.968 2.6665 18.416V13"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path
                                            d="M15.6665 7H19.9895C20.3985 7 20.7665 7.249 20.9175 7.629L22.5235 11.643C22.6175 11.879 22.6665 12.131 22.6665 12.385V18.333C22.6665 18.885 22.2185 19.333 21.6665 19.333H19.8355"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path d="M16.6664 19.42H8.83643" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M22.6665 14H18.6665V10H21.8665" stroke="#141B34" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M2.6665 4H7.6665" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M2.6665 7H5.6665" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M3.6665 10H2.6665" stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                                <button type="button" class="nxt-address-action nxt-delete-address"
                                    data-address-id="<?php echo esc_attr($address_data['custom_id']); ?>"
                                    title="<?php esc_attr_e('Delete address', 'hello-elementor-child'); ?>">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13 3.66669L12.5868 10.3501C12.4813 12.0577 12.4285 12.9114 12.0005 13.5253C11.7889 13.8287 11.5164 14.0849 11.2005 14.2774C10.5614 14.6667 9.70599 14.6667 7.99516 14.6667C6.28208 14.6667 5.42554 14.6667 4.78604 14.2766C4.46987 14.0838 4.19733 13.8272 3.98579 13.5232C3.55792 12.9084 3.5063 12.0534 3.40307 10.3435L3 3.66669"
                                            stroke="#141B34" stroke-linecap="round" />
                                        <path
                                            d="M2 3.66665H14M10.7038 3.66665L10.2487 2.7278C9.94638 2.10415 9.79522 1.79233 9.53448 1.59785C9.47664 1.55471 9.4154 1.51634 9.35135 1.48311C9.06261 1.33331 8.71608 1.33331 8.02302 1.33331C7.31255 1.33331 6.95732 1.33331 6.66379 1.48939C6.59873 1.52398 6.53666 1.56391 6.4782 1.60876C6.21443 1.81111 6.06709 2.13435 5.7724 2.78082L5.36862 3.66665"
                                            stroke="#141B34" stroke-linecap="round" />
                                        <path d="M6.33331 11L6.33331 7" stroke="#141B34" stroke-linecap="round" />
                                        <path d="M9.66669 11L9.66669 7" stroke="#141B34" stroke-linecap="round" />
                                    </svg>

                                </button>
                                <button type="button" class="nxt-address-action nxt-edit-custom-address"
                                    data-address-id="<?php echo esc_attr($address_data['custom_id']); ?>"
                                    title="<?php esc_attr_e('Edit address', 'hello-elementor-child'); ?>">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_1_5820)">
                                            <path
                                                d="M9.38248 2.59028C9.87927 2.05203 10.1277 1.78291 10.3916 1.62593C11.0285 1.24715 11.8127 1.23537 12.4602 1.59486C12.7286 1.74385 12.9846 2.00539 13.4967 2.52849C14.0088 3.05158 14.2648 3.31313 14.4106 3.58726C14.7626 4.24873 14.751 5.04984 14.3802 5.70045C14.2266 5.97008 13.9631 6.22383 13.4362 6.73132L7.16709 12.7695C6.16859 13.7312 5.66934 14.2121 5.04538 14.4558C4.42142 14.6995 3.73548 14.6816 2.36359 14.6457L2.17693 14.6408C1.75928 14.6299 1.55046 14.6244 1.42907 14.4867C1.30768 14.3489 1.32425 14.1362 1.3574 13.7108L1.37539 13.4798C1.46868 12.2823 1.51533 11.6836 1.74915 11.1455C1.98297 10.6073 2.3863 10.1703 3.19296 9.29631L9.38248 2.59028Z"
                                                stroke="#141B34" stroke-linejoin="round" />
                                            <path d="M8.66666 2.66669L13.3333 7.33335" stroke="#141B34" stroke-linejoin="round" />
                                            <path d="M9.33334 14.6667L14.6667 14.6667" stroke="#141B34" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1_5820">
                                                <rect width="16" height="16" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="nxt-address-content">
                        <div class="nxt-address-field">
                            <span class="nxt-field-label "><?php esc_html_e('Recipient', 'hello-elementor-child'); ?></span>
                            <span class="nxt-field-value nxt-field-label-recipient"><?php echo esc_html(trim($address_data['first_name'] . ' ' . $address_data['last_name'])); ?></span>
                        </div>

                        <div class="nxt-address-field">
                            <span class="nxt-field-label"><?php esc_html_e('Phone number', 'hello-elementor-child'); ?></span>
                            <span class="nxt-field-value"><?php echo esc_html($address_data['phone']); ?></span>
                        </div>

                        <div class="nxt-address-field">
                            <span class="nxt-field-label"><?php esc_html_e('Building type', 'hello-elementor-child'); ?></span>
                            <span
                                class="nxt-field-value"><?php echo esc_html($address_data['building_type'] ?: __('House', 'hello-elementor-child')); ?></span>
                        </div>

                        <div class="nxt-address-field">
                            <span class="nxt-field-label"><?php esc_html_e('Address', 'hello-elementor-child'); ?></span>
                            <span class="nxt-field-value"><?php echo wp_kses_post($address_data['formatted']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="nxt-address-card nxt-address-empty">
                <div class="nxt-address-content">
                    <p><?php printf(esc_html__('You have not set up any addresses yet. %s', 'hello-elementor-child'), '<a href="#" class="nxt-add-address-btn">' . esc_html__('Add address', 'hello-elementor-child') . '</a>'); ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>