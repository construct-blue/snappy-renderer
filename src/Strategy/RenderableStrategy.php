<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class RenderableStrategy implements Strategy
{
    private Strategy $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer, $data = null): string
    {
        if ($view instanceof Renderable) {
            return (new IterableStrategy($renderer))->execute(
                $view->render($renderer, $data),
                $renderer,
                $data
            );
        }
        return $this->strategy->execute($view, $renderer, $data);
    }
}