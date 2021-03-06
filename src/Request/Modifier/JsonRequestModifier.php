<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Request\Modifier;

use Symfony\Component\HttpFoundation\Request;

/**
 * Decode json-request body
 */
class JsonRequestModifier implements RequestModifierInterface
{
    /**
     * @var array
     */
    private $supportedTypes;

    /**
     * @param array $supportedTypes
     */
    public function __construct(
        array $supportedTypes = [
            'json',
            'application/vnd.api+json',
        ]
    ) {
        $this->supportedTypes = $supportedTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function modify(Request $request)
    {
        if (!$this->supports($request)) {
            return;
        }

        $decodedRequestBody = json_decode($request->getContent(), true);

        if (!is_array($decodedRequestBody) || json_last_error() !== JSON_ERROR_NONE) {
            return;
        }

        $request->request->replace($decodedRequestBody);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function supports(Request $request): bool
    {
        $contentType = $request->headers->get('Content-Type');

        foreach ($this->supportedTypes as $supportedType) {
            if ($supportedType === $contentType || $supportedType === $request->getContentType()) {
                return true;
            }
        }

        return false;
    }
}
