<?php namespace ObitechBilmapay\LaravelPaystackSdk;

use ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi;
use ObitechBilmapay\LaravelPaystackSdk\Misc\PaystackVerificationApi;

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
}