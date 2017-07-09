<?php

declare (strict_types = 1);

namespace Oxidmod\RestBundle;

use Oxidmod\RestBundle\DependencyInjection\RestExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Build container
 */
class RestBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new RestExtension();
    }

}
