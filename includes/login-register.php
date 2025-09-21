<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// 1) Shortcode for custom Login form
function custom_wc_login_form_shortcode()
{
    if (is_user_logged_in()) {
        return '<p>' . esc_html__('You are already logged in.', 'hello-elementor-child') . '</p>';
    }
    ob_start();
    // Display notices (errors etc)
    wc_print_notices();

    // Use WooCommerce’s login form only
    woocommerce_login_form(
        array(
            'redirect' => wc_get_page_permalink('myaccount'),
            'hidden' => false
        )
    );

    return ob_get_clean();
}
add_shortcode('wc_login_form_custom', 'custom_wc_login_form_shortcode');


// 2) Shortcode for custom Register form
function custom_wc_register_form_shortcode()
{
    if (is_user_logged_in()) {
        return '<p>' . esc_html__('You already have an account.', 'hello-elementor-child') . '</p>';
    }
    ob_start();
    wc_print_notices();

    ?>
    <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

        <?php do_action('woocommerce_register_form_start'); ?>
        <div class="nxt-form_grid">

            <p class="form-row form-row-first">
                <label for="reg_first_name"><?php esc_html_e('First name', 'your-textdomain'); ?> <span
                        class="required">*</span></label>
                <input type="text" name="first_name" id="reg_first_name" autocomplete="given-name"
                    value="<?php echo !empty($_POST['first_name']) ? esc_attr(wp_unslash($_POST['first_name'])) : ''; ?>"
                    placeholder="<?php esc_attr_e('First name', 'your-textdomain'); ?>" />
            </p>

            <p class="form-row form-row-last">
                <label for="reg_last_name"><?php esc_html_e('Last name', 'your-textdomain'); ?> <span
                        class="required">*</span></label>
                <input type="text" name="last_name" id="reg_last_name" autocomplete="family-name"
                    value="<?php echo !empty($_POST['last_name']) ? esc_attr(wp_unslash($_POST['last_name'])) : ''; ?>"
                    placeholder="<?php esc_attr_e('Last name', 'your-textdomain'); ?>" />
            </p>

            <?php if ('no' === get_option('woocommerce_registration_generate_username')): ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required"
                            aria-hidden="true">*</span><span
                            class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                        id="reg_username" autocomplete="username"
                        value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"
                        required aria-required="true" /><?php // @codingStandardsIgnoreLine 
                                ?>
                </p>

            <?php endif; ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required"
                        aria-hidden="true">*</span><span
                        class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email"
                    autocomplete="email"
                    value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required
                    aria-required="true" /><?php // @codingStandardsIgnoreLine 
                        ?>
            </p>

            <?php if ('no' === get_option('woocommerce_registration_generate_password')): ?>

                <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required"
                            aria-hidden="true">*</span><span
                            class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                    <div class="password-input-wrapper">
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password"
                            id="reg_password" autocomplete="new-password" required aria-required="true"
                            aria-describedby="password-strength-indicator password-requirements" />
                        <button type="button" class="password-toggle show"
                            aria-label="<?php esc_attr_e('Toggle password visibility', 'woocommerce'); ?>"
                            title="<?php esc_attr_e('Show/Hide password', 'woocommerce'); ?>">
                            <span class="eye-icon"><svg width="18" height="14" viewBox="0 0 18 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.9536 6.57319C17.207 6.92846 17.3337 7.10609 17.3337 7.36904C17.3337 7.63198 17.207 7.80962 16.9536 8.16488C15.8152 9.76117 12.908 13.2024 9.00033 13.2024C5.09264 13.2024 2.18541 9.76117 1.04703 8.16489C0.79367 7.80962 0.666992 7.63198 0.666992 7.36904C0.666992 7.10609 0.79367 6.92846 1.04703 6.57319C2.18541 4.97691 5.09264 1.53571 9.00033 1.53571C12.908 1.53571 15.8152 4.97691 16.9536 6.57319Z"
                                        stroke="black" stroke-width="1.25" />
                                    <path
                                        d="M11.5 7.36896C11.5 5.98825 10.3807 4.86896 9 4.86896C7.61929 4.86896 6.5 5.98825 6.5 7.36896C6.5 8.74967 7.61929 9.86896 9 9.86896C10.3807 9.86896 11.5 8.74967 11.5 7.36896Z"
                                        stroke="black" stroke-width="1.25" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    <div id="password-strength-indicator" class="nxt-secured_pass"
                        aria-label="<?php esc_attr_e('Password strength indicator', 'woocommerce'); ?>">
                        <span class="nxt-secured_item" aria-hidden="true"></span>
                        <span class="nxt-secured_item" aria-hidden="true"></span>
                        <span class="nxt-secured_item" aria-hidden="true"></span>
                    </div>
                    <span id="password-requirements" class="nxt-pass_info" aria-live="polite">At least 1 number, 8 characters, 1
                        symbol</span>
                </div>

            <?php else: ?>

                <p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>

            <?php endif; ?>

        </div>

        <?php do_action('woocommerce_register_form'); ?>

        <p class="woocommerce-form-row form-row">
            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
            <button type="submit"
                class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit"
                name="register"
                value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
        </p>

        <?php do_action('woocommerce_register_form_end'); ?>

    </form>

    <?php

    return ob_get_clean();
}
add_shortcode('wc_register_form_custom', 'custom_wc_register_form_shortcode');


// 3) Validate the custom register fields
add_filter('woocommerce_registration_errors', 'custom_validate_extra_register_fields', 10, 3);
function custom_validate_extra_register_fields($errors, $username, $email)
{
    if (isset($_POST['first_name']) && empty(trim($_POST['first_name']))) {
        $errors->add('first_name_error', __('First name is required!', 'hello-elementor-child'));
    }
    if (isset($_POST['last_name']) && empty(trim($_POST['last_name']))) {
        $errors->add('last_name_error', __('Last name is required!', 'hello-elementor-child'));
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            $errors->add('password_length_error', __('Password must be at least 8 characters.', 'your-textdomain'));
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors->add('password_number_error', __('Password must contain at least one number.', 'your-textdomain'));
        }
        if (!preg_match('/[\W]/', $password)) {
            $errors->add('password_symbol_error', __('Password must contain at least one special character.', 'your-textdomain'));
        }
    }
    return $errors;
}

// 4) Save first name & last name upon successful registration
add_action('woocommerce_created_customer', 'custom_save_extra_register_fields');
function custom_save_extra_register_fields($customer_id)
{
    if (isset($_POST['first_name'])) {
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (isset($_POST['last_name'])) {
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['last_name']));
    }
}

// 5) Redirect if logged in and trying to access login/register pages
function custom_redirect_logged_in_user()
{
    if (is_user_logged_in()) {
        // change these slugs or IDs as your Login/Register pages
        if (is_page('login') || is_page('register')) {
            wp_safe_redirect(wc_get_page_permalink('myaccount'));
            exit;
        }
    }
}
add_action('template_redirect', 'custom_redirect_logged_in_user');

// 6) Redirect after registration
add_filter('woocommerce_registration_redirect', 'custom_registration_redirect');
function custom_registration_redirect($redirect_to)
{
    // you can change this to any page you like
    $redirect_to = wc_get_page_permalink('myaccount');
    return $redirect_to;
}




function add_logo_and_welcome_text()
{
    ?>
    <div class="registration-logo">
        <?php
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="site-logo" />';
        } else {
            echo '<h1 class="site-title">' . get_bloginfo('name') . '</h1>';
        }
        ?>
    </div>
    <?php
}
add_action('woocommerce_login_form_start', 'add_logo_and_welcome_text');
add_action('woocommerce_register_form_start', 'add_logo_and_welcome_text');



add_action('woocommerce_login_form_start', function () {
    ?>
    <div class="registration-header-text">
        <h2> <?php esc_html_e('Welcome back', 'hello-elementor-child') ?> </h2>
        <p> <?php esc_html_e('Sign in to continue your fishing journey!', 'hello-elementor-child') ?> </p>
    </div>
    <?php
});
add_action('woocommerce_register_form_start', function () {
    ?>
    <div class="registration-header-text">
        <h2> <?php esc_html_e('Get started now', 'hello-elementor-child') ?> </h2>
        <p> <?php esc_html_e('Join us today and start your fishing journey!', 'hello-elementor-child') ?> </p>
    </div>
    <?php
});

// Link under login form
add_action('woocommerce_login_form_end', function () {
    ?>
    <div class="already-have-account">
        <p>
            <?php esc_html_e("Don't have an account?", "hello-elementor-child"); ?>
            <a href="<?php echo esc_url(home_url('/register')); ?>" class="login-link">
                <?php esc_html_e('Create Account', 'hello-elementor-child'); ?>
            </a>
        </p>
    </div>
    <?php
});

// Link under registration form
add_action('woocommerce_register_form_end', function () {
    ?>
    <div class="already-have-account">
        <p>
            <?php esc_html_e("Already have an account?", "hello-elementor-child"); ?>
            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="login-link">
                <?php esc_html_e('Login', 'hello-elementor-child'); ?>
            </a>
        </p>
    </div>
    <?php
});




function add_social_login_and_privacy_policy()
{
    ?>
    <!-- Social Login -->
    <div class="social-login-section">
        <div class="or-divider">
            <span><?php esc_html_e('Or continue with', 'hello-elementor-child'); ?></span>
        </div>
        <div class="nxt-social-login-btn_wrapper">
            <?php
            if (class_exists('NextendSocialLogin', false)) {
                echo '<a href="  ' . home_url() . '/wp-login.php?loginSocial=google" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="google" data-popupwidth="600" data-popupheight="600">
            <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.8243 11.9339L5.30218 13.8831L3.39381 13.9235C2.82349 12.8657 2.5 11.6554 2.5 10.3693C2.5 9.12559 2.80246 7.95279 3.33859 6.9201H3.339L5.03798 7.23159L5.78223 8.92037C5.62646 9.3745 5.54156 9.862 5.54156 10.3693C5.54162 10.9198 5.64134 11.4473 5.8243 11.9339Z" fill="#FBBB00"/><path d="M17.3701 8.96765C17.4562 9.42134 17.5011 9.88988 17.5011 10.3687C17.5011 10.9057 17.4446 11.4295 17.3371 11.9347C16.972 13.6539 16.018 15.1551 14.6965 16.2175L14.6961 16.2171L12.5561 16.1079L12.2532 14.2172C13.1301 13.7029 13.8155 12.8981 14.1764 11.9347H10.166V8.96765H14.235H17.3701Z" fill="#518EF8"/><path d="M14.6926 16.2176L14.6931 16.218C13.4078 17.2511 11.7751 17.8692 9.99775 17.8692C7.14159 17.8692 4.65836 16.2728 3.3916 13.9235L5.82209 11.934C6.45546 13.6243 8.08608 14.8276 9.99775 14.8276C10.8194 14.8276 11.5892 14.6055 12.2498 14.2177L14.6926 16.2176Z" fill="#28B446"/><path d="M14.7884 4.59565L12.3588 6.58478C11.6751 6.15746 10.867 5.91061 10.0012 5.91061C8.04628 5.91061 6.38515 7.16911 5.78351 8.92009L3.34025 6.91982H3.33984C4.58806 4.51324 7.1026 2.86902 10.0012 2.86902C11.821 2.86902 13.4895 3.51724 14.7884 4.59565Z" fill="#F14336"/></svg>' . esc_html__('Google', 'hello-elementor-child') . '
            </a>';
            }
            ?>
        </div>
    </div>

    <!-- WooCommerce Privacy Policy (via hook) -->
    <div class="privacy-policy-section">
        <?php do_action('nextend_social_login_form_end'); ?>
    </div>
    <?php
}



add_action('woocommerce_login_form_end', 'add_social_login_and_privacy_policy');
add_action('woocommerce_register_form_end', 'add_social_login_and_privacy_policy');



function manage_woocommerce_privacy_policy_hooks()
{

    remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);

    add_action('nextend_social_login_form_end', 'wc_registration_privacy_policy_text', 20);
}
add_action('init', 'manage_woocommerce_privacy_policy_hooks');
