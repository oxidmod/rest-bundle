<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle;

use Oxidmod\RestBundle\DependencyInjection\Compiler\RequestModifierCompilerPass;
use Oxidmod\RestBundle\DependencyInjection\Compiler\ResponseModifierCompilerPass;
use Oxidmod\RestBundle\DependencyInjection\Compiler\TransformerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Rest bundle
 */
class RestBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TransformerCompilerPass());
        $container->addCompilerPass(new RequestModifierCompilerPass());
        $container->addCompilerPass(new ResponseModifierCompilerPass());
    }
}
