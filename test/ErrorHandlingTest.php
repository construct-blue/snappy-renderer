<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use PHPUnit\Framework\TestCase;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderable\Elements;
use SnappyRenderer\Renderer;

class ErrorHandlingTest extends TestCase
{
    public function testShouldConvertThrowables(): void
    {
        self::expectException(RenderException::class);
        $renderer = new Renderer();
        $renderer->render([
            function ($object) {
                return $object->undefined;
            }
        ], (object)[]);
    }

    public function testShouldCatchInfiniteLoops(): void
    {
        self::expectException(RenderException::class);
        self::expectExceptionCode(RenderException::CODE_MAX_NESTING_LEVEL);
        $renderer = new Renderer();
        $renderer->render(new InfiniteLoopRenderable(), (object)[]);
    }
}
