/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Customer/js/model/customer',
        'SEQR_Plugin/js/view/seqr-invoice'
    ],
    function ($, quote, urlBuilder, storage, errorProcessor, customer, seqrInvoice) {
        'use strict';

        return function () {
            var serviceUrl,
                payload,
                paymentData = quote.paymentMethod();

            /**
             * Checkout for guest and registered customer.
             */
            if (!customer.isLoggedIn()) {
                serviceUrl = urlBuilder.createUrl('/guest-carts/:cartId/selected-payment-method', {
                    cartId: quote.getQuoteId()
                });
                payload = {
                    cartId: quote.getQuoteId(),
                    method: paymentData
                };
            } else {
                serviceUrl = urlBuilder.createUrl('/carts/mine/seqr-payment', {});
                payload = {
                    cartId: quote.getQuoteId(),
                };
            }

            $.ajax({ 
            	   type: "GET",
            	   dataType: "json",
            	   url: "/".concat(serviceUrl),
            	   success: function(response){ 
//            		   alert('success');
            		   seqrInvoice(response[0]);
            	   },
            	   fail : function (response) {
                     errorProcessor.process(response);
                   }
            });
        };
    }
);