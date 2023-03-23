<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Transaction;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

class PaystackTransactionApi extends PaystackSdk
{
	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * Initialize a transaction from your backend
	 *
	 * @param int|float $amount Amount should be in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR
	 * @param string $email Customer's email address
	 * @param string $currency The transaction currency (NGN, GHS, ZAR or USD). Defaults to your integration currency.
	 * @param string|null $reference Unique transaction reference. Only -, ., = and alphanumeric characters allowed.
	 * @param array $options This could be any other paystack options list here => https://paystack.com/docs/api/#transaction-initialize
	 *
	 * @return PaystackTransactionApi
	 */
	public function createLink(int|float $amount, string $email, string $currency = "NGN", string $reference = null, array $options = []): PaystackTransactionApi
	{
		try {
			$paramsData = ["amount" => $amount * 100, "email" => $email, "reference" => $reference, "currency" => $currency];

			$validator = Validator::make($paramsData, [
				"amount" => ["bail", "required", "numeric"],
				"email" => ["bail", "required", "email"],
				"currency" => ["bail", "required", "string", "size:3"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			if (!empty($reference)) {
				$paramsData["reference"] = $reference;
			}

			$data = !empty(count($options)) ? [...$paramsData, ...$options] : $paramsData;

			$response = $this->resource(config("paystack.endpoint.transaction.initiate"))->post($data);

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
	 * Initialize a transaction from using a subscription plan code. This will automatically create a subscription for that plan.
	 *
	 * @param string $planCode The plan card as returned when calling the subscription apis
	 * @param string $email Customer's email address
	 * @param string $currency The transaction currency (NGN, GHS, ZAR or USD). Defaults to your integration currency.
	 * @param string|null $reference Unique transaction reference. Only -, ., = and alphanumeric characters allowed.
	 * @param array $optionals This could be any other paystack optional parameters on their documentation you which to pass along the api call  view a list of them here => https://paystack.com/docs/api/#transaction-initialize
	 *
	 * @return PaystackTransactionApi
	 */
	public function createFromPlanCode(string $planCode, string $email, string $currency = "NGN", string $reference = null, array $optionals = []): PaystackTransactionApi
	{
		try {
			$paramsData = ["plan" => $planCode, "email" => $email, "reference" => $reference, "currency" => $currency];

			$validator = Validator::make($paramsData, [
				"plan" => ["bail", "required", "string"],
				"email" => ["bail", "required", "email"],
				"currency" => ["bail", "required", "string", "size:3", Rule::in(["NGN", "ZAR", "GHN", "USD"])],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			if (!empty($reference)) {
				$paramsData["reference"] = $reference;
			}

			$data = !empty(count($optionals)) ? [...$paramsData, ...$optionals] : $paramsData;

			$response = $this->resource(config("paystack.endpoint.transaction.initiate"))->post($data);

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
	 * Confirm the status of a transaction
	 *
	 * @param string $reference
	 *
	 * @return PaystackTransactionApi
	 */
	public function verify(string $reference): PaystackTransactionApi
	{
		try {
			$paramsData = ["reference" => $reference];

			$validator = Validator::make($paramsData, [
				"reference" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transaction.verify"), $reference)->get();

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
	 * List transactions carried out on your integration.
	 *
	 * @param int $perPage
	 * @param int $page
	 *
	 * @return PaystackTransactionApi
	 */
	public function fetchTransactions(int $perPage = 50, int $page = 1): PaystackTransactionApi
	{
		try {
			$requestData = ["perPage" => $perPage, "page" => $page];

			$validator = Validator::make(
				$requestData,
				[
					"perPage" => ["bail", "numeric"],
					"page" => ["bail", "numeric"],
				],
				[] // custom validation messages
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transaction.all"))->get($requestData);

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
	 * Get details of a transaction carried out on your integration.
	 *
	 * @param int|string $id
	 *
	 * @return PaystackTransactionApi
	 */
	public function fetchTransaction(int|string $id): PaystackTransactionApi
	{
		try {
			$requestData = ["transaction_id" => $id];

			$validator = Validator::make($requestData, [
				"transaction_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transaction.single"), $id)->get();

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
	 * All authorizations marked as reusable can be charged with this endpoint whenever you need to receive payments.
	 *
	 * @param int|float $amount
	 * @param string $email
	 * @param string $authcode
	 * @param string $currency
	 * @param int $reference
	 * @param array $options
	 *
	 * @return PaystackTransactionApi
	 */
	public function chargeAuth(int|float $amount, string $email, string $authcode, string $currency = "NGN", string|int $reference = 0, array $options): PaystackTransactionApi
	{
		try {
			$paramsData = ["amount" => $amount * 100, "email" => $email, "authorization_code" => $authcode, "reference" => $reference, "currency" => $currency];

			$validator = Validator::make($paramsData, [
				"amount" => ["bail", "required", "numeric"],
				"email" => ["bail", "required", "email"],
				"currency" => ["bail", "required", "string", "size:3"],
				"authorization_code" => ["bail", "required", "string", "starts_with:AUTH_"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			if (!empty($reference)) {
				$paramsData["reference"] = $reference;
			}

			$data = !empty(count($options)) ? [...$paramsData, ...$options] : $paramsData;

			$response = $this->resource(config("paystack.endpoint.transaction.chargeAuth"))->post($data);

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
	 * All Mastercard and Visa authorizations can be checked with this endpoint to know if they have funds for the payment you seek.
	 *___________
	 * his endpoint should be used when you do not know the exact amount to
	 * charge a card when rendering a service. It should be used to
	 * check if a card has enough funds based on a maximum range value.
	 * It is well suited for:
	 * 1. Ride hailing services
	 * 2. Logistics services
	 *
	 * @param int|float $amount
	 * @param string $email
	 * @param string $authcode
	 * @param string $currency
	 *
	 * @return PaystackTransactionApi
	 */
	public function checkAuth(int|float $amount, string $email, string $authcode, string $currency = "NGN"): PaystackTransactionApi
	{
		try {
			$paramsData = ["amount" => $amount * 100, "email" => $email, "authorization_code" => $authcode, "currency" => $currency];

			$validator = Validator::make($paramsData, [
				"amount" => ["bail", "required", "numeric"],
				"email" => ["bail", "required", "email"],
				"currency" => ["bail", "required", "string", "size:3"],
				"authorization_code" => ["bail", "required", "string", "starts_with:AUTH_"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transaction.checkAuth"))->post($paramsData);

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
	 * View the timeline of a transaction
	 *
	 * @param int|string $id_or_reference
	 *
	 * @return PaystackTransactionApi
	 */
	public function fetchTimeline(int|string $id_or_reference): PaystackTransactionApi
	{
		try {
			$requestData = ["transaction_id" => $id_or_reference];

			$validator = Validator::make($requestData, [
				"transaction_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transaction.timeline"), $id_or_reference)->get();

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
	 * Total amount received on your account
	 *
	 * @param int $perPage
	 * @param int $page
	 * @param string $from
	 * @param string $to
	 *
	 * @return PaystackTransactionApi
	 */
	public function fetchTransactionTotals(int $perPage = 100, int $page = 1, string $from = "", string $to = ""): PaystackTransactionApi
	{
		try {
			if (!empty($from) && !empty($page)) {
				$paramsData = ["from" => $from, "to" => $to, "perPage" => $perPage, "page" => $page];
			} else {
				$paramsData = ["perPage" => $perPage, "page" => $page];
			}

			$validator = Validator::make(
				$paramsData,
				!empty($from) && !empty($to)
					? [
						"perPage" => ["bail", "required", "numeric"],
						"page" => ["bail", "required", "numeric"],
						"from" => ["bail", "required", "date"],
						"to" => ["bail", "required", "date"],
					]
					: [
						"perPage" => ["bail", "required", "numeric"],
						"page" => ["bail", "required", "numeric"],
					]
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transaction.totals"))->get($paramsData);

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
	 * List transactions carried out on your integration.
	 *
	 * @param array $options
	 *
	 * @return PaystackTransactionApi
	 */
	public function export(array $options = []): PaystackTransactionApi
	{
		try {
			$response = $this->resource(config("paystack.endpoint.transaction.export"))->get($options);

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
	 * Retrieve part of a payment from a customer
	 *
	 * @param int|float $amount
	 * @param string $email
	 * @param string $authcode
	 * @param string $currency
	 * @param string $atLeast
	 * @param string $reference
	 *
	 * @return PaystackTransactionApi
	 */
	public function partialDebit(int|float $amount, string $email, string $authcode, string $currency = "NGN", string $atLeast = "", string|int $reference = ""): PaystackTransactionApi
	{
		try {
			$paramsData = ["amount" => $amount * 100, "email" => $email, "authorization_code" => $authcode, "currency" => $currency];

			$validator = Validator::make($paramsData, [
				"amount" => ["bail", "required", "numeric"],
				"email" => ["bail", "required", "email"],
				"currency" => ["bail", "required", "string", "size:3"],
				"authorization_code" => ["bail", "required", "string", "starts_with:AUTH_"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			if (!empty($reference)) {
				$paramsData["reference"] = $reference;
			}

			if (!empty($atLeast)) {
				$paramsData["at_least"] = $atLeast;
			}

			$response = $this->resource(config("paystack.endpoint.transaction.partialDebit"))->post($paramsData);

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
