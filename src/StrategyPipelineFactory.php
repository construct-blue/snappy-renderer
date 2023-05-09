<?php

namespace SnappyRenderer;

use SnappyRenderer\Strategy\CaptureStrategy;
use SnappyRenderer\Strategy\ClosureStrategy;
use SnappyRenderer\Strategy\Factory\PipelineStrategyFactory;
use SnappyRenderer\Strategy\InvalidViewStrategy;
use SnappyRenderer\Strategy\IterableStrategy;
use SnappyRenderer\Strategy\RenderableStrategy;
use SnappyRenderer\Strategy\StringStrategy;

class StrategyPipelineFactory
{
    public function create(): StrategyPipeline
    {
        return new StrategyPipeline(
            new InvalidViewStrategy(),
            new PipelineStrategyFactory(RenderableStrategy::class),
            new PipelineStrategyFactory(ClosureStrategy::class),
            new PipelineStrategyFactory(IterableStrategy::class),
            new PipelineStrategyFactory(StringStrategy::class),
            new PipelineStrategyFactory(CaptureStrategy::class),
        );
    }
}