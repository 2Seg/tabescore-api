<?php

namespace App\Http\Apis;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\PromiseInterface;

class YahooApi extends AbstractApi
{
    const SMALL  = 'small';
    const MEDIUM = 'medium';
    const LARGE  = 'large';

    public function __construct()
    {
        parent::__construct(
            env('YAHOO_URL'),
            env('YAHOO_APP_ID')
        );

        $this->params = ['appid' => $this->appId];
    }

    /**
     * Get products from JAN number
     *
     * @link https://developer.yahoo.co.jp/webapi/shopping/shopping/v1/itemsearch.html
     *
     * @param  string $jan
     * @param  int $hits
     * @return ResponseInterface
     */
    public function getProducts(string $jan, int $hits = 50): ResponseInterface
    {
        $url    = "{$this->url}/ShoppingWebService/V3/itemSearch";
        $params = array_merge($this->params, [
            'jan_code'  => $jan,
            'hits'      => $hits,
        ]);

        return $this->get($url, $params);
    }

    /**
     * Get product detail asynchronously
     *
     * @link https://developer.yahoo.co.jp/webapi/shopping/shopping/v1/itemlookup.html
     *
     * @param  string $code
     * @param  string $detailLevel
     * @return PromiseInterface
     */
    public function getProductAsync(string $code, $detailLevel = self::LARGE): PromiseInterface
    {
        $url    = "{$this->url}/ShoppingWebService/V1/json/itemLookup";
        $params = array_merge($this->params, [
            'itemcode'       => $code,
            'responsegroup'  => $detailLevel,
        ]);

        return $this->getAsync($url, $params);
    }
}