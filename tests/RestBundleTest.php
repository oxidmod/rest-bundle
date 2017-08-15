<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests;

use Oxidmod\RestBundle\DependencyInjection\Compiler\RequestModifierCompilerPass;
use Oxidmod\RestBundle\DependencyInjection\Compiler\ResponseModifierCompilerPass;
use Oxidmod\RestBundle\DependencyInjection\Compiler\TransformerCompilerPass;
use Oxidmod\RestBundle\RestBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Test for RestBundle
 */
class RestBundleTest extends TestCase
{
    /**
     * @var RestBundle
     */
    public $bundle;

    public function testBuild()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects(static::any())
            ->method('addCompilerPass')
            ->with(static::isInstanceOf(CompilerPassInterface::class))
            ->willReturnCallback(function (CompilerPassInterface $compilerPass) {
                switch (true) {
                    case $compilerPass instanceof TransformerCompilerPass:
                    case $compilerPass instanceof RequestModifierCompilerPass:
                    case $compilerPass instanceof ResponseModifierCompilerPass:
                        return;
                    default:
                        $this->fail('Unexpected compiler pass.');
                }
            });
    }

    protected function setUp()
    {
        $this->bundle = new RestBundle();

        parent::setUp();
    }

    protected function assertPreConditions()
    {
        static::assertInstanceOf(Bundle::class, $this->bundle);

        parent::assertPreConditions();
    }
}
