<?php

namespace Blue\Snappy\Renderer\Strategy\Factory;

use Blue\Snappy\Renderer\Exception\StrategyFactoryException;
use Blue\Snappy\Renderer\Strategy;
use Blue\Snappy\Renderer\Strategy\Base\PipelineStrategy;
use Blue\Snappy\Renderer\StrategyFactory;

class PipelineStrategyFactory implements StrategyFactory
{
    private string $class;

    /**
     * @param class-string<PipelineStrategy> $class
     * @throws StrategyFactoryException
     */
    public function __construct(string $class)
    {
        if (!in_array(PipelineStrategy::class, class_parents($class), true)) {
            throw new StrategyFactoryException(
                sprintf('Strategy class must extend %s.', PipelineStrategy::class)
            );
        }

        $this->class = $class;
    }


    public function create(Strategy $next): Strategy
    {
        return new $this->class($next);
    }
}