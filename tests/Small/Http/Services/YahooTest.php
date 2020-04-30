<?php

namespace Tests\Small\Http\Services;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->url   = env('YAHOO_URL');
        $this->appId = env('YAHOO_APP_ID');

        Http::fake();
    }

    protected function init()
    {
        return new Yahoo;
    }

    public function testGetProducts(): void
    {
        $response = $this->mock(Response::class);
        $jan      = 4902105033746;

        Http::shouldReceive('get')
            ->with("{$this->url}/ShoppingWebService/V1/json/itemSearch", [
                'appid' => $this->appId,
                'jan'   => $jan,
                'hits'  => 50
            ])
            ->andReturn($response);

        $response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getProducts($jan);
        $this->assertSame([], $actual);
    }

    public function testGetProduct(): void
    {
        $response    = $this->mock(Response::class);
        $code        = 'v-drug_0270030-4902105033746x12';
        $detailLevel = Yahoo::SMALL;

        Http::shouldReceive('get')
            ->with("{$this->url}/ShoppingWebService/V1/json/itemLookup", [
                'appid'          => $this->appId,
                'itemcode'       => $code,
                'responsegroupe' => $detailLevel
            ])
            ->andReturn($response);

        $response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getProduct($code, $detailLevel);
        $this->assertSame([], $actual);
    }
}