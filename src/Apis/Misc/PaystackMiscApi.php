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
	 * @param string $country The targed country. Default to Nigeria
	 * @param string $perPage Number of banks to list per page.
	 * @param array $params Other paystack query string params.
	 * @see https://paystack.com/docs/api/#miscellaneous-bank
	 *
	 * @return PaystackMiscApi
	 */
	public function fetchBanks(
		string $country = "nigeria",
		int $perPage = 50,
		array $params = []
	): PaystackMiscApi {
		try {
			$customerData = ["country" => $country, "perPage" => $perPage, ...$params];

			$validator = Validator::make(
				$customerData,
				[
					"country" => ["bail", "required", "string"],
					"perPage" => ["bail", "required", "numeric"],
				],
				[] // custom validation messages
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.misc.banks"))->get($customerData);

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