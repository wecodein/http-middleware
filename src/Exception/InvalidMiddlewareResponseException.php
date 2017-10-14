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

namespace WeCodeIn\Http\Server\Exception;

use Interop\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;

class InvalidMiddlewareResponseException extends OutOfBoundsException
{
    public static function forMiddleware(MiddlewareInterface $middleware) : self
    {
        return new self(sprintf(
            'Middleware %s did not return a %s',
            get_class($middleware),
            ResponseInterface::class
        ));
    }
}
