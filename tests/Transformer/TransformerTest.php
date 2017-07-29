<?php

declare (strict_types = 1);

namespace Oxidmod\RestBundle\Tests\Transformer;

use League\Fractal\TransformerAbstract;;
use Oxidmod\RestBundle\Transformer\Transformer;
use Oxidmod\RestBundle\Transformer\TransformerInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test for TransformerTest
 */
class TransformerTest extends TestCase
{
    /**
     * @var Transformer
     */
    private $transformer;

    /**
     * @var TransformerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customTransformer;

    public function testInvokeTransformer()
    {
        $object = new \stdClass();
        $data = [];

        $this->customTransformer->expects(static::once())
            ->method('supports')
            ->with($object)
            ->willReturn(true);

        $this->customTransformer->expects(static::once())
            ->method('transform')
            ->with($object)
            ->willReturn($data);

        static::assertEquals($data, call_user_func($this->transformer, $object));
    }

    /**
     * @expectedException \Oxidmod\RestBundle\Transformer\Exception\UnsupportedObjectException
     */
    public function testExceptionIfObjectNotSupported()
    {
        $object = new \stdClass();

        $this->customTransformer->expects(static::once())
            ->method('supports')
            ->willReturn(false);

        call_user_func($this->transformer, $object);
    }

    protected function setUp()
    {
        $this->customTransformer = $this->createMock(TransformerInterface::class);

        $this->transformer = new Transformer([
            $this->customTransformer,
        ]);

        parent::setUp();
    }

    protected function assertPreConditions()
    {
        static::assertInstanceOf(TransformerAbstract::class, $this->transformer);

        parent::assertPreConditions();
    }
}
