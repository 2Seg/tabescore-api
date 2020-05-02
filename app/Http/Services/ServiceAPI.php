<?php

namespace App\Http\Services;

abstract class ServiceAPI
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
}