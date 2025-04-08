jQuery(document).ready(function($) {
    'use strict';

    $('#payorc-lookup').on('click', function() {
        var orderId = $('#payorc-order-id').val();
        
        if (!orderId) {
            alert(payorcL10n.enterOrderId);
            return;
        }

        var $button = $(this);
        var $result = $('#payorc-transaction-result');
        var $notice = $result.find('.notice');
        var $jsonResponse = $result.find('.json-response');

        // Disable button and show loading state
        $button.prop('disabled', true).text('Looking up...');
        $result.hide();
        $notice.removeClass('success error').empty();
        $jsonResponse.empty();

        // Make AJAX request
        $.ajax({
            url: payorcAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'payorc_get_transaction',
                nonce: payorcAdmin.nonce,
                order_id: orderId
            },
            success: function(response) {
                if (response.success) {
                    $notice.addClass('success').text('Transaction found successfully.');
                    
                    // Format the response data
                    var formattedResponse = {
                        m_order_id: response.data.transaction.id_order || "",
                        p_order_id: response.data.transaction.p_order_id || "",
                        p_request_id: response.data.transaction.p_request_id || "",
                        psp_ref_id: response.data.transaction.psp_ref_id || "",
                        psp_txn_id: response.data.transaction.psp_txn_id || "",
                        transaction_id: response.data.transaction.transaction_id || "",
                        status: response.data.transaction.status || "",
                        status_code: response.data.transaction.status_code || "",
                        remark: response.data.transaction.remark || "",
                        paydart_category: response.data.transaction.paydart_category || "",
                        currency: response.data.transaction.currency || "",
                        amount: response.data.transaction.amount || "",
                        m_customer_id: response.data.transaction.id_customer || "",
                        psp: response.data.transaction.psp || "",
                        payment_method: response.data.transaction.payment_method || "",
                        m_payment_token: response.data.transaction.m_payment_token || "",
                        transaction_time: response.data.transaction.transaction_time || "",
                        return_url: response.data.transaction.return_url || "",
                        payment_method_data: {
                            scheme: response.data.transaction.cc_schema || "",
                            card_country: response.data.transaction.card_country || "",
                            card_type: response.data.transaction.cc_type || "",
                            mask_card_number: response.data.transaction.cc_mask || ""
                        },
                        apm_name: response.data.transaction.apm_name || "",
                        apm_identifier: response.data.transaction.apm_identifier || "",
                        sub_merchant_identifier: response.data.transaction.sub_merchant_identifier || "",
                        ajax: "1"
                    };

                    // Convert to pretty JSON string
                    var prettyJson = JSON.stringify(formattedResponse, null, 2);
                    
                    // Add syntax highlighting
                    prettyJson = prettyJson.replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                            var cls = 'number';
                            if (/^"/.test(match)) {
                                if (/:$/.test(match)) {
                                    cls = 'key';
                                } else {
                                    cls = 'string';
                                }
                            } else if (/true|false/.test(match)) {
                                cls = 'boolean';
                            } else if (/null/.test(match)) {
                                cls = 'null';
                            }
                            return '<span class="' + cls + '">' + match + '</span>';
                        });
                    
                    $jsonResponse.html(prettyJson);
                } else {
                    $notice.addClass('error').text(response.data.message || 'No transaction found.');
                }
            },
            error: function() {
                $notice.addClass('error').text('An error occurred while looking up the transaction.');
            },
            complete: function() {
                $button.prop('disabled', false).text('Lookup Transaction');
                $result.show();
            }
        });
    });

    // Handle Enter key in the input field
    $('#payorc-order-id').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#payorc-lookup').click();
        }
    });
}); 