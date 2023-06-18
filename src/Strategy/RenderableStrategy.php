<?php
/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy;

use Blue\Snappy\Renderer\Exception\RenderException;
use Blue\Snappy\Renderer\Renderable;
use Blue\Snappy\Renderer\Renderer;
use Blue\Snappy\Renderer\Strategy\Base\PipelineStrategy;

final class RenderableStrategy extends PipelineStrategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer, $data): string
    {
        if ($view instanceof Renderable) {
            return (new IterableStrategy($renderer))->execute(
                $view->render($renderer, $data),
                $renderer,
                $data
            );
        }
        return $this->next($view, $renderer, $data);
    }
}