<?php namespace ObitechBilmapay\LaravelPaystackSdk\Helpers;

use Exception;
use ObitechBilmapay\LaravelPaystackSdk\PaystackApis;
use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

/**
 * Http client request handles
 */
trait HttpClientHelpers
{
	public function resource(string $path, string|int|float|null $pathParam = null): PaystackSdk
	{
		if (!empty($path)) {
			$this->resourcesPath = config("baseurl", "https://api.paystack.co") . $path;
		}

		if (!empty($pathParam)) {
			$this->resourcesPath = addPathParam($this->resourcesPath, $pathParam);
		}

		return $this;
	}

	public function post(array $data = []): \Illuminate\Http\Client\Response|string
	{
		return $this->HttpClient->post($this->resourcesPath, $data);
	}

	public function get(array $data = []): \Illuminate\Http\Client\Response|string
	{
		return $this->HttpClient->get($this->resourcesPath, $data);
	}

	public function put(array $data = []): \Illuminate\Http\Client\Response|string
	{
		return $this->HttpClient->put($this->resourcesPath, $data);
	}
	public function patch(array $data = []): \Illuminate\Http\Client\Response|string
	{
		return $this->HttpClient->patch($this->resourcesPath, $data);
	}

	public function delete(array $data = []): \Illuminate\Http\Client\Response|string
	{
		return $this->HttpClient->delete($this->resourcesPath, $data);
	}
}