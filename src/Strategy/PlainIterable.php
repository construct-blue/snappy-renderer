<?php

declare(strict_types=1);

namespace Blue\Core\View\Strategy;

use Blue\Core\View\NextStrategy;
use Blue\Core\View\Renderable\RenderableIterable;
use Blue\Core\View\Strategy;
use Blue\Core\View\Renderer;

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