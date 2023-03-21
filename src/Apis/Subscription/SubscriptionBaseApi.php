<?php namespace ObitechBilmapay\LaravelPaystackSdk\Apis\Subscription;

use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

class SubscriptionBaseApi extends PaystackSdk
{
	use SubscriptionApi, SubscriptionPlanApi;

	public function __construct()
	{
		/* This section initialized the sdk configurations so don't mess with it. */
		parent::__construct();
	}
}
