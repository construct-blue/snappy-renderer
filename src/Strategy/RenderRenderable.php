<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

/**
 * @phpstan-import-type element from Renderable
 */
class RenderRenderable implements Strategy
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
        if ($element instanceof Renderable) {
            ob_start();
            try {
                foreach ($element->render($model) as $item) {
                    echo $renderer->render($item, $model);
                }
            } finally {
                $result = ob_get_clean();
            }
            return $result;
        }
        return $next->continue($element, $model, $renderer);
    }
}