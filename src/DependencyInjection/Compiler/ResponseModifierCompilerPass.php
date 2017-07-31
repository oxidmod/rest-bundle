<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add response modifiers to root modifier
 */
class ResponseModifierCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    const MODIFIER_SERVICE_ID = 'oxidmod_rest.response_modifier';

    /**
     * @var string
     */
    const MODIFIER_SERVICE_TAG = 'oxidmod_rest.response_modifier';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(static::MODIFIER_SERVICE_ID)) {
            return;
        }

        $rootModifierDefinition = $container->getDefinition(static::MODIFIER_SERVICE_ID);

        $taggedServices = $container->findTaggedServiceIds(static::MODIFIER_SERVICE_TAG);

        $modifiers = [];
        foreach ($taggedServices as $id => $tags) {
            $modifiers[] = new Reference($id);
        }

        $rootModifierDefinition->replaceArgument(1, $modifiers);
    }
}
