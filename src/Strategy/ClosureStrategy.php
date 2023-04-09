<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use Closure;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class ClosureStrategy implements Strategy
{
    private Strategy $strategy;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function execute($view, Renderer $renderer): string
    {
        if ($view instanceof Closure) {
            return $renderer->render($view($renderer));
        }
        return $this->strategy->execute($view, $renderer);
    }
}