<?php

namespace Blue\Snappy\Renderer;

use Blue\Snappy\Renderer\Strategy\CaptureStrategy;
use Blue\Snappy\Renderer\Strategy\ClosureStrategy;
use Blue\Snappy\Renderer\Strategy\Factory\PipelineStrategyFactory;
use Blue\Snappy\Renderer\Strategy\InvalidViewStrategy;
use Blue\Snappy\Renderer\Strategy\IterableStrategy;
use Blue\Snappy\Renderer\Strategy\RenderableStrategy;
use Blue\Snappy\Renderer\Strategy\StringStrategy;

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