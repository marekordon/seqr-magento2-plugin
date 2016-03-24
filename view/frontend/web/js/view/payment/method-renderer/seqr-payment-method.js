/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
     	'ko',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/model/quote',
        'SEQR_Plugin/js/view/payment/set-payment-method',
        'SEQR_Plugin/js/view/payment/seqr-getinvoice'
    ],
    function (
    		ko,
    		Component,
    		setShippingInformationAction,
    		stepNavigator,
    		quote,
    		setPaymentMethodAction,
    		seqrGetInvoice) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'SEQR_Plugin/payment/seqr-payment'
            },
            

            /** Returns send check to info */
            getMailingAddress: function() {
                return 'test';
            },
            
            goToNextStep: function () {
            	setPaymentMethodAction();
                stepNavigator.next();
                var test = seqrGetInvoice();
//                alert (JSON.stringify(test));
            },
            
            getCode: function () {
                return this.item.method;
            },

           
        });
    }
);
