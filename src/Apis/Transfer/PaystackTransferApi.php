<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Transfer;

use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Paystack;

/**
 * The paystack transfer Api
 */
class PaystackTransferApi extends PaystackSdk
{
	/**
	 * Th transaction recipient
	 *
	 * @var string
	 */
	protected string $recipient_code;
	/**
	 * The transfer recipient account number
	 *
	 * @var string
	 */
	protected string $recipient_account;
	/**
	 * The transfer recipient bank name
	 *
	 * @var string
	 */
	protected string $recipient_bank;
	/**
	 * the transfer recipient code (A Unique bank code or id)
	 *
	 * @var string
	 */
	protected string $recipient_bank_code;
	/**
	 * The recipient currency type
	 *
	 * @var string
	 */
	protected string $recipient_currency;

	/* The traits */
	use TransferRecipientApi;

	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}

	/**
	 * Status of transfer object returned will be pending if OTP is disabled. In the event that an OTP is required, status will read otp.
	 *
	 * @param int|float $amount Amount to transfer in kobo if currency is NGN and pesewas if currency is GHS.
	 * @param string $recipient Code for transfer recipient or Account number
	 * @param string|null|null $bankCode The customer bank code
	 * @param string $currency The currency to transfer to
	 * @param string|int|null|null $reference The transaction reference
	 * @param string|null $remark Transaction remark or description
	 *
	 * @return PaystackTransferApi
	 */
	public function send(int|float $amount, string $recipient, string|null $bankCode = null, string $currency = "NGN", string|int|null $reference = null, string $remark = null): PaystackTransferApi
	{
		try {
			$customerData = ["amount" => $amount, "recipient" => $recipient, "bank_code" => $bankCode, "currency" => $currency, "reference" => $reference, "reason" => $remark];

			$validator = Validator::make(
				$customerData,
				/* check if the account is the recipient code  */
				Str::startsWith($recipient, "RCP_")
					? [
						"amount" => ["bail", "required", "numeric"],
						"recipient" => ["bail", "required", "string", "starts_with:RCP_"],
					]
					: [
						"amount" => ["bail", "required", "numeric"],
						"recipient" => ["bail", "required", "string", "size:10"],
						"currency" => ["bail", "required", "string", "size:3"],
						"bank_code" => ["bail", "required", "string"],
					],
				[] // custom validation messages
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			/* Initiate transfer if the account number is a recipient code */
			if (Str::startsWith($recipient, "RCP_")) {
				/* intiate transfer */

				$transferParams["source"] = "balance";
				$transferParams["amount"] = $amount * 100;
				$transferParams["recipient"] = $recipient;
				$transferParams["currency"] = $currency;

				/* add transaction remarks */
				$appName = config("paystack.appname");
				if (!empty($remark) && !empty($appName)) {
					$transferParams["reason"] = "{$appName}: $remark ";
				}

				$response = $this->resource(config("paystack.endpoint.transfer.send"))->post($customerData);

				if (!$response->successful()) {
					return $this->setError($response->json());
				} else {
					return $this->setResponse($response->object());
				}
			} else {
				/* resolve account number  */
				if (!is_numeric($recipient)) {
					return $this->setError("The account number is invalid.");
				}
				$verification = Paystack::Verification()->resolveAcountNumber($recipient, $bankCode);
				if (!$verification->success) {
					return $this->setError($verification->errors());
				}

				/* Create recipient  */
				$verifiedName = $verification->response->data->account_name;
				$verifiedAccountNo = $verification->response->data->account_number;
				$resolveRecipient = Paystack::Transfer()->createRecipient("nuban", $verifiedName, $verifiedAccountNo, $bankCode, $currency);
				if (!$resolveRecipient->success) {
					return $this->setError($resolveRecipient->errors());
				}

				/* initiate transfer  */
				$recipient = $resolveRecipient->response->data->recipient_code;

				/* intiate transfer */

				$transferParams["source"] = "balance";
				$transferParams["amount"] = $amount * 100;
				$transferParams["recipient"] = $recipient;
				$transferParams["currency"] = $currency;

				/* add transaction remarks */
				$appName = config("paystack.appname");
				if (!empty($remark) && !empty($appName)) {
					$transferParams["reason"] = "{$appName}: $remark ";
				}

				$response = $this->resource(config("paystack.endpoint.transfer.send"))->post($customerData);

				if (!$response->successful()) {
					return $this->setError($response->json());
				} else {
					return $this->setResponse($response->object());
				}
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	/**
	 * You need to disable the Transfers OTP requirement to use this endpoint.
	 *
	 * @param array $recipients A list of transfer object. Each object should contain amount, recipient, and reference or any of the params
	 * available at: https://paystack.com/docs/api/#transfer-initiate
	 *
	 * @return PaystackTransferApi
	 */
	public function sendBulk(array $recipients): PaystackTransferApi
	{
		try {
			/* validation the transfer object */
			foreach ($recipients as $recipient) {
				if (!is_array($recipient)) {
					return $this->setError("Invalid recipient data in array.");
				}

				$recipientValidation["amount"] = isset($recipient["amount"]) ? $recipient["amount"] : "";
				$recipientValidation["recipient"] = isset($recipient["recipient"]) ? $recipient["recipient"] : "";
				$recipientValidation["reference"] = isset($recipient["reference"]) ? $recipient["reference"] : "";

				$validator = Validator::make($recipientValidation, [
					"amount" => ["bail", "required", "numeric"],
					"recipient" => ["bail", "required", "string", "starts_with:RCP_"],
					"reference" => ["bail", "required", "string"],
				]);

				if ($validator->fails()) {
					return $this->setError($validator->errors()->getMessages());
				}
			}

			/* append the app name on each recipient reason or transfer remark */
			foreach ($recipients as $index => $recipient) {
				$appName = config("paystack.appname");

				if (isset($recipient["reason"])) {
					/* add transaction remarks */
					$recipients[$index]["reason"] = "{$appName}: {$recipient["reason"]} ";
				} elseif (isset($recipient["remark"])) {
					$recipients[$index]["reason"] = "{$appName}: {$recipient["remark"]} ";
				} elseif (isset($recipient["description"])) {
					$recipients[$index]["reason"] = "{$appName}: {$recipient["description"]} ";
				}

				if (isset($recipient["amount"])) {
					$recipients[$index]["amount"] = $recipient["amount"] * 100;
				}
			}

			$transfers = ["transfers" => $recipients, "source" => "balance"];
			$response = $this->resource(config("paystack.endpoint.transfer.bulk"))->post($transfers);

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
	 * List the transfers made on your integration.
	 *
	 * @param int $perPage
	 * @param int $page
	 *
	 * @return PaystackTransferApi
	 */
	public function fetchTransfers(int $perPage = 50, int $page = 1): PaystackTransferApi
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

			$response = $this->resource(config("paystack.endpoint.transfer.all"))->get($requestData);

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
	 * Get details of a transfer on your integration.
	 *
	 * @param string $id_or_code
	 *
	 * @return PaystackTransferApi
	 */
	public function fetchTransfer(string $id_or_code): PaystackTransferApi
	{
		try {
			$requestData = ["recipient" => $id_or_code];

			$validator = Validator::make($requestData, [
				"recipient" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transfer.single"), $id_or_code)->get();

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
	 * Verify the status of a transfer on your integration.
	 *
	 * @param string $reference
	 *
	 * @return PaystackTransferApi
	 */
	public function verifyTransfer(string $reference): PaystackTransferApi
	{
		try {
			$requestData = ["reference" => $reference];

			$validator = Validator::make($requestData, [
				"reference" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.transfer.verify"), $reference)->get();

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
