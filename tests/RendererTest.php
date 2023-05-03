<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use Generator;
use PHPUnit\Framework\TestCase;
use SnappyRenderer\DefaultStrategy;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Helper\Arguments;
use SnappyRenderer\Helper\Capture;
use SnappyRenderer\Helper\Placeholder;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use Throwable;

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

    public function dataProvider_ViewTypes(): Generator
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
     * @dataProvider dataProvider_ViewTypes
     * @param mixed $view
     * @return void
     * @throws RenderException
     */
    public function testShouldRenderDifferentViewTypes($view): void
    {
        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    /**
     * @throws RenderException
     */
    public function testShouldThrowExceptionForInfiniteRenderLoops(): void
    {
        self::iniSet('xdebug.max_nesting_level', '5000');
        $view = new class implements Renderable {
            public function render(Renderer $renderer, $data = null): Generator
            {
                yield new static();
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
    public function testShouldReplacePlaceholderWithCapture(): void
    {
        $view = [
            new Placeholder('replace'),
            [
                'world!',
                new Capture(new Placeholder('replace'), 'Hello ')
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    public function testShouldAppendToCapture(): void
    {
        $view = [
            new Placeholder('replace'),
            [
                new Capture(new Placeholder('replace'), 'Hello '),
                new Capture(new Placeholder('replace'), 'world!')
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));
    }

    public function testShouldResetCaptures(): void
    {
        $view = [
            new Placeholder('replace'),
            [
                new Capture(new Placeholder('replace'), 'Hello '),
                new Capture(new Placeholder('replace'), 'world!')
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view));

        $view2 = [
            new Placeholder('replace'),
            [
                new Capture(new Placeholder('replace'), 'Hello '),
                new Capture(new Placeholder('replace'), 'world!')
            ],
        ];

        self::assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render($view2));
    }


    public function testShouldThrowExcetionWhenPlaceholderIsUsedMoreThenOnce(): void
    {
        self::expectExceptionObject(new RenderException('Placeholder "replace" already in use.'));
        $view = [
            new Placeholder('replace'),
            new Placeholder('replace')
        ];
        $this->renderer->render($view);
    }


    public function testShouldConvertThrowablesToRenderException(): void
    {
        // @phpstan-ignore-next-line
        $view = fn() => count(null);
        try {
            $x = $view();
        } catch (Throwable $throwable) {
            self::expectExceptionObject(RenderException::forThrowableInView($throwable, $view));
        }

        $this->renderer->render($view);
    }

    public function testShouldRenderListOfItemsInLoop(): void
    {
        $view = fn(Renderer $renderer, array $items) => $renderer->loop(
            fn(Renderer $renderer, string $item) => "<p>$item</p>",
            $items
        );
        self::assertEquals(
            '<p>foo</p><p>bar</p><p>baz</p>',
            $this->renderer->render($view, new Arguments(['items' => ['foo', 'bar', 'baz']]))
        );
    }

    public function testShouldRenderConditionally(): void
    {
        $view = fn(Renderer $renderer, array $items) => $renderer->loop(
            fn(Renderer $renderer, string $item) => $renderer->conditional(
                "<p>%s</p>",
                fn($item) => $item === 'foo',
                $item
            ),
            $items
        );
        self::assertEquals(
            '<p>foo</p>',
            $this->renderer->render($view, new Arguments(['items' => ['foo', 'bar', 'baz']]))
        );
    }

    public function testShouldPassArgumentsToClosureByName(): void
    {
        $view = fn(string $name, string $greeting) => "$greeting $name!";
        self::assertEquals(
            self::HELLO_WORLD_STRING,
            $this->renderer->render($view, new Arguments(['greeting' => 'Hello', 'name' => 'world']))
        );
    }

    public function testShouldFormatStrings(): void
    {
        $this->assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render('Hello %s!', 'world'));
        $this->assertEquals(self::HELLO_WORLD_STRING, $this->renderer->render('%s %s!', ['Hello', 'world']));
    }
}
