<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

defined('ABSPATH') || exit;

/**
 * Hook - woocommerce_before_edit_account_form.
 *
 * @since 2.6.0
 */

do_action('woocommerce_before_edit_account_form'); ?>

<div class="nxt-edit-account-page">
    <div class="nxt-edit-account-content">

        <!-- Profile Information Section -->
        <div class="nxt-profile-section">
            <h2 class="nxt-section-title">Complete your data</h2>

            <!-- Profile Picture Upload -->
            <div class="nxt-profile-picture-section">
                <div class="nxt-profile-picture">
                    <div class="nxt-profile-avatar">
                        <?php
                        $user_id = get_current_user_id();
                        $avatar_id = get_user_meta($user_id, 'user_avatar', true);
                        
                        if ($avatar_id) {
                            $avatar_url = wp_get_attachment_image_url($avatar_id, 'thumbnail');
                        } else {
                            $avatar_url = get_avatar_url($user_id, array('size' => 120));
                        }
                        ?>
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="Profile Picture" class="nxt-avatar-image" id="nxt-avatar-display">
                    </div>
                    <div class="nxt-profile-picture-info">
                        <p class="nxt-upload-text">Add your profile picture</p>
                        <p class="nxt-upload-description">You can add or remove your profile picture as you like.</p>
                    </div>
                    <div class="nxt-profile-picture-actions">
                        <input type="file" id="avatar-file-input" accept="image/*" style="display: none;">
                        <button type="button" class="nxt-upload-btn nxt-profileBtn" id="nxt-upload-avatar">Upload
                        </button>
                        <div class="nxt-upload-loading" id="nxt-upload-loading" style="display: none;">
                            <div class="nxt-loading-spinner"></div>
                            <span>Uploading...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?>>

                <?php do_action('woocommerce_edit_account_form_start'); ?>

                <div class="nxt-form-fields">
                    <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                        <label for="account_first_name"><?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;<span
                                class="required" aria-hidden="true">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                            name="account_first_name" id="account_first_name" autocomplete="given-name"
                            value="<?php echo esc_attr($user->first_name); ?>" aria-required="true" />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                        <label for="account_last_name"><?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;<span
                                class="required" aria-hidden="true">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                            name="account_last_name" id="account_last_name" autocomplete="family-name"
                            value="<?php echo esc_attr($user->last_name); ?>" aria-required="true" />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="account_display_name"><?php esc_html_e('Display name', 'woocommerce'); ?>&nbsp;<span
                                class="required" aria-hidden="true">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                            name="account_display_name" id="account_display_name"
                            aria-describedby="account_display_name_description"
                            value="<?php echo esc_attr($user->display_name); ?>" aria-required="true" /> <span
                            id="account_display_name_description"><em><?php esc_html_e('This will be how your name will be displayed in the account section and in reviews', 'woocommerce'); ?></em></span>
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide"
                        style="display: none;">
                        <label for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span
                                class="required" aria-hidden="true">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--email input-text"
                            name="account_email" id="account_email" autocomplete="email"
                            value="<?php echo esc_attr($user->user_email); ?>" aria-required="true" />
                    </p>

                    <!-- Country/Region -->
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="billing_country"><?php esc_html_e('Country / Region', 'woocommerce'); ?>&nbsp;<span
                                class="required">*</span></label>
                        <select name="billing_country" id="billing_country"
                            class="woocommerce-Select woocommerce-Input--select country_to_state country_select"
                            autocomplete="country" data-placeholder="<?php esc_attr_e('Select', 'woocommerce'); ?>">
                            <option value=""><?php esc_html_e('Select', 'woocommerce'); ?></option>
                            <?php
                            $countries = WC()->countries->get_countries();
                            $selected_country = get_user_meta($user_id, 'billing_country', true);
                            foreach ($countries as $key => $value) {
                                echo '<option value="' . esc_attr($key) . '"' . selected($selected_country, $key, false) . '>' . esc_html($value) . '</option>';
                            }
                            ?>
                        </select>
                    </p>

                    <!-- Phone Number -->
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="billing_phone"><?php esc_html_e('Phone', 'woocommerce'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <div class="nxt-phone-input-wrapper">
                        <select name="billing_phone_country_code" id="billing_phone_country_code"
                            class="woocommerce-Select woocommerce-Input--select phone-country-code">
                            <?php
                            $selected_country_code = get_user_meta($user_id, 'billing_phone_country_code', true);
                            $country_codes = nxt_get_country_phone_codes();
                            foreach ($country_codes as $code => $country) {
                                echo '<option value="' . esc_attr($code) . '"' . selected($selected_country_code, $code, false) . '>' . esc_html($code . ' ' . $country) . '</option>';
                            }
                            ?>
                        </select>
                        <input type="tel" class="woocommerce-Input woocommerce-Input--text input-text"
                            name="billing_phone" id="billing_phone" autocomplete="tel"
                            value="<?php echo esc_attr(get_user_meta($user_id, 'billing_phone', true)); ?>"
                            placeholder="000 000 000" />
                    </div>
                    </p>



                </div>

                <p class="nxt-save-info-btn">
                    <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
                    <button type="submit" class="woocommerce-Button button" name="save_account_details"
                        value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save my information', 'woocommerce'); ?></button>
                    <input type="hidden" name="action" value="save_account_details" />
                </p>

                <?php do_action('woocommerce_edit_account_form'); ?>

            </form>
        </div>

        <!-- Login Information Section -->
        <div class="nxt-login-section">
            <div class="nxt_changePass_wrapper">
                <h2 class="nxt-section-title">Login information</h2>

                <div class="nxt-login-info">
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span
                                class="required" aria-hidden="true">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--email input-text"
                            name="account_email" id="account_email" autocomplete="email"
                            value="<?php echo esc_attr($user->user_email); ?>" aria-required="true" disabled />
                    </p>

                    <button type="button" class="nxt-change-password-btn nxt-profileBtn" id="nxt-change-password-trigger">
                        <?php esc_html_e('Change Password', 'woocommerce'); ?>
                    </button>
                </div>

                <!-- Password Change Form (Hidden by default) -->
                <form class="woocommerce-ChangePasswordForm change-password" action="" method="post"
                    style="display: none;" id="nxt-password-change-form">
                    <?php wp_nonce_field('change_password', 'change-password-nonce'); ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label
                            for="password_current"><?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                            name="password_current" id="password_current" autocomplete="off" />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label
                            for="password_1"><?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                            name="password_1" id="password_1" autocomplete="off" />
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="password_2"><?php esc_html_e('Confirm new password', 'woocommerce'); ?></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                            name="password_2" id="password_2" autocomplete="off" />
                    </p>

                    <p class="nxt-change-password-actions">
                        <button type="submit" class="woocommerce-Button button nxt-profileBtn" name="change_password"
                            value="<?php esc_attr_e('Change password', 'woocommerce'); ?>"><?php esc_html_e('Change Password', 'woocommerce'); ?></button>
                    </p>
                </form>
            </div>
            <!-- Logout Section -->
            <div class="nxt-logout-section">
                <a href="<?php echo esc_url(wp_logout_url(wc_get_page_permalink('myaccount'))); ?>"
                    class="nxt-logout-link" id="nxt-logout-trigger">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V3.33333C2 2.97971 2.14048 2.64057 2.39052 2.39052C2.64057 2.14048 2.97971 2 3.33333 2H6"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M10 11.3333L14 7.33333L10 3.33333" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M14 7.33333H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    LOGOUT
                </a>
            </div>
        </div>


    </div>
</div>

<?php do_action('woocommerce_after_edit_account_form'); ?>

<style>
.nxt-upload-loading {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #666;
    font-size: 14px;
}

.nxt-loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007cba;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
    jQuery(document).ready(function ($) {
        // Simple avatar upload
        $('#nxt-upload-avatar').on('click', function () {
            $('#avatar-file-input').click();
        });

        $('#avatar-file-input').on('change', function () {
            var file = this.files[0];
            
            if (file) {
                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Please select an image file.');
                    return;
                }
                
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB.');
                    return;
                }
                
                // Show loading state
                $('#nxt-upload-avatar').hide();
                $('#nxt-upload-loading').show();
                
                // Upload file via AJAX
                var formData = new FormData();
                formData.append('action', 'nxt_upload_avatar');
                formData.append('avatar', file);
                formData.append('nonce', '<?php echo wp_create_nonce('nxt_upload_avatar'); ?>');
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Update the avatar image immediately
                            $('#nxt-avatar-display').attr('src', response.data.url);
                            
                            // Show success message briefly
                            $('#nxt-upload-loading span').text('Uploaded!');
                            setTimeout(function() {
                                $('#nxt-upload-loading').hide();
                                $('#nxt-upload-avatar').show();
                                $('#nxt-upload-loading span').text('Uploading...');
                            }, 1500);
                        } else {
                            alert('Error uploading avatar: ' + response.data);
                            $('#nxt-upload-loading').hide();
                            $('#nxt-upload-avatar').show();
                        }
                    },
                    error: function() {
                        alert('Error uploading avatar. Please try again.');
                        $('#nxt-upload-loading').hide();
                        $('#nxt-upload-avatar').show();
                    }
                });
            }
        });

        // Password change toggle
        $('#nxt-change-password-trigger').on('click', function () {
            $('#nxt-password-change-form').slideToggle();
            $(this).hide();
        });

        // Logout confirmation
        $('#nxt-logout-trigger').on('click', function (e) {
            e.preventDefault();

            NxtModal.confirm({
                title: 'Are you sure want to logout',
                content: 'Logging out will end your current session. Make sure you\'ve saved any important information. Do you wish to proceed with logging out?',
                buttons: [
                    {
                        id: 'nxt-logout-cancel',
                        text: 'No, Keep here',
                        class: 'nxt-btn nxt-btn-secondary',
                        callback: function () {
                            NxtModal.close();
                        }
                    },
                    {
                        id: 'nxt-logout-confirm',
                        text: 'Yes, Log Out',
                        class: 'nxt-btn nxt-btn-primary',
                        callback: function () {
                            window.location.href = $('#nxt-logout-trigger').attr('href');
                        }
                    }
                ]
            });
        });
    });
</script>