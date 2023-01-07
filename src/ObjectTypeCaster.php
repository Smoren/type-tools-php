<?php

namespace Smoren\TypeTools;

use TypeError;

/**
 * Tool for casting types of objects.
 */
class ObjectTypeCaster
{
    /**
     * Cast object to another relative type (upcast or downcast).
     *
     * @param object $sourceObject
     * @param class-string $destinationClass
     *
     * @return mixed
     */
    public static function cast(object $sourceObject, string $destinationClass)
    {
        if(!class_exists($destinationClass)) {
            throw new TypeError("Class '{$destinationClass}' does not exist");
        }

        $sourceClass = get_class($sourceObject);

        // Unfortunately PHPstan has a problem with function is_subclass_of(). So:
        // @phpstan-ignore-next-line
        if(!is_subclass_of($sourceClass, $destinationClass) && !is_subclass_of($destinationClass, $sourceClass)) {
            throw new TypeError("Classes '{$sourceClass}' and '{$destinationClass}' must be relatives");
        }

        /** @var string $serialized */
        $serialized = strstr(serialize($sourceObject), '"');

        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($destinationClass),
            $destinationClass,
            strstr($serialized, ':')
        ));
    }
}
