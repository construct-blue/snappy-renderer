<?php

declare(strict_types=1);

namespace SnappyRenderer;

use SnappyRenderer\Exception\RenderException;
use SnappyRenderer\Strategy\InvalidViewStrategy;

final class StrategyPipeline implements Strategy
{
    private Strategy $strategy;

    /**
     * @param class-string<AbstractStrategy> ...$strategyClasses
     */
    public function __construct(string ...$strategyClasses)
    {
        $this->strategy = new InvalidViewStrategy();

        foreach ($strategyClasses as $strategyClass) {
            /** @uses AbstractStrategy::__construct */
            $this->strategy = new $strategyClass($this->strategy);
        }
    }

    /**
     * @param mixed $view
     * @param Renderer $renderer
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer, $data): string
    {
        return $this->strategy->execute($view, $renderer, $data);
    }
}