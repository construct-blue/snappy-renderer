<?php

declare(strict_types=1);

namespace SnappyRenderer\Renderable;

use SnappyRenderer\Renderable;
use Closure;

/**
 * @phpstan-import-type element from Renderable
 * @implements Renderable<object>
 */
class Functional implements Renderable
{
    private Closure $closure;

    /**
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    /***
     * @param object $model
     * @return iterable<element>
     */
    public function render(object $model): iterable
    {
        return ($this->closure)($model);
    }
}