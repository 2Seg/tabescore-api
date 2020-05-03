<?php

namespace Tests\Small\Http\Services;

use ReflectionClass;
use Mockery\MockInterface;
use Illuminate\Http\Request;
use Tests\Small\SmallTestCase;
use App\Http\Services\SevenElevenService;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class SevenElevenTest extends SmallTestCase
{
    /** @var string */
    protected $url;

    /** @var string */
    protected $appId;

    /** @var string */
    protected $secret;

    /** @var array */
    protected $params;

    /** @var MockInterface */
    protected $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url    = env('SEVEN_ELEVEN_URL');
        $this->appId  = env('SEVEN_ELEVEN_APP_ID');
        $this->secret = env('SEVEN_ELEVEN_SECRET');
        $this->params = [
            'ResponseFormat' => 'JSON',
            'ApiUserId'      => $this->appId,
            'Timestamp'      => '2000-01-01',
        ];
        $this->response = $this->mock(Response::class);

        Http::fake();
    }

    protected function init(): SevenElevenService
    {
        $sevenEleven = new SevenElevenService;
        $reflection  = new ReflectionClass($sevenEleven);
        $property = $reflection->getProperty('params');
        $property->setAccessible(true);
        $property->setValue($sevenEleven, $this->params);

        return $sevenEleven;
    }

    public function testGetCategories(): void
    {
        $url          = "{$this->url}/af_api/affiliate/rest/GetShoppingCategory";
        $categoryCode = '4015522';
        $params       = array_merge($this->params, [
            'CategoryCode' => $categoryCode,
        ]);
        ksort($params);
        $signature    = ['Signature' => $this->sign($url, $params)];

        Http::shouldReceive('get')
            ->with($url, array_merge($params, $signature))
            ->once()
            ->andReturn($this->response);

        $this->response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getCategories($categoryCode);
        $this->assertSame([], $actual);
    }

    public function testGetProducts(): void
    {
        $url          = "{$this->url}/af_api/affiliate/rest/SearchProduct";
        $categoryCode = '4015522';
        $keyword      = 'ペットフード';
        $params       = array_merge($this->params, [
            'CategoryCode' => $categoryCode,
            'KeywordIn'    => $keyword,
        ]);
        ksort($params);
        $signature    = ['Signature' => $this->sign($url, $params)];

        Http::shouldReceive('get')
            ->with($url, array_merge($params, $signature))
            ->once()
            ->andReturn($this->response);

        $this->response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getProducts($categoryCode, $keyword);
        $this->assertSame([], $actual);
    }

    public function testGetRankings(): void
    {
        $url          = "{$this->url}/af_api/affiliate/rest/SearchRanking";
        $categoryCode = '4015522';
        $keyword      = 'ペットフード';
        $params       = array_merge($this->params, [
            'CategoryCode' => $categoryCode,
        ]);
        ksort($params);
        $signature    = ['Signature' => $this->sign($url, $params)];

        Http::shouldReceive('get')
            ->with($url, array_merge($params, $signature))
            ->once()
            ->andReturn($this->response);

        $this->response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getRankings($categoryCode, $keyword);
        $this->assertSame([], $actual);
    }

    public function testGetReviews(): void
    {
        $url         = "{$this->url}/af_api/affiliate/rest/SearchProductReview";
        $productCode = '4015522';
        $params      = array_merge($this->params, [
            'ProductCode' => $productCode,
        ]);
        ksort($params);
        $signature    = ['Signature' => $this->sign($url, $params)];

        Http::shouldReceive('get')
            ->with($url, array_merge($params, $signature))
            ->once()
            ->andReturn($this->response);

        $this->response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getReviews($productCode);
        $this->assertSame([], $actual);
    }

    protected function sign(string $url, array $params, string $method = Request::METHOD_GET): string
    {
        $url = "$method|$url";

        foreach($params as $key => $value)
            $url .= "|$key=$value";

        return base64_encode(hash_hmac('sha256', rawurlencode($url), $this->secret, true));
    }
}