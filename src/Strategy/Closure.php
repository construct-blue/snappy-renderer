<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable\RenderableClosure;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class Closure implements Strategy
{
    public function render(mixed $element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        if ($element instanceof \Closure) {
            return $renderer->render(new RenderableClosure($element), $model);
        }
        return $next->continue($element, $model, $renderer);
    }
}