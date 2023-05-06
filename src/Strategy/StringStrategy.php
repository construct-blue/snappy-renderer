<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class StringStrategy implements Strategy
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
     */
    public function execute($view, Renderer $renderer, $data = null): string
    {
        if (is_string($view)) {
            return $view;
        }
        return $this->strategy->execute($view, $renderer, $data);
    }
}