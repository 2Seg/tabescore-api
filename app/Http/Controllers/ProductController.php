<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Handlers\GetProductHandler;
use App\Http\Requests\GetProductScoreRequest;

class ProductController extends Controller
{
    /**
     * Retrieve a product
     *
     * @param  GetProductScoreRequest $request
     * @param  GetProductHandler $handler
     * @return ApiResponse
     */
    public function getScore(GetProductScoreRequest $request, GetProductHandler $handler): ApiResponse
    {
        return response()->api($handler->handle($request));
    }
}
