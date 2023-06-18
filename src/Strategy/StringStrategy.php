<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy;

use Blue\Snappy\Renderer\Renderer;
use Blue\Snappy\Renderer\Strategy\Base\PipelineStrategy;

final class StringStrategy extends PipelineStrategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     */
    public function execute($view, Renderer $renderer, $data): string
    {
        if (is_string($view)) {
            return $view;
        }
        return $this->next($view, $renderer, $data);
    }
}