<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use Closure;
use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderable\Functional;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;
use Throwable;

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
            try {
                return $renderer->render(new Functional($element), $model);
            } catch (Throwable $throwable) {
                throw RenderException::forThrowableInElement($throwable, $element);
            }
        }
        return $next->continue($element, $model, $renderer);
    }
}