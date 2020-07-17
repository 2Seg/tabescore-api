<?php

namespace App\Http\Apis;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\PromiseInterface;

abstract class AbstractApi
{
    protected $client;

    /** @var $string */
    protected $url;

    /** @var $string */
    protected $appId;

    /** @var array */
    protected $params = [];

    public function __construct(string $url, string $appId)
    {
        $this->url   = $url;
        $this->appId = $appId;

        $this->client = new Client([
            'base_uri' => $this->url,
            'headers'  => ['Content-Type' => 'application/json'],
        ]);
    }

    /**
     * Perform a GET HTTP request
     *
     * @param  string $url
     * @param  array $params
     * @return ResponseInterface
     */
    protected function get(string $url, array $params = []): ResponseInterface
    {
        return $this->client->get($url, ['query' => $params]);
    }

    /**
     * Perform a GET HTTP request
     *
     * @param  string $url
     * @param  array $params
     * @return ResponseInterface
     */
    protected function getAsync(string $url, array $params = []): PromiseInterface
    {
        return $this->client->getAsync($url, ['query' => $params]);
    }
}