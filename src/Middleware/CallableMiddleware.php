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

namespace WeCodeIn\Http\ServerMiddleware\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Dusan Vejin <dutekvejin@gmail.com>
 */
class CallableMiddleware implements MiddlewareInterface
{
    /**
     * @var callable
     */
    protected $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        return ($this->callable)($request, $delegate);
    }
}
