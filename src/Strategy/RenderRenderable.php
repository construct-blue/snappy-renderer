<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;
use Throwable;

/**
 * @phpstan-import-type element from Renderable
 */
class RenderRenderable implements Strategy
{
    private const MAX_NESTING_LEVEL = 1000;

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
        if ($element instanceof Renderable) {
            ob_start();
            try {
                foreach ($element->render($model) as $item) {
                    if (ob_get_level() > self::MAX_NESTING_LEVEL) {
                        throw new RenderException(
                            sprintf('Maximum nesting level reached in: %s', get_class($element)),
                            RenderException::CODE_MAX_NESTING_LEVEL
                        );
                    }
                    echo $renderer->render($item, $model);
                }
            } catch (Throwable $throwable) {
                throw RenderException::forThrowableInElement($throwable, $element);
            } finally {
                $result = ob_get_clean();
            }
            return $result;
        }
        return $next->continue($element, $model, $renderer);
    }
}