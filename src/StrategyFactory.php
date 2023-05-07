<?php

namespace SnappyRenderer;

use SnappyRenderer\Strategy\CaptureStrategy;
use SnappyRenderer\Strategy\ClosureStrategy;
use SnappyRenderer\Strategy\IterableStrategy;
use SnappyRenderer\Strategy\RenderableStrategy;
use SnappyRenderer\Strategy\StringStrategy;

final class StrategyFactory
{
    public const DEFAULT_PIPELINE = [
        IterableStrategy::class,
        RenderableStrategy::class,
        ClosureStrategy::class,
        StringStrategy::class,
        CaptureStrategy::class
    ];

    /**
     * @param class-string<AbstractStrategy> ...$classes
     * @return Strategy
     */
    public function createPipeline(string ...$classes): Strategy
    {
        return new StrategyPipeline(...$classes);
    }

    /**
     * @return Strategy
     */
    public function createDefault(): Strategy
    {
        return $this->createPipeline(...static::DEFAULT_PIPELINE);
    }
}