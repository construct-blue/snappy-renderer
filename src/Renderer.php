<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer;

use Closure;
use Blue\Snappy\Renderer\Exception\RenderException;
use Blue\Snappy\Renderer\Helper\Arguments;
use Blue\Snappy\Renderer\Helper\Capture;
use Blue\Snappy\Renderer\Helper\Conditional;
use Blue\Snappy\Renderer\Helper\Loop;
use Blue\Snappy\Renderer\Helper\Placeholder;
use Throwable;

final class Renderer implements Strategy
{
    private Strategy $strategy;
    private int $maxLevel = 0;
    private int $level = 0;

    public function __construct(Strategy $strategy = null, int $maxLevel = 256)
    {
        $this->strategy = $strategy ?? (new StrategyPipelineFactory())->create();
        $this->setMaxLevel($maxLevel);
    }

    /**
     * @param mixed $view
     * @param mixed|null $data
     * @return string
     * @throws RenderException
     */
    public function render($view, $data = null): string
    {
        try {
            $clone = clone $this;
            return $clone->execute($view, $clone, $data);
        } catch (Throwable $throwable) {
            throw RenderException::forThrowableInView($throwable, $view);
        }
    }

    /**
     * @param mixed $view
     * @param iterable<int, mixed> $items
     * @return string
     * @throws RenderException
     */
    public function loop($view, iterable $items): string
    {
        return $this->render(new Loop($view, $items));
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
     * @param string $placeholder
     * @param mixed $view
     * @return string
     * @throws RenderException
     */
    public function capture(string $placeholder, $view): string
    {
        return $this->render(new Capture(new Placeholder($placeholder), $view));
    }

    /**
     * @throws RenderException
     */
    public function placeholder(string $code): string
    {
        return $this->render(new Placeholder($code));
    }

    /**
     * @param array<string, mixed> $args
     * @return Arguments
     */
    public function arguments(array $args): Arguments
    {
        return new Arguments($args);
    }


    /**
     * @param array<string, mixed> $args
     * @return Arguments
     */
    public function args(array $args): Arguments
    {
        return $this->arguments($args);
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