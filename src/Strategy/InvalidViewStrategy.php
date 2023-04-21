<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderer;
use SnappyRenderer\Strategy;

class InvalidViewStrategy implements Strategy
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer, $data = null): string
    {
        throw RenderException::forInvalidView($view);
    }
}