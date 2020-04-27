<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class PingController extends Controller
{
    /**
     * Ping the API
     *
     * @return JsonResponse
     */
    public function ping(): JsonResponse
    {
        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
