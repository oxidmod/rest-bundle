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
     * @param Manager $fractalManager
     * @param TransformerAbstract $transformer
     */
    public function __construct(
        Manager $fractalManager,
        TransformerAbstract $transformer
    ) {
        $this->fractalManager = $fractalManager;
        $this->transformer = $transformer;
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

        return new Response(
            $this->fractalManager->createData($resource)->toJson(),
            $httpCode
        );
    }

    /**
     * @param mixed $data
     * @param string|null $resourceKey
     *
     * @return ResourceInterface
     */
    private function createResource($data, string $resourceKey = null): ResourceInterface
    {
        if (null === $data) {
            return new NullResource();
        }

        if (is_array($data) || $data instanceof \Traversable) {
            return new Collection($data, $this->transformer, $resourceKey);
        }

        return new Item($data, $this->transformer, $resourceKey);
    }
}
