<?php namespace ObitechBilmapay\LaravelPaystackSdk;

interface PaystackSdkInterface
{
	/**
	 * A static helper for initializing the sdk options
	 *
	 * @param string $secretKey
	 * @param string $env
	 * @param string $baseUrl
	 * @param string $appName
	 *
	 * @return PaystackSdk
	 */
	public static function init(string $secretKey, string $env = "test", string $baseUrl = "", string $appName = "BilmaPay");

	/**
	 * Set the operation data
	 *
	 * @param array $newData
	 *
	 * @return $this
	 */
	public function setData(array $newData);
	/**
	 * Get the data in state
	 *
	 *
	 * @return array
	 */
	public function getData();

	/**
	 * This method set or updated the data item value
	 *
	 * @param string $itemName
	 * @param string $itemValue
	 *
	 * @return $this
	 */
	public function setDataItem(string $itemName, string $itemValue);
	/**
	 * This helper checks and remove the provided item name fromt he data array.
	 *
	 * @param string $itemName
	 *
	 * @return $this
	 */
	public function removeDataItem(string $itemName);

	// ------------------------------------------------------------
	/**
	 * Set the api secret key
	 *
	 * @param string $key
	 *
	 * @return PaystackSdk
	 */
	public function setKey(string $key): PaystackSdk;
	/**
	 * Set the api base endpoint url
	 *
	 * @param string $endpoint
	 *
	 * @return PaystackSdk
	 */
	public function setEndpoint(string $endpoint): PaystackSdk;
	/**
	 * Set the api environment mode posible values clould be live or test mode.
	 *
	 * @param string $env Possible values could be live|test
	 *
	 * @return PaystackSdk
	 */
	public function setEnv(string $env): PaystackSdk;
	/**
	 * Set the application name
	 *
	 * @param string $appName
	 *
	 * @return PaystackSdk
	 */
	public function setAppName(string $appName): PaystackSdk;
}