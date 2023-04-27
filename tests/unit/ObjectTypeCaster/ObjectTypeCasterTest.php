<?php

declare(strict_types=1);

namespace Smoren\TypeTools\Tests\Unit\ObjectTypeCaster;

use Codeception\Test\Unit;
use Smoren\TypeTools\ObjectTypeCaster;
use Smoren\TypeTools\Tests\Unit\Fixtures\ChildClass;
use Smoren\TypeTools\Tests\Unit\Fixtures\IndependentClass;
use Smoren\TypeTools\Tests\Unit\Fixtures\ParentClass;

class ObjectTypeCasterTest extends Unit
{
    /**
     * @dataProvider dataProviderForSuccess
     * @param object $sourceObject
     * @param class-string $destinationClass
     * @param array $expected
     * @return void
     */
    public function testSuccess(object $sourceObject, string $destinationClass, array $expected): void
    {
        // When
        /** @var ParentClass $result */
        $result = ObjectTypeCaster::cast($sourceObject, $destinationClass);

        // Then
        $this->assertEquals($expected, $result->toArray());
        $this->assertEquals($destinationClass, get_class($result));
    }

    /**
     * @return array
     */
    public function dataProviderForSuccess(): array
    {
        return [
            [
                new ChildClass(1, 2, 3),
                ParentClass::class,
                [1, 2],
            ],
            [
                new ParentClass(1, 2),
                ChildClass::class,
                [1, 2, null],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFailNotRelatives
     * @param object $sourceObject
     * @param class-string $destinationClass
     * @return void
     */
    public function testFailNotRelatives(object $sourceObject, string $destinationClass): void
    {
        try {
            // When
            ObjectTypeCaster::cast($sourceObject, $destinationClass);
            $this->fail();
        } catch(\TypeError $e) {
            // Then
            $this->assertSame(
                "Classes '".get_class($sourceObject)."' and '{$destinationClass}' must be relatives",
                $e->getMessage()
            );
        }
    }

    /**
     * @return array
     */
    public function dataProviderForFailNotRelatives(): array
    {
        return [
            [
                new ParentClass(1, 2),
                IndependentClass::class,
            ],
            [
                new ChildClass(1, 2, 3),
                IndependentClass::class,
            ],
            [
                new IndependentClass(1, 2, 3),
                ParentClass::class,
            ],
            [
                new IndependentClass(1, 2, 3),
                ChildClass::class,
            ],
            [
                new ParentClass(1, 2),
                ParentClass::class,
            ],
            [
                new ChildClass(1, 2, 3),
                ChildClass::class,
            ],
            [
                new IndependentClass(1, 2, 3),
                IndependentClass::class,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFailNotExist
     * @param object $sourceObject
     * @param class-string $destinationClass
     * @return void
     */
    public function testFailNotExist(object $sourceObject, string $destinationClass): void
    {
        try {
            // When
            ObjectTypeCaster::cast($sourceObject, $destinationClass);
            $this->fail();
        } catch(\TypeError $e) {
            // Then
            $this->assertSame("Class '{$destinationClass}' does not exist", $e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function dataProviderForFailNotExist(): array
    {
        return [
            [
                new ParentClass(1, 2),
                'ThisClassDoesNotExist',
            ],
            [
                new ParentClass(1, 2),
                '',
            ],
            [
                new ParentClass(1, 2),
                '0',
            ],
            [
                new ParentClass(1, 2),
                '42',
            ],
        ];
    }
}
