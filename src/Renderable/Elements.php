<?php

declare(strict_types=1);

namespace SnappyRenderer\Renderable;

use SnappyRenderer\Renderable;

/**
 * @phpstan-import-type element from Renderable
 * @implements Renderable<object>
 */
class Elements implements Renderable
{
    /**
     * @var iterable<element>
     */
    private iterable $elements;

    /**
     * @param iterable<element> $elements
     */
    public function __construct(iterable $elements)
    {
        $this->elements = $elements;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param object $model
     * @return iterable<element>
     */
    public function render(object $model): iterable
    {
        return $this->elements;
    }
}