<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer;

interface Strategy
{
    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     */
    public function execute($view, Renderer $renderer, $data): string;
}