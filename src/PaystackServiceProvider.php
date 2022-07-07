<?php namespace ObitechBilmapay\LaravelPaystackSdk;

use Illuminate\Support\ServiceProvider;

class PaystackServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind("paystack", function ($app) {
			return new PaystackSdk(config("paystack.secret"), config("paystack.mode"), config("paystack.endpoint"), config("paystack.appname"));
		});

		/* register the paystack configs  */
		$this->mergeConfigFrom(__DIR__ . "/../Config/config.php", "paystack");
	}

	public function boot()
	{
		/* Give user the ability to modify configs options */
		if ($this->app->runningInConsole()) {
			$this->publishes(
				[
					__DIR__ . "/../Config/config.php" => config_path("paystack.php"),
				],
				"config"
			);
		}
	}
}