<?php

namespace Tests\Small\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Tests\Small\SmallTestCase;
use Throwable;

class HandlerTest extends SmallTestCase
{
    /** @var MockObject */
    protected $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $this->createMock(Container::class);
    }

    protected function init(): Handler
    {
        return new Handler($this->container);
    }

    public function testReport(): void
    {
        $throwable = $this->createMock(Throwable::class);
        $logger    = $this->createMock(LoggerInterface::class);

        $this->container
            ->expects($this->once())
            ->method('make')
            ->with(LoggerInterface::class)
            ->willReturn($logger);

        $logger
            ->expects($this->once())
            ->method('error')
            ->with('', ['exception' => $throwable]);

        $this->init()->report($throwable);
    }

    public function testRenderThrowable(): void
    {
        app('config')->set('app.env', 'production');

        $request           = $this->createMock(Request::class);
        $throwable         = $this->createMock(Throwable::class);
        $baseHandler       = $this->createMock(BaseHandler::class);
        $data              = ['error' => ['title' => get_class($throwable), 'message' => '']];
        $jsonResponse      = new JsonResponse(
            json_decode(json_encode($data), true, 512),
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

        $baseHandler
            ->expects($this->once())
            ->method('render')
            ->willReturn($jsonResponse);

        $request
            ->expects($this->once())
            ->method('expectsJson')
            ->willReturn(true);

        $actual = $this->init()->render($request, $throwable);
        $this->assertContainsOnlyInstancesOf(JsonResponse::class, [$baseHandler->render($request, $throwable), $actual]);
    }
}