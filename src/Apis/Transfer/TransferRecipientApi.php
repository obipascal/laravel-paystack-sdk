<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Transfer;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Paystack;

/**
 * This api handles single transfer flow
 */
trait TransferRecipientApi
{
	/**
	 * Creates a new recipient. A duplicate account number will lead to the retrieval of the existing record.
	 *
	 * @param string $type Recipient Type. It could be one of: nuban, mobile_money or basa
	 * @param string|null $name A name for the recipient
	 * @param string|null $accountNumber Required if type is nuban or basa
	 * @param string|null $bankCode Required if type is nuban or basa. You can get the list of Bank Codes by calling the List Banks endpoint.
	 * @param string $currency  A description for this recipient
	 * @param string $desc A description for this recipient
	 * @param array $metadata Store additional information about your recipient in a structured format, JSON
	 *
	 * @return PaystackTransferApi
	 */
	public function createRecipient(
		string $type = "nuban",
		string $name = null,
		string $accountNumber = null,
		string $bankCode = null,
		string $currency = "NGN",
		string $desc = "",
		array $metadata = []
	): PaystackTransferApi {
		try {
			$requestData = [
				"type" => $type,
				"name" => $name ?? $this->recipient_name,
				"account_number" => $accountNumber ?? $this->recipient_account,
				"bank_code" => $bankCode ?? $this->recipient_bank_code,
				"currency" => $currency ?? $this->recipient_currency,
				"description" => $desc,
				"metadata" => $metadata,
			];

			$validator = Validator::make(
				$requestData,
				[
					"type" => ["bail", "required", "string", Rule::in(["nuban", "mobile_money", "basa"])],
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

			$response = $this->resource(config("paystack.endpoint.transfer.recipient.create"))->post($requestData);

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

	/**
	 * Create multiple transfer recipients in batches. A duplicate account number will lead to the retrieval of the existing record.
	 *
	 * @param array $recipientObjs A list of transfer recipient object. Each object should contain type, name, and bank_code. Any Create Transfer Recipient param can also be passed.
	 *
	 * @return PaystackTransferApi
	 */
	public function createBulkRecipients(array $recipientObjs): PaystackTransferApi
	{
		try {
			$requestData = ["batch" => $recipientObjs];

			foreach ($recipientObjs as $recipientData) {
				if (!empty($recipientData) && is_array($recipientData)) {
					$validator = Validator::make(
						$recipientData,
						[
							"type" => ["bail", "required", "string", Rule::in(["nuban", "mobile_money", "basa"])],
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
				}
			}

			$response = $this->resource(config("paystack.endpoint.transfer.recipient.bulk"))->post($requestData);

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
	 * List transfer recipients available on your integration
	 *
	 * @param int $perPage Specify how many records you want to retrieve per page. If not specify we use a default value of 50.
	 * @param int $page Specify exactly what page you want to retrieve. If not specify we use a default value of 1.
	 *
	 * @return PaystackTransferApi
	 */
	public function fetchRecipients(int $perPage = 50, int $page = 1): PaystackTransferApi
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

			$response = $this->resource(config("paystack.endpoint.transfer.recipient.all"))->get($requestData);

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
	 * Fetch the details of a transfer recipient
	 *
	 * @param string $recipientCode An ID or code for the recipient whose details you want to receive.
	 *
	 * @return PaystackTransferApi
	 */
	public function fetchRecipient(string $recipientCode): PaystackTransferApi
	{
		try {
			$requestData = ["recipient" => $recipientCode];

			$validator = Validator::make($requestData, [
				"recipient" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transfer.recipient.single"), $recipientCode)->get($requestData);

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
	 * Update an existing recipient. An duplicate account number will lead to the retrieval of the existing record.
	 *
	 * @param string $recipientCode Transfer Recipient's ID or code
	 * @param string $name A name for the recipient
	 * @param string|null $email Email address of the recipient
	 *
	 * @return PaystackTransferApi
	 */
	public function updateRecipient(string $recipientCode, string $name, string $email = null): PaystackTransferApi
	{
		try {
			$requestData = ["recipient" => $recipientCode, "name" => $name, "email" => $email];

			$validator = Validator::make(
				$requestData,
				!empty($email)
					? [
						"recipient" => ["bail", "required", "string"],
						"name" => ["bail", "required", "string"],
						"email" => ["bail", "email"],
					]
					: [
						"recipient" => ["bail", "required", "string"],
						"name" => ["bail", "required", "string"],
					]
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transfer.recipient.update"), $recipientCode)->put($requestData);

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
	 * Deletes a transfer recipient (sets the transfer recipient to inactive)
	 *
	 * @param string $recipientCode An ID or code for the recipient who you want to delete.
	 *
	 * @return PaystackTransferApi
	 */
	public function deleteRecipient(string $recipientCode): PaystackTransferApi
	{
		try {
			$requestData = ["recipient" => $recipientCode];

			$validator = Validator::make($requestData, [
				"recipient" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transfer.recipient.delete"), $recipientCode)->delete();

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