<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class Stringable implements Strategy
{
    public function render(mixed $element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        if ($element instanceof \Stringable) {
            return (string)$element;
        }
        return $next->continue($element, $model, $renderer);
    }
}