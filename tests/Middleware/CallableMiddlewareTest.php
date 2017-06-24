<?php
/**
 * This file is part of the http-middleware package.
 *
 * Copyright (c) wecodein
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace WeCodeIn\Http\ServerMiddleware\Tests\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WeCodeIn\Http\ServerMiddleware\Middleware\CallableMiddleware;

/**
 * Class CallableMiddlewareTest
 * @package Franky\Http\ServerMiddleware\Middleware
 */
class CallableMiddlewareTest extends TestCase
{

    /**
     * @group ServerMiddleware
     */
    public function testReturnDefaultResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $defaultResponse = $this->createMock(ResponseInterface::class);
        $delegate = $this->createMock(DelegateInterface::class);

        $callable = $this->createPartialMock(\stdClass::class, ['__invoke']);
        $callable->expects($this->once())
            ->method('__invoke')
            ->with($request, $delegate)
            ->willReturn($defaultResponse);

        $middleware = new CallableMiddleware($callable);
        $response = $middleware->process($request, $delegate);

        $this->assertSame($defaultResponse, $response);
    }
}
