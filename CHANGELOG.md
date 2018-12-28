# Change Log
All notable changes to this project will be documented in this file.

## 1.0.1 - December 28, 2018

### Added

- Added support for `psr/http-factory` ^1.0
- Added support for `psr/http-server-handler` ^1.0
- Added support for `psr/http-server-middlewar` ^1.0

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Removed support for `http-interop/http-factory`
- Removed support for `http-interop/http-server-handler`
- Removed support for `http-interop/http-server-middleware`

### Fixed

- Nothing.

## 1.0.0 - November 23, 2017

### Added

- Added support for `http-interop/http-server-handler` 1.0.1
- Added support for `http-interop/http-server-middleware` 1.0.1

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Removed `WeCodeIn\Http\Server\Exception` namespace.

### Fixed

- Nothing.


## 0.5.0 - October 14, 2017

### Added

- Added support for `http-interop/http-middleware` 0.5.0
- Added `WeCodeIn\Http\Server\RequestHandler`

### Changed

- Renamed namespace to `WeCodeIn\Http\Server`

### Deprecated

- Nothing.

### Removed

- Removed `WeCodeIn\Http\ServerMiddleware\Dispatcher` class. Use `WeCodeIn\Http\Server\RequestHandler` instead.

### Fixed

- Nothing.