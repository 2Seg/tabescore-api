<?php

namespace App\Http\Services;

class Rakuten extends AbstractService
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
     * @return array|null
     */
    public function getProducts(?string $keyword): ?array
    {
        $url    = "{$this->url}/services/api/IchibaItem/Search/20170706";
        $params = array_merge($this->params, [
            'keyword' => $keyword ?? '',
        ]);

        return $this->get($url, $params);
    }
}