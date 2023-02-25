<?php

declare(strict_types=1);

namespace Blue\Core\View\Renderable;

use Blue\Core\View\Renderable;
use Closure;

class RenderableClosure implements Renderable
{
    private Closure $closure;

    /**
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function render(object $model): iterable
    {
        return ($this->closure)($model);
    }
}