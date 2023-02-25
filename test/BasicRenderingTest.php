<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy\Pipeline\Pipe;
use stdClass;
use Stringable;

class BasicRenderingTest extends TestCase
{
    private function mockRenderable(iterable $elements): Renderable
    {
        $builder = new RenderableMockBuilder($this);
        $builder->setIterable($elements);
        return $builder->getMock();
    }

    private function mockStringable(string $content): Stringable
    {
        $stringable = $this->getMockBuilder(Stringable::class)
            ->onlyMethods(['__toString'])
            ->getMockForAbstractClass();
        $stringable->method('__toString')->willReturn($content);
        return $stringable;
    }

    public function testShouldThrowExceptionForInvalidElement()
    {
        $invalidRenderable = new stdClass();
        self::expectExceptionObject(RenderException::forInvalidElement($invalidRenderable));
        $renderable = $this->mockRenderable([$invalidRenderable]);

        $renderer = new Renderer(new Pipe());
        $renderer->render($renderable, new stdClass());
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
