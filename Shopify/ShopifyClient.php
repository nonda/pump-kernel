<?php
/**
 * Created by PhpStorm.
 * User: kicoe
 * Date: 2018-12-28
 * Time: 17:56
 */

namespace Nonda\Shopify;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;

/**
 * @method array get(string|array $uri, array $options = [])
 * @method array put(string|array $uri, array $options = [])
 * @method array post(string|array $uri, array $options = [])
 * @method array delete(string|array $uri, array $options = [])
 */
class ShopifyClient
{
    protected $url;
    protected $accessToken;
    protected $shopName;
    /** @var Client */
    protected $httpClient;
    protected $apiKey;

    public static $requestCount;

    public function __construct($apiKey, $accessToken, $shopName)
    {
        $this->apiKey = $apiKey;
        $this->setAccessToken($accessToken);
        $this->setShopName($shopName);
        $this->httpClient = new Client();
    }

    protected function setAccessToken($accessToken)
    {
        if (preg_match('/^([a-zA-Z0-9]{10,100})$/', $accessToken)===0) {
            throw new \InvalidArgumentException('Access token should be between 10 and 100 letters and numbers');
        }
        $this->accessToken = $accessToken;
    }

    protected function setShopName($shopName)
    {
        if (preg_match('/^[a-zA-Z0-9\-]{3,100}\.myshopify\.(?:com|io)$/', $shopName) === 0) {
            throw new \InvalidArgumentException(
                'Shop name should be 3-100 letters, numbers, or hyphens e.g. your-store.myshopify.com'
            );
        }
        $this->shopName = $shopName;
    }

    protected function uriBuilder($resource)
    {
        return "https://{$this->apiKey}:{$this->accessToken}@{$this->shopName}/admin/{$resource}";
    }

    private function authHeaders()
    {
        return [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Shopify-Access-Token: ' . $this->accessToken
        ];
    }

    public function __call($method, $args)
    {
        $now = time();

        if (!isset(self::$requestCount[$now])) {
            self::$requestCount = [$now => 0];
        }

        if (self::$requestCount[$now]++ > 5) {
            while (time() === $now) {
                usleep(10000);
            }

            $now = time();
            self::$requestCount = [$now => 1];
        }

        if (!in_array($method, ['get', 'post', 'put', 'delete'], true)) {
            throw new \InvalidArgumentException('Method not valid');
        }

        if (count($args) < 1) {
            throw new \InvalidArgumentException('Magic request methods require a URI and optional options array');
        }

        // $1 可以传string 'orders/$order_id/cancel.json?x=xx' 也可以传array ['orders', $order_id, 'cancel']
        if (is_string($args[0])) {
            $resource = $args[0];
        } else {
            $resource = implode('/', $args[0]).'.json';
        }

        $uri = $this->uriBuilder($resource);

        $opts = [
            'headers' => $this->authHeaders(),
        ];

        // 不能写'body' 无法转换json raw
        !isset($args[1]) ?: $opts[RequestOptions::JSON] = $args[1];

        $result = [
            'code' => 0,
            'msg' => '',
            'data' => [],
        ];

        try {
            $response = $this->httpClient->request($method, $uri, $opts);
        } catch (ClientException $e) {
            $result['code'] = $e->getCode();
            $result['msg'] = $e->getMessage();
            return $result;
        }

        $result['code'] = $response->getStatusCode();
        $result['data'] = json_decode($response->getBody()->getContents(), true);

        return $result;
    }
    
}