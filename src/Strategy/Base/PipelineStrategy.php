<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy\Base;

use Blue\Snappy\Renderer\Renderer;
use Blue\Snappy\Renderer\Strategy;

abstract class PipelineStrategy implements Strategy
{
    private Strategy $strategy;

    /**
     * @param Strategy $strategy
     */
    final public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
        $this->init();
    }

    protected function init(): void
    {
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