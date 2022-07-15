<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Charge;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

class PaystackChargeApi extends PaystackSdk
{
	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * Generate a bank ussed code that user can pay offline
	 *
	 * @param int|float $amount
	 * @param string $email
	 * @param string $reference
	 * @param array $options
	 *
	 * @return PaystackChargeApi
	 */
	public function ussd(int|float $amount, string $email, string $bankCode, string $reference = "", array $options = []): PaystackChargeApi
	{
		try {
			$customerData = ["amount" => $amount * 100, "email" => $email, "ussd" => ["type" => $bankCode], "metadata" => $options];

			$validator = Validator::make(
				[...$customerData, "bank" => $bankCode],
				[
					"amount" => ["bail", "required", "numeric"],
					"email" => ["bail", "required", "email"],
					"bank" => ["bail", "required", "string"],
				]
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			if (!empty($reference)) {
				$customerData["reference"] = $reference;
			}

			$response = $this->resource(config("paystack.endpoint.charge.initiate"))->post($customerData);

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