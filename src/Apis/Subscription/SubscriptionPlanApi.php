<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Subscription;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Subscription Plan Api
 */
trait SubscriptionPlanApi
{
	public function createSubPlan(string $name, float|int $amount, string $interval, ?string $desc = null): SubscriptionBaseApi
	{
		try {
			$Params = ["name" => $name, "amount" => $amount * 100, "interval" => $interval, "description" => $desc];

			$validator = Validator::make($Params, [
				"name" => ["bail", "required", "string"],
				"amount" => ["bail", "required", "numeric"],
				"interval" => ["bail", "required", "string", Rule::in(["daily", "weekly", "monthly", "biannually", "annually"])],
				"description" => ["bail", "nullable", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.plan.create"))->post($Params);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	public function fetchSubPlan(string $id): SubscriptionBaseApi
	{
		try {
			$Params = ["plan_id" => $id];

			$validator = Validator::make($Params, [
				"plan_id" => ["bail", "required", "string"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.plan.show"), $id)->get();

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	public function fetchSubPlans(int $perPage = 100): SubscriptionBaseApi
	{
		try {
			$Params = ["perPage" => $perPage];

			$validator = Validator::make($Params, [
				"perPage" => ["bail", "nullable", "numeric"],
			]);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.plan.list"))->get($Params);

			if (!$response->successful()) {
				return $this->setError($response->json());
			} else {
				return $this->setResponse($response->object());
			}
		} catch (Exception $th) {
			return $this->setError($th->getMessage());
		}
	}

	public function updateSubPlan(string $id, string $name, float|int $amount, string $interval, ?string $desc = null, ?string $currency = null): SubscriptionBaseApi
	{
		try {
			$Params = ["name" => $name, "amount" => $amount * 100, "interval" => $interval, "description" => $desc, "currency" => $currency ?? config("paystack.currency")];

			$validator = Validator::make(
				["plan_id" => $id, ...$Params],
				[
					"name" => ["bail", "required", "string"],
					"amount" => ["bail", "required", "numeric"],
					"interval" => ["bail", "required", "string", Rule::in(["daily", "weekly", "monthly", "biannually", "annually"])],
					"description" => ["bail", "nullable", "string"],
					"currency" => ["bail", "nullable", "string", Rule::in(["NGN", "ZAR", "GHS", "USD"])],
				]
			);

			if ($validator->fails()) {
				return $this->setError($validator->errors()->getMessages());
			}

			$response = $this->resource(config("paystack.endpoint.subscription.plan.update"), $id)->put($Params);

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
