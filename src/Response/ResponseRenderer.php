<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Response;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\TransformerAbstract;
use Oxidmod\RestBundle\Transformer\Exception\UnsupportedObjectException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Response renderer
 */
class ResponseRenderer
{
    /**
     * @var Manager
     */
    private $fractalManager;

    /**
     * @var TransformerAbstract
     */
    private $transformer;

    /**
     * @var ResponseModifier
     */
    private $responseModifier;

    /**
     * @param Manager $fractalManager
     * @param TransformerAbstract $transformer
     * @param ResponseModifier $responseModifier
     */
    public function __construct(
        Manager $fractalManager,
        TransformerAbstract $transformer,
        ResponseModifier $responseModifier
    ) {
        $this->fractalManager = $fractalManager;
        $this->transformer = $transformer;
        $this->responseModifier = $responseModifier;
    }

    /**
     * @param mixed $data
     * @param int $httpCode
     * @param string|null $resourceKey
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     * @throws UnsupportedObjectException
     */
    public function renderResponse($data, int $httpCode, string $resourceKey = null): Response
    {
        $resource = $this->createResource($data, $resourceKey);

        $response = new Response(
            $this->fractalManager->createData($resource)->toJson(),
            $httpCode
        );

        return $this->responseModifier->modifyResponse($response);
    }

    /**
     * @param mixed $data
     * @param string|null $resourceKey
     *
     * @return ResourceInterface
     */
    private function createResource($data, string $resourceKey = null): ResourceInterface
    {
        switch (true) {
            case $data === null:
                return new NullResource();
            case is_array($data):
            case $data instanceof \Traversable:
                return new Collection($data, $this->transformer, $resourceKey);
            default:
                return new Item($data, $this->transformer, $resourceKey);
        }
    }
}
