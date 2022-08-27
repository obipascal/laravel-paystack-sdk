<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Customer;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

class PaystackCustomerApi extends PaystackSdk
{
	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * Create New customer
	 *
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $email
	 * @param string $phoneNumber
	 * @param array $metadata
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function createCustomer(string $firstName, string $lastName, string $email, string $phoneNumber, array $metadata = []): PaystackCustomerApi
	{
		try {
			$customerData = ["first_name" => $firstName, "last_name" => $lastName, "email" => $email, "phone_number" => $phoneNumber, "metadata" => $metadata];

			$validator = Validator::make(
				$customerData,
				[
					"first_name" => ["bail", "required", "string"],
					"last_name" => ["bail", "required", "string"],
					"phone" => ["bail", "required", "string"],
					"email" => ["bail", "required", "email"],
				],
				[] // custom validation messages
			);
			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.customer.create"))->post($customerData);

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
	 * Get details of a customer on your integration.
	 *
	 * @param int|string $customerId An email or customer code for the customer you want to fetch
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function getCustomer(int|string $customerId): PaystackCustomerApi
	{
		try {
			$params = ["customer_id" => $customerId];

			$validator = Validator::make(
				$params,
				[
					"customer_id" => ["bail", "required", "string"],
				],
				[] // custom validation messages
			);
			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.customer.signle"), $customerId)->get();

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
	 * List customers available on your integration.
	 *
	 * @param int $perPage
	 * @param int $page
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function getCustomers(int $perPage = 50, int $page = 1): PaystackCustomerApi
	{
		try {
			$params = ["perPage" => $perPage, "page" => $page];

			$validator = Validator::make(
				$params,
				[
					"perPage" => ["bail", "required", "numeric"],
					"page" => ["bail", "required", "numeric"],
				],
				[] // custom validation messages
			);
			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.customer.all"))->get($params);

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
	 * Update a customer's details on your integration
	 *
	 * @param string|int $customerCode
	 * @param string $firstName
	 * @param string $lastName
	 * @param string|null $phoneNumber
	 * @param array $customData
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function updateCustomer(string|int $customerCode, string $firstName, string $lastName, string $phoneNumber = null, array $customData = []): PaystackCustomerApi
	{
		try {
			$customerData = ["customer_code" => $customerCode, "first_name" => $firstName, "last_name" => $lastName, "phone_number" => $phoneNumber, "metadata" => $customData];

			$validator = Validator::make(
				$customerData,
				[
					"customer_code" => ["bail", "required"],
					"first_name" => ["bail", "required", "string"],
					"last_name" => ["bail", "required", "string"],
				],
				[] // custom validation messages
			);
			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			/* remove any paramters that are empty */
			foreach ($customerData as $field => $value) {
				if (empty($value)) {
					unset($customerData[$field]);
				}
			}

			/* remove the customer code as this is not required at paystack level. */
			unset($customerData["customer_code"]);

			$response = $this->resource(config("paystack.endpoint.customer.update"), $customerCode)->put($customerData);

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
	 * Validate a customer's identity
	 *
	 * @param string|int $customerCode
	 * @param array $params
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function validateCustomerIdentity(string|int $customerCode, array $params): PaystackCustomerApi
	{
		try {
			$customerData = ["customer_code" => $customerCode, ...$params];

			$validator = Validator::make(
				$customerData,
				[
					"customer_code" => ["bail", "required"],
					"first_name" => ["bail", "required", "string"],
					"country" => ["bail", "required", "string", "size:2"],
					"type" => ["bail", "required", "string", Rule::in(["bank_account", "bvn"])],
					"bvn" => ["bail", "required", "string", "size:11"],
					"bank_code" => ["bail", "required", "numeric"],
					"account_number" => ["bail", "required", "string", "size:10"],
				],
				[] // custom validation messages
			);
			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			/* remove any paramters that are empty */
			foreach ($customerData as $field => $value) {
				if (empty($value)) {
					unset($customData[$field]);
				}
			}

			/* remove the customer code as this is not required at paystack level. */
			unset($customerData["customer_code"]);

			$response = $this->resource(config("paystack.endpoint.customer.validate"), $customerCode)->post($customerData);

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
	 * Whitelist or blacklist a customer on your integration
	 *
	 * @param string|int $customerCode Customer's code, or email address
	 * @param string $action One of the possible risk actions [ default, deny ]. allow to whitelist. deny to blacklist. Customers start with a default risk action.
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function customerRiskManagement(string|int $customer, string $action): PaystackCustomerApi
	{
		try {
			$customerData = ["customer" => $customer, "risk_action" => $action];

			$validator = Validator::make(
				$customerData,
				[
					"customer" => ["bail", "required"],
					"risk_action" => ["bail", "required", "string", Rule::in(["default", "deny"])],
				],
				[
					"risk_action.in" => "The selected risk action value is invalid allowed values are [default | deny]",
				] // custom validation messages
			);
			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.customer.risk_action"))->post($customerData);

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
	 * Deactivate an authorization when the card needs to be forgotten
	 *
	 * @param string $cardAuthorizationCode Authorization code to be deactivated
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function revokeCustomerAuthorization(string $cardAuthorizationCode): PaystackCustomerApi
	{
		try {
			$customerData = ["authorization_code" => $cardAuthorizationCode];

			$validator = Validator::make(
				$customerData,
				[
					"authorization_code" => ["bail", "required", "string", "starts_with:AUTH_"],
				],
				[] // custom validation messages
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.customer.revoke_auth"))->post($customerData);

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