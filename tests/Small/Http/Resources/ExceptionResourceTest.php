<?php

namespace Tests\Small\Http\Resources;

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

        $this->resource = $this->createMock(Exception::class);
    }

    protected function init(): ExceptionResource
    {
        return new ExceptionResource($this->resource);
    }

    public function testToArrayInLocalEnv(): void
    {
        $excepted = [
            'title'   => get_class($this->resource),
            'message' => '',
            new MergeValue(null),
        ];

        $actual = $this->init()->toArray(null);
        $this->assertEquals(
            array_slice($excepted, 0, 2),
            array_slice($actual, 0, 2)
        );
        $this->assertInstanceOf(MergeValue::class, $actual[0]);
    }

    public function testToArrayInLocalEnvWithoutDebug(): void
    {
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

        $excepted = [
            'title'   => get_class($this->resource),
            'message' => '',
            new MissingValue,
        ];

        $actual = $this->init()->toArray(null);
        $this->assertEquals($excepted, $actual);
    }

    public function testToArrayInProductionEnvWithDebug(): void
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

    public function testResolveInProductionEnv(): void
    {
        app('config')->set('app.env', 'production');

        $excepted = ['error' => [
            'title'   => get_class($this->resource),
            'message' => '',
        ]];
        $actual = $this->init()->resolve(null);

        $this->assertEquals($excepted, $actual);
    }
}
