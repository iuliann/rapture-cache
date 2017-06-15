# Rapture PHP Cache Component

[![PhpVersion](https://img.shields.io/badge/php-5.4-orange.svg?style=flat-square)](#)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](#)

Basic cache component for PHP.

## Requirements

- PHP v5.4
- php-apcu, php-date, php-memcached, php-pcre

## Install

```
composer require iuliann/rapture-cache
```

## Quick start

```php
$cache = new Apcu(['namespace' =>  'app']);
$cache->set('user', 'JohnDoe', CacheInterface::ONE_HOUR);
$cache->get('user');
```

## About

### Author

Iulian N. `rapture@iuliann.ro`

### Testing

```
cd ./test && phpunit
```

### License

Rapture PHP Cache is licensed under the MIT License - see the `LICENSE` file for details.
