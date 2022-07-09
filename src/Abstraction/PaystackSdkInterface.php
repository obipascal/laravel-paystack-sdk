<?php namespace ObitechBilmapay\LaravelPaystackSdk\Abstraction;

use ObitechBilmapay\LaravelPaystackSdk\PaystackApis;
use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

interface PaystackSdkInterface extends HttpClientInterface
{
	/**
	 * Set the http request headers
	 *
	 * @param array $headers
	 *
	 * @return PaystackSdk
	 */
	public function setHeaders(array $headers): PaystackSdk;

	public function setResponse(object|string|array $response = null);
	public function setError(array|string $error);
	public function errors();
}