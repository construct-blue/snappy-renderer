<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy;

use Blue\Snappy\Renderer\Exception\RenderException;
use Blue\Snappy\Renderer\Renderer;
use Blue\Snappy\Renderer\Strategy;

final class InvalidViewStrategy implements Strategy
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
    public function execute($view, Renderer $renderer, $data): string
    {
        throw RenderException::forInvalidView($view);
    }
}