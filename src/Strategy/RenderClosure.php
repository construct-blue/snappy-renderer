<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use Closure;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderable\RenderableClosure;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

/**
 * @phpstan-import-type element from Renderable
 */
class RenderClosure implements Strategy
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
        if ($element instanceof Closure) {
            return $renderer->render(new RenderableClosure($element), $model);
        }
        return $next->continue($element, $model, $renderer);
    }
}