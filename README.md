# http-middleware

[![Build Status][ico-build]][link-build]
[![Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-code-coverage]][link-code-coverage]
[![Latest Version][ico-version]][link-packagist]
[![PDS Skeleton][ico-pds]][link-pds]

PSR-15 compliant implementation for processing middleware queues.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require wecodein/http-middleware
```

## Usage

``` php
$requestHandler = new RequestHandler($responseFactory, ...$middlewares);
$response = $requestHandler->handle($serverRequest);
```

See [examples][link-examples].

## Credits

- [Dusan Vejin][link-author]
- [All Contributors][link-contributors]

## License

Released under MIT License - see the [License File](LICENSE) for details.


[ico-version]: https://img.shields.io/packagist/v/wecodein/http-middleware.svg
[ico-build]: https://travis-ci.org/wecodein/http-middleware.svg?branch=master
[ico-code-coverage]: https://img.shields.io/scrutinizer/coverage/g/wecodein/http-middleware.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/wecodein/http-middleware.svg
[ico-pds]: https://img.shields.io/badge/pds-skeleton-blue.svg

[link-packagist]: https://packagist.org/packages/wecodein/http-middleware
[link-build]: https://travis-ci.org/wecodein/http-middleware
[link-code-coverage]: https://scrutinizer-ci.com/g/wecodein/http-middleware/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/wecodein/http-middleware
[link-pds]: https://github.com/php-pds/skeleton
[link-author]: https://github.com/dutekvejin
[link-contributors]: ../../contributors
[link-examples]: examples
