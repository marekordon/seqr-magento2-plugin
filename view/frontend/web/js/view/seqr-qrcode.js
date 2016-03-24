/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'jquery',
//        'Magento_Ui/js/form/form',
        'uiComponent',
        'ko',
        'Magento_Customer/js/model/customer',
        'Magento_Customer/js/model/address-list',
        'Magento_Checkout/js/model/address-converter',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/create-shipping-address',
        'Magento_Checkout/js/action/select-shipping-address',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-address/form-popup-state',
        'Magento_Checkout/js/model/shipping-service',
        'Magento_Checkout/js/action/select-shipping-method',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/set-shipping-information',
        'Magento_Checkout/js/model/new-customer-address',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Ui/js/modal/modal',
        'mage/translate',
        'Magento_Checkout/js/action/place-order',
        'SEQR_Plugin/js/view/seqr-invoice'
    ],
    function(
        $,
        Component,
        ko,
        customer,
        addressList,
        addressConverter,
        quote,
        createShippingAddress,
        selectShippingAddress,
        shippingRatesValidator,
        formPopUpState,
        shippingService,
        selectShippingMethodAction,
        rateRegistry,
        setShippingInformationAction,
        newAddress,
        stepNavigator,
        modal,
        $t,
        placeOrderAction,
        seqrInvoice
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'SEQR_Plugin/seqr-qrcode'
            },
            isVisible: ko.observable(false),
            isPlaceOrderActionAllowed: ko.observable(quote.billingAddress() != null),


            initialize: function () {
                var self = this;
                this._super();

                stepNavigator.registerStep('seqr-qrcode', 'SEQR', this.isVisible, 30);
//            	var element = $('#opc-seqr');
//            	$('#opc-seqr').on('visibilitychange', this.initPayment());
                seqrInvoice.subscribe(
                        function (changes) {
                        	var image = document.createElement('img');
//                        	alert(typeof changes);
                        	var testowy = "asdf";
                        	testowy = changes;
                        	image.src = "https://extdev.seqr.com/se-qr-web/qrgenerator?code=" +testowy;
                            $("#seqr-code-image").replaceWith(image);
                        });

                return this;
            },
            

            placeOrder: function (data, event) {
                return true;
            },
            
            getData: function() {
                return {
                    "method": 'seqr-payment',
                    "po_number": null,
                    "cc_owner": null,
                    "cc_number": null,
                    "cc_type": null,
                    "cc_exp_year": null,
                    "cc_exp_month": null,
                    "additional_data": null
                };
            },
            
            initPayment: function() {
            	alert('initPayment');
            	
            	
//            	var payload = { cartId: quote.getQuoteId()};
//            	return storage.post(
//                        'carts/mine/seqr-payment',
//                        JSON.stringify(payload)
//                    ).done(
//                        function (response) {
//                            quote.setTotals(response.totals);
//                            paymentService.setPaymentMethods(methodConverter(response.payment_methods));
//                        }
//                    ).fail(
//                        function (response) {
//                            errorProcessor.process(response);
//                        }
//                    );
            },
            
            test: function(){
            	alert('poszło');
            }

        });
    }
);