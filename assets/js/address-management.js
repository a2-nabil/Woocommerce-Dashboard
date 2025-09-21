/**
 * Address Management JavaScript
 * Handles address functionality using the reusable modal system
 */

(function($) {
    'use strict';

    // Address Management Class
    class AddressManager {
        constructor() {
            this.currentAddressType = null;
            this.currentAddressId = null;
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Add address button click
            $(document).on('click', '.nxt-add-address-btn', (e) => {
                e.preventDefault();
                this.openAddressFormModal();
            });

            // Delete address button click
            $(document).on('click', '.nxt-delete-address', (e) => {
                e.preventDefault();
                const addressId = $(e.currentTarget).data('address-id');
                this.openDeleteModal(addressId);
            });

            // Edit custom address button click
            $(document).on('click', '.nxt-edit-custom-address', (e) => {
                e.preventDefault();
                const addressId = $(e.currentTarget).data('address-id');
                this.openEditAddressModal(addressId);
            });

            // Set default billing button click
            $(document).on('click', '.nxt-set-default-billing', (e) => {
                e.preventDefault();
                const addressId = $(e.currentTarget).data('address-id');
                this.setDefaultAddress(addressId, 'billing');
            });

            // Set default shipping button click
            $(document).on('click', '.nxt-set-default-shipping', (e) => {
                e.preventDefault();
                const addressId = $(e.currentTarget).data('address-id');
                this.setDefaultAddress(addressId, 'shipping');
            });

            // Delete default address button click
            $(document).on('click', '.nxt-delete-default-address', (e) => {
                e.preventDefault();
                const addressType = $(e.currentTarget).data('address-type');
                this.openDeleteDefaultModal(addressType);
            });
        }

        openAddressFormModal(addressId = null) {
            const formContent = this.createWooCommerceAddressForm(addressId);
            const title = addressId ? 'Edit Address' : 'Add New Address';
            
            NxtModal.show({
                title: title,
                content: formContent,
                type: 'info',
                size: 'large',
                buttons: [
                    {
                        id: 'nxt-address-cancel',
                        text: 'Cancel',
                        class: 'nxt-btn nxt-btn-secondary',
                        callback: () => NxtModal.close()
                    },
                    {
                        id: 'nxt-address-save',
                        text: addressId ? 'Update Address' : 'Save Address',
                        class: 'nxt-btn nxt-btn-primary',
                        callback: () => this.saveAddress(addressId)
                    }
                ],
                onShow: (modal) => {
                    // Initialize form validation
                    this.initFormValidation(modal);
                    // Initialize country and state dropdowns
                    this.initCountryStateDropdowns(modal);
                    if (addressId) {
                        this.loadAddressData(modal, addressId);
                    }
                }
            });
        }

        openEditAddressModal(addressId) {
            this.openAddressFormModal(addressId);
        }

        openDeleteModal(addressId) {
            this.currentAddressId = addressId;
            
            NxtModal.confirm({
                title: 'Delete Address',
                content: `
                    <div class="nxt-delete-content">
                        <div class="nxt-delete-icon">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 4C12.96 4 4 12.96 4 24C4 35.04 12.96 44 24 44C35.04 44 44 35.04 44 24C44 12.96 35.04 4 24 4ZM32 30.08L29.92 32L24 26.08L18.08 32L16 30.08L21.92 24L16 17.92L18.08 16L24 21.92L29.92 16L32 17.92L26.08 24L32 30.08Z" fill="#E40606"/>
                            </svg>
                        </div>
                        <h3>Are you sure want to delete this address?</h3>
                        <p>This action cannot be undone. The address will be permanently removed from your account.</p>
                    </div>
                `,
                type: 'error',
                buttons: [
                    {
                        id: 'nxt-delete-cancel',
                        text: 'Cancel',
                        class: 'nxt-btn nxt-btn-secondary',
                        callback: () => NxtModal.close()
                    },
                    {
                        id: 'nxt-delete-confirm',
                        text: 'Yes, Delete',
                        class: 'nxt-btn nxt-btn-danger',
                        callback: () => this.deleteAddress()
                    }
                ]
            });
        }

        openDeleteDefaultModal(addressType) {
            this.currentAddressType = addressType;
            
            NxtModal.confirm({
                title: 'Delete Default Address',
                content: `
                    <div class="nxt-delete-content">
                        <div class="nxt-delete-icon">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 4C12.96 4 4 12.96 4 24C4 35.04 12.96 44 24 44C35.04 44 44 35.04 44 24C44 12.96 35.04 4 24 4ZM32 30.08L29.92 32L24 26.08L18.08 32L16 30.08L21.92 24L16 17.92L18.08 16L24 21.92L29.92 16L32 17.92L26.08 24L32 30.08Z" fill="#E40606"/>
                            </svg>
                        </div>
                        <h3>Are you sure want to delete this ${addressType} address?</h3>
                        <p>This action cannot be undone. The default ${addressType} address will be permanently removed from your account.</p>
                    </div>
                `,
                type: 'error',
                buttons: [
                    {
                        id: 'nxt-delete-cancel',
                        text: 'Cancel',
                        class: 'nxt-btn nxt-btn-secondary',
                        callback: () => NxtModal.close()
                    },
                    {
                        id: 'nxt-delete-confirm',
                        text: 'Yes, Delete',
                        class: 'nxt-btn nxt-btn-danger',
                        callback: () => this.deleteDefaultAddress()
                    }
                ]
            });
        }

        createWooCommerceAddressForm(addressId = null) {
            return `
                <div class="nxt-address-form">
                    <div class="nxt-form-row">
                        <div class="nxt-form-group">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name" name="first_name" class="nxt-form-input" required>
                        </div>
                        <div class="nxt-form-group">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" class="nxt-form-input" required>
                        </div>
                    </div>
                    
                    <div class="nxt-form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" class="nxt-form-input">
                    </div>
                    
                    <div class="nxt-form-group">
                        <label for="address_1">Address Line 1 *</label>
                        <input type="text" id="address_1" name="address_1" class="nxt-form-input" required>
                    </div>
                    
                    <div class="nxt-form-group">
                        <label for="address_2">Address Line 2</label>
                        <input type="text" id="address_2" name="address_2" class="nxt-form-input">
                    </div>
                    
                    <div class="nxt-form-row">
                        <div class="nxt-form-group">
                            <label for="city">City *</label>
                            <input type="text" id="city" name="city" class="nxt-form-input" required>
                        </div>
                        <div class="nxt-form-group">
                            <label for="postcode">Postal Code *</label>
                            <input type="text" id="postcode" name="postcode" class="nxt-form-input" required>
                        </div>
                    </div>
                    
                    <div class="nxt-form-row">
                        <div class="nxt-form-group">
                            <label for="country">Country *</label>
                            <select id="country" name="country" class="nxt-form-input" required>
                                <option value="">Select Country</option>
                            </select>
                        </div>
                        <div class="nxt-form-group">
                            <label for="state">State/Province</label>
                            <select id="state" name="state" class="nxt-form-input">
                                <option value="">Select State</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="nxt-form-row">
                        <div class="nxt-form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="nxt-form-input">
                        </div>
                        <div class="nxt-form-group">
                            <label for="building_type">Building Type</label>
                            <select id="building_type" name="building_type" class="nxt-form-input">
                                <option value="house">House</option>
                                <option value="apartment">Apartment</option>
                                <option value="office">Office</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
        }

        initCountryStateDropdowns(modal) {
            const countrySelect = modal.find('#country');
            const stateSelect = modal.find('#state');
            
            // Populate countries from WooCommerce
            if (typeof wc_address_params !== 'undefined' && wc_address_params.countries) {
                const countries = wc_address_params.countries;
                countrySelect.empty().append('<option value="">Select Country</option>');
                
                Object.keys(countries).forEach(code => {
                    countrySelect.append(`<option value="${code}">${countries[code]}</option>`);
                });
            }
            
            // Handle country change
            countrySelect.on('change', () => {
                const countryCode = countrySelect.val();
                this.updateStateDropdown(stateSelect, countryCode);
            });
        }

        updateStateDropdown(stateSelect, countryCode) {
            stateSelect.empty().append('<option value="">Select State</option>');
            
            if (!countryCode) return;
            
            // Get states for the selected country
            if (typeof wc_address_params !== 'undefined' && wc_address_params.states && wc_address_params.states[countryCode]) {
                const states = wc_address_params.states[countryCode];
                
                if (typeof states === 'object') {
                    Object.keys(states).forEach(code => {
                        stateSelect.append(`<option value="${code}">${states[code]}</option>`);
                    });
                } else {
                    // If states is a string, it means no states for this country
                    stateSelect.append('<option value="">No states available</option>');
                }
            } else {
                // Fallback: convert to text input if no states data
                stateSelect.replaceWith('<input type="text" id="state" name="state" class="nxt-form-input" placeholder="State/Province">');
            }
        }

        loadAddressData(modal, addressId) {
            // Fetch address data via AJAX
            const data = {
                action: 'nxt_get_address_data',
                address_id: addressId,
                nonce: wc_address_params.get_address_nonce
            };

            $.ajax({
                url: wc_address_params.ajax_url,
                type: 'POST',
                data: data,
                success: (response) => {
                    if (response.success && response.data) {
                        this.populateFormFields(modal, response.data);
                    } else {
                        console.error('Error loading address data:', response.data);
                    }
                },
                error: (xhr, status, error) => {
                    console.error('AJAX error loading address data:', error);
                }
            });
        }

        populateFormFields(modal, addressData) {
            const form = modal.find('.nxt-address-form');
            
            // Populate text inputs
            const textFields = ['first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'postcode', 'phone'];
            textFields.forEach(field => {
                const input = form.find(`[name="${field}"]`);
                if (input.length && addressData[field]) {
                    input.val(addressData[field]);
                }
            });
            
            // Populate country dropdown
            const countrySelect = form.find('[name="country"]');
            if (countrySelect.length && addressData.country) {
                countrySelect.val(addressData.country);
                // Trigger country change to load states
                countrySelect.trigger('change');
                
                // Set state after a short delay to ensure states are loaded
                setTimeout(() => {
                    const stateSelect = form.find('[name="state"]');
                    if (stateSelect.length && addressData.state) {
                        stateSelect.val(addressData.state);
                    }
                }, 100);
            }
            
            // Populate building type dropdown
            const buildingTypeSelect = form.find('[name="building_type"]');
            if (buildingTypeSelect.length && addressData.building_type) {
                buildingTypeSelect.val(addressData.building_type);
            }
        }

        initFormValidation(modal) {
            const form = modal.find('.nxt-address-form');
            const inputs = form.find('input[required], select[required]');
            
            inputs.on('blur', function() {
                const input = $(this);
                const value = input.val().trim();
                
                if (input.prop('required') && !value) {
                    input.addClass('error');
                    input.attr('title', 'This field is required');
                } else {
                    input.removeClass('error');
                    input.removeAttr('title');
                }
            });
        }

        saveAddress(addressId = null) {
            const modal = $('.nxt-modal.show');
            const form = modal.find('.nxt-address-form');
            
            // Validate form
            let isValid = true;
            const requiredFields = form.find('input[required], select[required]');
            
            requiredFields.each(function() {
                const input = $(this);
                const value = input.val().trim();
                
                if (!value) {
                    input.addClass('error');
                    input.attr('title', 'This field is required');
                    isValid = false;
                } else {
                    input.removeClass('error');
                    input.removeAttr('title');
                }
            });
            
            if (!isValid) {
                this.showMessage('Please fill in all required fields', 'error');
                return;
            }
            
            // Collect form data
            const formData = {
                action: 'nxt_save_custom_address',
                nonce: wc_address_params.save_nonce
            };
            
            if (addressId) {
                formData.address_id = addressId;
            }
            
            form.find('input, select').each(function() {
                const input = $(this);
                formData[input.attr('name')] = input.val();
            });
            
            // Show loading modal
            NxtModal.loading('Saving address...');
            
            $.ajax({
                url: wc_address_params.ajax_url,
                type: 'POST',
                data: formData,
                success: (response) => {
                    NxtModal.close(); // Close loading modal
                    
                    if (response.success) {
                        this.showMessage('Address saved successfully', 'success');
                        // Reload page to show updated addresses
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        this.showMessage(response.data || 'Error saving address', 'error');
                    }
                },
                error: () => {
                    NxtModal.close(); // Close loading modal
                    this.showMessage('Error saving address', 'error');
                }
            });
        }

        setDefaultAddress(addressId, addressType) {
            const data = {
                action: 'nxt_set_default_address',
                address_id: addressId,
                address_type: addressType,
                nonce: wc_address_params.set_default_nonce
            };

            // Show loading modal
            NxtModal.loading('Setting default address...');

            $.ajax({
                url: wc_address_params.ajax_url,
                type: 'POST',
                data: data,
                success: (response) => {
                    NxtModal.close(); // Close loading modal
                    
                    if (response.success) {
                        this.showMessage('Default ' + addressType + ' address updated successfully', 'success');
                        // Reload page to show updated addresses
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        this.showMessage(response.data || 'Error setting default address', 'error');
                    }
                },
                error: () => {
                    NxtModal.close(); // Close loading modal
                    this.showMessage('Error setting default address', 'error');
                }
            });
        }

        deleteAddress() {
            if (!this.currentAddressId) {
                return;
            }

            const data = {
                action: 'nxt_delete_address',
                address_id: this.currentAddressId,
                nonce: wc_address_params.delete_nonce
            };

            // Show loading modal
            NxtModal.loading('Deleting address...');

            $.ajax({
                url: wc_address_params.ajax_url,
                type: 'POST',
                data: data,
                success: (response) => {
                    NxtModal.close(); // Close loading modal
                    
                    if (response.success) {
                        this.showMessage('Address deleted successfully', 'success');
                        // Reload page to show updated addresses
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        this.showMessage(response.data || 'Error deleting address', 'error');
                    }
                },
                error: () => {
                    NxtModal.close(); // Close loading modal
                    this.showMessage('Error deleting address', 'error');
                }
            });
        }

        deleteDefaultAddress() {
            if (!this.currentAddressType) {
                return;
            }

            const data = {
                action: 'nxt_delete_default_address',
                address_type: this.currentAddressType,
                nonce: wc_address_params.delete_default_nonce
            };

            // Show loading modal
            NxtModal.loading('Deleting default address...');

            $.ajax({
                url: wc_address_params.ajax_url,
                type: 'POST',
                data: data,
                success: (response) => {
                    NxtModal.close(); // Close loading modal
                    
                    if (response.success) {
                        this.showMessage('Default ' + this.currentAddressType + ' address deleted successfully', 'success');
                        // Reload page to show updated addresses
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        this.showMessage(response.data || 'Error deleting default address', 'error');
                    }
                },
                error: () => {
                    NxtModal.close(); // Close loading modal
                    this.showMessage('Error deleting default address', 'error');
                }
            });
        }

        showMessage(message, type = 'info') {
            // Create and show message
            const messageClass = type === 'success' ? 'nxt-message-success' : 'nxt-message-error';
            const messageHtml = `
                <div class="nxt-message ${messageClass}">
                    <span>${message}</span>
                    <button type="button" class="nxt-message-close">&times;</button>
                </div>
            `;
            
            $('body').append(messageHtml);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                $('.nxt-message').fadeOut(() => {
                    $('.nxt-message').remove();
                });
            }, 5000);
        }
    }

    // Initialize when document is ready
    $(document).ready(() => {
        if ($('.nxt-addresses-page').length) {
            new AddressManager();
        }
    });

    // Add message styles
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .nxt-message {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 12px;
                min-width: 300px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                font-family: "Poppins", sans-serif;
            }
            .nxt-message-success {
                background: #10B981;
            }
            .nxt-message-error {
                background: #EF4444;
            }
            .nxt-message-close {
                background: none;
                border: none;
                color: white;
                font-size: 20px;
                cursor: pointer;
                padding: 0;
                margin-left: auto;
            }
            .nxt-address-type-options {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            .nxt-address-type-btn {
                display: flex;
                align-items: center;
                gap: 16px;
                padding: 20px;
                border: 2px solid #E5E5E5;
                border-radius: 12px;
                background: #ffffff;
                cursor: pointer;
                transition: all 0.3s ease;
                text-align: left;
                width: 100%;
            }
            .nxt-address-type-btn:hover {
                border-color: #1F487E;
                background: #F8F9FF;
            }
            .nxt-address-type-icon {
                flex-shrink: 0;
            }
            .nxt-address-type-info h3 {
                margin: 0 0 4px 0;
                font-size: 16px;
                font-weight: 500;
                color: #000000;
                font-family: "Poppins", sans-serif;
            }
            .nxt-address-type-info p {
                margin: 0;
                font-size: 14px;
                color: #666666;
                line-height: 20px;
            }
            .nxt-delete-content {
                text-align: center;
            }
            .nxt-delete-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 16px;
            }
            .nxt-delete-content h3 {
                margin: 0 0 12px 0;
                font-size: 20px;
                font-weight: 500;
                color: #000000;
                font-family: "Poppins", sans-serif;
            }
            .nxt-delete-content p {
                margin: 0;
                font-size: 14px;
                color: #666666;
                line-height: 20px;
            }
            .nxt-address-form {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }
            .nxt-form-row {
                display: flex;
                gap: 16px;
            }
            .nxt-form-row .nxt-form-group {
                flex: 1;
            }
            .nxt-form-group {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .nxt-form-group label {
                font-size: 14px;
                font-weight: 500;
                color: #000000;
                font-family: "Poppins", sans-serif;
            }
            .nxt-form-input {
                padding: 12px 16px;
                font-size: 14px;
                font-family: "Poppins", sans-serif;
                transition: all 0.3s ease;
            }
            .nxt-form-input:focus {
                outline: none;
                border-color: #1F487E;
                box-shadow: 0 0 0 3px rgba(31, 72, 126, 0.1);
            }
            .nxt-form-input.error {
                border-color: #E40606;
                box-shadow: 0 0 0 3px rgba(228, 6, 6, 0.1);
            }
            @media (max-width: 768px) {
                .nxt-form-row {
                    flex-direction: column;
                    gap: 12px;
                }
            }
        `)
        .appendTo('head');

})(jQuery);
