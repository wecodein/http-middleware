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

namespace WeCodeIn\Http\Server;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    protected $responseFactory;
    protected $middlewares;

    public function __construct(ResponseFactoryInterface $responseFactory, MiddlewareInterface ...$middlewares)
    {
        $this->responseFactory = $responseFactory;
        $this->middlewares = $middlewares;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $handler = clone $this;

        if (null === key($handler->middlewares)) {
            return $this->responseFactory->createResponse();
        }

        $middleware = current($handler->middlewares);
        next($handler->middlewares);

        return $middleware->process($request, $handler);
    }
}
