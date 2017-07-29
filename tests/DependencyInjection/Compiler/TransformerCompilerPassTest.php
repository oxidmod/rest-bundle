<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\DependencyInjection\Compiler;

use Oxidmod\RestBundle\DependencyInjection\Compiler\TransformerCompilerPass;
use Oxidmod\RestBundle\Transformer\Transformer;
use Oxidmod\RestBundle\Transformer\TransformerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Test for TransformerCompilerPass
 */
class TransformerCompilerPassTest extends TestCase
{
    /**
     * @var TransformerCompilerPass
     */
    private $compiler;

    public function testProcessIfTransformerNotFound()
    {
        $container = $this->createMock(ContainerBuilder::class);
        $container->expects(static::once())
            ->method('hasDefinition')
            ->with(TransformerCompilerPass::TRANSFORMER_SERVICE_ID)
            ->willReturn(false);

        $this->compiler->process($container);
    }

    public function testProcess()
    {
        $container = new ContainerBuilder();

        $rootTransformer = new Definition(Transformer::class);
        $rootTransformer->addArgument([]);

        $container->setDefinition(TransformerCompilerPass::TRANSFORMER_SERVICE_ID, $rootTransformer);

        $transformerId = 'test_transformer';
        $transformerDefinition = new Definition(TransformerInterface::class);
        $transformerDefinition->addTag(TransformerCompilerPass::TRANSFORMER_SERVICE_TAG);
        $container->setDefinition($transformerId, $transformerDefinition);

        static::assertEquals([], $rootTransformer->getArgument(0));

        $this->compiler->process($container);

        static::assertEquals([
            new Reference($transformerId),
            ],
            $rootTransformer->getArgument(0)
        );
    }

    protected function setUp()
    {
        $this->compiler = new TransformerCompilerPass();

        parent::setUp();
    }

    protected function assertPreConditions()
    {
        static::assertInstanceOf(CompilerPassInterface::class, $this->compiler);

        parent::assertPreConditions();
    }
}
