<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SevenEleven extends ServiceAPI
{
    const JSON = 'JSON';
    const XML  = 'XML';
    const ALGO = 'sha256';

    /** @var string */
    protected $secret;

    public function __construct()
    {
        parent::__construct(
            env('SEVEN_ELEVEN_URL'),
            env('SEVEN_ELEVEN_APP_ID')
        );

        $this->secret = env('SEVEN_ELEVEN_SECRET');
        $this->params = [
            'ResponseFormat' => self::JSON,
            'ApiUserId'      => $this->appId,
            'Timestamp'      => now()->toIso8601ZuluString(),
        ];
    }

    /**
     * Get information about categories
     *
     * @link https://7af.omni7.jp/af_static_site/API2.html
     *
     * @param  string|null $categoryCode 4015522
     * @param  string|null $keyword      ペットフード
     * @return array|null
     */
    public function getCategories(?string $categoryCode, ?string $keyword = null): ?array
    {
        $url    = "{$this->url}/af_api/affiliate/rest/GetShoppingCategory";
        $params = $this->getParams($url, [
            'CategoryCode' => $categoryCode ?? '',
            'KeywordIn'    => $keyword ?? '',
        ]);

        return Http::get($url, $params)->json();
    }

    /**
     * Get products from category/keyword
     *
     * @link https://7af.omni7.jp/af_static_site/API3.html
     *
     * @param  string|null $categoryCode 4015522
     * @param  string|null $keyword      ペットフード
     * @return array|null
     */
    public function getProducts(?string $categoryCode, ?string $keyword = null): ?array
    {
        $url    = "{$this->url}/af_api/affiliate/rest/SearchProduct";
        $params = $this->getParams($url, [
            'CategoryCode' => $categoryCode ?? '',
            'KeywordIn'    => $keyword ?? '',
        ]);

        return Http::get($url, $params)->json();
    }

    /**
     * Get rankings from category/keyword
     *
     * @link https://7af.omni7.jp/af_static_site/API4.html
     *
     * @param  string|null $categoryCode 4015522
     * @param  string|null $keyword      ペットフード
     * @return array|null
     */
    public function getRankings(?string $categoryCode, ?string $keyword = null): ?array
    {
        $url    = "{$this->url}/af_api/affiliate/rest/SearchRanking";
        $params = $this->getParams($url, [
            'CategoryCode' => $categoryCode ?? '',
            'KeywordIn'    => $keyword ?? '',
        ]);

        return Http::get($url, $params)->json();
    }

    /**
     * Get reviews from category/keyword
     *
     * @link https://7af.omni7.jp/af_static_site/API5.html
     *
     * @param  string|null $productCode
     * @return array|null
     */
    public function getReviews(?string $productCode): ?array
    {
        $url    = "{$this->url}/af_api/affiliate/rest/SearchProductReview";
        $params = $this->getParams($url, [
            'ProductCode' => $productCode ?? '',
        ]);

        return Http::get($url, $params)->json();
    }

    /**
     * Get necessary request parameters
     *
     * @param  string|null $url
     * @param  array|null $params
     * @return array
     */
    protected function getParams(string $url, ?array $params): array
    {
        $signParams = array_merge($this->params, $params);
        ksort($signParams);

        return array_merge(
            $signParams,
            ['Signature' => $this->sign($url, $signParams)]
        );
    }

    /**
     * Create the API signature
     *
     * @link https://7af.omni7.jp/af_static_site/static_063.html
     *
     * @param  string $url
     * @param  array $params
     * @param  string $method
     * @return string
     */
    protected function sign(string $url, array $params, string $method = Request::METHOD_GET): string
    {
        $url = "$method|$url";

        foreach($params as $key => $value)
            $url .= "|$key=$value";

        return base64_encode(hash_hmac(self::ALGO, rawurlencode($url), $this->secret, true));
    }
}