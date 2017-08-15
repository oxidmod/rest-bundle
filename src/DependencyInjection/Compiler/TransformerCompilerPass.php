<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Collect all custom transformers
 */
class TransformerCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    const TRANSFORMER_SERVICE_ID = 'oxidmod_rest.transformer';

    /**
     * @var string
     */
    const TRANSFORMER_SERVICE_TAG = 'oxidmod_rest.transformer';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(static::TRANSFORMER_SERVICE_ID)) {
            return;
        }

        $definition = $container->getDefinition(static::TRANSFORMER_SERVICE_ID);

        $transformers = array_keys($container->findTaggedServiceIds(static::TRANSFORMER_SERVICE_TAG));

        $transformersSet = [];
        foreach ($transformers as $transformer) {
            $transformersSet[] = new Reference($transformer);
        }

        $definition->replaceArgument(0, $transformersSet);
    }
}
