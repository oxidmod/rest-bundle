<?php

declare (strict_types=1);

namespace Oxidmod\RestBundle\Tests\Response;

use League\Fractal\Manager;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;
use Oxidmod\RestBundle\Response\ResponseModifier;
use Oxidmod\RestBundle\Response\ResponseRenderer;
use Oxidmod\RestBundle\Transformer\Exception\UnsupportedObjectException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test for ResponseRendererTest
 */
class ResponseRendererTest extends TestCase
{
    /**
     * @expectedException \Oxidmod\RestBundle\Transformer\Exception\UnsupportedObjectException
     */
    public function testTransformerException()
    {
        $transformer = new class extends TransformerAbstract {
            public function transform($data) {
                throw new UnsupportedObjectException($data);
            }
        };

        $renderer = $this->getRenderer($transformer);

        $renderer->renderResponse(new \stdClass(), 200);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testResponseException()
    {
        $transformer = new class extends TransformerAbstract {
            public function transform($data) {
                return [];
            }
        };

        $renderer = $this->getRenderer($transformer);

        $renderer->renderResponse(new \stdClass(), 666);
    }

    public function testResponseRenderResponseFromObject()
    {
        $transformer = new class extends TransformerAbstract {
            public function transform($data) {
                return ['test' => 'val'];
            }
        };

        $renderer = $this->getRenderer($transformer);

        $expectedContent = json_encode(['data' => ['test' => 'val']]);

        static::assertEquals(
            new Response($expectedContent, 200),
            $renderer->renderResponse(new \stdClass(), 200)
        );
    }

    public function testResponseRenderResponseFromArray()
    {
        $transformer = new class extends TransformerAbstract {
            public function transform($data) {
                return ['test' => 'val'];
            }
        };

        $renderer = $this->getRenderer($transformer);

        $expectedContent = json_encode([
            'data' => [
                ['test' => 'val'],
            ]
        ]);

        static::assertEquals(
            new Response($expectedContent, 200),
            $renderer->renderResponse([new \stdClass()], 200)
        );
    }

    public function testResponseRenderResponseFromNull()
    {
        $renderer = $this->getRenderer(new class extends TransformerAbstract {});

        $expectedContent = json_encode([
            'data' => []
        ]);

        static::assertEquals(
            new Response($expectedContent, 200),
            $renderer->renderResponse(null, 200)
        );
    }

    public function providerForRenderResponse():array
    {
        return [
            [['test' => 'val'], 200, new Response(json_encode(['data' => ['test' => 'val']]), 200)],

        ];
    }

    /**
     * @param TransformerAbstract $transformer
     * @return ResponseRenderer
     */
    private function getRenderer($transformer): ResponseRenderer
    {
        $responseModifier = $this->createMock(ResponseModifier::class);
        $responseModifier->expects(static::any())
            ->method('modifyResponse')
            ->willReturnCallback(function (Response $response) {
                return $response;
            });

        return new ResponseRenderer(
            new Manager(),
            $transformer,
            $responseModifier
        );
    }
}
