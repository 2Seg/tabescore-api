<?php

namespace Tests\Small\Http\Services;

use Mockery\MockInterface;
use App\Http\Services\Yahoo;
use Tests\Small\SmallTestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class YahooTest extends SmallTestCase
{
    /** @var string */
    protected $url;

    /** @var string */
    protected $appId;

    /** @var MockInterface */
    protected $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url      = env('YAHOO_URL');
        $this->appId    = env('YAHOO_APP_ID');
        $this->response = $this->mock(Response::class);

        Http::fake();
    }

    protected function init(): Yahoo
    {
        return new Yahoo;
    }

    public function testGetProducts(): void
    {
        $url    = "{$this->url}/ShoppingWebService/V1/json/itemSearch";
        $jan    = '4902105033746';
        $params = [
            'appid' => $this->appId,
            'jan'   => $jan,
            'hits'  => 50
        ];

        Http::shouldReceive('get')
            ->once()
            ->with($url, $params)
            ->andReturn($this->response);

        $this->response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getProducts($jan);
        $this->assertSame([], $actual);
    }

    public function testGetProduct(): void
    {
        $url         = "{$this->url}/ShoppingWebService/V1/json/itemLookup";
        $code        = 'v-drug_0270030-4902105033746x12';
        $detailLevel = Yahoo::SMALL;
        $params      = [
            'appid'          => $this->appId,
            'itemcode'       => $code,
            'responsegroupe' => $detailLevel,
        ];

        Http::shouldReceive('get')
            ->once()
            ->with($url, $params)
            ->andReturn($this->response);

        $this->response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getProduct($code, $detailLevel);
        $this->assertSame([], $actual);
    }
}