<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\EventListener;

use Oxidmod\RestBundle\EventListener\KernelRequestListener;
use Oxidmod\RestBundle\Request\RequestModifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Test for KernelRequestListener
 */
class KernelRequestListenerTest extends TestCase
{
    /**
     * @var RequestModifier|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestModifier;

    /**
     * @var KernelRequestListener
     */
    private $subscriber;

    public function testOnKernelRequest()
    {
        $request = new Request();

        $event = new GetResponseEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->requestModifier->expects(static::once())
            ->method('modifyRequest')
            ->with($request);

        $this->subscriber->onKernelRequest($event);
    }

    protected function setUp()
    {
        $this->requestModifier = $this->createMock(RequestModifier::class);

        $this->subscriber = new KernelRequestListener($this->requestModifier);

        parent::setUp();
    }
}
