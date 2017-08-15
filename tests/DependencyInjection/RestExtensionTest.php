<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Oxidmod\RestBundle\DependencyInjection\RestExtension;

/**
 * Test for RestExtension
 */
class RestExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new RestExtension(),
        ];
    }

    public function testExtensionLoaded()
    {
        $this->load();

        $this->assertContainerBuilderHasParameter('oxidmod_rest.json_api.base_url');
        $this->assertContainerBuilderHasParameter('oxidmod_rest.serializer');
        $this->assertContainerBuilderHasParameter('oxidmod_rest.response_content_type');
    }
}
