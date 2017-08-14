<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\Response\Modifier;

use Oxidmod\RestBundle\Response\Modifier\ContentTypeModifier;
use Oxidmod\RestBundle\Response\Modifier\ResponseModifierInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test for ContentTypeModifierTest
 */
class ContentTypeModifierTest extends TestCase
{
    const HEADER_ACCEPT = 'application/json';

    /**
     * @var ContentTypeModifier
     */
    private $modifier;

    public function testSkipModifyingIfAnotherAcceptHeader()
    {
        $response = new Response();
        $request = new Request();

        static::assertSame($response, $this->modifier->modify($response, $request));
        static::assertFalse($response->headers->has('Content-Type'));
    }

    public function testModify()
    {
        $response = new Response();
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => self::HEADER_ACCEPT]);

        $modifiedResponse = $this->modifier->modify($response, $request);

        static::assertFalse($response->headers->has('Content-Type'));
        static::assertEquals(self::HEADER_ACCEPT, $modifiedResponse->headers->get('Content-Type'));
    }

    protected function setUp()
    {
        $this->modifier = new ContentTypeModifier(self::HEADER_ACCEPT);

        parent::setUp();
    }

    protected function assertPreConditions()
    {
        static::assertInstanceOf(ResponseModifierInterface::class, $this->modifier);

        parent::assertPreConditions();
    }
}
