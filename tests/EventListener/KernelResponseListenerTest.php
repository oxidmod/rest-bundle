<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\EventListener;

use Oxidmod\RestBundle\EventListener\KernelResponseListener;
use Oxidmod\RestBundle\Response\ResponseModifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Test for KernelResponseListener
 */
class KernelResponseListenerTest extends TestCase
{
    /**
     * @var ResponseModifier|\PHPUnit_Framework_MockObject_MockObject
     */
    private $responseModifier;

    /**
     * @var KernelResponseListener
     */
    private $listener;

    public function testOnKernelRequest()
    {
        $request = new Request();
        $response = new Response();
        $modifiedResponse = clone $response;

        $event = new FilterResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $this->responseModifier->expects(static::once())
            ->method('modifyResponse')
            ->with($response, $request)
            ->willReturn($modifiedResponse);

        $this->listener->onKernelResponse($event);

        static::assertSame($modifiedResponse, $event->getResponse());
    }

    protected function setUp()
    {
        $this->responseModifier = $this->createMock(ResponseModifier::class);

        $this->listener = new KernelResponseListener($this->responseModifier);

        parent::setUp();
    }
}
