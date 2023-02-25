<?php

declare(strict_types=1);

namespace Blue\Core\View\Renderable;

use Blue\Core\View\Renderable;

class RenderableIterable implements Renderable
{
    private iterable $iterable;

    public function __construct(iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    public function render(object $model): iterable
    {
        return $this->iterable;
    }
}