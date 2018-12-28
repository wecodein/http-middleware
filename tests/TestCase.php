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

use Http\Factory\Guzzle\ResponseFactory;
use Http\Factory\Guzzle\ServerRequestFactory;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WeCodeIn\Http\Server\RequestHandler;

class TestCase extends BaseTestCase
{
    protected function getServerRequestFactory(): ServerRequestFactoryInterface
    {
        return new ServerRequestFactory();
    }

    protected function createServerRequest(): ServerRequestInterface
    {
        return $this->getServerRequestFactory()
            ->createServerRequest('GET', 'http://example.com');
    }

    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return new ResponseFactory();
    }

    protected function createResponse(int $code = 200): ResponseInterface
    {
        return $this->getResponseFactory()
            ->createResponse($code);
    }

    protected function createRequestHandler(MiddlewareInterface ...$middlewares): RequestHandlerInterface
    {
        return new RequestHandler($this->getResponseFactory(), ...$middlewares);
    }
}
