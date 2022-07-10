<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Misc;

use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaystackVerificationApi extends PaystackSdk
{
	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * Confirm an account belongs to the right customer
	 *
	 * @param string $accountNumber Account Number
	 * @param string $bankCode You can get the list of bank codes by calling the List Bank endpoint
	 *
	 * @return PaystackVerificationApi
	 */
	public function resolveAcountNumber(string $accountNumber, string $bankCode): PaystackVerificationApi
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

			$response = $this->resource(config("paystack.endpoint.misc.resolve_account_number"))->get($customerData);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	/**
	 * Get more information about a customer's card
	 *
	 * @param string $bin First 6 characters of card
	 *
	 * @return PaystackVerificationApi
	 */
	public function resolveCardBIN(string $bin): PaystackVerificationApi
	{
		try {
			$customerData = ["bin" => $bin];

			$validator = Validator::make(
				$customerData,
				[
					"bin" => ["bail", "required", "string", "size:6"],
				],
				[] // custom validation messages
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.misc.resolve_card_bin"), $bin)->get();

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	/**
	 * Confirm the authenticity of a customer's account number before sending money
	 *
	 * @param string $accountName Customer's first and last name registered with their bank
	 * @param string $accountNumber Customer’s account number
	 * @param string $accountType This can take one of: [ personal, business ]
	 * @param string $bankCode The bank code of the customer’s bank. You can fetch the bank codes by using our List Bank endpoint
	 * @param string $countryCode The two digit ISO code of the customer’s bank
	 * @param string|null $docType Customer’s mode of identity. This could be one of: [ identityNumber, passportNumber, businessRegistrationNumber ]
	 * @param string|null $docNo Customer document identification number
	 *
	 * @return PaystackVerificationApi
	 */
	public function validateAccount(
		string $accountName,
		string $accountNumber,
		string $accountType,
		string $bankCode,
		string $countryCode,
		string $docType = null,
		string $docNo = null
	): PaystackVerificationApi {
		try {
			$customerData = [
				"account_name" => $accountName,
				"account_number" => $accountNumber,
				"account_type" => $accountType,
				"bank_code" => $bankCode,
				"country_code" => $countryCode,
				"document_type" => $docType,
				"document_number" => $docNo,
			];

			$validator = Validator::make(
				$customerData,
				!empty($docType) && !empty($docNo)
					? [
						"account_name" => ["bail", "required", "string", "size:10"],
						"account_number" => ["bail", "required", "string"],
						"account_type" => ["bail", "required", "string", Rule::in(["personal", "business"])],
						"bank_code" => ["bail", "required", "string"],
						"country_code" => ["bail", "required", "string", "size:2"],
						"document_type" => ["bail", "required", "string", Rule::in(["identityNumber", "passportNumber", "businessRegistrationNumber"])],
						"document_number" => ["bail", "required", "string", "size:2"],
					]
					: [
						"account_name" => ["bail", "required", "string", "size:10"],
						"account_number" => ["bail", "required", "string"],
						"account_type" => ["bail", "required", "string", Rule::in(["personal", "business"])],
						"bank_code" => ["bail", "required", "string"],
						"country_code" => ["bail", "required", "string", "size:2"],
					],
				[] // custom validation messages
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			/* remove document type and number if not provided  */
			if (empty($docType) && empty($docNo)) {
				unset($customerData["document_type"]);
				unset($customerData["document_number"]);
			}

			$response = $this->resource(config("paystack.endpoint.misc.validate_account"))->post($customerData);

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