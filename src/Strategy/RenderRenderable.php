<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class RenderRenderable implements Strategy
{
    public function render($element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        if ($element instanceof \SnappyRenderer\Renderable) {
            return $renderer->render($element, $model);
        }
        return $next->continue($element, $model, $renderer);
    }
}