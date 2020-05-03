<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

abstract class AbstractService
{
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
    }

    /**
     * Perform a GET HTTP request
     *
     * @param  string $url
     * @param  array $params
     * @return array|null
     */
    protected function get(string $url, array $params = []): ?array
    {
        return Http::get($url, $params)->json();
    }
}