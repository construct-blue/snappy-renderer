<?php

namespace SnappyRenderer\Strategy\Factory;

use SnappyRenderer\Exception\StrategyFactoryException;
use SnappyRenderer\Strategy;
use SnappyRenderer\Strategy\Base\PipelineStrategy;
use SnappyRenderer\StrategyFactory;

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