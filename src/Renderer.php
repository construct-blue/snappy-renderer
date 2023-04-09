<?php

declare(strict_types=1);

namespace SnappyRenderer;

use SnappyRenderer\Exception\RenderException;

final class Renderer implements Strategy
{
    private Strategy $strategy;
    private int $maxLevel = 0;
    private int $level = 0;

    public function __construct(Strategy $strategy)
    {
        $this->strategy = $strategy;
        $this->setMaxLevel(1000);
    }

    /**
     * @param $view
     * @return string
     * @throws RenderException
     */
    public function render($view): string
    {
        return $this->execute($view, clone $this);
    }

    /**
     * @param $view
     * @param Renderer $renderer
     * @return string
     * @throws RenderException
     */
    public function execute($view, Renderer $renderer): string
    {
        $renderer->assertLevel($view);
        return $this->strategy->execute($view, $renderer);
    }

    public function getMaxLevel(): int
    {
        return $this->maxLevel;
    }

    public function setMaxLevel(int $maxLevel): void
    {
        $this->maxLevel = $maxLevel;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    private function assertLevel($view)
    {
        $this->level++;
        if ($this->getLevel() > $this->getMaxLevel()) {
            throw RenderException::forMaxNestingLevel($view, $this->getMaxLevel());
        }
    }
}