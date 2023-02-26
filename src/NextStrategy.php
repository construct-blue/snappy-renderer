<?php

declare(strict_types=1);

namespace SnappyRenderer;

use SnappyRenderer\Exception\RenderException;

/**
 * @phpstan-import-type element from Renderable
 */
interface NextStrategy
{
    /**
     * @param element $element
     * @param object $model
     * @param Renderer $renderer
     * @return string
     * @throws RenderException
     */
    public function continue($element, object $model, Renderer $renderer): string;
}