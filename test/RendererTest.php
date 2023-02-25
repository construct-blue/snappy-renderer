<?php

declare(strict_types=1);

namespace BlueTest\Core\View;

use Blue\Core\View\Exception\RenderException;
use Blue\Core\View\Renderable;
use Blue\Core\View\Renderer;
use Blue\Core\View\Strategy\Pipeline\Pipe;
use PHPUnit\Framework\TestCase;
use stdClass;
use Stringable;

class RendererTest extends TestCase
{
    private function mockRenderable(iterable $elements): Renderable
    {
        $renderable = $this->getMockBuilder(Renderable::class)
            ->onlyMethods(['render'])
            ->getMockForAbstractClass();
        $renderable->method('render')->willReturn($elements);
        return $renderable;
    }

    public function testShouldThrowExceptionForInvalidElement()
    {
        self::expectException(RenderException::class);
        self::expectExceptionMessage('Element of type stdClass could not be rendered.');
        $renderable = $this->mockRenderable([new stdClass()]);

        $renderer = new Renderer(new Pipe());
        $renderer->render($renderable, new stdClass());
    }

    private function mockStringable(string $content): Stringable
    {
        $stringable = $this->getMockBuilder(Stringable::class)
            ->onlyMethods(['__toString'])
            ->getMockForAbstractClass();
        $stringable->method('__toString')->willReturn($content);
        return $stringable;
    }

    public function testShouldRenderStringables()
    {
        $component = $this->mockRenderable([$this->mockStringable('hello world')]);
        $renderer = new Renderer(new Pipe());
        $result = $renderer->render($component, new stdClass());
        $this->assertEquals('hello world', $result);
    }

    public function testShouldRenderRenderablesRecursively()
    {
        $renderable = $this->mockRenderable([
            $this->mockRenderable([
                    $this->mockStringable('hello'),
                    $this->mockRenderable([$this->mockStringable(' ')])]
            ),
            $this->mockRenderable([$this->mockStringable('world')])
        ]);

        $renderer = new Renderer(new Pipe());
        $result = $renderer->render($renderable, new stdClass());
        $this->assertEquals('hello world', $result);
    }

    public function testShouldRenderPlainStrings()
    {
        $renderable = $this->mockRenderable(['hello world']);

        $renderer = new Renderer(new Pipe());
        $result = $renderer->render($renderable, new stdClass());
        $this->assertEquals('hello world', $result);
    }

    public function testShouldRenderClosures()
    {
        $renderable = $this->mockRenderable([fn() => ['hello world']]);
        $renderer = new Renderer(new Pipe());
        $result = $renderer->render($renderable, new stdClass());
        $this->assertEquals('hello world', $result);
    }

    public function testShouldAllowPassingOfVariables()
    {
        $renderable = $this->mockRenderable([new class implements Renderable {
            public function render(object $model): iterable
            {
                return [
                    'hello ', $model->name
                ];
            }
        }]);
        $renderer = new Renderer(new Pipe());
        $model = new stdClass();
        $model->name = 'world';
        $result = $renderer->render($renderable, $model);
        $this->assertEquals('hello world', $result);
    }

    public function testShouldRenderIterables()
    {
        $renderable = $this->mockRenderable([['hello', ' ', 'world']]);
        $renderer = new Renderer(new Pipe());
        $result = $renderer->render($renderable, new stdClass());
        $this->assertEquals('hello world', $result);
    }
}
