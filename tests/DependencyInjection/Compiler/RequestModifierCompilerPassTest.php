<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\DependencyInjection\Compiler;

use Oxidmod\RestBundle\DependencyInjection\Compiler\RequestModifierCompilerPass;
use Oxidmod\RestBundle\Request\Modifier\RequestModifierInterface;
use Oxidmod\RestBundle\Request\RequestModifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Test for RequestModifierCompilerPass
 */
class RequestModifierCompilerPassTest extends TestCase
{
    /**
     * @var RequestModifierCompilerPass
     */
    private $compiler;

    public function testProcessIfTransformerNotFound()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects(static::once())
            ->method('hasDefinition')
            ->with(RequestModifierCompilerPass::MODIFIER_SERVICE_ID)
            ->willReturn(false);

        $this->compiler->process($container);
    }

    public function testProcess()
    {
        $container = new ContainerBuilder();

        $rootModifier = new Definition(RequestModifier::class);
        $rootModifier->addArgument([]);

        $container->setDefinition(RequestModifierCompilerPass::MODIFIER_SERVICE_ID, $rootModifier);

        $modifierId = 'test_modifier';
        $modifierDefinition = new Definition(RequestModifierInterface::class);
        $modifierDefinition->addTag(RequestModifierCompilerPass::MODIFIER_SERVICE_TAG);
        $container->setDefinition($modifierId, $modifierDefinition);

        static::assertEquals([], $rootModifier->getArgument(0));

        $this->compiler->process($container);

        static::assertEquals([
            new Reference($modifierId),
        ], $rootModifier->getArgument(0));
    }

    protected function setUp()
    {
        $this->compiler = new RequestModifierCompilerPass();

        parent::setUp();
    }

    protected function assertPreConditions()
    {
        static::assertInstanceOf(CompilerPassInterface::class, $this->compiler);

        parent::assertPreConditions();
    }
}
