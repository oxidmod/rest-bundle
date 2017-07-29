<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Transformer;

use Oxidmod\RestBundle\Transformer\Exception\UnsupportedObjectException;

/**
 * All transformers should implement this interface
 */
interface TransformerInterface
{
    /**
     * @param mixed $object
     *
     * @return array
     *
     * @throws UnsupportedObjectException
     */
    public function transform($object): array;

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function supports($object): bool;
}
