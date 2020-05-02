<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class Yahoo extends ServiceAPI
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
     * @param  int $jan
     * @param  int $hits
     * @return array|null
     */
    public function getProducts(int $jan, int $hits = 50): ?array
    {
        $url    = "{$this->url}/ShoppingWebService/V1/json/itemSearch";
        $params = array_merge($this->params, [
            'jan'  => $jan,
            'hits' => $hits,
        ]);

        return Http::get($url, $params)->json();
    }

    /**
     * Get product detail
     *
     * @link https://developer.yahoo.co.jp/webapi/shopping/shopping/v1/itemlookup.html
     *
     * @param  string $code
     * @param  string $detailLevel
     * @return array|null
     */
    public function getProduct(string $code, $detailLevel = self::LARGE): ?array
    {
        $url    = "{$this->url}/ShoppingWebService/V1/json/itemLookup";
        $params = array_merge($this->params, [
            'itemcode'       => $code,
            'responsegroupe' => $detailLevel,
        ]);

        return Http::get($url, $params)->json();
    }
}