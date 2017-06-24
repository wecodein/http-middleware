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

namespace WeCodeIn\Http\ServerMiddleware;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class Dispatcher implements DelegateInterface
{
    /**
     * @var \SplPriorityQueue
     */
    protected $queue;

    /**
     * @var int
     */
    protected $serial = PHP_INT_MAX;

    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->queue = new \SplPriorityQueue();
        $this->responseFactory = $responseFactory;
    }

    public function insert(MiddlewareInterface $middleware, int $priority = 0) : Dispatcher
    {
        $this->queue->insert($middleware, [$priority, $this->serial--]);
        return $this;
    }

    public function extract() : MiddlewareInterface
    {
        return $this->queue->extract();
    }

    public function isEmpty() : bool
    {
        return $this->queue->isEmpty();
    }

    public function process(ServerRequestInterface $request) : ResponseInterface
    {
        if ($this->isEmpty()) {
            return $this->responseFactory->createResponse();
        }

        $middleware = $this->extract();
        $response = $middleware->process($request, $this);

        if (!$response instanceof ResponseInterface) {
            throw new \OutOfBoundsException(sprintf(
                'Middleware %s did not return a %s', get_class($middleware), ResponseInterface::class
            ));
        }

        return $response;
    }

    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $delegate = clone $this;
        return $delegate->process($request);
    }

    public function __clone()
    {
        $this->queue = clone $this->queue;
    }
}
