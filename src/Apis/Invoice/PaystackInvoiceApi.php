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

	public function createProductInvoice(
		string $email,
		array $productItems,
		string $dueDate,
		string $currency = "NGN",
		string $description = "",
		array $taxes = [],
		bool $drafted = false,
		array $options = []
	): PaystackChargeApi {
		try {
			$customerData = [
				"email" => $email,
				"line_items" => $productItems,
				"due_date" => $dueDate,
				"currency" => $currency,
				"description" => $description,
				"tax" => $taxes,
				"draft" => $drafted,
				...$options,
			];

			$validator = Validator::make($customerData, [
				"email" => ["bail", "required", "email"],
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
}