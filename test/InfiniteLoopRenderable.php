<?php

declare(strict_types=1);

namespace SnappyRendererTest;

use SnappyRenderer\Renderable;

class InfiniteLoopRenderable implements Renderable
{
    public function render(object $model): iterable
    {
        yield new InfiniteLoopRenderable();
    }
}