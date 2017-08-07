<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\EventListener;

use Oxidmod\RestBundle\Request\RequestModifier;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Listen to kernel request event
 */
class KernelRequestListener
{
    /**
     * @var RequestModifier
     */
    private $requestModifier;

    /**
     * @param RequestModifier $requestModifier
     */
    public function __construct(RequestModifier $requestModifier)
    {
        $this->requestModifier = $requestModifier;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->requestModifier->modifyRequest($event->getRequest());
    }
}
