<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Request;

use Oxidmod\RestBundle\Request\Modifier\RequestModifierInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Root modifier for request
 */
class RequestModifier
{
    /**
     * @var RequestModifierInterface[]
     */
    private $modifiers;

    /**
     * @param RequestModifierInterface[] $modifiers
     */
    public function __construct(array $modifiers)
    {
        foreach ($modifiers as $modifier) {
            $this->addResponseModifier($modifier);
        }
    }

    /**
     * @param Request $request
     *
     * @return void
     */
    public function modifyRequest(Request $request)
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->modify($request);
        }
    }

    /**
     * @param RequestModifierInterface $modifier
     */
    private function addResponseModifier(RequestModifierInterface $modifier)
    {
        $this->modifiers[] = $modifier;
    }
}
