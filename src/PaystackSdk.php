<?php namespace ObitechBilmapay\LaravelPaystackSdk;

use Illuminate\Support\Facades\Http;
use ObitechBilmapay\LaravelPaystackSdk\Abstraction\PaystackSdkInterface;
use ObitechBilmapay\LaravelPaystackSdk\Helpers\HttpClientHelpers;
use ObitechBilmapay\LaravelPaystackSdk\Helpers\PaystackSdkHelpers;
use stdClass;

/**
 * The Official Paystack Standard Development Kit For PHP Laravel framwork
 * @package Library
 * @author BilmaPay by OBITECH INVENT <obitechinvents@gmail.com>
 * @license MIT
 * @link https://paystack.com/docs/api
 */
class PaystackSdk implements PaystackSdkInterface
{
	/**
	 * the operation status
	 *
	 * @var bool
	 */
	public bool $success;
	/**
	 * The api request response data
	 *
	 * @var object|array|string|null
	 */
	public $response;
	/**
	 * The response data
	 *
	 * @var object|array|null
	 */
	public $data;
	/**
	 * The operaion or api request error
	 *
	 * @var array|string
	 */
	protected $error;
	/**
	 * The api resource path
	 *
	 * @var string
	 */
	protected string $resourcesPath;

	/**
	 * The http client
	 *
	 * @var \Illuminate\Support\Facades\Http
	 */
	protected $HttpClient;
	/**
	 * The http client request headers
	 *
	 * @var array
	 */
	protected array $HttpClientHeaders = [];

	/** The handles and helpers trait */
	use PaystackSdkHelpers, HttpClientHelpers;

	public function __construct()
	{
		/* configure the http */
		if (!empty(count($this->HttpClientHeaders))) {
			/* config with headers options*/
			$this->HttpClient = Http::connectTimeout(config("paystack.timeout", 3))
				->retry(config("paystack.retry", 3), 100)
				->withToken(config("paystack.secret"))
				->acceptJson()
				->withHeaders($this->HttpClientHeaders);
		} else {
			/* config without headers options */
			$this->HttpClient = Http::connectTimeout(config("paystack.timeout", 3))
				->retry(config("paystack.retry", 3), 100)
				->withToken(config("paystack.secret"))
				->acceptJson()
				->withHeaders($this->HttpClientHeaders);
		}
	}
}