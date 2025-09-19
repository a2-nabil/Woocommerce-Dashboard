<?php
// Prevent direct access
if (! defined('ABSPATH')) {
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
            'hidden'   => false
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
                <label for="reg_first_name"><?php esc_html_e('First name', 'your-textdomain'); ?> <span class="required">*</span></label>
                <input type="text" name="first_name" id="reg_first_name" autocomplete="given-name" value="<?php echo ! empty($_POST['first_name']) ? esc_attr(wp_unslash($_POST['first_name'])) : ''; ?>" placeholder="<?php esc_attr_e('First name', 'your-textdomain'); ?>" />
            </p>

            <p class="form-row form-row-last">
                <label for="reg_last_name"><?php esc_html_e('Last name', 'your-textdomain'); ?> <span class="required">*</span></label>
                <input type="text" name="last_name" id="reg_last_name" autocomplete="family-name" value="<?php echo ! empty($_POST['last_name']) ? esc_attr(wp_unslash($_POST['last_name'])) : ''; ?>" placeholder="<?php esc_attr_e('Last name', 'your-textdomain'); ?>" />
            </p>

            <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (! empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine 
                                                                                                                                                                                                                                                                                                    ?>
                </p>

            <?php endif; ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (! empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine 
                                                                                                                                                                                                                                                                                    ?>
            </p>

            <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
                    <div class="password-input-wrapper">
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" aria-describedby="password-strength-indicator password-requirements" />
                        <button type="button" class="password-toggle show" aria-label="<?php esc_attr_e('Toggle password visibility', 'woocommerce'); ?>" title="<?php esc_attr_e('Show/Hide password', 'woocommerce'); ?>">
                            <span class="eye-icon"><svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.9536 6.57319C17.207 6.92846 17.3337 7.10609 17.3337 7.36904C17.3337 7.63198 17.207 7.80962 16.9536 8.16488C15.8152 9.76117 12.908 13.2024 9.00033 13.2024C5.09264 13.2024 2.18541 9.76117 1.04703 8.16489C0.79367 7.80962 0.666992 7.63198 0.666992 7.36904C0.666992 7.10609 0.79367 6.92846 1.04703 6.57319C2.18541 4.97691 5.09264 1.53571 9.00033 1.53571C12.908 1.53571 15.8152 4.97691 16.9536 6.57319Z" stroke="black" stroke-width="1.25" />
                                    <path d="M11.5 7.36896C11.5 5.98825 10.3807 4.86896 9 4.86896C7.61929 4.86896 6.5 5.98825 6.5 7.36896C6.5 8.74967 7.61929 9.86896 9 9.86896C10.3807 9.86896 11.5 8.74967 11.5 7.36896Z" stroke="black" stroke-width="1.25" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    <div id="password-strength-indicator" class="nxt-secured_pass" aria-label="<?php esc_attr_e('Password strength indicator', 'woocommerce'); ?>">
                        <span class="nxt-secured_item" aria-hidden="true"></span>
                        <span class="nxt-secured_item" aria-hidden="true"></span>
                        <span class="nxt-secured_item" aria-hidden="true"></span>
                    </div>
                    <span id="password-requirements" class="nxt-pass_info" aria-live="polite">At least 1 number, 8 characters, 1 symbol</span>
                </div>

            <?php else : ?>

                <p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>

            <?php endif; ?>

        </div>

        <?php do_action('woocommerce_register_form'); ?>

        <p class="woocommerce-form-row form-row">
            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
            <button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
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
    if ( isset( $_POST['password'] ) ) {
        $password = $_POST['password'];
        if ( strlen( $password ) < 8 ) {
            $errors->add( 'password_length_error', __( 'Password must be at least 8 characters.', 'your-textdomain' ) );
        }
        if ( ! preg_match( '/[0-9]/', $password ) ) {
            $errors->add( 'password_number_error', __( 'Password must contain at least one number.', 'your-textdomain' ) );
        }
        if ( ! preg_match( '/[\W]/', $password ) ) {
            $errors->add( 'password_symbol_error', __( 'Password must contain at least one special character.', 'your-textdomain' ) );
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
        <?php do_shortcode('[nextend_social_login]'); ?>
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
