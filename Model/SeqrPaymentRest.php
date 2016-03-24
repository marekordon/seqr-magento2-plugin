<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SEQR\Plugin\Model;

/**
 * Class SeqrPaymentRest
 */
if (!class_exists('SEQR\Plugin\Model\SeqrPaymentRest')) {
	class SeqrPaymentRest implements \SEQR\Plugin\API\InterfaceSeqrPayment
	{
	
	    /**
	     * @var \Magento\Quote\Api\GuestBillingAddressManagementInterface
	     */
	    protected $billingAddressManagement;
	
	    /**
	     * @var \Magento\Quote\Api\GuestPaymentMethodManagementInterface
	     */
	    protected $paymentMethodManagement;
	
	    /**
	     * @var \Magento\Quote\Api\GuestCartManagementInterface
	     */
	    protected $cartManagement;
	    
	    /**
	     * Core store config
	     * @var \Magento\Framework\App\Config\ScopeConfigInterface
	     */
	    protected $_scopeConfig;
	    
	    /**
	     * Core store config
	     * @var \Magento\Quote\Api\CartRepositoryInterface
	     */
	    protected $quoteRepository;
	
	    /**
	     * @param \Magento\Quote\Api\GuestBillingAddressManagementInterface $billingAddressManagement
	     * @param \Magento\Quote\Api\GuestPaymentMethodManagementInterface $paymentMethodManagement
	     * @param \Magento\Quote\Api\GuestCartManagementInterface $cartManagement
	     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
	     */
	    public function __construct(
	        \Magento\Quote\Api\GuestBillingAddressManagementInterface $billingAddressManagement,
	        \Magento\Quote\Api\GuestPaymentMethodManagementInterface $paymentMethodManagement,
	        \Magento\Quote\Api\GuestCartManagementInterface $cartManagement,
	    	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
	    	\Magento\Quote\Api\CartRepositoryInterface $quoteRepository
	    ) {
	        $this->billingAddressManagement = $billingAddressManagement;
	        $this->paymentMethodManagement = $paymentMethodManagement;
	        $this->cartManagement = $cartManagement;
	        $this->_scopeConfig = $scopeConfig;
	        $this->quoteRepository = $quoteRepository;
	    }
		
		/**
		 *
		 * {@inheritDoc}
		 *
		 */
		public function get($cart) {


			$seqrAddr = $this->_scopeConfig->getValue('payment/seqr_payment/soap_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$client = new \SoapClient($seqrAddr);
			
			$orderArray = $this->getSendInvoiceArray($cart);
			$result = $client->sendInvoice($orderArray);

			$toReturn = array(
				"url" => $result->return->invoiceQRCode,
				"invoiceReference" => $result->return->invoiceReference
			);
			
			return $toReturn;
		}
		
		
		/**
		 *
		 * @param int $cart cart ID 
		 * @return array array with order in SEQR sendInvoice format.
		 */
		private function getSendInvoiceArray($cart){
			/** @var \Magento\Quote\Model\Quote $quote */
			$quote = $this->quoteRepository->get($cart);
			$totalAmount = number_format((float)($quote->getGrandTotal()), 2, '.', '');
			
			
			$terminalId = $this->_scopeConfig->getValue('payment/seqr_payment/terminal_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$terminalPass = $this->_scopeConfig->getValue('payment/seqr_payment/terminal_password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			
			$currencyCode = $quote->getCurrency()->getStoreCurrencyCode();
			
			
			$itemRows = array_map(function($item) use ($currencyCode) {
                return array(
                    'itemDescription' => $item->getName(),
                    'itemSKU' => $item->getSku(),
                    'itemQuantity' => $item->getQty(),
                    'itemTotalAmount' => array(
                        'currency' => $currencyCode,
                        'value' => number_format((float)($item->getPrice()), 2, '.', '')
                    )
                );
            }, $quote->getAllVisibleItems());
			
			$params = array(
					'context' => array(
							'channel' => 'WS',
							'clientId' => 'marekordon',
							'initiatorPrincipalId' => array(
									'id' => $terminalId,
									'type' => 'TERMINALID',
									'userId' => '9900'
							),
							'password' => $terminalPass,
							'clientRequestTimeout' => '0',
							'clientReference' => 'Invoice No.'.$cart
					),
					'invoice' => array(
							'acknowledgmentMode' => 'NO_ACKNOWLEDGMENT',
							'title' => $this->_scopeConfig->getValue('payment/seqr_payment/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
							'totalAmount' => array(
									'currency' => $currencyCode,
									'value' => $totalAmount
							),
							'cashierId' => 'Magento2',
							'clientInvoiceId' => $cart,
							'issueDate' => date('Y-m-d\Th:i:s'),
							'paymentMode' => 'IMMEDIATE_DEBIT',
							'invoiceRows' => $itemRows
					)
			);
			
			return $params;
		}
		
		
	
	}
}