<?php

namespace Tests\Small\Http\Services;

use Mockery\MockInterface;
use Tests\Small\SmallTestCase;
use App\Http\Services\RakutenService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RakutenTest extends SmallTestCase
{
    /** @var string */
    protected $url;

    /** @var string */
    protected $appId;

    /** @var string */
    protected $affiliateId;

    /** @var MockInterface */
    protected $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url         = env('RAKUTEN_URL');
        $this->appId       = env('RAKUTEN_APP_ID');
        $this->affiliateId = env('RAKUTEN_AFFILIATE_ID');
        $this->response    = $this->mock(Response::class);

        Http::fake();
    }

    protected function init(): RakutenService
    {
        return new RakutenService;
    }

    public function testGetProducts(): void
    {
        $url     = "{$this->url}/services/api/IchibaItem/Search/20170706";
        $keyword = '4902105033746';
        $params  = [
            'applicationId' => $this->appId,
            'affiliateId'   => $this->affiliateId,
            'keyword'       => $keyword,
        ];

        Http::shouldReceive('get')
            ->once()
            ->with($url, $params)
            ->andReturn($this->response);

        $this->response
            ->shouldReceive('json')
            ->once()
            ->andReturn([]);

        $actual = $this->init()->getProducts($keyword);
        $this->assertSame([], $actual);
    }
}