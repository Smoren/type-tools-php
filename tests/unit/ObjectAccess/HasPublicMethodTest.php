<?php

namespace Smoren\TypeTools\Tests\Unit\ObjectAccess;

use Codeception\Test\Unit;
use Smoren\TypeTools\ObjectAccess;
use Smoren\TypeTools\Tests\Unit\Fixtures\ClassWithAccessibleProperties;
use stdClass;

class HasPublicMethodTest extends Unit
{
    /**
     * @param object $input
     * @param string $key
     * @return void
     * @dataProvider fromObjectTrueDataProvider
     */
    public function testFromObjectTrue(object $input, string $key): void
    {
        // When
        $result = ObjectAccess::hasPublicMethod($input, $key);

        // Then
        $this->assertTrue($result);
    }

    public function fromObjectTrueDataProvider(): array
    {
        return [
            [new ClassWithAccessibleProperties(), 'getPublicPropertyWithGetterAccess'],
            [new ClassWithAccessibleProperties(), 'setPublicPropertyWithGetterAccess'],
            [new ClassWithAccessibleProperties(), 'getProtectedPropertyWithGetterAccess'],
            [new ClassWithAccessibleProperties(), 'setProtectedPropertyWithGetterAccess'],
            [new ClassWithAccessibleProperties(), 'getPrivatePropertyWithGetterAccess'],
            [new ClassWithAccessibleProperties(), 'setPrivatePropertyWithGetterAccess'],
        ];
    }

    /**
     * @param object $input
     * @param string $key
     * @return void
     * @dataProvider fromObjectFalseDataProvider
     */
    public function testFromObjectFalse(object $input, string $key): void
    {
        // When
        $result = ObjectAccess::hasPublicMethod($input, $key);

        // Then
        $this->assertFalse($result);
    }

    public function fromObjectFalseDataProvider(): array
    {
        return [
            [new ClassWithAccessibleProperties(), 'unknownMethod'],
            [new ClassWithAccessibleProperties(), 'protectedMethod'],
            [new ClassWithAccessibleProperties(), 'privateMethod'],
        ];
    }
}
