<?php

namespace App\Providers;

use App\Http\Responses\ApiResponse;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ResponseFactory $response): void
    {
        $response->macro('api', function ($data) {
            return new ApiResponse($data);
        });
    }
}
