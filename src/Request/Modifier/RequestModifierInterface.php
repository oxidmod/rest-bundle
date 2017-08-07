<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Request\Modifier;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for request pre processors
 */
interface RequestModifierInterface
{
    /**
     * @param Request $request
     * @return Request
     */
    public function modify(Request $request): Request;
}
