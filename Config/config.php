<?php

return [
	"secret" => env("PAYSTACK_SECRET", ""),
	"mode" => env("PAYSTACK_MODE", "test"),
	"endpoint" => env("PAYSTACK_ENDPOINT", "https://api.paystack.co"),
	"appname" => env("PAYSTACK_APP_NAME", "BilmaPay"),
];