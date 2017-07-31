<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Response\Modifier;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Add application/json header
 */
class ContentTypeModifier implements ResponseModifierInterface
{
    const HEADER_APPLICATION_JSON = 'application/json';
    const HEADER_APPLICATION_JSON_API = 'application/vnd.api+json';

    private $defaultContentType;

    /**
     * @param string $defaultContentType
     */
    public function __construct(string $defaultContentType = null)
    {
        $this->defaultContentType = $defaultContentType ?? self::HEADER_APPLICATION_JSON_API;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Response $response, Request $request = null): Response
    {
        if (null === $request) {
            return $response;
        }

        $header = $request->headers->get('Content-Type');

        $clone = clone $response;

        switch ($header) {
            case self::HEADER_APPLICATION_JSON_API:
            case self::HEADER_APPLICATION_JSON:
                $clone->headers->set('Content-Type', $header);
                break;
            default:
                $clone->headers->set('Content-Type', $this->defaultContentType);
                break;
        }

        return $clone;
    }
}
