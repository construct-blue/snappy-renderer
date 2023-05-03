<?php

declare(strict_types=1);

namespace SnappyRenderer;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Strategy\CaptureStrategy;
use SnappyRenderer\Strategy\ClosureStrategy;
use SnappyRenderer\Strategy\InvalidViewStrategy;
use SnappyRenderer\Strategy\IterableStrategy;
use SnappyRenderer\Strategy\RenderableStrategy;
use SnappyRenderer\Strategy\StringStrategy;

class DefaultStrategy implements Strategy
{
    private Strategy $strategy;

    public function __construct()
    {
        $renderStrategy = new StringStrategy(
            new ClosureStrategy(
                new RenderableStrategy(
                    new IterableStrategy(
                        new InvalidViewStrategy()
                    )
                )
            )
        );

        $this->strategy = new CaptureStrategy($renderStrategy);
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer, $data = null): string
    {
        return $this->strategy->execute($view, $renderer, $data);
    }
}