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
    /**
     * @var string
     */
    private $responseContentType;

    /**
     * @param string $responseContentType
     */
    public function __construct(string $responseContentType)
    {
        $this->responseContentType = $responseContentType;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Response $response, Request $request): Response
    {
        $acceptableContentTypes = $request->getAcceptableContentTypes();

        foreach ($acceptableContentTypes as $contentType) {
            if ($contentType === $this->responseContentType) {
                $clone = clone $response;
                $clone->headers->set('Content-Type', $contentType);

                return $clone;
            }
        }

        return $response;
    }
}
