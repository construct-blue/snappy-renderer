<?php

declare(strict_types=1);

namespace SnappyRendererTest;

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

    public function contracts(): iterable
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
                public function render(Renderer $renderer): iterable
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
     * @return void
     */
    public function testContracts($view)
    {
        $this->assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    public function testShouldThrowExceptionForInfiniteRenderLoops()
    {
        $view = new class implements Renderable {
            public function render(Renderer $renderer): iterable
            {
                yield new static;
            }
        };
        self::expectExceptionObject(RenderException::forMaxNestingLevel($view, $this->renderer->getMaxLevel()));
        $this->renderer->render($view);
    }

    public function testShouldResetLevel()
    {
        self::assertEquals(0, $this->renderer->getLevel());
        $this->renderer->render(['', ['']]);
        self::assertEquals(0, $this->renderer->getLevel());
    }

    public function testShouldReplaceCapturables()
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
}
