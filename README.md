# PHP Type Tools

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/smoren/type-tools)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Smoren/type-tools-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Smoren/type-tools-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Smoren/type-tools-php/badge.svg?branch=master)](https://coveralls.io/github/Smoren/type-tools-php?branch=master)
![Build and test](https://github.com/Smoren/type-tools-php/actions/workflows/test_master.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Helpers for different operations with PHP data types, variables and containers.

## How to install to your project
```
composer require smoren/type-tools
```

## Quick Reference

### Unique Extractor
| Method                     | Description                                          | Code Snippet                                |
|----------------------------|------------------------------------------------------|---------------------------------------------|
| [`getString`](#Get-String) | Returns unique string of the given variable          | `UniqueExtractor::getString($var, $strict)` |
| [`getHash`](#Get-Hash)     | Returns unique md5 hash string of the given variable | `UniqueExtractor::getHash($var, $strict)`   |

### Object Type Caster
| Method          | Description                           | Code Snippet                                                |
|-----------------|---------------------------------------|-------------------------------------------------------------|
| [`cast`](#Cast) | Cast object to another relative type  | `ObjectTypeCaster::cast($sourceObject, $destinationClass)`  |

### Object Access
| Method                                          | Description                                                       | Code Snippet                                                     |
|-------------------------------------------------|-------------------------------------------------------------------|------------------------------------------------------------------|
| [`getPropertyValue`](#Get-Property-Value)       | Returns value of the object property                              | `ObjectAccess::getPropertyValue($object, $propertyName)`         |
| [`setPropertyValue`](#Set-Property-Value)       | Sets value of the object property                                 | `ObjectAccess::setPropertyValue($object, $propertyName, $value)` |
| [`hasReadableProperty`](#Has-Readable-Property) | Returns true if object has readable property by name or by getter | `ObjectAccess::hasReadableProperty($object, $propertyName)`      |
| [`hasWritableProperty`](#Has-Writable-Property) | Returns true if object has writable property by name or by getter | `ObjectAccess::hasWritableProperty($object, $propertyName)`      |
| [`hasPublicProperty`](#Has-Public-Property)     | Returns true if object has public property                        | `ObjectAccess::hasPublicProperty($object, $propertyName)`        |
| [`hasPublicMethod`](#Has-Public-Method)         | Returns true if object has public method                          | `ObjectAccess::hasPublicMethod($object, $methodName)`            |
| [`hasProperty`](#Has-Property)                  | Returns true if object has property                               | `ObjectAccess::hasProperty($object, $propertyName)`              |
| [`hasMethod`](#Has-Method)                      | Returns true if object has method                                 | `ObjectAccess::hasMethod($object, $methodName)`                  |

### Map Access
| Method              | Description                                            | Code Snippet                                      |
|---------------------|--------------------------------------------------------|---------------------------------------------------|
| [`get`](#Get)       | Returns value from the container by key                | `MapAccess::get($container, $key, $defaultValue)` |
| [`set`](#Set)       | Sets value to the container by key                     | `MapAccess::set($container, $key, $value)`        |
| [`exists`](#Exists) | Returns true if accessible key exists in the container | `MapAccess::exists($container, $key)`             |

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

#### Get String

Returns unique string of the given variable.

```UniqueExtractor::getString(mixed $var, bool $strict): string```

```php
use Smoren\TypeTools\UniqueExtractor;

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

#### Get Hash

Returns unique md5 hash string of the given variable.

```UniqueExtractor::getHash(mixed $var, bool $strict): string```

```php
use Smoren\TypeTools\UniqueExtractor;

$intValue = 5;
$floatValue = 5.0;

$intValueStrictHash = UniqueExtractor::getHash($intValue, true);
$floatValueStrictHash = UniqueExtractor::getHash($floatValue, true);

var_dump($intValueStrictHash === $floatValueStrictHash);
// false

$intValueNonStrictHash = UniqueExtractor::getHash($intValue, false);
$floatValueNonStrictHash = UniqueExtractor::getHash($floatValue, false);

var_dump($intValueNonStrictHash === $floatValueNonStrictHash);
// true
```

### Object Type Caster

Tool for casting types of objects.

#### Cast

Cast object to another relative type (upcast or downcast).

```ObjectTypeCaster::cast(object $sourceObject, string $destinationClass): mixed```

```php
use Smoren\TypeTools\ObjectTypeCaster;

class ParentClass
{
    public int $a;
    protected int $b;

    public function __construct(int $a, int $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function toArray(): array
    {
        return [$this->a, $this->b];
    }
}

class ChildClass extends ParentClass
{
    private $c = null;

    public function __construct(int $a, int $b, int $c)
    {
        parent::__construct($a, $b);
        $this->c = $c;
    }

    public function toArray(): array
    {
        return [$this->a, $this->b, $this->c];
    }
}

/* Downcast */

$parentClassObject = new ParentClass(1, 2);
print_r($parentClassObject->toArray());
// [1, 2]

$castedToChildClass = ObjectTypeCaster::cast($parentClassObject, ChildClass::class);
print_r($castedToChildClass->toArray());
// [1, 2, null]

var_dump(get_class($castedToChildClass));
// ChildClass

/* Upcast */

$childClassObject = new ChildClass(1, 2, 3);
print_r($childClassObject->toArray());
// [1, 2, 3]

$castedToParentClass = ObjectTypeCaster::cast($childClassObject, ParentClass::class);
print_r($castedToParentClass->toArray());
// [1, 2]

var_dump(get_class($castedToParentClass));
// ParentClass
```

### Object Access

Tool for reflecting and accessing object properties and methods.

#### Get Property Value

Returns value of the object property.

```ObjectAccess::getPropertyValue(object $object, string $propertyName): mixed```

Can access property by its name or by getter.

```php
use Smoren\TypeTools\ObjectAccess;

class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
    
    public function getPrivateProperty(): int
    {
        return $this->privateProperty;
    }
}

$myObject = new MyClass();

// Getting by name:
var_dump(ObjectAccess::getPropertyValue($myObject, 'publicProperty'));
// 1

// Getting by getter (getPrivateProperty()):
var_dump(ObjectAccess::getPropertyValue($myObject, 'privateProperty'));
// 2
```

#### Set Property Value

Sets value of the object property.

```ObjectAccess::setPropertyValue(object $object, string $propertyName, mixed $value): void```

Can access property by its name or by setter.

```php
use Smoren\TypeTools\ObjectAccess;

class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
    
    public function setPrivateProperty(int $value): void
    {
        $this->privateProperty = $value;
    }
    
    public function toArray(): array
    {
        return [$this->publicProperty, $this->privateProperty];
    }
}

$myObject = new MyClass();

// Setting by name:
ObjectAccess::setPropertyValue($myObject, 'publicProperty', 11);

// Setting by setter (setPrivateProperty()):
ObjectAccess::getPropertyValue($myObject, 'privateProperty', 22);

print_r($myObject->toArray());
// [11, 22]

```

#### Has Readable Property

Returns true if object has property that is readable by name or by getter.

```ObjectAccess::hasReadableProperty(object $object, string $propertyName): bool```

```php
use Smoren\TypeTools\ObjectAccess;

class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
    private int $notAccessibleProperty = 3;
    
    public function getPrivateProperty(): int
    {
        return $this->privateProperty;
    }
}

$myObject = new MyClass();

// Accessible by name:
var_dump(ObjectAccess::hasReadableProperty($myObject, 'publicProperty'));
// true

// Accessible by getter:
var_dump(ObjectAccess::hasReadableProperty($myObject, 'privateProperty'));
// true

// Not accessible:
var_dump(ObjectAccess::hasReadableProperty($myObject, 'notAccessibleProperty'));
// false
```

#### Has Writable Property

Returns true if object has property that is writable by name or by setter.

```ObjectAccess::hasWritableProperty(object $object, string $propertyName): bool```

```php
use Smoren\TypeTools\ObjectAccess;

class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
    private int $notAccessibleProperty = 3;
    
    public function setPrivateProperty(int $value): void
    {
        $this->privateProperty = $value;
    }
}

$myObject = new MyClass();

// Accessible by name:
var_dump(ObjectAccess::hasWritableProperty($myObject, 'publicProperty'));
// true

// Accessible by setter:
var_dump(ObjectAccess::hasWritableProperty($myObject, 'privateProperty'));
// true

// Not accessible:
var_dump(ObjectAccess::hasWritableProperty($myObject, 'notAccessibleProperty'));
// false
```

#### Has Public Property

Returns true if object has public property.

```ObjectAccess::hasPublicProperty(object $object, string $propertyName): bool```

```php
use Smoren\TypeTools\ObjectAccess;

class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
}

$myObject = new MyClass();

var_dump(ObjectAccess::hasPublicProperty($myObject, 'publicProperty'));
// true

var_dump(ObjectAccess::hasPublicProperty($myObject, 'privateProperty'));
// false
```

#### Has Public Method

Returns true if object has public method.

```ObjectAccess::hasPublicMethod(object $object, string $methodName): bool```

```php
use Smoren\TypeTools\ObjectAccess;

class MyClass {
    public function publicMethod(): int
    {
        return 1;
    }
    
    private function privateMethod(): int
    {
        return 2;
    }
}

$myObject = new MyClass();

var_dump(ObjectAccess::hasPublicMethod($myObject, 'publicMethod'));
// true

var_dump(ObjectAccess::hasPublicMethod($myObject, 'privateMethod'));
// false
```

#### Has Property

Returns true if object has property.

```ObjectAccess::hasProperty(object $object, string $propertyName): bool```

```php
use Smoren\TypeTools\ObjectAccess;

class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
}

$myObject = new MyClass();

var_dump(ObjectAccess::hasProperty($myObject, 'publicProperty'));
// true

var_dump(ObjectAccess::hasProperty($myObject, 'privateProperty'));
// true

var_dump(ObjectAccess::hasProperty($myObject, 'anotherProperty'));
// false
```

#### Has Method

Returns true if object has method.

```ObjectAccess::hasMethod(object $object, string $methodName): bool```

```php
use Smoren\TypeTools\ObjectAccess;

class MyClass {
    public function publicMethod(): int
    {
        return 1;
    }
    
    private function privateMethod(): int
    {
        return 2;
    }
}

$myObject = new MyClass();

var_dump(ObjectAccess::hasMethod($myObject, 'publicMethod'));
// true

var_dump(ObjectAccess::hasMethod($myObject, 'privateMethod'));
// true
```

### Map Access

Tool for map-like accessing of different containers by string keys.

Can access:
- properties of objects (by name or by getter);
- elements of arrays and ArrayAccess objects (by key).

#### Get

Returns value from the container by key or default value if key does not exist or not accessible.

```MapAccess::get(mixed $container, string $key, mixed $defaultValue = null): mixed```

```php
use Smoren\TypeTools\MapAccess;

$array = [
    'a' => 1,
];

var_dump(MapAccess::get($array, 'a', 0));
// 1

var_dump(MapAccess::get($array, 'b', 0));
// 0

var_dump(MapAccess::get($array, 'b'));
// null

class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
    private int $notAccessibleProperty = 3;
    
    public function getPrivateProperty(): int
    {
        return $this->privateProperty;
    }
}

$myObject = new MyClass();

// Accessible by name:
var_dump(MapAccess::get($myObject, 'publicProperty', 0));
// 1

// Accessible by getter:
var_dump(MapAccess::get($myObject, 'privateProperty'));
// 2

// Not accessible:
var_dump(MapAccess::get($myObject, 'notAccessibleProperty', -1));
// -1

var_dump(MapAccess::get($myObject, 'notAccessibleProperty'));
// null

// Nonexistent:
var_dump(MapAccess::get($myObject, 'nonexistentProperty', -1));
// -1

var_dump(MapAccess::get($myObject, 'nonexistentProperty'));
// null
```

#### Set

Sets value to the container by key.

```MapAccess::set(mixed $container, string $key, mixed $value): void```

```php
use Smoren\TypeTools\MapAccess;

$array = [
    'a' => 1,
];

MapAccess::set($array, 'a', 11);
MapAccess::set($array, 'b', 22);

print_r($array);
// ['a' => 11, 'b' => 22]

class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
    
    public function setPrivateProperty(int $value): void
    {
        $this->privateProperty = $value;
    }
    
    public function toArray(): array
    {
        return [$this->publicProperty, $this->privateProperty];
    }
}

$myObject = new MyClass();

// Accessible by name:
MapAccess::get($myObject, 'publicProperty', 11);

// Accessible by getter:
MapAccess::get($myObject, 'privateProperty', 22);

print_r($myObject->toArray());
// [11, 22]
```

#### Exists

Returns true if accessible key exists in the container.

```MapAccess::exists(mixed $container, string $key): bool```

```php
use Smoren\TypeTools\MapAccess;

$array = [
    'a' => 1,
];

var_dump(MapAccess::exists($array, 'a'));
// true

var_dump(MapAccess::exists($array, 'b'));
// false
class MyClass {
    public int $publicProperty = 1;
    private int $privateProperty = 2;
    private int $notAccessibleProperty = 3;
    
    public function getPrivateProperty(): int
    {
        return $this->privateProperty;
    }
}

$myObject = new MyClass();

// Accessible by name:
var_dump(MapAccess::exists($myObject, 'publicProperty'));
// true

// Accessible by getter:
var_dump(MapAccess::exists($myObject, 'privateProperty'));
// true

// Not accessible:
var_dump(MapAccess::get($myObject, 'notAccessibleProperty'));
// false

// Nonexistent:
var_dump(MapAccess::get($myObject, 'nonexistentProperty', -1));
// false
```

## Unit testing
```
composer install
composer test-init
composer test
```

## License

PHP Type Tools is licensed under the MIT License.
