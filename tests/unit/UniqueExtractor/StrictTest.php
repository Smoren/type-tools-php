<?php

declare(strict_types=1);

namespace Smoren\TypeTools\Tests\Unit\UniqueExtractor;

use Codeception\Test\Unit;
use Smoren\TypeTools\Tests\Unit\Fixtures\SerializableFixture;
use Smoren\TypeTools\UniqueExtractor;

class StrictTest extends Unit
{
    /**
     * @dataProvider dataProviderForString
     * @param mixed $var
     * @param string $expected
     * @return void
     */
    public function testString($var, string $expected): void
    {
        // Given
        // When
        $result = UniqueExtractor::getString($var, true);

        // Then
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function dataProviderForString(): array
    {
        return [
            // null
            [null, 'NULL_'],

            // booleans
            [true, 'boolean_1'],
            [false, 'boolean_0'],

            // integers
            [0, 'integer_0'],
            [1, 'integer_1'],
            [22, 'integer_22'],
            [-22, 'integer_-22'],

            // floats
            [0.0, 'double_0'],
            [1.0, 'double_1'],
            [0.1, 'double_0.1'],
            [1.1, 'double_1.1'],
            [22.0, 'double_22'],
            [-22.0, 'double_-22'],
            [INF, 'double_INF'],
            [-INF, 'double_-INF'],
            [NAN, 'double_NAN'],

            // strings
            ['', 'string_'],
            ['0', 'string_0'],
            ['1', 'string_1'],
            ['00', 'string_00'],
            ['10', 'string_10'],
            ['01', 'string_01'],
            ['1a', 'string_1a'],
            ['abc', 'string_abc'],
            ['Abc', 'string_Abc'],

            // arrays
            [[], 'array_'.serialize([])],
            [[0], 'array_'.serialize([0])],
            [[1], 'array_'.serialize([1])],
            [[1, 2, 3], 'array_'.serialize([1, 2, 3])],
            [[1, 2, '3'], 'array_'.serialize([1, 2, '3'])],
            [['a', 'b', 'c'], 'array_'.serialize(['a', 'b', 'c'])],
            [['a' => [1, 2, 3], [4, 5, 6]], 'array_'.serialize(['a' => [1, 2, 3], [4, 5, 6]])],

            // stdClass objects
            [$obj = (object)[], 'object_'.spl_object_id($obj)],
            [$obj = (object)[1, 2, 3], 'object_'.spl_object_id($obj)],
            [$obj = (object)['a' => 1], 'object_'.spl_object_id($obj)],
            [$obj = (object)['a' => [1, 2, 3]], 'object_'.spl_object_id($obj)],

            // another objects
            [$obj = new SerializableFixture(1), 'object_'.spl_object_id($obj)],
            [$obj = new SerializableFixture(2), 'object_'.spl_object_id($obj)],

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
     * @dataProvider dataProviderForHash
     * @param mixed $var
     * @param string $expected
     * @return void
     */
    public function testHash($var, string $expected): void
    {
        // Given
        // When
        $result = UniqueExtractor::getHash($var, true);

        // Then
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function dataProviderForHash(): array
    {
        return [
            // null
            [null, md5('NULL_')],

            // booleans
            [true, md5('boolean_1')],
            [false, md5('boolean_0')],

            // integers
            [0, md5('integer_0')],
            [1, md5('integer_1')],
            [22, md5('integer_22')],
            [-22, md5('integer_-22')],

            // floats
            [0.0, md5('double_0')],
            [1.0, md5('double_1')],
            [0.1, md5('double_0.1')],
            [1.1, md5('double_1.1')],
            [22.0, md5('double_22')],
            [-22.0, md5('double_-22')],
            [INF, md5('double_INF')],
            [-INF, md5('double_-INF')],
            [NAN, md5('double_NAN')],

            // strings
            ['', md5('string_')],
            ['0', md5('string_0')],
            ['1', md5('string_1')],
            ['00', md5('string_00')],
            ['10', md5('string_10')],
            ['01', md5('string_01')],
            ['1a', md5('string_1a')],
            ['abc', md5('string_abc')],
            ['Abc', md5('string_Abc')],

            // arrays
            [[], md5('array_'.serialize([]))],
            [[0], md5('array_'.serialize([0]))],
            [[1], md5('array_'.serialize([1]))],
            [[1, 2, 3], md5('array_'.serialize([1, 2, 3]))],
            [[1, 2, '3'], md5('array_'.serialize([1, 2, '3']))],
            [['a', 'b', 'c'], md5('array_'.serialize(['a', 'b', 'c']))],
            [['a' => [1, 2, 3], [4, 5, 6]], md5('array_'.serialize(['a' => [1, 2, 3], [4, 5, 6]]))],

            // stdClass objects
            [$obj = (object)[], md5('object_'.spl_object_id($obj))],
            [$obj = (object)[1, 2, 3], md5('object_'.spl_object_id($obj))],
            [$obj = (object)['a' => 1], md5('object_'.spl_object_id($obj))],
            [$obj = (object)['a' => [1, 2, 3]], md5('object_'.spl_object_id($obj))],

            // another objects
            [$obj = new SerializableFixture(1), md5('object_'.spl_object_id($obj))],
            [$obj = new SerializableFixture(2), md5('object_'.spl_object_id($obj))],

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
     * @return string
     */
    protected function getResourceId($resource): string
    {
        return get_resource_type($resource).'_'.get_resource_id($resource);
    }
}
