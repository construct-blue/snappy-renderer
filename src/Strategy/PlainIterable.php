<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable\RenderableIterable;
use SnappyRenderer\Strategy;
use SnappyRenderer\Renderer;

class PlainIterable implements Strategy
{
    public function render(mixed $element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        if (is_iterable($element)) {
            return $renderer->render(new RenderableIterable($element), $model);
        }
        return $next->continue($element, $model, $renderer);
    }
}