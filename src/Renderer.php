<?php

declare(strict_types=1);

namespace SnappyRenderer;

/**
 * @phpstan-import-type element from Renderable
 */
final class Renderer
{
    private Strategy $strategy;
    private NextStrategy $errorHandler;

    /**
     * @param Strategy|null $strategy
     */
    public function __construct(Strategy $strategy = null, NextStrategy $errorHandler = null)
    {
        $this->strategy = $strategy ?? new RenderPipeline();
        $this->errorHandler = $errorHandler ?? new Strategy\Error\ThrowException();
    }

    /**
     * @template T of object
     * @param element $element
     * @param object&T $model
     * @return string
     * @throws Exception\RenderException
     */
    public function render($element, object $model): string
    {
        return $this->strategy->render($element, $model, $this, $this->errorHandler);
    }
}