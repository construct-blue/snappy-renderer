<?php

namespace SnappyRenderer;

abstract class AbstractStrategy implements Strategy
{
    private Strategy $strategy;

    /**
     * @param Strategy $strategy
     */
    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param null|mixed $data
     * @return string
     */
    protected function next($view, Renderer $renderer, $data): string
    {
        return $this->strategy->execute($view, $renderer, $data);
    }
}