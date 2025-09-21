/**
 * Reusable Modal System
 * A flexible modal system that can be used across all pages
 */

(function($) {
    'use strict';

    // Modal Manager Class
    class ModalManager {
        constructor() {
            this.activeModal = null;
            this.modalCounter = 0;
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Close modal events
            $(document).on('click', '.nxt-modal-close, .nxt-modal-overlay', (e) => {
                e.preventDefault();
                this.close();
            });

            // Prevent modal close when clicking inside modal content
            $(document).on('click', '.nxt-modal-content', (e) => {
                e.stopPropagation();
            });

            // Close modal on escape key
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.activeModal) {
                    this.close();
                }
            });
        }

        /**
         * Create and show a modal
         * @param {Object} options - Modal configuration
         * @param {string} options.title - Modal title
         * @param {string} options.content - Modal content HTML
         * @param {string} options.type - Modal type (default, confirm, info, warning, error)
         * @param {Array} options.buttons - Array of button configurations
         * @param {string} options.size - Modal size (small, medium, large, full)
         * @param {boolean} options.closable - Whether modal can be closed
         * @param {Function} options.onShow - Callback when modal is shown
         * @param {Function} options.onClose - Callback when modal is closed
         * @returns {string} Modal ID
         */
        show(options = {}) {
            const modalId = 'nxt-modal-' + (++this.modalCounter);
            const config = {
                title: 'Modal Title',
                content: '',
                type: 'default',
                buttons: [],
                size: 'medium',
                closable: true,
                onShow: null,
                onClose: null,
                ...options
            };

            const modalHtml = this.createModalHtml(modalId, config);
            
            // Remove any existing modal
            this.close();
            
            // Add modal to body
            $('body').append(modalHtml);
            $('body').addClass('modal-open');
            
            this.activeModal = $('#' + modalId);
            
            // Bind button events
            this.bindModalEvents(modalId, config);
            
            // Show modal with animation
            setTimeout(() => {
                this.activeModal.addClass('show');
            }, 10);
            
            // Call onShow callback
            if (typeof config.onShow === 'function') {
                config.onShow(this.activeModal);
            }
            
            return modalId;
        }

        /**
         * Create modal HTML
         */
        createModalHtml(modalId, config) {
            const sizeClass = 'nxt-modal-' + config.size;
            const typeClass = 'nxt-modal-' + config.type;
            
            let buttonsHtml = '';
            if (config.buttons.length > 0) {
                buttonsHtml = '<div class="nxt-modal-actions">';
                config.buttons.forEach(button => {
                    const buttonClass = button.class || 'nxt-btn nxt-btn-primary';
                    const buttonId = button.id ? `id="${button.id}"` : '';
                    buttonsHtml += `<button type="button" class="${buttonClass}" ${buttonId}>${button.text}</button>`;
                });
                buttonsHtml += '</div>';
            }

            return `
                <div id="${modalId}" class="nxt-modal ${typeClass} ${sizeClass}">
                    <div class="nxt-modal-overlay"></div>
                    <div class="nxt-modal-content">
                        <div class="nxt-modal-header">
                            <h2 class="nxt-modal-title">${config.title}</h2>
                            ${config.closable ? '<button type="button" class="nxt-modal-close"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>' : ''}
                        </div>
                        <div class="nxt-modal-body">
                            ${config.content}
                            ${buttonsHtml}
                        </div>
                    </div>
                </div>
            `;
        }

        /**
         * Bind modal events
         */
        bindModalEvents(modalId, config) {
            const modal = $('#' + modalId);
            
            config.buttons.forEach(button => {
                if (button.id) {
                    modal.find('#' + button.id).on('click', (e) => {
                        e.preventDefault();
                        if (typeof button.callback === 'function') {
                            button.callback(e, modal);
                        }
                    });
                }
            });
        }

        /**
         * Close active modal
         */
        close() {
            if (this.activeModal) {
                this.activeModal.removeClass('show');
                setTimeout(() => {
                    this.activeModal.remove();
                    $('body').removeClass('modal-open');
                    this.activeModal = null;
                }, 300);
            }
        }

        /**
         * Show confirmation modal
         */
        confirm(options = {}) {
            const config = {
                title: 'Confirm Action',
                content: 'Are you sure you want to proceed?',
                type: 'confirm',
                buttons: [
                    {
                        id: 'nxt-confirm-cancel',
                        text: 'Cancel',
                        class: 'nxt-btn nxt-btn-secondary',
                        callback: () => this.close()
                    },
                    {
                        id: 'nxt-confirm-ok',
                        text: 'Confirm',
                        class: 'nxt-btn nxt-btn-primary',
                        callback: options.onConfirm || (() => this.close())
                    }
                ],
                ...options
            };
            
            return this.show(config);
        }

        /**
         * Show alert modal
         */
        alert(options = {}) {
            const config = {
                title: 'Alert',
                content: 'This is an alert message.',
                type: 'info',
                buttons: [
                    {
                        id: 'nxt-alert-ok',
                        text: 'OK',
                        class: 'nxt-btn nxt-btn-primary',
                        callback: () => this.close()
                    }
                ],
                ...options
            };
            
            return this.show(config);
        }

        /**
         * Show loading modal
         */
        loading(message = 'Loading...') {
            const config = {
                title: '',
                content: `
                    <div class="nxt-loading-content">
                        <div class="nxt-loading-spinner"></div>
                        <p>${message}</p>
                    </div>
                `,
                type: 'loading',
                size: 'small',
                closable: false,
                buttons: []
            };
            
            return this.show(config);
        }
    }

    // Create global modal manager instance
    window.NxtModal = new ModalManager();

    // Add modal styles
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .nxt-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .nxt-modal.show {
                opacity: 1;
                visibility: visible;
            }

            .nxt-modal-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
            }

            .nxt-modal-content {
                position: relative;
                background: #ffffff;
                border-radius: 16px;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                transform: scale(0.9) translateY(-20px);
                transition: all 0.3s ease;
            }

            .nxt-modal.show .nxt-modal-content {
                transform: scale(1) translateY(0);
            }

            /* Modal Sizes */
            .nxt-modal-small .nxt-modal-content {
                max-width: 400px;
                width: 90%;
            }

            .nxt-modal-medium .nxt-modal-content {
                max-width: 500px;
                width: 90%;
            }

            .nxt-modal-large .nxt-modal-content {
                max-width: 800px;
                width: 95%;
            }

            .nxt-modal-full .nxt-modal-content {
                max-width: 95%;
                width: 95%;
                height: 95vh;
            }

            .nxt-modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 24px 32px;
                border-bottom: 1px solid #E5E5E5;
            }

            .nxt-modal-title {
                margin: 0;
                font-size: 20px;
                font-weight: 500;
                color: #000000;
                font-family: "Poppins", sans-serif;
            }

            .nxt-modal-close {
                width: 32px;
                height: 32px;
                border: none;
                background: transparent;
                color: #666666;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                padding: 0 !important;
            }

            .nxt-modal-close:hover {
                background: #F5F6FA;
                color: #000000;
            }

            .nxt-modal-close svg {
                width: 24px;
                height: 24px;
            }

            .nxt-modal-body {
                padding: 32px;
            }

            .nxt-modal-actions {
                display: flex;
                gap: 12px;
                justify-content: center;
                margin-top: 24px;
            }

            /* Modal Types */
            .nxt-modal-confirm .nxt-modal-header {
                border-bottom-color: #FFA500;
            }

            .nxt-modal-info .nxt-modal-header {
                border-bottom-color: #1F487E;
            }

            .nxt-modal-warning .nxt-modal-header {
                border-bottom-color: #FFA500;
            }

            .nxt-modal-error .nxt-modal-header {
                border-bottom-color: #E40606;
            }

            .nxt-modal-loading .nxt-modal-header {
                border-bottom: none;
                padding: 16px 32px;
            }

            /* Loading Styles */
            .nxt-loading-content {
                text-align: center;
                padding: 20px;
            }

            .nxt-loading-spinner {
                width: 40px;
                height: 40px;
                border: 4px solid #E5E5E5;
                border-top: 4px solid #1F487E;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 16px;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* Button Styles */

            .nxt-btn-secondary {
                background: #F5F6FA;
                color: #666666;
                border: 1px solid #E5E5E5;
            }

            .nxt-btn-secondary:hover {
                background: #E5E5E5;
                color: #000000;
            }

            .nxt-btn-danger {
                background: #E40606;
                color: #ffffff;
            }

            .nxt-btn-danger:hover {
                background: #c82333;
                color: #ffffff;
            }

            .nxt-btn-success {
                background: #10B981;
                color: #ffffff;
            }

            .nxt-btn-success:hover {
                background: #059669;
                color: #ffffff;
            }

            body.modal-open {
                overflow: hidden;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .nxt-modal-content {
                    width: 95%;
                    margin: 20px;
                }

                .nxt-modal-header,
                .nxt-modal-body {
                    padding: 20px;
                }

                .nxt-modal-actions {
                    flex-direction: column;
                    gap: 8px;
                }
            }
        `)
        .appendTo('head');

})(jQuery);
