<?php namespace ObitechBilmapay\LaravelPaystackSdk;

class PaystackSdk implements PaystackSdkInterface
{
	public string $message;
	protected array $data;
	public mixed $response;

	public function __construct(protected string $secretKey, protected string $env = "test", protected string $baseUrl = "", protected string $appName = "BilmaPay")
	{
	}

	public static function init(string $secretKey, string $env = "test", string $baseUrl = "", string $appName = "BilmaPay")
	{
		return new self($secretKey, $env, $baseUrl, $appName);
	}

	public function setData(array $newData)
	{
		$this->data = $newData;
		return $this;
	}

	public function getData()
	{
		return $this->data;
	}

	public function setDataItem(string $itemName, string $newValue)
	{
		if (!empty($itemName) && !empty($newValue)) {
			$this->data[$itemName] = $newValue;
		}

		return $this;
	}

	public function removeDataItem(string $itemName)
	{
		if (!empty($this->data) && !empty($itemName) && isset($this->data[$itemName])) {
			unset($this->data[$itemName]);
		}

		return $this;
	}

	public function getDataItem(string $itemName)
	{
		if (!empty($this->data) && !empty($itemName) && isset($this->data[$itemName])) {
			return $this->data[$itemName];
		}

		return false;
	}

	public function setKey(string $key): PaystackSdk
	{
		if (!empty($key)) {
			$this->secretKey = $key;
		}

		return $this;
	}

	public function setEndpoint(string $endpoint): PaystackSdk
	{
		if (!empty($endpoint)) {
			$this->baseUrl = $endpoint;
		}

		return $this;
	}

	public function setEnv(string $env): PaystackSdk
	{
		if (!empty($env)) {
			$this->env = $env;
		}

		return $this;
	}

	public function setAppName(string $appName): PaystackSdk
	{
		if (!empty($appName)) {
			$this->appName = $appName;
		}

		return $this;
	}

	public function __get($name)
	{
		return $this->$name;
	}
}