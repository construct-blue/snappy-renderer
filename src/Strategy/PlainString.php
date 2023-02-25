<?php

declare(strict_types=1);

namespace Blue\Core\View\Strategy;

use Blue\Core\View\NextStrategy;
use Blue\Core\View\Renderer;
use Blue\Core\View\Strategy;

class PlainString implements Strategy
{
    public function render(mixed $element, object $model, Renderer $renderer, NextStrategy $next): string
    {
        if (is_string($element)) {
            return $element;
        }
        return $next->continue($element, $model, $renderer);
    }
}