<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\Request\Modifier;

use Oxidmod\RestBundle\Request\Modifier\JsonRequestModifier;
use Oxidmod\RestBundle\Request\Modifier\RequestModifierInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test for JsonRequestModifier
 */
class JsonRequestModifierTest extends TestCase
{
    /**
     * @var JsonRequestModifier
     */
    private $modifier;

    public function testNotJsonRequest()
    {
        $request = new Request([],[],[],[],[],['HTTP_CONTENT_TYPE' => 'multipart/form-data'], json_encode([]));

        $request->request = $this->createMock(ParameterBag::class);
        $request->request->expects(static::never())->method(static::anything());

        $this->modifier->modify($request);
    }

    public function providerForTestNotJsonRequest(): array
    {
        return [
            [new Request([],[],[],[],[],['HTTP_CONTENT_TYPE' => 'multipart/form-data'], 'not a json content'), []],
            [new Request([],['test' => 'val'],[],[],[],['HTTP_CONTENT_TYPE' => 'multipart/form-data'], 'not a json content'), ['test' => 'val']],
        ];
    }

    /**
     * @param string $contentType
     * @param string $content
     *
     * @dataProvider providerForTestWithMalformedBody
     */
    public function testJsonRequestWithMalformedBody(string $contentType, string $content)
    {
        $request = new Request([],[],[],[],[],['HTTP_CONTENT_TYPE' => $contentType], $content);

        $this->modifier->modify($request);

        static::assertSame([], $request->request->all());
    }

    public function providerForTestWithMalformedBody(): array
    {
        return [
            ['application/json', '{not json string'],
            ['application/vnd.api+json', '{not json string'],
            ['application/json', ''],
            ['application/vnd.api+json', ''],
            ['application/json', json_encode('')],
            ['application/vnd.api+json', json_encode('')],
        ];
    }

    /**
     * @param string $contentType
     * @param string $content
     * @param array $expectedRequest
     *
     * @dataProvider providerForTestModify
     */
    public function testModify(string $contentType, string $content, array $expectedRequest)
    {
        $request = new Request([],[],[],[],[],['HTTP_CONTENT_TYPE' => $contentType], $content);

        $this->modifier->modify($request);

        static::assertSame($expectedRequest, $request->request->all());
    }

    public function providerForTestModify(): array
    {
        return [
            ['application/json', json_encode(['test']), ['test']],
            ['application/vnd.api+json', json_encode(['test']), ['test']],
            ['application/json', json_encode(['test' => 'val']), ['test' => 'val']],
            ['application/vnd.api+json', json_encode(['test' => 'val']), ['test' => 'val']],
        ];
    }

    protected function setUp()
    {
        $this->modifier = new JsonRequestModifier();

        parent::setUp();
    }

    protected function assertPreConditions()
    {
        static::assertInstanceOf(RequestModifierInterface::class, $this->modifier);

        parent::assertPreConditions();
    }
}
