<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use Generator;
use SnappyRenderer\Capture;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use PHPUnit\Framework\TestCase;
use SnappyRenderer\DefaultStrategy;

class RendererTest extends TestCase
{
    private Renderer $renderer;

    public const HELLO_WORLD_STRING = 'Hello world!';
    public const HELLO_WORLD_ARRAY = ['Hello', ' ', 'world', '!'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->renderer = new Renderer(new DefaultStrategy());
    }

    public function contracts(): Generator
    {
        yield 'Should render string.' => [self::HELLO_WORLD_STRING];
        yield 'Should render array of strings.' => [self::HELLO_WORLD_ARRAY];
        yield 'Should render closure returning string.' => [fn() => self::HELLO_WORLD_STRING];
        yield 'Should render closure returning array strings.' => [fn() => self::HELLO_WORLD_ARRAY];
        yield 'Should render array of closures returning array of strings' => [
            [
                fn() => ['Hello', ' '],
                fn() => ['world', '!']
            ]
        ];
        yield 'Should render renderable objects.' => [
            new class implements Renderable {
                /**
                 * @param Renderer $renderer
                 * @param mixed|null $data
                 * @return string[]
                 */
                public function render(Renderer $renderer, $data = null): array
                {
                    return RendererTest::HELLO_WORLD_ARRAY;
                }
            }
        ];
        yield 'Should render closure returning generator.' => [
            fn() => yield from self::HELLO_WORLD_ARRAY
        ];
    }

    /**
     * @dataProvider contracts
     * @param mixed $view
     * @return void
     * @throws RenderException
     */
    public function testContracts($view): void
    {
        $this->assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    /**
     * @throws RenderException
     */
    public function testShouldThrowExceptionForInfiniteRenderLoops(): void
    {
        $view = new class implements Renderable {
            public function render(Renderer $renderer, $data = null): Generator
            {
                yield new static;
            }
        };
        self::expectExceptionObject(
            RenderException::forMaxNestingLevel($view, $this->renderer->getMaxLevel())
        );
        $this->renderer->render($view);
    }

    /**
     * @throws RenderException
     */
    public function testShouldResetLevel(): void
    {
        self::assertEquals(0, $this->renderer->getLevel());
        $this->renderer->render(['', ['']]);
        self::assertEquals(0, $this->renderer->getLevel());
    }

    /**
     * @throws RenderException
     */
    public function testShouldReplaceCapturables(): void
    {
        $view = [
            'replace',
            [
                'world!',
                new Capture('replace', 'Hello ')
            ],
        ];

        $this->assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    public function testShouldRenderListOfItemsInLoop(): void
    {
        $view = fn(Renderer $renderer, array $items) => $renderer->loop(
            fn(Renderer $renderer, $item) => "<p>$item</p>",
            $items
        );
        $this->assertEquals(
            '<p>foo</p><p>bar</p><p>baz</p>',
            $this->renderer->render($view, ['foo', 'bar', 'baz'])
        );
    }

    public function testShouldRenderConditionally()
    {
        $view = fn(Renderer $renderer, array $items) => $renderer->loop(
            fn(Renderer $renderer, $item) => $renderer->conditional(
                "<p>%s</p>",
                fn($item) => $item === 'foo',
                $item
            ),
            $items
        );
        $this->assertEquals(
            '<p>foo</p>',
            $this->renderer->render($view, ['foo', 'bar', 'baz'])
        );
    }
}
