<?php

declare(strict_types=1);

namespace SnappyRenderer\Renderable;

use SnappyRenderer\Renderable;

/**
 * @phpstan-import-type element from Renderable
 * @implements Renderable<object>
 */
class RenderableIterable implements Renderable
{
    /**
     * @var iterable<element>
     */
    private iterable $iterable;

    /**
     * @param iterable<element> $iterable
     */
    public function __construct(iterable $iterable)
    {
        $this->iterable = $iterable;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param object $model
     * @return iterable<element>
     */
    public function render(object $model): iterable
    {
        return $this->iterable;
    }
}