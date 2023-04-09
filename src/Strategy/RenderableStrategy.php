<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

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

    public function execute($view, Renderer $renderer): string
    {
        if ($view instanceof Renderable) {
            return $renderer->render($view->render($renderer));
        }
        return $this->strategy->execute($view, $renderer);
    }
}