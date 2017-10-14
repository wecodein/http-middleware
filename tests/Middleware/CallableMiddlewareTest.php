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

namespace WeCodeIn\Http\Server\Tests\Middleware;

use WeCodeIn\Http\Server\Middleware\CallableMiddleware;
use WeCodeIn\Http\Server\Tests\TestCase;

class CallableMiddlewareTest extends TestCase
{
    public function testProcessReturnsCallableProducedResponse()
    {
        $callable = function () use (&$responseReturnedByCallable) {
            return $responseReturnedByCallable = $this->createResponse();
        };

        $request = $this->createServerRequest();
        $handler = $this->createRequestHandler();

        $middleware = new CallableMiddleware($callable);
        $responseReturnedByCallableMiddleware = $middleware->process($request, $handler);

        $this->assertSame($responseReturnedByCallable, $responseReturnedByCallableMiddleware);
    }
}
