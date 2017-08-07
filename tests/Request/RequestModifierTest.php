<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\Request;

use Oxidmod\RestBundle\Request\Modifier\RequestModifierInterface;
use Oxidmod\RestBundle\Request\RequestModifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test for RequestModifier
 */
class RequestModifierTest extends TestCase
{
    /**
     * @var RequestModifierInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customModifier;

    /**
     * @var RequestModifier
     */
    private $rootModifier;

    public function testModify()
    {
        $request = new Request();

        $this->customModifier->expects(static::once())
            ->method('modify')
            ->with($request)
            ->willReturnCallback(function (Request $request) {
                $request->request->replace(['test' => 'val']);

                return;
            });

        $this->rootModifier->modifyRequest($request);

        static::assertSame(['test' => 'val'], $request->request->all());
    }

    protected function setUp()
    {
        $this->customModifier = $this->createMock(RequestModifierInterface::class);

        $this->rootModifier = new RequestModifier([$this->customModifier]);

        parent::setUp();
    }
}
