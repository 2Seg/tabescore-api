<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Http\Handlers\Product\ScoreHandler;
use App\Http\Requests\GetProductScoreRequest;

class ProductController extends Controller
{
    /**
     * Retrieve a product
     *
     * @param  GetProductScoreRequest $request
     * @param  ScoreHandler $handler
     * @return ApiResponse
     */
    public function getScore(GetProductScoreRequest $request, ScoreHandler $handler): ApiResponse
    {
        return response()->api($handler->handle($request));
    }
}
