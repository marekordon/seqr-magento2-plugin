<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
* See COPYING.txt for license details.
*/
namespace SEQR\Plugin\API;

interface InterfaceSeqrPayment
{
// 	/**
// 	 * Set payment information and place order for a specified cart.
// 	 *
// 	 * @param string $cartId
// 	 * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
// 	 * @param \Magento\Quote\Api\Data\AddressInterface $billingAddress
// 	 * @throws \Magento\Framework\Exception\CouldNotSaveException
// 	 * @return string qrcode.
// 	 */
// 	public function getQRCode(
// 			$cartId,
// 			\Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
// 			\Magento\Quote\Api\Data\AddressInterface $billingAddress
// 			);

	/**
	 * Set payment information and place order for a specified cart.
	 *
	 * @param int $b cokolwiek
	 * @return string qrcode.
	 */
	public function get($cart);
	

}