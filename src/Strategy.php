<?php

declare(strict_types=1);

namespace SnappyRenderer;

/**
 * @phpstan-import-type element from Renderable
 */
interface Strategy
{
    /**
     * @param element $element
     * @param object $model
     * @param Renderer $renderer
     * @param NextStrategy $next
     * @return string
     * @throws Exception\RenderException
     */
    public function render($element, object $model, Renderer $renderer, NextStrategy $next): string;
}