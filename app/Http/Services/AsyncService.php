<?php

namespace App\Http\Services;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;

class AsyncService
{
    protected const CONCURRENCY = 25;

    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function makePool(
        callable $generator,
        ?callable $fulfilled = null,
        ?callable $rejected = null,
        int $concurrency = self::CONCURRENCY
    ): Pool {
        return new Pool($this->client, $generator(), [
            'concurrency' => $concurrency,
            'fulfilled'   => $fulfilled,
            'rejected'    => $rejected,
        ]);
    }
}