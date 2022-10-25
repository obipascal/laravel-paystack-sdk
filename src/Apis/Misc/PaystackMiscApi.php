<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Misc;

use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaystackMiscApi extends PaystackSdk
{
	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * List / fetch banks
	 *
	 * @param string $accountNumber Account Number
	 * @param string $bankCode You can get the list of bank codes by calling the List Bank endpoint
	 *
	 * @return PaystackMiscApi
	 */
	public function resolveAcountNumber(string $accountNumber, string $bankCode): PaystackMiscApi
	{
		try {
			$customerData = ["account_number" => $accountNumber, "bank_code" => $bankCode];

			$validator = Validator::make(
				$customerData,
				[
					"account_number" => ["bail", "required", "string"],
					"bank_code" => ["bail", "required", "string"],
				],
				[] // custom validation messages
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.misc.resolve_account_number"))->get(
				$customerData
			);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}
}