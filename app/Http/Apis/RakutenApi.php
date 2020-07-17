<?php

namespace App\Http\Apis;

use Psr\Http\Message\ResponseInterface;

class RakutenApi extends AbstractApi
{
    const JSON = 'json';
    const XML  = 'xml';

    /** @var $affiliateId */
    protected $affiliateId;

    public function __construct()
    {
        parent::__construct(
            env('RAKUTEN_URL'),
            env('RAKUTEN_APP_ID')
        );

        $this->affiliateId = env('RAKUTEN_AFFILIATE_ID');
        $this->params      = [
            'applicationId' => $this->appId,
            'affiliateId'   => $this->affiliateId,
        ];
    }

    /**
     * Get products by keyword (or JAN)
     *
     * @link https://webservice.rakuten.co.jp/api/productsearch/
     *
     * @param  string|null $keyword
     * @return ResponseInterface
     */
    public function getProducts(?string $keyword): ResponseInterface
    {
        $url    = "{$this->url}/services/api/IchibaItem/Search/20170706";
        $params = array_merge($this->params, [
            'keyword' => $keyword ?? '',
        ]);

        return $this->get($url, $params);
    }
}