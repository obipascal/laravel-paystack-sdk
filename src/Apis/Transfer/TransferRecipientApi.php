<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Transfer;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * This api handles single transfer flow
 */
trait TransferRecipientApi
{
	public function createBankRecipient(
		string $name = null,
		string $accountNumber = null,
		string $bankCode = null,
		string $currency = "NGN",
		string $desc = "",
		array $metadata = []
	): PaystackTransferApi {
		try {
			$customerData = [
				"name" => $name ?? $this->recipient_name,
				"account_number" => $accountNumber ?? $this->recipient_account,
				"bank_code" => $bankCode ?? $this->recipient_bank_code,
				"currency" => $currency ?? $this->recipient_currency,
				"description" => $desc,
				"metadata" => $metadata,
			];

			$validator = Validator::make(
				$customerData,
				[
					"name" => ["bail", "required", "string"],
					"account_number" => ["bail", "required", "string"],
					"bank_code" => ["bail", "required", "string"],
					"currency" => ["bail", "required", "string", "size:3"],
				],
				[] // custom validation messages
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transfer.recipient.create"))->post($customerData);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				/* Set the recipient for chainning  */
				$this->recipient_code = $response->object()->data->recipient_code;

				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}
}