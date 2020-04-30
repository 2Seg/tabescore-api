<?php

namespace Tests\Small\Http\Responses;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Tests\Small\SmallTestCase;

class ApiResponseTest extends SmallTestCase
{
    protected function init(): ApiResponse
    {
        return new ApiResponse();
    }

    public function testToResponse(): void
    {
        $actual = $this->init()->toResponse(null);
        $this->assertEquals(new JsonResponse(['data' => '']), $actual);
    }
}