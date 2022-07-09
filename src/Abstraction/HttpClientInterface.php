<?php namespace ObitechBilmapay\LaravelPaystackSdk\Abstraction;

use ObitechBilmapay\LaravelPaystackSdk\PaystackSdk;

interface HttpClientInterface
{
	/**
	 * Set the resource path for the api request.
	 *
	 * @param string $path
	 * @param string|int|float|null $pathParam
	 *
	 * @return PaystackSdk
	 */
	public function resource(string $path, string|int|float|null $pathParam = null): PaystackSdk;
	/**
	 * Make post request to a particular resource
	 *
	 * @param array $data The request params or form Data to send along with
	 * the request
	 *
	 * @return \Illuminate\Http\Client\Response|string
	 */
	public function post(array $data = []): \Illuminate\Http\Client\Response|string;
	/**
	 * Make get request to a particular resource
	 *
	 * @param array $data This will be the url
	 * endcoded params or query string
	 *
	 * @return \Illuminate\Http\Client\Response|string
	 */
	public function get(array $data = []): \Illuminate\Http\Client\Response|string;
	/**
	 * Make put request to a particular resource
	 *
	 * @param array $data The request params
	 *
	 * @return \Illuminate\Http\Client\Response|string
	 */
	public function put(array $data = []): \Illuminate\Http\Client\Response|string;
	/**
	 * Make patch request to a particular resource
	 *
	 * @param array $data The request params
	 *
	 * @return \Illuminate\Http\Client\Response
	 */
	public function patch(array $data = []): \Illuminate\Http\Client\Response|string;
	/**
	 * Make delete request to a particular resource
	 *
	 * @param array $data The request params
	 *
	 * @return \Illuminate\Http\Client\Response|string
	 */
	public function delete(array $data = []): \Illuminate\Http\Client\Response|string;
}