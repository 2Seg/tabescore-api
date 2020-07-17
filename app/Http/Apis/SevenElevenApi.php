<?php

namespace App\Http\Apis;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;

class SevenElevenApi extends AbstractApi
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
     * @param  string $categoryCode 401522
     * @return ResponseInterface
     */
    public function getCategories(string $categoryCode): ResponseInterface
    {
        $url    = "{$this->url}/af_api/affiliate/rest/GetShoppingCategory";
        $params = $this->getParams($url, [
            'CategoryCode' => $categoryCode ?? '',
        ]);

        return $this->get($url, $params);
    }

    /**
     * Get products from category/keyword
     *
     * @link https://7af.omni7.jp/af_static_site/API3.html
     *
     * @param  string|null $categoryCode 4015522
     * @param  string|null $keyword      ペットフード
     * @return ResponseInterface
     */
    public function getProducts(?string $categoryCode, ?string $keyword = null): ResponseInterface
    {
        $url    = "{$this->url}/af_api/affiliate/rest/SearchProduct";
        $params = $this->getParams($url, [
            'CategoryCode' => $categoryCode ?? '',
            'KeywordIn'    => $keyword ?? '',
        ]);

        return $this->get($url, $params);
    }

    /**
     * Get rankings from category
     *
     * @link https://7af.omni7.jp/af_static_site/API4.html
     *
     * @param  string|null $categoryCode 4015522
     * @return ResponseInterface
     */
    public function getRankings(?string $categoryCode): ResponseInterface
    {
        $url    = "{$this->url}/af_api/affiliate/rest/SearchRanking";
        $params = $this->getParams($url, [
            'CategoryCode' => $categoryCode ?? '',
        ]);

        return $this->get($url, $params);
    }

    /**
     * Get reviews from category/keyword
     *
     * @link https://7af.omni7.jp/af_static_site/API5.html
     *
     * @param  string|null $productCode
     * @return ResponseInterface
     */
    public function getReviews(?string $productCode): ResponseInterface
    {
        $url    = "{$this->url}/af_api/affiliate/rest/SearchProductReview";
        $params = $this->getParams($url, [
            'ProductCode' => $productCode ?? '',
        ]);

        return $this->get($url, $params);
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