<?php namespace ObitechBilmapay\LaravelPaystackSdk;

use ObitechBilmapay\LaravelPaystackSdk\Apis\Charge\PaystackChargeApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Invoice\PaystackInvoiceApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Misc\PaystackMiscApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Misc\PaystackVerificationApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Subaccounts\SubaccountApi;
use ObitechBilmapay\LaravelPaystackSdk\Apis\Subscription\SubscriptionBaseApi;
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

	/**
	 * The Invoices API allows you issue out and manage payment requests
	 *
	 * @docs https://paystack.com/docs/api/#invoice
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Invoice\PaystackInvoiceApi
	 */
	public static function Invoice(): PaystackInvoiceApi
	{
		return new PaystackInvoiceApi();
	}

	/**
	 * Paystack Miscellaneous apis endpoints
	 *
	 * @see https://paystack.com/docs/api/#miscellaneous-bank
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Misc\PaystackMiscApi
	 */
	public static function Misc(): PaystackMiscApi
	{
		return new PaystackMiscApi();
	}

	/**
	 * The Subaccounts API allows you create and manage subaccounts on your integration. Subaccounts can be used to split payment between two accounts (your main account and a sub account)
	 * @see https://paystack.com/docs/api/#subaccount-create
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Subaccounts\SubaccountApi
	 */
	public static function Subaccount(): SubaccountApi
	{
		return new SubaccountApi();
	}

	/**
	 * The Subscriptions API allows you create and manage recurring payment on your integration.
	 * The Plans API allows you create and manage installment payment options on your integration.
	 * @see https://paystack.com/docs/api/subscription
	 * @see https://paystack.com/docs/api/plan
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Subscription\SubscriptionBaseApi
	 */
	public static function Subscription(): SubscriptionBaseApi
	{
		return new SubscriptionBaseApi();
	}
}
