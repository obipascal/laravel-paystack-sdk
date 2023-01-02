<?php

return [
	/**
	 * Paystack test or live secret key
	 */
	"secret" => env("PAYSTACK_SECRET_KEY", ""),
	/**
	 * Paystack API base url: This property is provided if they is any changes in
	 * the feature.
	 */
	"baseurl" => env("PAYSTACK_ENDPOINT", "https://api.paystack.co"),
	/**
	 * The application name registered with Paystack.
	 */
	"appname" => env("PAYSTACK_APP_NAME", "BilmaPay"),
	/**
	 * The Request time out valuel default to 3 seconds
	 */
	"timeout" => (int) env("PAYSTACK_TIMEOUT", 3),
	/**
	 * The number of times the request should be retried if they was a any error at the initial try
	 */
	"retry" => (int) env("PAYSTACK_RETRY", 3),

	/**
	 * This the the paystack resource endpoints routes
	 * ________________________________________________
	 * NOTE: DO NOT CHANGE THEIR KEY RATHER CHANGE THEIR VALUES
	 */
	"endpoint" => [
		/** Customer resource group */
		"customer" => [
			"create" => "/customer",
			"all" => "/customer",
			"signle" => "/customer/:pathParam",
			"update" => "/customer/:pathParam",
			"validate" => "/customer/:pathParam/identification",
			"risk_action" => "/customer/set_risk_action",
			"revoke_auth" => "/customer/deactivate_authorization",
		],

		/** Bank transfer api endpoints */

		"transfer" => [
			"recipient" => [
				"create" => "/transferrecipient",
				"bulk" => "/transferrecipient/bulk",
				"all" => "/transferrecipient",
				"single" => "/transferrecipient/:pathParam",
				"update" => "/transferrecipient/:pathParam",
				"delete" => "/transferrecipient/:pathParam",
			],
			"send" => "/transfer",
			"bulk" => "/transfer/bulk",
			"all" => "/transfer",
			"single" => "/transfer/:pathParam",
			"verify" => "/transfer/verify/:pathParam",
		],

		/* Miscellaneous api endpoints */
		"misc" => [
			"resolve_account_number" => "/bank/resolve",
			"validate_account" => "/bank/validate",
			"resolve_card_bin" => "/decision/bin/:pathParam",
			"banks" => "/bank",
		],

		/* Transaction endpoint*/
		"transaction" => [
			"initiate" => "/transaction/initialize",
			"verify" => "/transaction/verify/:pathParam",
			"all" => "/transaction",
			"single" => "/transaction/:pathParam",
			"chargeAuth" => "/transaction/charge_authorization",
			"checkAuth" => "/transaction/check_authorization",
			"timeline" => "/transaction/timeline/:pathParam",
			"totals" => "/transaction/totals",
			"export" => "/transaction/export",
			"partialDebit" => "/transaction/partial_debit",
		],

		/* Charge endpoint */
		"charge" => [
			"initiate" => "/charge",
		],

		/* Invoice endpoint */
		"invoice" => [
			"create" => "/paymentrequest",
			"all" => "/paymentrequest",
			"single" => "/paymentrequest/:pathParam",
			"verify" => "/paymentrequest/verify/:pathParam",
			"pushNotifi" => "/paymentrequest/notify/:pathParam",
			"totals" => "/paymentrequest/totals",
			"finalize" => "/paymentrequest/finalize/:pathParam",
			"update" => "/paymentrequest/:pathParam",
			"archive" => "/paymentrequest/archive/:pathParam",
		],

		/* Subaccounts */
		"subaccounts" => [
			"create" => "/subaccount",
			"index" => "/subaccount",
			"show" => "/subaccount/:pathParam",
			"update" => "/subaccount/:pathParam",
		],
	],
];