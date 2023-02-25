<?php

declare(strict_types=1);

namespace SnappyRenderer;

use Stringable;

/**
 * @template T of object
 */
interface Renderable
{
    /**
     * @param object&T $model
     * @return iterable<Renderable|Stringable>
     */
    public function render(object $model): iterable;
}