<?php namespace ObitechBilmapay\LaravelPaystackSdk\Helpers;

use ObitechBilmapay\LaravelPaystackSdk\PaystackApis;
use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

/**
 * Paystack sdk config helpers methods
 */
trait PaystackSdkHelpers
{
	public function setHeaders(array $headers): PaystackSdk
	{
		if (!empty(count($headers))) {
			$this->HttpClientHeaders = $headers;
		}

		return $this;
	}

	public function setResponse(object|string|array $response = null)
	{
		$this->response = $response;
		$this->success = true;

		if (is_object($response)) {
			if (isset($response->data)) {
				$this->data = $response->data;
			}
		}

		return $this;
	}

	public function setError(array|string $error)
	{
		$this->error = $error;
		$this->success = false;

		return $this;
	}

	public function errors()
	{
		if (!empty($this->error) && !is_array($this->error)) {
			if (strpos($this->error, "}") && strpos($this->error, '"message":')) {
				$errorMsg = substr($this->error, strpos($this->error, '"message":'), strpos($this->error, "}") - 1);

				$msg = explode(":", $errorMsg)[1] ?? "";
				$msg = $msg = str_replace('"', "", str_replace("}", "", $msg));

				$this->error = $msg;
			}
		}
		return $this->error;
	}
}