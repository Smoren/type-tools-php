<?php

declare(strict_types=1);

namespace Smoren\TypeTools\Tests\Unit\UniqueExtractor;

use Codeception\Test\Unit;
use Smoren\TypeTools\Tests\Unit\Fixtures\SerializableFixture;
use Smoren\TypeTools\UniqueExtractor;

class NonStrictTest extends Unit
{
    /**
     * @dataProvider dataProviderForStringNonStrict
     * @param mixed $var
     * @param string $expected
     * @return void
     */
    public function testStringNonStrict($var, string $expected): void
    {
        // Given
        // When
        $result = UniqueExtractor::getString($var, false);

        // Then
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function dataProviderForStringNonStrict(): array
    {
        return [
            // null
            [null, 'boolean_0'],

            // booleans
            [true, 'boolean_1'],
            [false, 'boolean_0'],

            // integers
            [0, 'boolean_0'],
            [1, 'boolean_1'],
            [22, 'numeric_22'],
            [-22, 'numeric_-22'],

            // floats
            [0.0, 'boolean_0'],
            [1.0, 'boolean_1'],
            [0.1, 'numeric_0.1'],
            [1.1, 'numeric_1.1'],
            [22.0, 'numeric_22'],
            [-22.0, 'numeric_-22'],
            [INF, 'numeric_INF'],
            [-INF, 'numeric_-INF'],
            [NAN, 'numeric_NAN'],

            // strings
            ['', 'boolean_0'],
            ['0', 'boolean_0'],
            ['1', 'boolean_1'],
            ['00', 'numeric_0'],
            ['10', 'numeric_10'],
            ['01', 'numeric_1'],
            ['1a', 'scalar_1a'],
            ['abc', 'scalar_abc'],
            ['Abc', 'scalar_Abc'],

            // arrays
            [[], 'array_'.md5(serialize([]))],
            [[0], 'array_'.md5(serialize([0]))],
            [[1], 'array_'.md5(serialize([1]))],
            [[1, 2, 3], 'array_'.md5(serialize([1, 2, 3]))],
            [[1, 2, '3'], 'array_'.md5(serialize([1, 2, '3']))],
            [['a', 'b', 'c'], 'array_'.md5(serialize(['a', 'b', 'c']))],
            [['a' => [1, 2, 3], [4, 5, 6]], 'array_'.md5(serialize(['a' => [1, 2, 3], [4, 5, 6]]))],

            // stdClass objects
            [(object)[], 'object_'.md5(serialize((object)[]))],
            [(object)[1, 2, 3], 'object_'.md5(serialize((object)[1, 2, 3]))],
            [(object)['a' => 1], 'object_'.md5(serialize((object)['a' => 1]))],
            [(object)['a' => [1, 2, 3]], 'object_'.md5(serialize((object)['a' => [1, 2, 3]]))],

            // another objects
            [new SerializableFixture(1), 'object_'.md5(serialize(new SerializableFixture(1)))],
            [new SerializableFixture(2), 'object_'.md5(serialize(new SerializableFixture(2)))],

            // closures
            [$func = function() {}, 'closure_'.spl_object_id($func)],
            [$func = static function() {}, 'closure_'.spl_object_id($func)],
            [$func = function(int $a) {return $a;}, 'closure_'.spl_object_id($func)],
            [$func = static function(int $a) {return $a;}, 'closure_'.spl_object_id($func)],

            // resources
            [$res = fopen('php://input', 'r'), 'resource_'.$this->getResourceId($res)],

            // generators
            [$gen = (function() {yield 1;})(), 'generator_'.spl_object_id($gen)],
        ];
    }

    /**
     * @dataProvider dataProviderForHashNonStrict
     * @param mixed $var
     * @param string $expected
     * @return void
     */
    public function testHashNonStrict($var, string $expected): void
    {
        // Given
        // When
        $result = UniqueExtractor::getHash($var, false);

        // Then
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function dataProviderForHashNonStrict(): array
    {
        return [
            // null
            [null, md5('boolean_0')],

            // booleans
            [true, md5('boolean_1')],
            [false, md5('boolean_0')],

            // integers
            [0, md5('boolean_0')],
            [1, md5('boolean_1')],
            [22, md5('numeric_22')],
            [-22, md5('numeric_-22')],

            // floats
            [0.0, md5('boolean_0')],
            [1.0, md5('boolean_1')],
            [0.1, md5('numeric_0.1')],
            [1.1, md5('numeric_1.1')],
            [22.0, md5('numeric_22')],
            [-22.0, md5('numeric_-22')],
            [INF, md5('numeric_INF')],
            [-INF, md5('numeric_-INF')],
            [NAN, md5('numeric_NAN')],

            // strings
            ['', md5('boolean_0')],
            ['0', md5('boolean_0')],
            ['1', md5('boolean_1')],
            ['00', md5('numeric_0')],
            ['10', md5('numeric_10')],
            ['01', md5('numeric_1')],
            ['1a', md5('scalar_1a')],
            ['abc', md5('scalar_abc')],
            ['Abc', md5('scalar_Abc')],

            // arrays
            [[], md5('array_'.md5(serialize([])))],
            [[0], md5('array_'.md5(serialize([0])))],
            [[1], md5('array_'.md5(serialize([1])))],
            [[1, 2, 3], md5('array_'.md5(serialize([1, 2, 3])))],
            [[1, 2, '3'], md5('array_'.md5(serialize([1, 2, '3'])))],
            [['a', 'b', 'c'], md5('array_'.md5(serialize(['a', 'b', 'c'])))],
            [['a' => [1, 2, 3], [4, 5, 6]], md5('array_'.md5(serialize(['a' => [1, 2, 3], [4, 5, 6]])))],

            // stdClass objects
            [(object)[], md5('object_'.md5(serialize((object)[])))],
            [(object)[1, 2, 3], md5('object_'.md5(serialize((object)[1, 2, 3])))],
            [(object)['a' => 1], md5('object_'.md5(serialize((object)['a' => 1])))],
            [(object)['a' => [1, 2, 3]], md5('object_'.md5(serialize((object)['a' => [1, 2, 3]])))],

            // another objects
            [new SerializableFixture(1), md5('object_'.md5(serialize(new SerializableFixture(1))))],
            [new SerializableFixture(2), md5('object_'.md5(serialize(new SerializableFixture(2))))],

            // closures
            [$func = function() {}, md5('closure_'.spl_object_id($func))],
            [$func = static function() {}, md5('closure_'.spl_object_id($func))],
            [$func = function(int $a) {return $a;}, md5('closure_'.spl_object_id($func))],
            [$func = static function(int $a) {return $a;}, md5('closure_'.spl_object_id($func))],

            // resources
            [$res = fopen('php://input', 'r'), md5('resource_'.$this->getResourceId($res))],

            // generators
            [$gen = (function() {yield 1;})(), md5('generator_'.spl_object_id($gen))],
        ];
    }

    /**
     * @param resource $resource
     * @return int
     */
    protected function getResourceId($resource): int
    {
        preg_match('/#([0-9]+)$/', (string)$resource, $matches);
        return (int)$matches[1];
    }
}
