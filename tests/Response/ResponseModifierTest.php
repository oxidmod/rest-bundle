<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\Response;

use Oxidmod\RestBundle\Response\Modifier\ResponseModifierInterface;
use Oxidmod\RestBundle\Response\ResponseModifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test for ResponseModifierTest
 */
class ResponseModifierTest extends TestCase
{
    /**
     * @var RequestStack|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestStack;

    /**
     * @var ResponseModifierInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customModifier;

    /**
     * @var ResponseModifier
     */
    private $rootModifier;

    public function testModify()
    {
        $request = new Request();
        $this->requestStack->expects(static::once())->method('getCurrentRequest')
            ->willReturn($request);

        $response = new Response();

        $modifiedResponse = clone $response;
        $modifiedResponse->headers->set('Content-Type', 'application/json');

        $this->customModifier->expects(static::once())
            ->method('modify')
            ->with($response, $request)
            ->willReturn($modifiedResponse);

        static::assertSame($modifiedResponse, $this->rootModifier->modifyResponse($response));
    }

    protected function setUp()
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->customModifier = $this->createMock(ResponseModifierInterface::class);

        $this->rootModifier = new ResponseModifier($this->requestStack, [$this->customModifier]);

        parent::setUp();
    }
}
