<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\RenderPipeline;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use stdClass;

class BasicRenderingTest extends TestCase
{
    private function mockRenderable(iterable $elements): Renderable
    {
        return new Renderable\RenderableIterable($elements);
    }

    private function mockStringable(string $content)
    {
        $stringable = $this->getMockBuilder(stdClass::class)
            ->addMethods(['__toString'])
            ->getMockForAbstractClass();
        $stringable->method('__toString')->willReturn($content);
        return $stringable;
    }

    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldThrowExceptionForInvalidElement()
    {
        $invalidRenderable = new stdClass();
        self::expectExceptionObject(RenderException::forInvalidElement($invalidRenderable));
        $renderable = $this->mockRenderable([$invalidRenderable]);

        $renderer = new Renderer(new RenderPipeline());
        $renderer->render($renderable, new stdClass());
    }

    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldRenderStringables()
    {
        $component = $this->mockRenderable([$this->mockStringable('hello world')]);
        $renderer = new Renderer(new RenderPipeline());
        $result = $renderer->render($component, new stdClass());
        self::assertEquals('hello world', $result);
    }

    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldRenderRenderablesRecursively()
    {
        $renderable = $this->mockRenderable([
            $this->mockRenderable([
                    $this->mockStringable('hello'),
                    $this->mockRenderable([$this->mockStringable(' ')])]
            ),
            $this->mockRenderable([$this->mockStringable('world')])
        ]);

        $renderer = new Renderer(new RenderPipeline());
        $result = $renderer->render($renderable, new stdClass());
        self::assertEquals('hello world', $result);
    }

    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldRenderPlainStrings()
    {
        $renderable = $this->mockRenderable(['hello world']);

        $renderer = new Renderer(new RenderPipeline());
        $result = $renderer->render($renderable, new stdClass());
        self::assertEquals('hello world', $result);
    }

    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldRenderClosures()
    {
        $renderable = $this->mockRenderable([fn() => ['hello world']]);
        $renderer = new Renderer(new RenderPipeline());
        $result = $renderer->render($renderable, new stdClass());
        self::assertEquals('hello world', $result);
    }

    /**
     * @return void
     * @throws RenderException
     */
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
        $renderer = new Renderer(new RenderPipeline());
        $model = new stdClass();
        $model->name = 'world';
        $result = $renderer->render($renderable, $model);
        self::assertEquals('hello world', $result);
    }

    /**
     * @return void
     * @throws RenderException
     */
    public function testShouldRenderIterables()
    {
        $renderable = $this->mockRenderable([['hello', ' ', 'world']]);
        $renderer = new Renderer(new RenderPipeline());
        $result = $renderer->render($renderable, new stdClass());
        self::assertEquals('hello world', $result);
    }
}
