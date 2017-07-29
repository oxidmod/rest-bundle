<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\DependencyInjection;

use Oxidmod\RestBundle\DependencyInjection\RestExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Test for RestExtension
 */
class RestExtensionTest extends TestCase
{
    /**
     * @var RestExtension
     */
    private $extension;

    public function testGetAlias()
    {
        static::assertSame('oxidmod_rest', $this->extension->getAlias());
    }

    protected function assertPreConditions()
    {
        static::assertInstanceOf(Extension::class, $this->extension);

        parent::assertPreConditions();
    }

    protected function setUp()
    {
        $this->extension = new RestExtension();

        parent::setUp();
    }
}
