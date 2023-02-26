<?php

declare(strict_types=1);

namespace SnappyRenderer;

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
     * @param Renderable<T> $renderable
     * @param object&T $model
     * @return string
     * @throws Exception\RenderException
     */
    public function render(Renderable $renderable, object $model): string
    {
        ob_start();
        try {
            foreach ($renderable->render($model) as $element) {
                echo $this->strategy->render($element, $model, $this, $this->errorHandler);
            }
        } finally {
            $result = ob_get_clean();
        }
        return $result;
    }
}