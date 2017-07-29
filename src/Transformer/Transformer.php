<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Transformer;

use League\Fractal\TransformerAbstract;
use Oxidmod\RestBundle\Transformer\Exception\UnsupportedObjectException;

/**
 * Transformer for fractal lib
 */
class Transformer extends TransformerAbstract
{
    /**
     * @var TransformerInterface[]
     */
    private $transformers = [];

    /**
     * @param TransformerInterface[] $transformers
     */
    public function __construct(array $transformers = [])
    {
        foreach ($transformers as $transformer) {
            $this->addTransformer($transformer);
        }
    }

    /**
     * @param TransformerInterface $transformer
     */
    private function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @param mixed $object
     *
     * @return array
     *
     * @throws UnsupportedObjectException
     */
    public function __invoke($object): array
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($object)) {
                return $transformer->transform($object);
            }
        }

        throw new UnsupportedObjectException($object);
    }
}
