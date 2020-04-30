<?php

namespace Tests\Small\Http\Resources;

use Illuminate\Support\Arr;
use App\Http\Resources\ExceptionResource;
use Exception;
use Illuminate\Http\Resources\MergeValue;
use Illuminate\Http\Resources\MissingValue;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Small\SmallTestCase;

class ExceptionResourceTest extends SmallTestCase
{
    /** @var MockObject */
    protected $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = $this->mock(Exception::class);
    }

    protected function init(): ExceptionResource
    {
        return new ExceptionResource($this->resource);
    }

    public function testToArrayInLocalEnv(): void
    {
        app('config')->set('app.env', 'local');
        app('config')->set('app.debug', true);

        $excepted = [
            'title'   => get_class($this->resource),
            'message' => '',
            new MergeValue([
                'file'  => $this->resource->getFile(),
                'line'  => $this->resource->getLine(),
                'trace' => collect($this->resource->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ]),
        ];

        $actual = $this->init()->toArray(null);
        $this->assertEquals($excepted, $actual);
    }

    public function testToArrayInLocalEnvNoDebug(): void
    {
        app('config')->set('app.env', 'local');
        app('config')->set('app.debug', false);

        $excepted = [
            'title'   => get_class($this->resource),
            'message' => '',
            new MissingValue,
        ];

        $actual = $this->init()->toArray(null);
        $this->assertEquals($excepted, $actual);
    }

    public function testToArrayInProductionEnv(): void
    {
        app('config')->set('app.env', 'production');
        app('config')->set('app.debug', true);

        $excepted = [
            'title'   => get_class($this->resource),
            'message' => '',
            new MissingValue,
        ];

        $actual = $this->init()->toArray(null);
        $this->assertEquals($excepted, $actual);
    }

    public function testToArrayInProductionEnvNoDebug(): void
    {
        app('config')->set('app.env', 'production');
        app('config')->set('app.debug', false);

        $expected = [
            'title'   => get_class($this->resource),
            'message' => '',
            new MissingValue,
        ];

        $actual = $this->init()->toArray(null);
        $this->assertEquals($expected, $actual);
    }

    public function testResolveInProductionEnv(): void
    {
        app('config')->set('app.env', 'production');
        app('config')->set('app.debug', true); // doesn't matter

        $expected = ['error' => [
            'title'   => get_class($this->resource),
            'message' => '',
        ]];
        $actual = $this->init()->resolve(null);

        $this->assertEquals($expected, $actual);
    }
}
