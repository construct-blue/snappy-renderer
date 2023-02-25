<?php

declare(strict_types=1);

namespace SnappyRenderer\Strategy\Pipeline;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\NextStrategy;
use SnappyRenderer\Renderer;

class Last implements NextStrategy
{
    public function continue(mixed $element, object $model, Renderer $renderer): string
    {
        throw RenderException::forInvalidElement($element);
    }
}