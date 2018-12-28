<?php
/**
 * This file is part of the http-middleware package.
 *
 * Copyright (c) Dusan Vejin
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare(strict_types=1);

namespace WeCodeIn\Http\Server\Tests;

use PHPUnit\Framework\MockObject\Matcher\Invocation;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandlerTest extends TestCase
{
    public function testHandleProducesResponse()
    {
        $request = $this->createServerRequest();

        $handler = $this->createRequestHandler();
        $response = $handler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testHandleProcessesMiddlewareQueue()
    {
        $middlewares = [];
        $middlewares[] = $this->getMockForMiddleware($this->once());
        $middlewares[] = $this->getMockForMiddleware($this->once());

        $request = $this->createServerRequest();

        $handler = $this->createRequestHandler(...$middlewares);
        $handler->handle($request);
    }

    public function testHandleProcessesMiddlewareQueueUntilFirstMiddlewareThatDoesNotCallHandle()
    {
        $middlewares = [];
        $middlewares[] = $this->getMockForMiddleware($this->once());
        $middlewares[] = $this->getMockForMiddleware($this->once(), $this->createResponse());
        $middlewares[] = $this->getMockForMiddleware($this->never());

        $request = $this->createServerRequest();

        $handler = $this->createRequestHandler(...$middlewares);
        $handler->handle($request);
    }

    public function testHandleReturnsResponseProducedByMiddlewareQueue()
    {
        $responseReturnedByMiddlewareQueue = $this->createResponse();

        $middlewares = [];
        $middlewares[] = $this->getMockForMiddleware($this->any(), $responseReturnedByMiddlewareQueue);

        $request = $this->createServerRequest();

        $handler = $this->createRequestHandler(...$middlewares);
        $responseReturnedByHandler = $handler->handle($request);

        $this->assertSame($responseReturnedByMiddlewareQueue, $responseReturnedByHandler);
    }

    protected function getMockForMiddleware(Invocation $matcher, $response = null)
    {
        $middleware = $this->createMock(MiddlewareInterface::class);

        $processCallback = function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($response) {
            if ($response) {
                return $response;
            }

            return $handler->handle($request);
        };

        $middleware->expects($matcher)
            ->method('process')
            ->willReturnCallback($processCallback);
        return $middleware;
    }
}
