# Type tools

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/smoren/type-tools)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Smoren/type-tools-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Smoren/type-tools-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Smoren/type-tools-php/badge.svg?branch=master)](https://coveralls.io/github/Smoren/type-tools-php?branch=master)
![Build and test](https://github.com/Smoren/type-tools-php/actions/workflows/test_master.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Helpers for different operations with PHP data types.

## How to install to your project
```
composer require smoren/type-tools
```

## Unit testing
```
composer install
composer test-init
composer test
```

## Usage

### Unique Extractor

Tool for extracting unique IDs and hashes of any PHP variables and data structures.

Works in two modes: strict and non-strict.

In strict mode:
- scalars: unique strictly by type;
- objects: unique by instance;
- arrays: unique by serialized value;
- resources: result is unique by instance.

In non-strict mode:
- scalars: unique by value;
- objects: unique by serialized value;
- arrays: unique by serialized value;
- resources: result is unique by instance.

#### UniqueExtractor

Helper for extracting unique string IDs and hashs for any data in PHP.

##### getString

```UniqueExtractor::getString(mixed $var, bool $strict): string```

```php
use function Smoren\Unique\UniqueExtractor;

$intValue = 5;
$floatValue = 5.0;

$intValueStrictUniqueId = UniqueExtractor::getString($intValue, true);
$floatValueStrictUniqueId = UniqueExtractor::getString($floatValue, true);

var_dump($intValueStrictUniqueId === $floatValueStrictUniqueId);
// false

$intValueNonStrictUniqueId = UniqueExtractor::getString($intValue, false);
$floatValueNonStrictUniqueId = UniqueExtractor::getString($floatValue, false);

var_dump($intValueNonStrictUniqueId === $floatValueNonStrictUniqueId);
// true
```
