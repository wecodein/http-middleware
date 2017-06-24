<?php
/**
 * This file is part of the http-middleware package.
 *
 * Copyright (c) Dusan Vejin
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace WeCodeIn\Http\ServerMiddleware;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Delegate
 * @package WeCodeIn\Http\ServerMiddleware
 */
class Delegate implements DelegateInterface
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

    /**
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->queue = new \SplPriorityQueue();
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @param int $priority
     * @return Delegate
     */
    public function insert(MiddlewareInterface $middleware, int $priority = 0) : Delegate
    {
        $this->queue->insert($middleware, [$priority, $this->serial--]);
        return $this;
    }

    /**
     * @return MiddlewareInterface
     */
    public function extract() : MiddlewareInterface
    {
        return $this->queue->extract();
    }

    /**
     * @return bool
     */
    public function isEmpty() : bool
    {
        return $this->queue->isEmpty();
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $delegate = clone $this;
        return $delegate->process($request);
    }

    /**
     * Clone queue to allow multiple processing of same queue.
     */
    public function __clone()
    {
        $this->queue = clone $this->queue;
    }
}
