<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Subaccounts;

use Illuminate\Support\Facades\Validator;
use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

class SubaccountApi extends PaystackSdk
{
	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * Create a new subaccount
	 *
	 * @param string $businessName The business name for this subaccount.
	 * @param string $bankCode The verified bank code of the bank
	 * @param string $accountNumber The verified account number associated with this subaccount
	 * @param float|int $charge This is the flat fee amount or percentage charge you want to charge this subaccount. NOTE: if $charge_as_flat_fee is set to TRUE the charge should be an integer amount otherwise a floating percentage.
	 * @param bool $charge_as_flat_fee
	 * @param array<string,string|array> $customerInfo
	 *
	 *  Customer Info Params(optional)
	 * ------------------------
	 * [
	 *
	 *  name => string,
	 *  _______
	 *  email => string,
	 *  _______
	 *  phone_number => string,
	 *  _______
	 *  description => string,
	 *  _______
	 *  info => array
	 *
	 * ]
	 *
	 * @return SubaccountApi
	 */
	public function create(string $businessName, string $bankCode, string $accountNumber, float|int $charge, bool $charge_as_flat_fee = false, array $customerInfo = []): SubaccountApi
	{
		try {
			if (!empty(count($customerInfo))) {
				if (isset($customerInfo["description"])) {
					$payloadData["description"] = $customerInfo["description"];
				}

				if (isset($customerInfo["email"])) {
					$payloadData["primary_contact_email"] = $customerInfo["email"];
				}

				if (isset($customerInfo["name"])) {
					$payloadData["primary_contact_name"] = $customerInfo["name"];
				}

				if (isset($customerInfo["phone_number"])) {
					$payloadData["primary_contact_phone"] = $customerInfo["phone_number"];
				}

				if (isset($customerInfo["info"])) {
					/* Addtional information must be an array before been included as metadata on payload request. */
					if (is_array($customerInfo["info"])) {
						$payloadData["metadata"] = $customerInfo["info"];
					}
				}
			}

			if ($charge_as_flat_fee) {
				$payloadData = ["business_name" => $businessName, "settlement_bank" => $bankCode, "account_number" => $accountNumber, "transaction_charge" => $charge];

				$validationParams["transaction_charge"] = ["bail", "numeric", "required"];
			} else {
				$payloadData = ["business_name" => $businessName, "settlement_bank" => $bankCode, "account_number" => $accountNumber, "percentage_charge" => $charge];
				$validationParams["percentage_charge"] = ["bail", "numeric", "required"];
			}

			/* merge customer data */
			$payloadData = [...$payloadData, ...$customerInfo];

			$validationParams["business_name"] = ["bail", "string", "required"];
			$validationParams["settlement_bank"] = ["bail", "numeric", "required"];
			$validationParams["description"] = ["bail", "string", "nullable"];
			$validationParams["primary_contact_email"] = ["bail", "email", "nullable"];
			$validationParams["primary_contact_name"] = ["bail", "string", "nullable"];
			$validationParams["primary_contact_phone"] = ["bail", "string", "nullable"];
			$validationParams["metadata"] = ["bail", "array", "nullable"];

			$validator = Validator::make($payloadData, $validationParams);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			dd($validationParams);
			$response = $this->resource(config("paystack.endpoint.subaccounts.create"))->post($payloadData);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (\Exception $th) {
			return $this->setError($th->getMessage());
		}
	}
}