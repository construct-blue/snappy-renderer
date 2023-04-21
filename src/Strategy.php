<?php

declare(strict_types=1);

namespace SnappyRenderer;

interface Strategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     */
    public function execute($view, Renderer $renderer, $data = null): string;
}