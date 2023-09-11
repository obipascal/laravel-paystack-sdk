<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Customer;
use Illuminate\Support\Facades\Validator;

trait PaystackVirtualAccountApi
{
	/**
	 * Create a dedicated virtual account for an existing customer
	 * @param string $customer Customer ID or code
	 * @param string $preferred_bank (optional) The bank slug for preferred bank. To get a list of available banks, use the List Banks endpoint, passing pay_with_bank_transfer=true query parameter
	 * @param string $subaccount (optional) Subaccount code of the account you want to split the transaction with
	 * @param string $split_code (optional) Split code consisting of the lists of accounts you want to split the transaction with
	 * @param string $first_name (optional) Customer's first name
	 * @param string $last_name (optional) Customer's last name
	 * @param string $phone (optional) Customer's phone number
	 *
	 * @param array<string> $data
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function createVirtaulAccount(array $data = []): PaystackCustomerApi
	{
		try {
			$customerData = [...$data];

			$response = $this->resource(config("paystack.endpoint.dva.create"))->post($customerData);

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
	 * With this endpoint, you can create a customer, validate the customer, and assign a DVA to the customer.
	 * @param string $preferred_bank  The bank slug for preferred bank. To get a list of available banks, use the List Banks endpoint, passing pay_with_bank_transfer=true query parameter
	 * @param string $subaccount  Subaccount code of the account you want to split the transaction with
	 * @param string $split_code  Split code consisting of the lists of accounts you want to split the transaction with
	 * @param string $first_name  Customer's first name
	 * @param string $last_name  Customer's last name
	 * @param string $phone  Customer's phone number
	 * @param string $email  Customer's email
	 * @param string $country Currently accepts NG only
	 * @param string $account_number Customer's account number
	 * @param string $bank_code Customer's bank code
	 * @param string $bvn Customer's Bank Verification Number
	 *
	 * @param array<string> $data
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function assignVirtaulAccount(array $data = []): PaystackCustomerApi
	{
		try {
			$customerData = [...$data];

			$response = $this->resource(config("paystack.endpoint.dva.assign"))->post($customerData);

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
	 * List dedicated virtual accounts available on your integration.
	 * @param bool $active Status of the dedicated virtual account
	 * @param string $currency The currency of the dedicated virtual account. Only NGN is currently allowed
	 *
	 * @param array<string> $data
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function getVirtaulAccounts(array $data = []): PaystackCustomerApi
	{
		try {
			$customerData = [...$data];

			$response = $this->resource(config("paystack.endpoint.dva.list"))->get($customerData);

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
	 * Get details of a dedicated virtual account on your integration.
	 *
	 * @param string $id
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function getVirtaulAccount(string $id): PaystackCustomerApi
	{
		try {
			$response = $this->resource(config("paystack.endpoint.dva.show"), $id)->get();

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
	 * Requery Dedicated Virtual Account for new transactions
	 * @param string $account_number Virtual account number to requery
	 * @param string $provider_slug The bank's slug in lowercase, without spaces e.g. wema-bank
	 *
	 * @param array<string> $data
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function requeryVirtaulAccounts(array $data = []): PaystackCustomerApi
	{
		try {
			$customerData = [...$data];

			$response = $this->resource(config("paystack.endpoint.dva.requery"))->get($customerData);

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
	 * Deactivate a dedicated virtual account on your integration.
	 *
	 * @param string $id
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function deactivateVirtaulAccount(string $id): PaystackCustomerApi
	{
		try {
			$response = $this->resource(config("paystack.endpoint.dva.deactivate"), $id)->delete();

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
	 * Get available bank providers for a dedicated virtual account
	 *
	 * @return \ObitechBilmapay\LaravelPaystackSdk\Apis\Customer\PaystackCustomerApi
	 */
	public function virtaulAccountProviders(): PaystackCustomerApi
	{
		try {
			$response = $this->resource(config("paystack.endpoint.dva.providers"))->get();

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
