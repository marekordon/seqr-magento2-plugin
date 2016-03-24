<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SEQR\Plugin\Model;



/**
 * Pay In Store payment method model
 */
class Payment extends \Magento\Payment\Model\Method\AbstractMethod
{

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'seqr-payment';

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = false;


    /**
     * Check whether payment method can be used
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {
    	$checkResult = new \StdClass();
    	$isActive = $this->isActive($quote ? $quote->getStoreId() : null);
    	$checkResult->isAvailable = $isActive;
    	$checkResult->isDeniedInConfig = !$isActive;
    	// for future use in observers
    	$this->_eventManager->dispatch(
    			'payment_method_is_active',
    			[
    					'result' => $checkResult,
    					'method_instance' => $this,
    					'quote' => $quote
    			]
    			);
    	$this->_logger->debug($checkResult->isAvailable);
    	return $checkResult->isAvailable;
    }

}
