jQuery(function($) {
    'use strict';

    var PayOrc = {
        init: function() {
            this.bindEvents();
            this.attachEvent();
        },

        bindEvents: function() {
            $('form.checkout').on('checkout_place_order_payorc', this.handleSubmit.bind(this));
        },

        handleSubmit: function() {
            if (payorc_params.checkout_mode === 'iframe') {
                // For iframe mode, let the form submit normally but handle the response in process_payment
                return true;
            }
            return true; // For hosted mode, let the form submit normally
        },

        openModal: function(iframeUrl) {
            // Create modal
            var modal = $('<div/>', {
                id: 'payorc-modal',
                css: {
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%',
                    'background': 'rgba(0,0,0,0.5)',
                    'z-index': '999999',
                    'display': 'flex',
                    'align-items': 'center',
                    'justify-content': 'center'
                }
            });

            // Create loader
            var loader = $('<div/>', {
                id: 'payorc-loader',
                css: {
                    'position': 'absolute',
                    'top': '50%',
                    'left': '50%',
                    'transform': 'translate(-50%, -50%)',
                    'z-index': '2'
                }
            }).html('<img src="' + payorc_params.plugin_url + 'assets/images/spinner-loader.gif" width="60px" height="60px" />');

            // Create iframe container
            var iframeContainer = $('<div/>', {
                css: {
                    'background': '#fff',
                    'padding': '0px',
                    'border-radius': '5px',
                    'position': 'relative',
                    'max-width': '90%',
                    'max-height': '90vh'
                }
            });

            // Create close button
            var closeButton = $('<button/>', {
                text: '×',
                css: {
                    'position': 'absolute',
                    'right': '10px',
                    'top': '10px',
                    'border': 'none',
                    'background': 'none',
                    'font-size': '24px',
                    'cursor': 'pointer',
                    'z-index': '3'
                }
            });

            // Create iframe
            var iframe = $('<iframe/>', {
                id: 'payorc-iframe',
                src: iframeUrl,
                css: {
                    'width': '90vh',
                    'height': '80vh',
                    'border': 'none'
                }
            });

            // Append elements
            iframeContainer.append(closeButton, loader, iframe);
            modal.append(iframeContainer);
            $('body').append(modal);

            // Handle close button click
            closeButton.on('click', function() {
                modal.remove();
                $('#payorc-loader').hide();
                $('#payorc-iframe').attr('src', '');
            });

            $(document).on('click', '#payorc-modal .modal-content span.close', function(e) {
                $('#payorc-modal').hide();
                $('#payorc-loader').hide();
                $('#payorc-iframe').attr('src', '');
            });

            // Show loader initially and hide after 3 seconds
            $('#payorc-loader').show();
            setTimeout(function() {
                $('#payorc-loader').hide();
            }, 3000);
        },

        attachEvent: function() {
            var self = this;
            var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
            var eventer = window[eventMethod];
            var messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";

            eventer(messageEvent, function(e) {
                console.log(e);
                try {
                    // Fix JSON data with escaped parameters and custom_data
                    let fixedData = e.data.replace(/"parameters":"(\[.*?\])"/g, function(match, p1) {
                        return `"parameters":"${p1.replace(/\\/g, '\\\\').replace(/"/g, '\\"')}"`;
                    }).replace(/"custom_data":"(\[.*?\])"/g, function(match, p1) {
                        return `"custom_data":"${p1.replace(/\\/g, '\\\\').replace(/"/g, '\\"')}"`;
                    }).replace(/"(\{.*?\})"/g, function(match) {
                        return match.replace(/\\/g, '\\\\').replace(/"/g, '\\"');
                    });
                    
                    var result = JSON.parse(fixedData);
                    console.log('PayOrc payment response:', result);

                    switch (result.status) {
                        case 'SUCCESS':
                            $('#payorc-loader').show();
                            self.handleValidation(result);
                            break;

                        case 'CANCELLED':
                            $('#payorc-loader').hide();
                            $('#payorc-iframe').attr('src', '');
                            setTimeout(function() {
                                $('#payorc-modal').remove();
                                window.location.href = result.return_url;
                            }, 200);
                            break;

                        case 'FAILED':
                            $('#payorc-loader').show();
                            self.handleValidation(result);
                            break;
                    }
                } catch (error) {
                    console.error('Error processing payment response:', error);
                    $('#payorc-loader').hide();
                    $('#payorc-modal').remove();
                    window.location.href = wc_checkout_params.checkout_url;
                }
            }, false);

            // Check for iframe URL in query parameters and open modal if present
            var urlParams = new URLSearchParams(window.location.search);
            var iframeUrl = urlParams.get('iframe_url');
            if (iframeUrl && payorc_params.checkout_mode === 'iframe') {
                this.openModal(decodeURIComponent(iframeUrl));
            }
        },

        handleValidation: function(data) {
            data.ajax = 1;
            
            // Show loader during validation
            $('#payorc-loader').show();
            
            $.ajax({
                type: 'POST',
                url: payorc_params.ajax_url,
                data: {
                    action: 'payorc_validate_payment',
                    nonce: payorc_params.nonce,
                    payment_data: data
                },
                dataType: 'json',
                success: function(response) {
                    console.log('PayOrc validation response:', response);
                    
                    if (response.success) {
                        setTimeout(function() {
                            window.location.href = response.data.redirect_url;
                        }, 1000);
                    } else {
                        console.error('Payment validation failed:', response.data ? response.data.message : 'Unknown error');
                        $('#payorc-loader').hide();
                        $('#payorc-modal').remove();
                        
                        // Add error message to checkout page
                        if ($('.woocommerce-error').length) {
                            $('.woocommerce-error').remove();
                        }
                        
                        var errorMessage = response.data && response.data.message 
                            ? response.data.message 
                            : 'Payment validation failed. Please try again.';
                            
                        $('form.checkout').prepend(
                            '<div class="woocommerce-error">' + errorMessage + '</div>'
                        );
                        
                        // Remove automatic reload for failed payments
                        if (data.status === 'FAILED') {
                            $('html, body').animate({
                                scrollTop: $('.woocommerce-error').offset().top - 100
                            }, 1000);
                        } else {
                            setTimeout(function() {
                                window.location.href = response.data ? response.data.redirect_url : wc_checkout_params.checkout_url;
                            }, 3000);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('PayOrc AJAX error:', {
                        status: jqXHR.status,
                        statusText: jqXHR.statusText,
                        responseText: jqXHR.responseText,
                        textStatus: textStatus,
                        errorThrown: errorThrown
                    });
                    
                    $('#payorc-loader').hide();
                    $('#payorc-modal').remove();
                    
                    // Add error message to checkout page
                    if ($('.woocommerce-error').length) {
                        $('.woocommerce-error').remove();
                    }
                    
                    $('form.checkout').prepend(
                        '<div class="woocommerce-error">An error occurred during payment validation. Please try again.</div>'
                    );
                    
                    // Scroll to error message instead of reloading
                    $('html, body').animate({
                        scrollTop: $('.woocommerce-error').offset().top - 100
                    }, 1000);
                }
            });
        }
    };

    PayOrc.init();
});