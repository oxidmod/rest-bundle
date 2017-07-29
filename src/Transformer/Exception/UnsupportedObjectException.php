<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Transformer\Exception;

/**
 * Exception thrown if try to transform unsupported object
 */
class UnsupportedObjectException extends \LogicException
{
    /**
     * @param mixed $object
     */
    public function __construct($object)
    {
        parent::__construct(
            sprintf(
                'Transformer for "%s" not found.',
                is_object($object) ? get_class($object) : gettype($object)
            )
        );
    }
}
