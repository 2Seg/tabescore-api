<?php

namespace Tests\Small\Http\Responses;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Tests\Small\SmallTestCase;
use Illuminate\Contracts\Routing\ResponseFactory;

class ApiResponseTest extends SmallTestCase
{
    protected function init(): ApiResponse
    {
        return new ApiResponse;
    }

    public function testToResponse(): void
    {
        $data         = ['data' => ''];
        $jsonResponse = $this->mock(JsonResponse::class);

        $this->mock(ResponseFactory::class, function ($mock) use ($data, $jsonResponse) {
            $mock->shouldReceive('json')
                ->once()
                ->with($data)
                ->andReturns($jsonResponse);
        });

        $actual = $this->init()->toResponse(null);
        $this->assertEquals($jsonResponse, $actual);
    }
}