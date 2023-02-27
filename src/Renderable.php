<?php

declare(strict_types=1);

namespace SnappyRenderer;

/**
 * @phpstan-type element self|self[]|scalar|scalar[]|object|object[]
 * @template T of object
 */
interface Renderable
{
    /**
     * @param object&T $model
     * @return iterable<element>
     * @throws Exception\RenderException
     */
    public function render(object $model): iterable;
}