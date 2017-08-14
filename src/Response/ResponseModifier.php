<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Response;

use Oxidmod\RestBundle\Response\Modifier\ResponseModifierInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Root modifier for rendered response
 */
class ResponseModifier
{
    /**
     * @var ResponseModifierInterface[]
     */
    private $modifiers;

    /**
     * @param ResponseModifierInterface[] $modifiers
     */
    public function __construct(array $modifiers)
    {
        foreach ($modifiers as $modifier) {
            $this->addResponseModifier($modifier);
        }
    }

    private function addResponseModifier(ResponseModifierInterface $modifier)
    {
        $this->modifiers[] = $modifier;
    }

    /**
     * @param Response $response
     * @param Request $request
     *
     * @return Response
     */
    public function modifyResponse(Response $response, Request $request): Response
    {
        foreach ($this->modifiers as $modifier) {
            $response = $modifier->modify($response, $request);
        }

        return $response;
    }
}
