<?php namespace ObitechBilmapay\LaravelPaystackSdk;

use ObitechBilmapay\LaravelPaystackSdk\Apis\Charge\PaystackChargeApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Misc\PaystackVerificationApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Transaction\PaystackTransactionApi;
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
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Misc\PaystackVerificationApi
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

	/**
	 * The Transactions API allows you create and manage payments on your integration
	 *
	 * @docs https://paystack.com/docs/api/#transaction-initialize
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Transaction\PaystackTransactionApi
	 */
	public static function Transaction(): PaystackTransactionApi
	{
		return new PaystackTransactionApi();
	}

	/**
	 * The Charge API allows you to configure payment channel of your choice when initiating a payment.
	 * @docs https://paystack.com/docs/api/#charge
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Charge\PaystackChargeApi
	 */
	public static function Charge(): PaystackChargeApi
	{
		return new PaystackChargeApi();
	}
}