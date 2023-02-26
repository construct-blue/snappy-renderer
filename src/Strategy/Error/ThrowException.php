<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy\Error;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderable;
use SnappyRenderer\Renderer;

/**
 * @phpstan-import-type element from Renderable
 * @internal
 */
final class ThrowException implements NextStrategy
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param element $element
     * @param object $model
     * @param Renderer $renderer
     * @return string
     * @throws RenderException
     */
    public function continue($element, object $model, Renderer $renderer): string
    {
        throw RenderException::forInvalidElement($element);
    }
}