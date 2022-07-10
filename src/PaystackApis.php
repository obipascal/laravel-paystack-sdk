<?php namespace ObitechBilmapay\LaravelPaystackSdk;

use ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Misc\PaystackVerificationApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Transfer\PaystackTransferApi;

class PaystackApis extends PaystackSdk
{
	/**
	 * The Paystack customer api, this api handles customer creation, update etc for more info
	 * refer to the paystack documentation
	 *
	 * @param string|null $secretKey
	 * @param string $baseUrl
	 * @param string $appName
	 *
	 * @docs https://paystack.com/docs/api/#customer
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public static function Customer(): PaystackCustomerApi
	{
		return new PaystackCustomerApi();
	}

	/**
	 * The Verification API allows you perform KYC processes
	 * @category Feature Availability: This feature is only available to businesses in Nigeria.
	 * @docs https://paystack.com/docs/api/#verification
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Misc\PaystackVerificationApi
	 */
	public static function Verification(): PaystackVerificationApi
	{
		return new PaystackVerificationApi();
	}

	/**
	 * 1. Creates a new recipient. A duplicate account number will lead to the retrieval of the existing record.
	 * 2. The Transfers API allows you automate sending money on your integration
	 * @docs https://paystack.com/docs/api/#transfer-recipient, https://paystack.com/docs/api/#transfer
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Transfer\PaystackTransferApi
	 */
	public static function Transfer(): PaystackTransferApi
	{
		return new PaystackTransferApi();
	}
}