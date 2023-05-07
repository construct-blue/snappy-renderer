<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\AbstractStrategy;
use SnappyRenderer\Renderer;

final class StringStrategy extends AbstractStrategy
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