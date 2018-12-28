<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Http\Factory\Diactoros\ResponseFactory;
use Http\Factory\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use WeCodeIn\Http\Server\RequestHandler;
use WeCodeIn\Http\Server\Middleware\CallableMiddleware;

$serverRequestFactory = new ServerRequestFactory();
$serverRequest = $serverRequestFactory->createServerRequest('GET', 'http://localhost');

$returnNoContentMiddleware = new CallableMiddleware(
    function (ServerRequestInterface $request, RequestHandlerInterface $requestHandler) {
        return $requestHandler->handle($request)
            ->withStatus(204, 'No Content');
    }
);

$middlewares = [$returnNoContentMiddleware];

$requestHandler = new RequestHandler(new ResponseFactory(), ...$middlewares);
$response = $requestHandler->handle($serverRequest);

assert($response->getStatusCode() === 204);
assert($response->getReasonPhrase() === 'No Content');
