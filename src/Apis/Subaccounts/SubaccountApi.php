<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Subaccounts;

use Illuminate\Support\Facades\Validator;
use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

/**
 * ## Subaccounts
 * The Subaccounts API allows you create and manage subaccounts on your integration. Subaccounts can be used to split payment between two accounts (your main account and a sub account)
 */
class SubaccountApi extends PaystackSdk
{
	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * Create a subacount on your integration
	 *
	 * @param string $businessName Name of business for subaccount
	 * @param string $bankCode Bank Code for the bank. You can get the list of Bank Codes by calling the List Banks endpoint.
	 * @param string $accountNumber Bank Account Number
	 * @param float|int $charge The default percentage charged when receiving on behalf of this subaccount
	 * @param array<string,string|array> $customerInfo
	 *
	 *  ### customerInfo (optional)
	 * ------------------------
	 *  **name** => string - A name for the contact person for this subaccount
	 *  _______
	 *  **email** => string - A contact email for the subaccount
	 *  _______
	 *  **phone_number** => string - A phone number to call for this subaccount
	 *  _______
	 *  **description** => string - A description for this subaccount
	 *  _______
	 *  **info** => array - This will be a stringified JSON object. Add a custom_fields attribute which has an array of objects if you would like the fields to be added to your transaction when displayed on the dashboard. Sample: {"custom_fields":[{"display_name":"Cart ID","variable_name": "cart_id","value": "8393"}]}
	 *
	 * @return SubaccountApi
	 */
	public function create(string $businessName, string $bankCode, string $accountNumber, float|int $charge, array $customerInfo = []): SubaccountApi
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

			$payloadData = ["business_name" => $businessName, "settlement_bank" => $bankCode, "account_number" => $accountNumber, "percentage_charge" => $charge];

			/* merge customer data */
			$payloadData = [...$payloadData, ...$customerInfo];

			$validationParams["percentage_charge"] = ["bail", "numeric", "required"];
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

	/**
	 * Update a subaccount details on your integration
	 *
	 * @param string $id_or_code Subaccount's ID or code
	 * @param string $businessName Name of business for subaccount
	 * @param string $bankCode Bank Code for the bank. You can get the list of Bank Codes by calling the List Banks endpoint.
	 * @param string $accountNumber Bank Account Number
	 * @param float|int $charge The default percentage charged when receiving on behalf of this subaccount
	 * @param array<string,string|array> $customerInfo
	 *
	 *  ### customerInfo (optional)
	 * ------------------------
	 *  **name** => string - A name for the contact person for this subaccount
	 *  _______
	 *  **email** => string - A contact email for the subaccount
	 *  _______
	 *  **phone_number** => string - A phone number to call for this subaccount
	 *  _______
	 *  **description** => string - A description for this subaccount
	 *  _______
	 *  **info** => array - This will be a stringified JSON object. Add a custom_fields attribute which has an array of objects if you would like the fields to be added to your transaction when displayed on the dashboard. Sample: {"custom_fields":[{"display_name":"Cart ID","variable_name": "cart_id","value": "8393"}]}
	 *  _______
	 *  **status** => boolean - Activate or deactivate a subaccount. Set value to true to activate subaccount or false to deactivate the subaccount.
	 *  _______
	 *  **schedule** => string - Any of **auto**, **weekly**, **monthly**, **manual**. **Auto** means payout is T+1 and manual means payout to the subaccount should only be made when requested. Defaults to auto
	 *
	 * @return SubaccountApi
	 */
	public function update(string $id_or_code, string $businessName, string $bankCode, string $accountNumber, float|int $charge, array $customerInfo = []): SubaccountApi
	{
		try {
			if (!empty(count($customerInfo))) {
				if (isset($customerInfo["schedule"])) {
					$payloadData["settlement_schedule"] = $customerInfo["schedule"];
				}

				if (isset($customerInfo["status"])) {
					$payloadData["active"] = $customerInfo["status"];
				}

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
						$payloadData["metadata"]["custom_fields"] = $customerInfo["info"];
					}
				}
			}

			$payloadData = ["business_name" => $businessName, "settlement_bank" => $bankCode, "account_number" => $accountNumber, "percentage_charge" => $charge];

			/* merge customer data */
			$payloadData = [...$payloadData, ...$customerInfo];

			$validationParams["percentage_charge"] = ["bail", "numeric", "required"];
			$validationParams["business_name"] = ["bail", "string", "required"];
			$validationParams["settlement_bank"] = ["bail", "numeric", "required"];
			$validationParams["description"] = ["bail", "string", "nullable"];
			$validationParams["active"] = ["bail", "boolean", "nullable"];
			$validationParams["primary_contact_email"] = ["bail", "email", "nullable"];
			$validationParams["primary_contact_name"] = ["bail", "string", "nullable"];
			$validationParams["primary_contact_phone"] = ["bail", "string", "nullable"];
			$validationParams["metadata.custom_fields.*"] = ["bail", "array", "nullable"];

			$validator = Validator::make($payloadData, $validationParams);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subaccounts.update"), $id_or_code)->put($payloadData);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (\Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	/**
	 * Get details of a subaccount on your integration.
	 *
	 * @param string $id_or_code The subaccout ID
	 *
	 * @return SubaccountApi
	 */
	public function fetch(string $id_or_code): SubaccountApi
	{
		try {
			$payloadData["subaccount_id"] = $id_or_code;

			$validationParams["subaccount_id"] = ["bail", "string", "required"];

			$validator = Validator::make($payloadData, $validationParams);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subaccounts.show"), $id_or_code)->get();

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (\Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	/**
	 * List subaccounts available on your integration.
	 *
	 * @param int $perPage Specify how many records you want to retrieve per page. If not specify we use a default value of 50.
	 * @param int $page Specify exactly what page you want to retrieve. If not specify we use a default value of 1.
	 *
	 * @return SubaccountApi
	 */
	public function fetchAll(int $perPage = 50, int $page = 1): SubaccountApi
	{
		try {
			$payloadData["perPage"] = $perPage;
			$payloadData["page"] = $page;

			$validationParams["perPage"] = ["bail", "numeric", "nullable"];
			$validationParams["page"] = ["bail", "numeric", "nullable"];

			$validator = Validator::make($payloadData, $validationParams);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subaccounts.index"))->get($payloadData);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (\Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	/**
	 * Get details of a subaccount on your integration.
	 *
	 * @param string $id_or_code The subaccout ID
	 * @param boolean $status Activate or deactivate a subaccount. Set value to true to activate subaccount or false to deactivate the subaccount.
	 *
	 * @return SubaccountApi
	 */
	public function toggleSubaccountStatus(string $id_or_code, bool $status): SubaccountApi
	{
		try {
			$payloadData["subaccount_id"] = $id_or_code;
			$payloadData["status"] = $status;

			$validationParams["subaccount_id"] = ["bail", "string", "required"];
			$validationParams["status"] = ["bail", "boolean", "required"];

			$validator = Validator::make($payloadData, $validationParams);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			unset($payloadData["status"]);
			unset($payloadData["subaccount_id"]);

			$payloadData["active"] = $status;

			$response = $this->resource(config("paystack.endpoint.subaccounts.update"), $id_or_code)->put($payloadData);

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