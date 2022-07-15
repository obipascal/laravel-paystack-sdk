<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Invoice;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

class PaystackInvoiceApi extends PaystackSdk
{
	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * Create an invoice for payment on your integration
	 *
	 * @param string $email
	 * @param array $productItems
	 * @param string $dueDate
	 * @param string $currency
	 * @param string $description
	 * @param array $taxes
	 * @param bool $drafted
	 * @param array $options
	 *
	 * @return PaystackInvoiceApi
	 */
	public function createProductInvoice(
		string $customer,
		array $productItems,
		string $dueDate,
		string $currency = "NGN",
		string $description = "",
		array $taxes = [],
		bool $drafted = false,
		array $options = []
	): PaystackInvoiceApi {
		try {
			$customerData = [
				"customer" => $customer,
				"line_items" => $productItems,
				"due_date" => $dueDate,
				"currency" => $currency,
				"description" => $description,
				"tax" => $taxes,
				"draft" => $drafted,
				...$options,
			];

			$validator = Validator::make($customerData, [
				"customer" => ["bail", "required", "string"],
				"due_date" => ["bail", "required", "date"],
				"line_items" => ["bail", "required", "array"],
				"currency" => ["bail", "required", "string", "size:3"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.create"))->post($customerData);

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
	 * Create an invoice for payment on your integration
	 *
	 * @param int|float $amount
	 * @param string $customer Customer id or code
	 * @param string $dueDate
	 * @param string $currency
	 * @param array $options
	 *
	 * @return PaystackInvoiceApi
	 */
	public function createPlainInvoice(int|float $amount, string $customer, string $dueDate, string $currency = "NGN", array $options = []): PaystackInvoiceApi
	{
		try {
			$customerData = [
				"amount" => $amount * 100,
				"customer" => $customer,
				"due_date" => $dueDate,
				"currency" => $currency,
				"description" => $description,
				...$options,
			];

			$validator = Validator::make($customerData, [
				"amount" => ["bail", "required", "numeric"],
				"customer" => ["bail", "required", "string"],
				"due_date" => ["bail", "required", "date"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.create"))->post($customerData);

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
	 * List the invoice available on your integration.
	 *
	 * @param int $perPage
	 * @param int $page
	 * @param array $options
	 *
	 * @return PaystackInvoiceApi
	 */
	public function fetchInvoices(int $perPage = 50, int $page = 1, array $options = []): PaystackInvoiceApi
	{
		try {
			$requestData = ["perPage" => $perPage, "page" => $page, ...$options];

			$validator = Validator::make($requestData, [
				"perPage" => ["bail", "numeric"],
				"page" => ["bail", "numeric"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.all"))->get($requestData);

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
	 * Verify details of an invoice on your integration.
	 *
	 * @param string|int $id_or_code
	 *
	 * @return PaystackInvoiceApi
	 */
	public function fetchInvoice(string|int $id_or_code): PaystackInvoiceApi
	{
		try {
			$requestData = ["invoice_id" => $id_or_code];

			$validator = Validator::make($requestData, [
				"invoice_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.single"), $id_or_code)->get();

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
	 * Verify details of an invoice on your integration.
	 *
	 * @param string|int $invoiceCode
	 *
	 * @return PaystackInvoiceApi
	 */
	public function verifyInvoice(string|int $invoiceCode): PaystackInvoiceApi
	{
		try {
			$requestData = ["invoice_id" => $invoiceCode];

			$validator = Validator::make($requestData, [
				"invoice_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.verify"), $invoiceCode)->get();

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
	 * Send notification of an invoice to your customers
	 *
	 * @param string|int $invoiceCode
	 *
	 * @return PaystackInvoiceApi
	 */
	public function sendInvoiceReminder(string|int $invoiceCode): PaystackInvoiceApi
	{
		try {
			$requestData = ["invoice_id" => $invoiceCode];

			$validator = Validator::make($requestData, [
				"invoice_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.pushNotifi"), $invoiceCode)->post();

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
	 * Get invoice metrics for dashboard
	 *
	 * @return PaystackInvoiceApi
	 */
	public function dashboardMetrics(): PaystackInvoiceApi
	{
		try {
			$response = $this->resource(config("paystack.endpoint.invoice.totals"))->get();

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
	 * Finalize a Draft Invoice
	 *
	 * @param string|int $invoiceCode
	 *
	 * @return PaystackInvoiceApi
	 */
	public function finalizeDraftedInvoice(string|int $invoiceCode): PaystackInvoiceApi
	{
		try {
			$requestData = ["invoice_id" => $invoiceCode];

			$validator = Validator::make($requestData, [
				"invoice_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.finalize"), $invoiceCode)->post();

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
	 * Update an invoice details on your integration
	 *
	 * @param string|int $invoiceCode
	 * @param array $options
	 *
	 * @return PaystackInvoiceApi
	 */
	public function updateInvoice(string|int $invoiceCode, array $options): PaystackInvoiceApi
	{
		try {
			$requestData = ["invoice_id" => $invoiceCode, ...$options];

			$validator = Validator::make($requestData, [
				"invoice_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.update"), $invoiceCode)->put($requestData);

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
	 * Used to archive an invoice. Invoice will no longer be fetched on list or returned on verify.
	 *
	 * @param string|int $invoiceCode
	 *
	 * @return PaystackInvoiceApi
	 */
	public function archiveInvoice(string|int $invoiceCode): PaystackInvoiceApi
	{
		try {
			$requestData = ["invoice_id" => $invoiceCode];

			$validator = Validator::make($requestData, [
				"invoice_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.invoice.archive"), $invoiceCode)->post();

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