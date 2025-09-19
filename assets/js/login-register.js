jQuery(document).ready(function($) {
    // Password strength checker
    function checkPasswordStrength(password) {
        let score = 0;
        let feedback = [];
        
        // Check length (at least 8 characters)
        if (password.length >= 8) {
            score++;
        } else {
            feedback.push('At least 8 characters');
        }
        
        // Check for numbers
        if (/\d/.test(password)) {
            score++;
        } else {
            feedback.push('At least 1 number');
        }
        
        // Check for symbols/special characters
        if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
            score++;
        } else {
            feedback.push('At least 1 symbol');
        }
        
        // Optional: Check for uppercase letters (not in original requirement but good practice)
        if (/[A-Z]/.test(password)) {
            score += 0.5; // Half point for bonus requirement
        }
        
        // Optional: Check for lowercase letters
        if (/[a-z]/.test(password)) {
            score += 0.5; // Half point for bonus requirement
        }
        
        return {
            score: Math.min(score, 3), // Cap at 3 for the 3 indicators
            feedback: feedback,
            strength: score >= 3 ? 'strong' : score >= 2 ? 'medium' : 'weak'
        };
    }
    
    // Update password strength indicator
    function updatePasswordStrength(password) {
        const result = checkPasswordStrength(password);
        const $indicators = $('.nxt-secured_item');
        const $feedback = $('.nxt-pass_info');
        
        // Reset all indicators
        $indicators.removeClass('filled weak medium strong');
        
        // Fill indicators based on score
        for (let i = 0; i < Math.floor(result.score); i++) {
            $indicators.eq(i).addClass('filled ' + result.strength);
        }
        
        // Update feedback text
        if (password.length === 0) {
            $feedback.text('At least 1 number, 8 characters, 1 symbol');
        } else if (result.feedback.length > 0) {
            $feedback.text('Missing: ' + result.feedback.join(', '));
        } else {
            $feedback.text('Strong password!');
            $feedback.addClass('strong-text');
        }
        
        // Remove strong-text class if password is not strong
        if (result.strength !== 'strong') {
            $feedback.removeClass('strong-text');
        }
    }
    
    // Password toggle functionality
    function togglePasswordVisibility() {
        const $passwordField = $('#reg_password');
        const $toggleBtn = $('.password-toggle');
        const $eyeIcon = $('.eye-icon');
        
        if ($passwordField.attr('type') === 'password') {
            $passwordField.attr('type', 'text');
            $toggleBtn.removeClass('show').addClass('hide');
            $toggleBtn.attr('aria-label', 'Hide password');
            $toggleBtn.attr('title', 'Hide password');
            $eyeIcon.html('<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><path fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" d="M1,32c0,0,11,15,31,15s31-15,31-15S52,17,32,17S1,32,1,32z"/><circle fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" cx="32" cy="32" r="7"/><line fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" x1="9" y1="55" x2="55" y2="9"/></svg>'); // Eye closed/hidden icon
        } else {
            $passwordField.attr('type', 'password');
            $toggleBtn.removeClass('hide').addClass('show');
            $toggleBtn.attr('aria-label', 'Show password');
            $toggleBtn.attr('title', 'Show password');
            $eyeIcon.html('<svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.9536 6.57319C17.207 6.92846 17.3337 7.10609 17.3337 7.36904C17.3337 7.63198 17.207 7.80962 16.9536 8.16488C15.8152 9.76117 12.908 13.2024 9.00033 13.2024C5.09264 13.2024 2.18541 9.76117 1.04703 8.16489C0.79367 7.80962 0.666992 7.63198 0.666992 7.36904C0.666992 7.10609 0.79367 6.92846 1.04703 6.57319C2.18541 4.97691 5.09264 1.53571 9.00033 1.53571C12.908 1.53571 15.8152 4.97691 16.9536 6.57319Z" stroke="black" stroke-width="1.25"/><path d="M11.5 7.36896C11.5 5.98825 10.3807 4.86896 9 4.86896C7.61929 4.86896 6.5 5.98825 6.5 7.36896C6.5 8.74967 7.61929 9.86896 9 9.86896C10.3807 9.86896 11.5 8.74967 11.5 7.36896Z" stroke="black" stroke-width="1.25"/></svg>'); // Eye open icon
        }
    }
    
    // Bind events
    $(document).on('input keyup', '#reg_password', function() {
        const password = $(this).val();
        updatePasswordStrength(password);
    });
    
    // Bind password toggle click
    $(document).on('click', '.password-toggle', function(e) {
        e.preventDefault();
        togglePasswordVisibility();
    });
    
    // Initialize password strength on page load
    const initialPassword = $('#reg_password').val() || '';
    updatePasswordStrength(initialPassword);
});
