<?php

declare(strict_types=1);

namespace SnappyRenderer;

use Closure;
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
     * @param mixed $view
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function render($view, $data = null): string
    {
        $clone = clone $this;
        return $clone->execute($view, $clone, $data);
    }

    /**
     * @param mixed $view
     * @param iterable<mixed> $items
     * @return string
     * @throws RenderException
     */
    public function loop($view, iterable $items): string
    {
        return $this->render(new Loop($view), $items);
    }

    /**
     * @param mixed $view
     * @param Closure $predicate
     * @param mixed $data
     * @return string
     * @throws RenderException
     */
    public function conditional($view, Closure $predicate, $data = null): string
    {
        return $this->render(new Conditional($view, $predicate), $data);
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
        $this->assertLevel($view);
        return $this->strategy->execute($view, $renderer, $data);
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

    /**
     * @param mixed $view
     * @return void
     * @throws RenderException
     */
    private function assertLevel($view): void
    {
        $this->level++;
        if ($this->getLevel() > $this->getMaxLevel()) {
            throw RenderException::forMaxNestingLevel($view, $this->getMaxLevel());
        }
    }
}