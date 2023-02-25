<?php

declare(strict_types=1);

namespace Blue\Core\View\Strategy\Pipeline;

use Blue\Core\View\Exception\RenderException;
use Blue\Core\View\NextStrategy;
use Blue\Core\View\Renderer;

class Last implements NextStrategy
{
    public function continue(mixed $element, object $model, Renderer $renderer): string
    {
        throw RenderException::forInvalidElement($element);
    }
}