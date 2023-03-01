<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;
use SnappyRenderer\Stringable;
use Throwable;

/**
 * @phpstan-import-type element from Renderable
 */
class RenderStringable implements Strategy
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
        if ($this->isStringable($element)) {
            try {
                /** @var Stringable $element */
                return $element->__toString();
            } catch (Throwable $throwable) {
                throw RenderException::forThrowableInElement($throwable, $element);
            }
        }
        return $next->continue($element, $model, $renderer);
    }

    /**
     * @param element $value
     * @return bool
     */
    function isStringable($value): bool
    {
        if ($value instanceof Stringable) {
            return true;
        } elseif (PHP_VERSION_ID < 80000) {
            return method_exists($value, '__toString');
        } elseif (interface_exists('Stringable')) {
            return $value instanceof \Stringable;
        }
        return false;
    }
}