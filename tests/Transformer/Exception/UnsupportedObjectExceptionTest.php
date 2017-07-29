<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\Transformer\Exception;

use Oxidmod\RestBundle\Transformer\Exception\UnsupportedObjectException;
use PHPUnit\Framework\TestCase;

/**
 * Test for UnsupportedObjectExceptionTest
 */
class UnsupportedObjectExceptionTest extends TestCase
{
    /**
     * @param $object
     * @param string $expectedMessage
     *
     * @dataProvider providerForCreateException
     */
    public function testCreateException($object, string $expectedMessage)
    {
        $exception = new UnsupportedObjectException($object);

        static::assertInstanceOf(\Throwable::class, $exception);
        static::assertEquals($expectedMessage, $exception->getMessage());
    }

    public function providerForCreateException(): array
    {
        return [
            [false, 'Transformer for "boolean" not found.'],
            [true, 'Transformer for "boolean" not found.'],
            [null, 'Transformer for "NULL" not found.'],
            [[], 'Transformer for "array" not found.'],
            [new \stdClass(), 'Transformer for "stdClass" not found.'],
            ['test string', 'Transformer for "string" not found.'],
            [123, 'Transformer for "integer" not found.'],
            [12.3, 'Transformer for "double" not found.'],
            [function () {}, 'Transformer for "Closure" not found.'],
            [new \Exception(), 'Transformer for "Exception" not found.'],
        ];
    }
}
