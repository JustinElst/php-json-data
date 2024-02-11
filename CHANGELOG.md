# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.6.1] - 2024-02-11
### Added
- Added PHP 8.3 support.
### Removed
- Removed PHP 8.0 support.

## [0.6.0] - 2023-06-16
### Changed
- JSON source structure may contain arbitrary objects, not only `stdClass`.
### Removed
- Dropped PHP 7 support.

## [0.5.3] - 2021-01-14
### Added
- PHP 8.0 support.

## [0.5.2] - 2019-11-13
### Added
- JSON comparators implemented (requires [ext-intl](https://www.php.net/manual/en/book.intl.php)).

## [0.5.1] - 2019-11-04
### Removed
- Unnecessary exception trait removed.

### Fixed
- Some exceptions fixed: moved to correct namespace, missing interface added.

## [0.5.0] - 2019-11-01
### Added
- Implementation totally refactored.
