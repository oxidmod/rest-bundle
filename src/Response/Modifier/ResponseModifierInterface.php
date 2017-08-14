<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Response\Modifier;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface for response post processors
 */
interface ResponseModifierInterface
{
    /**
     * @param Response $response
     * @param Request $request
     *
     * @return Response
     */
    public function modify(Response $response, Request $request): Response;
}
