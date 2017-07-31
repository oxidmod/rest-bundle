<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Response;

use Oxidmod\RestBundle\Response\Modifier\ResponseModifierInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Root modifier for rendered response
 */
class ResponseModifier
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var ResponseModifierInterface[]
     */
    private $modifiers;

    /**
     * @param RequestStack $requestStack
     * @param ResponseModifierInterface[] $modifiers
     */
    public function __construct(RequestStack $requestStack, array $modifiers)
    {
        $this->requestStack = $requestStack;

        foreach ($modifiers as $modifier) {
            $this->addResponseModifier($modifier);
        }
    }


    /**
     * @param Response $response
     * @return Response
     */
    public function modifyResponse(Response $response): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        foreach ($this->modifiers as $modifier) {
            $response = $modifier->modify($response, $request);
        }

        return $response;
    }

    private function addResponseModifier(ResponseModifierInterface $modifier)
    {
        $this->modifiers[] = $modifier;
    }
}
