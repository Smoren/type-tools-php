<?php

declare(strict_types=1);

namespace Smoren\TypeTools;

use ArrayAccess;

/**
 * Tool for map-like accessing of different containers by string keys.
 *
 * Can access:
 *  - properties of objects (by name or by getter);
 *  - elements of arrays and ArrayAccess objects (by key).
 */
class MapAccess
{
    /**
     * Returns value from the container by key or default value if key does not exist or not accessible.
     *
     * @template T
     *
     * @param array<string, T>|ArrayAccess<string, T>|object|mixed $container
     * @param string $key
     * @param T|null $defaultValue
     *
     * @return T|null
     */
    public static function get($container, string $key, $defaultValue = null)
    {
        switch(true) {
            case is_array($container):
                return static::getFromArray($container, $key, $defaultValue);
            case $container instanceof ArrayAccess:
                return static::getFromArrayAccess($container, $key, $defaultValue);
            case is_object($container):
                return static::getFromObject($container, $key, $defaultValue);
        }

        return $defaultValue;
    }

    /**
     * Returns true if the accessible key exists in the container.
     *
     * @param array<string, mixed>|ArrayAccess<string, mixed>|object|mixed $container
     * @param string $key
     *
     * @return bool
     */
    public static function exists($container, string $key): bool
    {
        switch(true) {
            case is_array($container):
                return static::existsInArray($container, $key);
            case $container instanceof ArrayAccess:
                return static::existsInArrayAccess($container, $key);
            case is_object($container):
                return static::existsInObject($container, $key);
        }
        return false;
    }

    /**
     * Returns value from the array by key or default value if key does not exist.
     *
     * @template T
     *
     * @param array<string, T> $container
     * @param string $key
     * @param T|null $defaultValue
     *
     * @return T|null
     */
    protected static function getFromArray(array $container, string $key, $defaultValue)
    {
        if(static::existsInArray($container, $key)) {
            return $container[$key];
        }

        return $defaultValue ?? null;
    }

    /**
     * Returns true if the key exists in the array.
     *
     * @template T
     * @param array<string, T> $container
     * @param string $key
     *
     * @return bool
     */
    protected static function existsInArray(array $container, string $key): bool
    {
        return array_key_exists($key, $container);
    }

    /**
     * Returns value from the ArrayAccess object by key or default value if key does not exist.
     *
     * @template T
     *
     * @param ArrayAccess<string, T> $container
     * @param string $key
     * @param T|null $defaultValue
     *
     * @return T|null
     */
    protected static function getFromArrayAccess(ArrayAccess $container, string $key, $defaultValue)
    {
        if(static::existsInArrayAccess($container, $key)) {
            return $container[$key];
        }

        return $defaultValue ?? null;
    }

    /**
     * Returns true if the key exists in the ArrayAccess object.
     *
     * @template T
     *
     * @param ArrayAccess<string, T> $container
     * @param string $key
     *
     * @return bool
     */
    protected static function existsInArrayAccess(ArrayAccess $container, string $key): bool
    {
        return $container->offsetExists($key);
    }

    /**
     * Returns value from the object by key or default value if key does not exist.
     *
     * @param object $container
     * @param string $key
     * @param mixed|null $defaultValue
     *
     * @return mixed|null
     */
    protected static function getFromObject(object $container, string $key, $defaultValue)
    {
        if(ObjectAccess::hasAccessibleProperty($container, $key)) {
            return ObjectAccess::getPropertyValue($container, $key);
        }

        return $defaultValue;
    }

    /**
     * Returns true if the key exists in the object.
     *
     * @param object $container
     * @param string $key
     *
     * @return bool
     */
    protected static function existsInObject(object $container, string $key): bool
    {
        return ObjectAccess::hasPublicProperty($container, $key)
            || ObjectAccess::hasPropertyAccessibleByGetter($container, $key);
    }
}
