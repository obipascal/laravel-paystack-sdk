<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Subscription;

use Exception;
use Illuminate\Support\Facades\Validator;

/**
 * Subscription apis
 */
trait SubscriptionApi
{
	public function createSub(string $customer, string $planCode, ?string $auth_code = null, ?string $startDate = null): SubscriptionBaseApi
	{
		try {
			$Params = ["customer" => $customer, "plan" => $planCode, "authorization" => $auth_code, "start_date" => $startDate];

			$validator = Validator::make($Params, [
				"customer" => ["bail", "required", "string"],
				"plan" => ["bail", "required", "string", "start_with:PLN_"],
				"authorization" => ["bail", "nullable", "string", "start_with:AUTH_"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.create"))->post($Params);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	public function fetchSub(string $id_or_code): SubscriptionBaseApi
	{
		try {
			$validator = Validator::make(
				["subscription_id" => $id_or_code],
				[
					"subscription_id" => ["bail", "required", "string"],
				]
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.show"), $id_or_code)->get();

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	public function fetchSubs(int $perPage = 100): SubscriptionBaseApi
	{
		try {
			$Params = ["perPage" => $perPage];

			$validator = Validator::make($Params, [
				"perPage" => ["bail", "nullable", "numeric"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.list"))->get($Params);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	public function enableSub(string $id_or_code, string $emailToken): SubscriptionBaseApi
	{
		try {
			$Params = ["code" => $id_or_code, "token" => $emailToken];

			$validator = Validator::make($Params, [
				"code" => ["bail", "required", "string"],
				"token" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.enable"))->post($Params);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	public function disableSub(string $id_or_code, string $emailToken): SubscriptionBaseApi
	{
		try {
			$Params = ["code" => $id_or_code, "token" => $emailToken];

			$validator = Validator::make($Params, [
				"code" => ["bail", "required", "string"],
				"token" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.disable"))->post($Params);

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
