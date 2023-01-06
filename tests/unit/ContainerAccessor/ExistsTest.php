<?php

namespace Smoren\TypeTools\Tests\Unit\ContainerAccessor;

use Codeception\Test\Unit;
use Smoren\TypeTools\ContainerAccessor;
use Smoren\TypeTools\Tests\Unit\Fixtures\ClassWithAccessibleProperties;
use ArrayAccess;
use ArrayObject;
use stdClass;

class ExistsTest extends Unit
{
    /**
     * @param array $input
     * @param string $key
     * @param bool $expected
     * @return void
     * @dataProvider existsInArrayDataProvider
     */
    public function testExistsInArray(array $input, string $key, bool $expected): void
    {
        // When
        $result = ContainerAccessor::exists($input, $key);

        // Then
        $this->assertEquals($expected, $result);
    }

    public function existsInArrayDataProvider(): array
    {
        return [
            [[], '', false],
            [[], '0', false],
            [[], 'a', false],
            [[], 'b', false],
            [['a' => 1, 'b' => 2], '', false],
            [['a' => 1, 'b' => 2], '0', false],
            [['a' => 1, 'b' => 2], '1', false],
            [['a' => 1, 'b' => 2], '2', false],
            [['a' => 1, 'b' => 2], 'a', true],
            [['a' => 1, 'b' => 2], 'b', true],
        ];
    }

    /**
     * @param ArrayAccess $input
     * @param string $key
     * @param bool $expected
     * @return void
     * @dataProvider existsInArrayAccessDataProvider
     */
    public function testExistsInArrayAccess(ArrayAccess $input, string $key, bool $expected): void
    {
        // When
        $result = ContainerAccessor::exists($input, $key);

        // Then
        $this->assertEquals($expected, $result);
    }

    public function existsInArrayAccessDataProvider(): array
    {
        $wrap = static function(array $input): ArrayAccess {
            return new ArrayObject($input);
        };

        return [
            [$wrap([]), '', false],
            [$wrap([]), '0', false],
            [$wrap([]), 'a', false],
            [$wrap([]), 'b', false],
            [$wrap(['a' => 1, 'b' => 2]), '', false],
            [$wrap(['a' => 1, 'b' => 2]), '0', false],
            [$wrap(['a' => 1, 'b' => 2]), '1', false],
            [$wrap(['a' => 1, 'b' => 2]), '2', false],
            [$wrap(['a' => 1, 'b' => 2]), 'a', true],
            [$wrap(['a' => 1, 'b' => 2]), 'b', true],
        ];
    }

    /**
     * @param stdClass $input
     * @param string $key
     * @param bool $expected
     * @return void
     * @dataProvider existsInStdClassDataProvider
     */
    public function testExistsInStdClass(stdClass $input, string $key, bool $expected): void
    {
        // When
        $result = ContainerAccessor::exists($input, $key);

        // Then
        $this->assertEquals($expected, $result);
    }

    public function existsInStdClassDataProvider(): array
    {
        $wrap = static function(array $input): object {
            return (object)$input;
        };

        return [
            [$wrap([]), '', false],
            [$wrap([]), '0', false],
            [$wrap([]), 'a', false],
            [$wrap([]), 'b', false],
            [$wrap(['a' => 1, 'b' => 2]), '', false],
            [$wrap(['a' => 1, 'b' => 2]), '0', false],
            [$wrap(['a' => 1, 'b' => 2]), '1', false],
            [$wrap(['a' => 1, 'b' => 2]), '2', false],
            [$wrap(['a' => 1, 'b' => 2]), 'a', true],
            [$wrap(['a' => 1, 'b' => 2]), 'b', true],
        ];
    }

    /**
     * @param object $input
     * @param string $key
     * @param bool $expected
     * @return void
     * @dataProvider existsInObjectDataProvider
     */
    public function testExistsInObject(object $input, string $key, bool $expected): void
    {
        // When
        $result = ContainerAccessor::exists($input, $key);

        // Then
        $this->assertEquals($expected, $result);
    }

    public function existsInObjectDataProvider(): array
    {
        return [
            [new ClassWithAccessibleProperties(), '', false],
            [new ClassWithAccessibleProperties(), '0', false],
            [new ClassWithAccessibleProperties(), 'unknownProperty', false],
            [new ClassWithAccessibleProperties(), 'publicProperty', true],
            [new ClassWithAccessibleProperties(), 'publicPropertyWithGetterAccess', true],
            [new ClassWithAccessibleProperties(), 'protectedProperty', false],
            [new ClassWithAccessibleProperties(), 'protectedPropertyWithGetterAccess', true],
            [new ClassWithAccessibleProperties(), 'privateProperty', false],
            [new ClassWithAccessibleProperties(), 'privatePropertyWithGetterAccess', true],
        ];
    }

    /**
     * @param scalar $input
     * @param string $key
     * @return void
     * @dataProvider existsInScalarDataProvider
     */
    public function testExistsInScalar($input, string $key): void
    {
        // When
        $result = ContainerAccessor::exists($input, $key);

        // Then
        $this->assertFalse($result);
    }

    public function existsInScalarDataProvider(): array
    {
        return [
            ['', ''],
            ['', '0'],
            ['', '1'],
            ['', '2'],
            [0, ''],
            [0, '0'],
            [0, '1'],
            [0, '2'],
            [1, ''],
            [1, '0'],
            [1, '1'],
            [1, '2'],
            ['0', ''],
            ['0', '0'],
            ['0', '1'],
            ['0', '2'],
            ['1', ''],
            ['1', '0'],
            ['1', '1'],
            ['1', '2'],
            ['111', ''],
            ['111', '0'],
            ['111', '1'],
            ['111', '2'],
        ];
    }
}
