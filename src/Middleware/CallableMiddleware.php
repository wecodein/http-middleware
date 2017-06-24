<?php
/**
 * This file is part of the http-middleware package.
 *
 * Copyright (c) Dusan Vejin
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace WeCodeIn\Http\ServerMiddleware\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CallableMiddleware
 * @package WeCodeIn\Http\ServerMiddleware\Middleware
 */
class CallableMiddleware implements MiddlewareInterface
{

    /**
     * @var callable
     */
    protected $callable;

    /**
     * CallableMiddleware constructor.
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        return ($this->callable)($request, $delegate);
    }
}
