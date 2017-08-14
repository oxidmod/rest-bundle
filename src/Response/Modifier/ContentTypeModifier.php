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
    private $contentType;

    /**
     * @param string $contentType
     */
    public function __construct(string $contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Response $response, Request $request): Response
    {
        $acceptableTypes = $request->getAcceptableContentTypes();

        foreach ($acceptableTypes as $acceptableType) {
            if ($acceptableType === $this->contentType) {
                $clone = clone $response;
                $clone->headers->set('Content-Type', $acceptableType);

                return $clone;
            }
        }

        return $response;
    }
}
