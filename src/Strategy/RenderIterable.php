<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderable\RenderableIterable;
use SnappyRenderer\Strategy;
use SnappyRenderer\Renderer;

/**
 * @phpstan-import-type element from Renderable
 */
class RenderIterable implements Strategy
{
    /**
     * @param element $element
     * @param object $model
     * @param Renderer $renderer
     * @param NextStrategy $next
     * @return string
     * @throws RenderException
     */
    public function render($element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        if (is_iterable($element)) {
            return $renderer->render(new RenderableIterable($element), $model);
        }
        return $next->continue($element, $model, $renderer);
    }
}