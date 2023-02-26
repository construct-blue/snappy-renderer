<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class RenderString implements Strategy
{
    public function render($element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        if (is_string($element)) {
            return $element;
        }
        return $next->continue($element, $model, $renderer);
    }
}