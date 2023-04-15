<?php

declare(strict_types=1);

namespace SnappyRenderer;

interface Strategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @return string
     */
    public function execute($view, Renderer $renderer): string;
}