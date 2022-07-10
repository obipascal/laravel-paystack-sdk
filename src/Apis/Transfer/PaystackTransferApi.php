<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Transfer;

use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

	public function send(int|float $amount, string $accountNumber, string $bankCode, string $currency = "NGN", string|int|null $reference = null, string $remark = null): PaystackTransferApi
	{
		try {
			$customerData = ["first_name" => $firstName, "last_name" => $lastName, "email" => $email, "phone_number" => $phoneNumber, "metadata" => $metadata];

			$validator = Validator::make(
				$customerData,
				[
					"first_name" => ["bail", "required", "string"],
					"last_name" => ["bail", "required", "string"],
					"phone_number" => ["bail", "required", "string"],
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
}