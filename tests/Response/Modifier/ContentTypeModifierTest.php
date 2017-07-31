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
    /**
     * @param string|null $defaultContentType
     *
     * @dataProvider providerForSkipModify
     */
    public function testSkipModifyingWithoutRequest(string $defaultContentType = null)
    {
        $modifier = new ContentTypeModifier($defaultContentType);

        $response = new Response();

        static::assertSame($response, $modifier->modify($response));
        static::assertFalse($response->headers->has('Content-Type'));
    }

    public function providerForSkipModify(): array
    {
        return [
            [null],
            ['Test'],
            [ContentTypeModifier::HEADER_APPLICATION_JSON],
            [ContentTypeModifier::HEADER_APPLICATION_JSON_API],
        ];
    }

    /**
     * @param string $expectedHeader
     * @param string|null $requestHeader
     * @param string|null $defaultContentType
     *
     * @dataProvider providerForTestModify
     */
    public function testModify(string $expectedHeader, string $requestHeader = null, string $defaultContentType = null)
    {
        $modifier = new ContentTypeModifier($defaultContentType);

        $response = new Response();

        $requestHeaders = null === $requestHeader ? [] : ['HTTP_CONTENT_TYPE' => $requestHeader];
        $request = new Request([], [], [], [], [], $requestHeaders);

        $modifiedResponse = $modifier->modify($response, $request);

        static::assertFalse($response->headers->has('Content-Type'));

        static::assertEquals($expectedHeader, $modifiedResponse->headers->get('Content-Type'));
    }

    public function providerForTestModify(): array
    {
        return [
            [ContentTypeModifier::HEADER_APPLICATION_JSON_API, null, null],
            [ContentTypeModifier::HEADER_APPLICATION_JSON_API, null, ContentTypeModifier::HEADER_APPLICATION_JSON_API],
            [ContentTypeModifier::HEADER_APPLICATION_JSON, null, ContentTypeModifier::HEADER_APPLICATION_JSON],
            ['Test', null, 'Test'],
            [ContentTypeModifier::HEADER_APPLICATION_JSON_API, 'Test', ContentTypeModifier::HEADER_APPLICATION_JSON_API],
            [ContentTypeModifier::HEADER_APPLICATION_JSON, 'Test', ContentTypeModifier::HEADER_APPLICATION_JSON],
            [ContentTypeModifier::HEADER_APPLICATION_JSON, ContentTypeModifier::HEADER_APPLICATION_JSON, null],
            [ContentTypeModifier::HEADER_APPLICATION_JSON, ContentTypeModifier::HEADER_APPLICATION_JSON, ContentTypeModifier::HEADER_APPLICATION_JSON_API],
            [ContentTypeModifier::HEADER_APPLICATION_JSON, ContentTypeModifier::HEADER_APPLICATION_JSON, ContentTypeModifier::HEADER_APPLICATION_JSON],
            [ContentTypeModifier::HEADER_APPLICATION_JSON_API, ContentTypeModifier::HEADER_APPLICATION_JSON_API, null],
            [ContentTypeModifier::HEADER_APPLICATION_JSON_API, ContentTypeModifier::HEADER_APPLICATION_JSON_API, ContentTypeModifier::HEADER_APPLICATION_JSON_API],
            [ContentTypeModifier::HEADER_APPLICATION_JSON_API, ContentTypeModifier::HEADER_APPLICATION_JSON_API, ContentTypeModifier::HEADER_APPLICATION_JSON],
        ];
    }

   protected function assertPreConditions()
   {
       static::assertInstanceOf(ResponseModifierInterface::class, new ContentTypeModifier());

       parent::assertPreConditions();
   }
}
