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

namespace WeCodeIn\Http\ServerMiddleware\Tests;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WeCodeIn\Http\ServerMiddleware\Dispatcher;
use WeCodeIn\Http\ServerMiddleware\Exception\InvalidMiddlewareResponseException;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class DispatcherTest extends TestCase
{

    /**
     * @group ServerMiddleware
     */
    public function testReturnDefaultResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $defaultResponse = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')
            ->willReturn($defaultResponse);

        $delegate = new Dispatcher($responseFactory);
        $response = $delegate($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame($defaultResponse, $response);
    }

    /**
     * @group ServerMiddleware
     */
    public function testProcessQueue()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $defaultResponse = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')
            ->willReturn($defaultResponse);

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->expects($this->exactly(2))
            ->method('process')
            ->willReturnCallback(function (ServerRequestInterface $request, DelegateInterface $delegate) {
                return $delegate->process($request);
            });

        $delegate = new Dispatcher($responseFactory);
        $delegate->insert($middleware);
        $delegate->insert($middleware);
        $delegate->process($request);
    }

    /**
     * @group ServerMiddleware
     */
    public function testInvokeQueueMultipleTimes()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $defaultResponse = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')
            ->willReturn($defaultResponse);

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->expects($this->exactly(4))
            ->method('process')
            ->willReturnCallback(function (ServerRequestInterface $request, DelegateInterface $delegate) {
                return $delegate->process($request);
            });

        $delegate = new Dispatcher($responseFactory);
        $delegate->insert($middleware);
        $delegate->insert($middleware);
        $delegate($request);
        $delegate($request);
    }

    /**
     * @group ServerMiddleware
     */
    public function testInvalidMiddlewareResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $defaultResponse = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')
            ->willReturn($defaultResponse);

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->method('process')
            ->willReturn('test');

        $this->expectException(InvalidMiddlewareResponseException::class);

        $delegate = new Dispatcher($responseFactory);
        $delegate->insert($middleware);
        $delegate->process($request);
    }
}
