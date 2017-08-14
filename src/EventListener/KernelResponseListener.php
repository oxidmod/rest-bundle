<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\EventListener;

use Oxidmod\RestBundle\Response\ResponseModifier;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Modify generated response
 */
class KernelResponseListener
{
    /**
     * @var ResponseModifier
     */
    private $responseModifier;

    /**
     * @param ResponseModifier $responseModifier
     */
    public function __construct(ResponseModifier $responseModifier)
    {
        $this->responseModifier = $responseModifier;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $this->responseModifier->modifyResponse($event->getResponse(), $event->getRequest());

        $event->setResponse($response);
    }
}
