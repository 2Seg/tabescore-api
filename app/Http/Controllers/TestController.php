<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function test(\Illuminate\Http\Request $request)
    {
        $client = new Client([
            'http_errors'     => false,
            'headers' => [
                'User-Agent' => 'Test/1.0'
            ]
        ]);

        $requests = function () {
            $uris = [
                'https://httpbin.org/get',
                'https://httpbin.org/delay/1',
                'https://httpbin.org/delay/2',
                'https://httpbin.org/status/500',
            ];
            for ($i = 0; $i < count($uris); $i++) {
                yield new Request('GET', $uris[$i]);
            }
        };

        $pool = new Pool($client, $requests(), [
            'concurrency' => 10,
            'fulfilled' => function ($response, $index) {
                // this is delivered each successful response
//                dump($index." fulfilled");
            },
            'rejected' => function ($response, $index) {
                // this is delivered each failed request
//                dump($index." rejected");
            },
        ]);
        // Initiate the transfers and create a promise
        $promise = $pool->promise();
        // Force the pool of requests to complete.
        $promise->wait();

        return response()->json(['bonsoir']);
    }
}
