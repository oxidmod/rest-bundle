<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\DependencyInjection;

use Oxidmod\RestBundle\Transformer\TransformerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Load bundle configuration
 */
class RestExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('services.yml');

        if (method_exists($this, 'registerForAutoconfiguration')) {
            $container->registerForAutoconfiguration(TransformerInterface::class, 'oxidmod_rest.transformer');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'oxidmod_rest';
    }
}
