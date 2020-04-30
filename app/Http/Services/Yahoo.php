<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class Yahoo extends ServiceAPI
{
    /** @var string */
    protected $appId;

    const SMALL  = 'small';
    const MEDIUM = 'medium';
    const LARGE  = 'large';

    public function __construct()
    {
        $this->url   = env('YAHOO_URL');
        $this->appId = env('YAHOO_APP_ID');
    }

    /**
     * Get products from JAN number
     *
     * @param  int $jan
     * @param  int $hits
     * @return array
     */
    public function getProducts(int $jan, int $hits = 50): ?array
    {
        return Http::get("{$this->url}/ShoppingWebService/V1/json/itemSearch", [
            'appid' => $this->appId,
            'jan'   => $jan,
            'hits'  => $hits,
        ])->json();
    }

    /**
     * Get product detail
     *
     * @param  string $code
     * @param  string $detailLevel
     * @return array
     */
    public function getProduct(string $code, $detailLevel = self::LARGE): ?array
    {
        return Http::get("{$this->url}/ShoppingWebService/V1/json/itemLookup", [
            'appid'          => $this->appId,
            'itemcode'       => $code,
            'responsegroupe' => $detailLevel,
        ])->json();
    }
}