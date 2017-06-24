<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Http\Factory\Guzzle\ResponseFactory;
use Http\Factory\Guzzle\ServerRequestFactory;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use WeCodeIn\Http\ServerMiddleware\Dispatcher;
use WeCodeIn\Http\ServerMiddleware\Middleware\CallableMiddleware;

$dispatcher =
    (new Dispatcher(new ResponseFactory()))
        ->insert(new CallableMiddleware(
            function (ServerRequestInterface $request, DelegateInterface $delegate) {
                return $delegate->process($request)
                    ->withStatus(204, 'No Content');
            }
        ));

$response = $dispatcher(
    (new ServerRequestFactory())
        ->createServerRequest('GET', 'http://localhost/')
);

assert($response->getStatusCode() === 204);
assert($response->getReasonPhrase() === 'No Content');
