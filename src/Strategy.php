<?php

declare(strict_types=1);

namespace Blue\Core\View;

interface Strategy
{
    public function render(mixed $element, object $model, Renderer $renderer, NextStrategy $next): string;
}