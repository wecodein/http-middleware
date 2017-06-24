<?php
/**
 * This file is part of the http-middleware package.
 *
 * Copyright (c) wecodein
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace WeCodeIn\Http\ServerMiddleware\Tests;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WeCodeIn\Http\ServerMiddleware\Delegate;

/**
 * Class DelegateTest
 * @package Franky\Http\ServerMiddleware
 */
class DelegateTest extends TestCase
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

        $delegate = new Delegate($responseFactory);
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

        $delegate = new Delegate($responseFactory);
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

        $delegate = new Delegate($responseFactory);
        $delegate->insert($middleware);
        $delegate->insert($middleware);
        $delegate($request);
        $delegate($request);
    }

    /**
     * @group ServerMiddleware
     */
    public function testBadMiddlewareReturnType()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $defaultResponse = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')
            ->willReturn($defaultResponse);

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->method('process')
            ->willReturn('test');

        $this->expectException(\OutOfBoundsException::class);

        $delegate = new Delegate($responseFactory);
        $delegate->insert($middleware);
        $delegate->process($request);
    }
}
