<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\DependencyInjection\Compiler;

use Oxidmod\RestBundle\DependencyInjection\Compiler\ResponseModifierCompilerPass;
use Oxidmod\RestBundle\Response\Modifier\ResponseModifierInterface;
use Oxidmod\RestBundle\Response\ResponseModifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Test for ResponseModifierCompilerPassTest
 */
class ResponseModifierCompilerPassTest extends TestCase
{
    /**
     * @var ResponseModifierCompilerPass
     */
    private $compiler;

    public function testProcessIfTransformerNotFound()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects(static::once())
            ->method('hasDefinition')
            ->with(ResponseModifierCompilerPass::MODIFIER_SERVICE_ID)
            ->willReturn(false);

        $this->compiler->process($container);
    }

    public function testProcess()
    {
        $container = new ContainerBuilder();

        $rootModifier = new Definition(ResponseModifier::class);
        $rootModifier->addArgument($this->createMock(RequestStack::class));
        $rootModifier->addArgument([]);

        $container->setDefinition(ResponseModifierCompilerPass::MODIFIER_SERVICE_ID, $rootModifier);

        $modifierId = 'test_transformer';
        $modifierDefinition = new Definition(ResponseModifierInterface::class);
        $modifierDefinition->addTag(ResponseModifierCompilerPass::MODIFIER_SERVICE_TAG);
        $container->setDefinition($modifierId, $modifierDefinition);

        static::assertEquals([], $rootModifier->getArgument(1));

        $this->compiler->process($container);

        static::assertEquals([
            new Reference($modifierId),
        ], $rootModifier->getArgument(1));
    }

    protected function setUp()
    {
        $this->compiler = new ResponseModifierCompilerPass();

        parent::setUp();
    }

    protected function assertPreConditions()
    {
        static::assertInstanceOf(CompilerPassInterface::class, $this->compiler);

        parent::assertPreConditions();
    }
}
