<?php

declare(strict_types=1);

namespace SnappyRenderer\Renderable;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Renderable;

/**
 * @template T of object
 * @phpstan-import-type element from Renderable
 * @implements Renderable<T>
 */
class Enumeration implements Renderable
{
    /**
     * @var iterable<T>
     */
    private iterable $models;

    /**
     * @var Renderable<T>
     */
    private Renderable $renderable;

    /**
     * @param iterable<T> $models
     * @param Renderable<T> $renderable
     */
    public function __construct(iterable $models, Renderable $renderable)
    {
        $this->models = $models;
        $this->renderable = $renderable;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param object $model
     * @return iterable<element>
     * @throws RenderException
     */
    public function render(object $model): iterable
    {
        foreach ($this->models as $model) {
            yield $this->renderable->render($model);
        }
    }
}